<?php

namespace Gryphon\Blt\Plugin\Commands;

use Acquia\Blt\Robo\BltTasks;
use Acquia\Blt\Robo\Common\EnvironmentDetector;
use GuzzleHttp\Client;

/**
 * Class GryphonCommands.
 */
class GryphonCommands extends BltTasks {

  /**
   * Enable a list of modules for all sites on a given environment.
   *
   * @param string $environment
   *   Environment name like `dev`, `test`, or `ode123`.
   * @param string $modules
   *   Comma separated list of modules to enable.
   *
   * @example blt gryphon:enable-modules dev views_ui,field
   *
   * @command gryphon:enable-modules
   * @aliases grem
   *
   */
  public function enableModules($environment, $modules) {
    $commands = $this->collectionBuilder();
    foreach ($this->getConfigValue('multisites') as $site) {
      $commands->addTask($this->taskDrush()
        ->alias("$site.$environment")
        ->drush('en ' . $modules));
    }

    $commands->run();
  }

  /**
   * Run the Drupal cron on all sites.
   *
   * @command drupal:cron
   */
  public function cron() {
    $this->config->set('drush.alias', '');
    $failed = [];
    foreach ($this->getConfigValue('multisites') as $multisite) {
      try {
        $this->say("Running Cron on <comment>$multisite</comment>...");
        $this->switchSiteContext($multisite);

        $task = $this->taskDrush()
          ->drush("cron")
          ->run();
        if (!$task->wasSuccessful()) {
          $failed[] = $multisite;
          $this->say("Cron on <comment>$multisite</comment> failed.");
          continue;
        }
        $this->say("Cron on <comment>$multisite</comment> completed successfully.");
      }
      catch (\Exception $e) {
        $this->say("Unable to run cron on <comment>$multisite</comment>: " . $e->getMessage());
        $failed[] = $multisite;
        continue;
      }
    }

    if ($failed) {
      $this->say("Cron failed on the following sites: " . implode(', ', $failed));
    }

    $this->say("Cron completed successfully on all sites.");
  }

  /**
   * Generate a list of emails for the given role on all sites.
   *
   * @command sdss:role-report
   */
  public function roleReport($role) {
    $information = [];
    foreach ($this->getConfigValue('multisites') as $site) {
      $emails = $this->taskDrush()
        ->alias("$site.prod")
        ->drush('sqlq')
        ->arg('SELECT d.mail FROM users_field_data d INNER JOIN user__roles r ON d.uid = r.entity_id WHERE r.roles_target_id = "' . $role . '" and d.mail NOT LIKE "%localhost%"')
        ->printOutput(FALSE)
        ->run()
        ->getMessage();

      $site_url = str_replace('_', '-', str_replace('__', '.', $site));

      if (str_contains($site_url, '.')) {
        [$first, $last] = explode('.', $site_url);
        $site_url = "$first-prod.$last";
      } else {
        $site_url .= "-prod";
      }
      if ($emails) {
        $emails = array_filter(explode("\n", $emails));
      }
      if (!$emails) {
        continue;
      }
      foreach ($emails as $email) {
        $information[] = [
          'site' => $site,
          'url' => "https://$site_url.stanford.edu",
          'users' => $email,
        ];
      }
    }
    $out = fopen('php://output', 'w');
    fputcsv($out, ['Site', 'Url', 'Emails']);
    foreach ($information as $info) {
      fputcsv($out, $info);
    }
    fclose($out);
  }

}
