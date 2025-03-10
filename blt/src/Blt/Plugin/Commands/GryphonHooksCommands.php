<?php

namespace Gryphon\Blt\Plugin\Commands;

use Acquia\Blt\Robo\BltTasks;
use Acquia\Blt\Robo\Common\EnvironmentDetector;
use Consolidation\AnnotatedCommand\CommandData;
use Drupal\Core\Serialization\Yaml;
use Robo\Contract\VerbosityThresholdInterface;

/**
 * Class GryphonHooksCommands for any pre or post command hooks.
 */
class GryphonHooksCommands extends BltTasks {

  /**
   * @hook pre-command sws:pre-code-deploy
   */
  public function preSwsCodeDeploy()   {
    // Enable the field_validation_legacy module before running database
    // updates in a deployment. This is part of upgrading the field_validation
    // module to 3.x and can be removed in a later release.
    // See: https://www.drupal.org/docs/extending-drupal/contributed-modules/contributed-module-documentation/field-validation/update-from-8x-1x-to-300
    // @todo Remove in a later release.
    foreach ($this->getConfigValue('multisites') as $site) {
      $this->taskDrush()
        ->alias("$site.local")
        ->drush('en')
        ->arg('field_validation_legacy')
        ->run();
    }
  }

  /**
   * @hook post-command drupal:sync:db
   */
  public function postDbSync() {
    // Enable the field_validation_legacy module after syncing a database and
    // before running database updates . This is part of upgrading the
    // field_validation module to 3.x and can be removed in a later release.
    // See: https://www.drupal.org/docs/extending-drupal/contributed-modules/contributed-module-documentation/field-validation/update-from-8x-1x-to-300
    // @todo Remove in a later release.
    return $this->taskDrush()
      ->drush('pm:install')
      ->arg('field_validation_legacy')
      ->run();
  }

  /**
   * After a multisite is created, modify the drush alias with default values.
   *
   * @hook post-command recipes:multisite:init
   */
  public function postMultiSiteInit() {
    $root = $this->getConfigValue('repo.root');
    $multisites = [];

    $default_alias = Yaml::decode(file_get_contents("$root/drush/sites/default.site.yml"));
    $sites = glob("$root/drush/sites/*.site.yml");
    foreach ($sites as $site_file) {
      $alias = Yaml::decode(file_get_contents($site_file));
      preg_match('/sites\/(.*)\.site\.yml/', $site_file, $matches);
      $site_name = $matches[1];

      $multisites[] = $site_name;
      if (count($alias) != count($default_alias)) {
        foreach ($default_alias as $environment => $env_alias) {
          $env_alias['uri'] = $this->getAliasUrl($site_name, $environment);
          $alias[$environment] = $env_alias;
        }
      }

      file_put_contents($site_file, Yaml::encode($alias));
    }

    // Add the site to the multisites in BLT's configuration.
    $root = $this->getConfigValue('repo.root');
    $blt_config = Yaml::decode(file_get_contents("$root/blt/blt.yml"));
    asort($multisites);
    $blt_config['multisites'] = array_values(array_unique($multisites));
    file_put_contents("$root/blt/blt.yml", Yaml::encode($blt_config));

    $this->say(sprintf('Remember to create the cron task. Run <info>gryphon:create-cron</info> to create the new cron job.'));
    $create_db = $this->ask('Would you like to create the database on Acquia now? (y/n)');
    if (str_starts_with(strtolower($create_db), 'y')) {
      $this->invokeCommand('gryphon:create-database');
    }
  }

  /**
   * Before creating the site settings files, copy the default site defaults.
   *
   * @hook pre-command blt:init:settings
   */
  public function preInitSettings() {
    $docroot = $this->getConfigValue('docroot');
    $dirs = glob("$docroot/sites/*/settings");
    $tasks = $this->collectionBuilder();
    foreach ($dirs as $dir) {
      $tasks->addTask($this->taskFilesystemStack()
        ->stopOnFail()
        ->setVerbosityThreshold(VerbosityThresholdInterface::VERBOSITY_VERBOSE)
        ->copy("$docroot/sites/default/settings/default.local.settings.php", "$dir/default.local.settings.php"));
    }
    $tasks->run();
  }

  /**
   * Copy the default global settings for local settings.
   *
   * @hook post-command blt:init:settings
   */
  public function postInitSettings() {
    $docroot = $this->getConfigValue('docroot');
    if (!file_exists("$docroot/sites/settings/local.settings.php")) {
      $this->taskFilesystemStack()
        ->stopOnFail()
        ->setVerbosityThreshold(VerbosityThresholdInterface::VERBOSITY_VERBOSE)
        ->copy("$docroot/sites/settings/default.local.settings.php", "$docroot/sites/settings/local.settings.php")
        ->run();

      $this->getConfig()
        ->expandFileProperties("$docroot/sites/settings/local.settings.php");
    }
    if (EnvironmentDetector::isLocalEnv()) {
      $this->invokeCommand('sws:keys');
    }
  }

  /**
   * Switch the context for the site that was copied.
   *
   * @hook pre-command artifact:ac-hooks:db-scrub
   */
  public function preDbScrub(CommandData $comand_data) {
    $args_options = $comand_data->getArgsAndOptions();
    // Databases should correlate directly to the site name. Except the default
    // directory which has a different database name. This allows the db scrub
    // drush command to operate on the correct database.
    $site = $args_options['db_name'] == 'stanfordsos' ? 'default' : $args_options['db_name'];
    $this->switchSiteContext($site);
  }

  /**
   * Get the url for the drush alias.
   *
   * @param string $site_name
   *   Site machine name, same as the directory.
   * @param string $environment
   *   Acquia environment.
   *
   * @return string
   *   Url that can be used in drush.
   */
  protected function getAliasUrl($site_name, $environment): string {
    $site_name = str_replace('_', '-', str_replace('__', '.', $site_name));
    if ($environment == 'local') {
      return $site_name;
    }

    $site_url = explode('.', $site_name, 2);
    if (count($site_url) >= 2) {
      [$site, $subdomain] = $site_url;
      return "$site-$environment.$subdomain.stanford.edu";
    }
    return "$site_name-$environment.stanford.edu";
  }

}
