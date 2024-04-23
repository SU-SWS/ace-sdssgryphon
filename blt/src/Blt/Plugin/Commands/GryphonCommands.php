<?php

namespace Gryphon\Blt\Plugin\Commands;

use Acquia\Blt\Robo\BltTasks;

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
<<<<<<< HEAD
   * Generate a list of emails for the given role on all sites.
   *
   * @command sdss:role-report
   */
  public function roleReport($role)
  {
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

  /**
   * Provisioning a site.
   *
   * @command gryphon:provision
   * @description This is command to show provision URLs.
   */
  public function provision($sitename) {
    // Check if sitename is empty
    if (empty($sitename)) {
      $this->say('Sitename cannot be empty. Exiting');
      return;
    }

    // Create three outputs for aliases for NetDB.
    $sitename_dev = $sitename . '-dev';
    $sitename_test = $sitename . '-test';
    $sitename_prod = $sitename . '-prod';

    // Display aliases for NetDB.
    $this->say($sitename_dev);
    $this->say($sitename_test);
    $this->say($sitename_prod);

    // Fetch DNS record of the URLs for the environments to check for viability.
    $this->checkForARecord($sitename_dev);
    $this->checkForARecord($sitename_test);
    $this->checkForARecord($sitename_prod);
  }

  // Prepend each with "stanford.edu" and check for DNS record.
  private function checkForARecord($domain) {
    $records = dns_get_record($domain . '.stanford.edu', DNS_A);

    if (!empty($records)) {
      $this->say($domain . '.stanford.edu: Found');
    } else {
      $this->say($domain . '.stanford.edu: Not found. Please remove aliases from NetDB, if permission is given');
      return;
    }
  }

  private function askToAddDomains()
  {
    $condition = $this->ask("Do you want to run a specific function? (yes/no)", "yes");

    if (strtolower($condition) === 'yes') {
      $this->say("Running the specific function...");
      //$this->runSpecificFunction();
    } else {
      $this->say("Skipped running the specific function.");
    }
  }
}
