<?php

namespace Drush\Commands\sdss;


use Drush\Commands\DrushCommands;
use Drupal\SwsDrush\Drush\Commands\SwsCommandsTrait;


class SdssCronCommands extends DrushCommands {
  use SwsCommandsTrait;

  /**
   * Run the Drupal cron on all multisites.
   *
   * @command sdss:drupal:cron
   */
  public function cron() {
    $this->runCronForAllSites('cron', 'Drupal cron');
  }

  /**
   * Run the Drupal Scheduler cron on all multisites to publish/unpublish nodes.
   *
   * @command sdss:drupal:cron:scheduler
   */
  public function cronScheduler() {
    $this->runCronForAllSites('cron:run scheduler_cron', 'Scheduler cron');
  }

  /**
   * Helper to run a Drush cron command on all multisites.
   */
  protected function runCronForAllSites($drushCommand, $label) {
    // Adjust this to your multisite list source.
    $multisites = $this->getConfig()->get('command.sws.options.multisites') ?? ['default'];
    $failed = [];
    foreach ($multisites as $site) {
      $this->output()->writeln("Running $label on <comment>$site</comment>...");
      $result = $this->localMachineHelper()->execute([
        'drush',
        '@' . $site,
        $drushCommand,
      ], NULL, $this->getDir());
      if (!$result->isSuccessful()) {
        $failed[] = $site;
        $this->output()->writeln("$label on <comment>$site</comment> failed.");
      } else {
        $this->output()->writeln("$label on <comment>$site</comment> completed successfully.");
      }
    }
    if ($failed) {
      $this->output()->writeln("$label failed on the following sites: " . implode(', ', $failed));
    } else {
      $this->output()->writeln("$label completed successfully on all sites.");
    }
  }
}
