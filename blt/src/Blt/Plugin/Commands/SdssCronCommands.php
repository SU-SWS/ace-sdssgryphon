<?php

namespace Gryphon\Blt\Plugin\Commands;

use Acquia\Blt\Robo\BltTasks;

/**
 * Class SdssCronCommands.
 */
class SdssCronCommands extends BltTasks {

  /**
   * Run the Drupal cron on all sites.
   *
   * @command sdss:drupal:cron
   */
  public function cron() {
    $this->runCronForAllSites('cron', 'Drupal cron');
  }

  /**
   * Run the Drupal Scheduler cron on all sites.
   *
   * This handles nodes scheduled to be published or unpublished and needs to
   * be run more frequently than most cron tasks.
   *
   * @command sdss:drupal:cron:scheduler
   */
  public function cronScheduler() {
    $this->runCronForAllSites('cron:run scheduler_cron', 'Scheduler cron');
  }

  /**
   * Run a cron-related Drush command on all multisites.
   *
   * @param string $drushCommand The Drush command to run (e.g., 'cron' or 
   * 'cron:run scheduler_cron').
   * @param string $label A human-readable label for output messages.
   */
  protected function runCronForAllSites($drushCommand, $label) {
    $this->config->set('drush.alias', '');
    $failed = [];
    foreach ($this->getConfigValue('multisites') as $multisite) {
      try {
        $this->say("Running $label on <comment>$multisite</comment>...");
        $this->switchSiteContext($multisite);

        $task = $this->taskDrush()
          ->drush($drushCommand)
          ->run();
        if (!$task->wasSuccessful()) {
          $failed[] = $multisite;
          $this->say("$label on <comment>$multisite</comment> failed.");
          continue;
        }
        $this->say("$label on <comment>$multisite</comment> completed successfully.");
      }
      catch (\Exception $e) {
        $this->say("Unable to run $label on <comment>$multisite</comment>: " . $e->getMessage());
        $failed[] = $multisite;
        continue;
      }
    }

    if ($failed) {
      $this->say("$label failed on the following sites: " . implode(', ', $failed));
      return;
    }

    $this->say("$label completed successfully on all sites.");
  }

}
