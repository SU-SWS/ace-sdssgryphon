<?php

namespace Gryphon\Blt\Plugin\Commands;

use Acquia\Blt\Robo\BltTasks;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

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
   * Provisioning a site.
   *
   * @command gryphon:provision
   * @description This is command to populate URLs by scafoliding the multisite and designating URLs on Acquia.
   */
  public function provision($sitename): void {
    if (empty($sitename)) {
      $this->say('Sitename cannot be empty. Exiting');
      return;
    }

    // Define environment types
    $env_types = ['dev', 'test', 'prod'];

    foreach ($env_types as $env_type) {
      $sitename_env = $sitename . '-' . $env_type;

      // Checks to see if domain name is in use.
      if ($this->checkForARecord($sitename_env)) {
        $this->say('A Record present in NetDB. Cannot proceed with provision until aliases released.');
      }
      //Outputs alias for NetDB.
      $this->say('A Record not present. Site name is free to use.');
      $this->say('Alias for NetDB: ' . $sitename_env);
      $this->addDomainsToAcquia($sitename, $env_type, $sitename_env);
//      $this->postMultiSiteInit($sitename);

    }
  }

  /**
   * @param $domain
   *
   * @return bool
   */
  private function checkForARecord($domain): bool {
    return !empty(dns_get_record($domain . '.stanford.edu', DNS_A));
  }

  /**
   * Adds domains to Acquia
   *
   * @param $sitename_env
   *
   * @return void
   */
  private function addDomainsToAcquia($sitename, $env_type, $sitename_env): void {
    $condition = $this->ask("Do you want to add the domain to acquia? (yes/no)", "yes");

    if (strtolower($condition) === 'yes') {
      $this->say("Running the specific function...");
      $this->gryphonAddDomain($env_type, $sitename_env . '.stanford.edu');
      $this->addToBltYml($sitename);
      $this->scaffoldingMultisite();
    } else {
      $this->say("Skipped running the specific function.");
    }
  }

  private function scaffoldingMultisite(): void {
    $gryphon = new GryphonHooksCommands();
    $gryphon->postMultiSiteInit();
  }

  /**
   * @return void
   *
   * @command gryphon:yaml
   * @description This is command to show provision URLs.
   */
  private function addToBltYml($sitename) {
    $bltYAML =  __DIR__ . "/../../../../blt.yml";

    if (!file_exists($bltYAML)){
      $this->say('No BLT yaml file to edit.');
      return;
    }

    $yamlContentsBlt = Yaml::parseFile($bltYAML);
    $yamlContentsBlt['multisites'][] = $sitename;
    sort($yamlContentsBlt['multisites']);
    $newMultisiteYaml = Yaml::dump($yamlContentsBlt, 10, 2);
    $this->taskWriteToFile($bltYAML)->text($newMultisiteYaml)->run();
  }
}
