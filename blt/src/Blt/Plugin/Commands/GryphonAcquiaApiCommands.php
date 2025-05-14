<?php

namespace Gryphon\Blt\Plugin\Commands;

use AcquiaCloudApi\Endpoints\Crons;
use GuzzleHttp\Client;
use Sws\BltSws\Blt\Plugin\Commands\SwsCommandTrait;
use Symfony\Component\Console\Question\Question;

if (!trait_exists('Sws\BltSws\Blt\Plugin\Commands\SwsCommandTrait')) {
  return;
}

/**
 * Class GryphonAcquiaApiCommands.
 *
 * @package Gryphon\Blt\Plugin\Commands
 */
class GryphonAcquiaApiCommands extends GryphonCommands {

  use SwsCommandTrait {
    connectAcquiaApi as traitConnectAcquiaApi;
  }

  /**
   * Create the scheduled job within Acquia to call drush cron.
   *
   * @command gryphon:create-cron
   *
   * @param string $environment
   *   Acquia environment name: `dev`, `test`, or `prod`.
   * @param string $site
   *   Site to create the cron job.
   *
   * @throws \Exception
   */
  public function createDrushCronJob($environment, $site) {
    if (!in_array($site, $this->getConfigValue('multisites'))) {
      throw new \Exception(sprintf('%s is not one of the multisites.', $site));
    }
    $this->connectAcquiaApi();
    $site = str_replace('_', '-', str_replace('__', '.', $site));
    $command = sprintf('/usr/local/bin/cron-wrapper.sh $AH_SITE_GROUP.$AH_SITE_ENVIRONMENT http://%s.stanford.edu', $site);
    $cron_job_api = new Crons($this->acquiaApi);
    $this->say($cron_job_api->create($this->getEnvironmentUuid($environment), $command, "0 */6 * * *", "Drush Cron $site")->message);
  }

  /**
   * Create a database on the Acquia environments, should match the site name.
   *
   * @command gryphon:create-database
   * @aliases grcd
   */
  public function createDatabase() {
    $database = $this->getMachineName('What is the name of the database? This ideally will match the site directory name. No special characters please.');
    $this->connectAcquiaApi();
    $this->say($this->acquiaDatabases->create($this->appId, $database)->message);
  }

  /**
   * Add a domain to Acquia environment.
   *
   * @param string $environment
   *   Environment: dev, test, or prod.
   * @param string $domains
   *   Comma separated new domain to add.
   *
   * @command gryphon:add-domain
   * @aliases grad
   */
  public function gryphonAddDomain($environment, $domains) {
    $this->connectAcquiaApi();
    foreach (explode(',', $domains) as $domain) {
      $this->say($this->acquiaDomains->create($this->getEnvironmentUuid($environment), $domain)->message);
    }
  }

  /**
   * Display all sites on the multi-site and a total count of sites.
   *
   * @command sdss:show-sites
   * @aliases sites
   */
  public function showSites() {
    $sites = $this->getConfigValue('multisites');
    asort($sites);
    foreach ($sites as $key => $site) {
      $this->say(sprintf('%s', $site));
    }
    $this->say(sprintf('Total sites: %s', count($sites)));
  }

  /**
   * Copy databases from production sites to staging sites. Includes option to
   * copy to dev sites.
   *
   * @command sdss:sync-stage
   * @aliases stage
   *
   * @option exclude Comma separated list of database names to skip.
   * @option force Force copying of databases even if they were already copied
   * recently.
   */
  public function syncStaging(array $options = [
    'exclude' => NULL,
    'force' => FALSE,
    'env' => 'test',
    'no-notify' => FALSE,
  ]) {
    $this->connectAcquiaApi();
    $from_uuid = $this->getEnvironmentUuid('prod');
    $to_uuid = $this->getEnvironmentUuid($options['env']);

    $this->taskStartedTime = time() - (60 * 60 * 24);

    $sites = $this->getSitesToSync($options);
    if (empty($options['no-interaction']) && !$this->confirm(sprintf('Are you sure you wish to stage the following sites: <comment>%s</comment>', implode(', ', $sites)))) {
      return;
    }
    $count = count($sites);
    $concurrent_copies = 5;
    $in_progress = [];
    while (!empty($sites)) {
      if (count($in_progress) >= $concurrent_copies) {
        // Check for completion.
        foreach ($in_progress as $key => $database_name) {
          if ($this->databaseCopyFinished($database_name)) {
            unset($in_progress[$key]);
          }
        }
      }

      $copy_these = array_splice($sites, 0, $concurrent_copies - count($in_progress));
      foreach ($copy_these as $database_name) {
        $in_progress[] = $database_name;
        $this->say(sprintf('Copying database %s', $database_name));
        $access_token = $this->getAccessToken();
        $client = new Client();
        $response = $client->post("https://cloud.acquia.com/api/environments/$to_uuid/databases", [
          'headers' => ['Authorization' => "Bearer $access_token"],
          'json' => ['name' => $database_name, 'source' => $from_uuid],
        ]);
        $message = json_decode((string) $response->getBody(), TRUE, 512, JSON_THROW_ON_ERROR);
        $this->say($message['message']);
      }
      echo '.';
      sleep(30);
    }
    $this->yell("$count database have been copied to staging.");

    $root = $this->getConfigValue('repo.root');
    if (file_exists("$root/keys/secrets.settings.php")) {
      include "$root/keys/secrets.settings.php";
    }
    if (!$options['no-notify'] && getenv('SLACK_NOTIFICATION_URL')) {
      $client = new Client();
      $client->post(getenv('SLACK_NOTIFICATION_URL'), [
        'form_params' => [
          'payload' => json_encode([
            'username' => 'Acquia Cloud',
            'text' => sprintf('%s Databases have been copied to %s environment.', $count, $options['env']),
            'icon_emoji' => 'information_source',
          ]),
        ],
      ]);
    }
  }

  /**
   * Call the API and using the notifications, find out if it's done copying.
   *
   * @param string $database_name
   *   Acquia database name.
   *
   * @return bool
   *   If the database has been copied in the past 12 hours.
   */
  protected function databaseCopyFinished(string $database_name): bool {
    $access_token = $this->getAccessToken();
    $client = new Client();
    $created_since = date('c', time() - (60 * 60 * 12));
    $response = $client->get("https://cloud.acquia.com/api/applications/{$this->appId}/notifications", [
      'headers' => ['Authorization' => "Bearer $access_token"],
      'query' => [
        'filter' => "event=DatabaseCopied;description=@*$database_name*;status!=in-progress;created_at>=$created_since",
      ],
    ]);
    $message = json_decode((string) $response->getBody(), TRUE, 512, JSON_THROW_ON_ERROR);
    return $message['total'] > 0;
  }

  /**
   * Call the API and fetch the OAuth token.
   *
   * @return string
   *   Access bearer token.
   */
  protected function getAccessToken(): string {
    if (isset($this->accessToken['expires']) && time() <= $this->accessToken['expires']) {
      return $this->accessToken['token'];
    }

    $client = new Client();
    $response = $client->post('https://accounts.acquia.com/api/auth/oauth/token', [
      'form_params' => [
        'client_id' => getenv('ACQUIA_KEY'),
        'client_secret' => getenv('ACQUIA_SECRET'),
        'grant_type' => 'client_credentials',
      ],
    ]);
    $response_body = json_decode((string) $response->getBody(), TRUE, 512, JSON_THROW_ON_ERROR);
    $this->accessToken = [
      'token' => $response_body['access_token'],
      'expires' => time() + $response_body['expires_in'] - 60,
    ];
    return $this->accessToken['token'];
  }

  /**
   * Create the Acquia cloud conf file that holds the key and secret creds.
   *
   * @throws \Acquia\Blt\Robo\Exceptions\BltException
   */
  protected function connectAcquiaApi() {
    $acquia_conf = $_SERVER['HOME'] . '/.acquia/cloud_api.conf';
    $key = getenv('ACQUIA_KEY');
    $secret = getenv('ACQUIA_SECRET');
    if ($key && $secret && !file_exists($acquia_conf)) {
      mkdir(dirname($acquia_conf), 0777, TRUE);
      $conf = ['key' => $key, 'secret' => $secret];
      file_put_contents($acquia_conf, json_encode($conf, JSON_PRETTY_PRINT));
    }

    if (!$this->acquiaApplications) {
      $this->traitConnectAcquiaApi();
    }
    try {
      $this->acquiaApplications->getAll();
    } catch (\Throwable $e) {
      $this->traitConnectAcquiaApi();
    }
  }

  /**
   * Get an overall list of database names to sync.
   *
   * @param array $options
   *   Array of keyed command options.
   *
   * @return array
   *   Array of database names to sync.
   */
  protected function getSitesToSync(array $options) {
    $sites = $this->getConfigValue('multisites');
    foreach ($sites as $key => &$db_name) {
      $db_name = $db_name == 'default' ? 'stanfordsos' : $db_name;

      if (strpos($db_name, 'sandbox') !== FALSE) {
        unset($sites[$key]);
        continue;
      }

      if(!$options['force']) {
        $this->say(sprintf('Checking if %s has recently been copied', $db_name));
        if ($this->databaseCopyFinished($db_name)) {
          unset($sites[$key]);
        }
      }
    }
    asort($sites);
    $sites = array_values($sites);
    if (!empty($options['exclude'])) {
      $exclude = explode(',', $options['exclude']);

      $sites = array_diff($sites, $exclude);
    }
    return array_values($sites);
  }

  /**
   * Ask the user for a new stanford url and validate the entry.
   *
   * @param string $message
   *   Prompt for the user.
   *
   * @return string
   *   User entered value.
   */
  protected function getNewDomain($message) {
    $question = new Question($this->formatQuestion($message));
    $question->setValidator(function ($answer) {
      if (empty($answer) || !preg_match('/\.stanford\.edu/', $answer) || preg_match('/^http/', $answer)) {
        throw new \RuntimeException(
          'You must provide a stanford.edu url. ie gryphon.stanford.edu'
        );
      }

      return $answer;
    });
    return $this->doAsk($question);
  }

  /**
   * Ask the user for a new stanford url and validate the entry.
   *
   * @param string $message
   *   Prompt for the user.
   *
   * @return string
   *   User entered value.
   */
  protected function getMachineName($message) {
    $question = new Question($this->formatQuestion($message));
    $question->setValidator(function ($answer) {
      $modified_answer = strtolower($answer);
      $modified_answer = preg_replace("/[^a-z0-9_]/", '_', $modified_answer);
      if ($modified_answer != $answer) {
        throw new \RuntimeException(
          'Only lower case alphanumeric characters with underscores are allowed.'
        );
      }
      return $answer;
    });
    return $this->doAsk($question);
  }

  /**
   * Get the environment UUID for the application from the machine name.
   *
   * @param string $name
   *   Environment machine name.
   *
   * @return string
   *   Environment UUID.
   *
   * @throws \Exception
   */
  protected function getEnvironmentUuid(string $name) {
    /** @var \AcquiaCloudApi\Response\EnvironmentResponse $env */
    foreach ($this->acquiaEnvironments->getAll($this->appId) as $env) {
      if ($env->name == $name) {
        return $env->uuid;
      }
    }
    throw new \Exception(sprintf('Unable to find environment name %s', $name));
  }

}
