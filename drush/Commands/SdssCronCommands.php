<?php

declare(strict_types=1);

namespace Drush\Commands;

use Drush\Boot\DrupalBootLevels;
use Drush\Commands\DrushCommands;
use Drupal\SwsDrush\Drush\Commands\SwsCommandsTrait;
use Drush\Attributes as CLI;

/**
 * SDSS drush commands for triggering cron on multisites.
 */
#[CLI\Bootstrap(level: DrupalBootLevels::NONE)]
final class SdssCronCommands extends DrushCommands {

  use SwsCommandsTrait;

  /**
   * Run the Drupal cron on all multisites.
   */
  #[CLI\Command(name: 'sdss:drupal:cron')]
  public function cron() {
    $this->runCronForAllSites('cron', 'Drupal cron');
  }

  /**
   * Run the Drupal Scheduler cron on all multisites to publish/unpublish nodes.
   */
  #[CLI\Command(name: 'sdss:drupal:cron:scheduler')]
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
