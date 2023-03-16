<?php

namespace Gryphon\Blt\Plugin\Commands;

use AcquiaCloudApi\Endpoints\Crons;
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
  public function humsciAddDomain($environment, $domains) {
    $this->connectAcquiaApi();
    foreach (explode(',', $domains) as $domain) {
      $this->say($this->acquiaDomains->create($this->getEnvironmentUuid($environment), $domain)->message);
    }
  }

  /**
   * Create the Acquia cloud conf file that holds the key and secret creds.
   *
   * @throws \Acquia\Blt\Robo\Exceptions\BltException
   */
  protected function connectAcquiaApi() {
    $config_file = $_SERVER['HOME'] . '/.acquia/cloud_api.conf';
    if (!file_exists($config_file)) {
      mkdir(dirname($config_file), 0777, TRUE);
      $conf = [
        'key' => getenv('ACE_KEY'),
        'secret' => getenv('ACE_SECRET'),
      ];
      file_put_contents($config_file, json_encode($conf));
    }
    self::traitConnectAcquiaApi();
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
