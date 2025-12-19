<?php

declare(strict_types=1);

namespace Drush\Commands;

use Drush\Boot\DrupalBootLevels;
use Drush\Commands\DrushCommands;
use Drupal\SwsDrush\Drush\Commands\SwsCommandsTrait;
use Drush\Attributes as CLI;
use Symfony\Component\Console\Input\InputOption;

/**
 * SDSS drush commands for syncing sites.
 */
#[CLI\Bootstrap(level: DrupalBootLevels::NONE)]
final class SdssSyncCommands extends DrushCommands {

  use SwsCommandsTrait;

  /**
   * Sync all multisite databases from production to a target environment 
   * (default: staging).
   *
   * Copies databases from prod to the specified environment, with options to 
   * exclude sites, force copy, and suppress notifications.
   * 
   * Replaces `blt stage`.
   *
   * @option exclude Comma separated list of database names to skip.
   * @option force Force copying of databases even if they were already copied recently.
   * @option env Target environment (default: test).
   * @option no-notify Suppress Slack notification.
   */
  #[CLI\Command(name: 'sws:drupal:sync-stage')]
  #[CLI\Option(name: 'exclude', description: 'Comma separated list of site names to skip.')]
  #[CLI\Option(name: 'force', description: 'Force copying of databases even if they were already copied recently.')]
  #[CLI\Option(name: 'env', description: 'Target environment (default: test).')]
  #[CLI\Option(name: 'no-notify', description: 'Suppress Slack notification.')]
  #[CLI\Option(name: 'app-id', description: 'Acquia application ID')]
  #[CLI\Option(name: 'app-key', description: 'Acquia API key')]
  #[CLI\Option(name: 'app-secret', description: 'Acquia API secret')]
  public function syncSitesStaging(
    $options = [
      'exclude' => NULL,
      'force' => FALSE,
      'env' => 'test',
      'no-notify' => FALSE,
      'app-id' => InputOption::VALUE_REQUIRED,
      'app-key' => InputOption::VALUE_REQUIRED,
      'app-secret' => InputOption::VALUE_REQUIRED,
  ]
  ) {
    $acquiaApi = $this->getAcquiaApi();
    $appId = $this->input()->getOption('app-id');

    // Parse options.
    $from_env = 'prod';
    $to_env = $options['env'] ?? 'test';
    $exclude = $options['exclude'] ? array_map('trim', explode(',', $options['exclude'])) : [];
    $force = !empty($options['force']);
    $no_notify = !empty($options['no-notify']);

    // Get environment UUIDs.
    $environments = $acquiaApi->acquiaEnvironments->getAll($appId);
    $env_uuids = [];
    foreach ($environments as $env) {
      $env_uuids[$env->name] = $env->uuid;
    }
    $from_uuid = $env_uuids[$from_env] ?? null;
    $to_uuid = $env_uuids[$to_env] ?? null;
    if (!$from_uuid || !$to_uuid) {
      $this->yell("Could not find UUIDs for prod or target environment.", 40, 'red');
      return;
    }

    // Get all databases/sites.
    $databases = $acquiaApi->acquiaDatabases->getNames($appId);
    $sites = [];
    foreach ($databases as $db) {
      if (!in_array($db->name, $exclude)) {
        $sites[] = $db->name;
      }
    }
    if (empty($sites)) {
      $this->say('No sites to sync.');
      return;
    }

    if (!$force && !$this->confirm(
      sprintf(
        'This will sync the databases for the following sites from prod to the %s environment:<comment> %s. </comment>Continue?',
        $to_env,
        implode(', ', $sites)
      )
    )) {
      return;
    }

    foreach ($sites as $database_name) {
      $this->output()->writeln("<info>Copying database $database_name from $from_env to $to_env</info>");
      $acquiaApi->acquiaDatabases->copy($from_uuid, $database_name, $to_uuid);
      $this->output()->writeln("<comment>Waiting 1 minute before next copy...</comment>");
      sleep(60); // 1 minute
    }

    $this->yell(count($sites) . " databases have been copied to $to_env.");

    if (!$no_notify && getenv('SLACK_NOTIFICATION_URL')) {
      $this->say('Slack notification sent.');
    }
  }
}
