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
   * Print "URLS" to the console.
   *
   * @command gryphon:provision
   * @description This is command to show provision URLs.
   */
  public function provision($sitename) {
    // Check if sitename is empty
    if (empty($sitename)) {
      echo "Sitename cannot be empty. Exiting.\n";
      return;
    }

    // Create three outputs without "stanford.edu"
    $sitename_dev = $sitename . '-dev';
    $sitename_test = $sitename . '-test';
    $sitename_prod = $sitename . '-prod';

    // Display aliases for NetDB.
    echo "Dev: $sitename_dev\n";
    echo "Test: $sitename_test\n";
    echo "Prod: $sitename_prod\n";

    // Check host command for each domain
    $this->checkHost($sitename_dev);
    $this->checkHost($sitename_test);
    $this->checkHost($sitename_prod);
  }

  // Prepend each with "stanford.edu" and check host command
  private function checkHost ($domain) {
    exec("host $domain.stanford.edu", $output, $resultCode);
    if ($resultCode === 0) {
      echo "$domain.stanford.edu: Found\n";
    } else {
      echo "$domain.stanford.edu: Not Found\n";
    }
  }
}
