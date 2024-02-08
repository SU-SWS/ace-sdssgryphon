<?php

/**
 * @file
 * Generated by BLT. Serves as an example of global includes.
 */

use Acquia\Blt\Robo\Common\EnvironmentDetector;

// When the encryption environment variable is not provided (local/ci/etc),
// fake the encryption string so that the site doesn't break.
if (!getenv('STANFORD_ENCRYPT')) {
  putenv("STANFORD_ENCRYPT=" . substr(file_get_contents("$repo_root/salt.txt"), 0, 32));
}

/**
 * An example global include file.
 *
 * To use this file, rename to global.settings.php.
 */

$settings['file_temp_path'] = '/tmp';

if (EnvironmentDetector::isAhEnv()) {
  // Set the temp directory as per https://docs.acquia.com/acquia-cloud/manage/files/broken/
  $settings['file_temp_path'] = '/mnt/gfs/' . EnvironmentDetector::getAhGroup() . '.' . EnvironmentDetector::getAhEnv() . '/tmp';
  $settings['letsencrypt_challenge_directory'] = $settings['file_temp_path'];

  // Lock the UI to read_only when on production or test in Acquia.
  if (
    (EnvironmentDetector::isAhProdEnv() || EnvironmentDetector::isAhStageEnv())
    && PHP_SAPI !== 'cli'
  ) {
    $settings['config_readonly'] = TRUE;
    $settings['config_readonly_whitelist_patterns'] = [
      'system.menu.*',
      'core.menu.static_menu_link_overrides',
      'system.theme',
      'metatag.metatag_defaults.*',
      'google_tag.container.*',
      'google_tag.settings',
      'user.role.*',
      'system.action.user_add_role_action.*',
      'system.action.user_remove_role_action.*',
      'samlauth.authentication',
      'stanford_earth_r25.adminsettings',
      'stanford_earth_r25.credentialsettings',
      'stanford_earth_r25.stanford_earth_r25.*',
    ];
    $settings['config_readonly_content_link_providers'] = [
      'menu_link_content',
      'menu_link',
    ];
  }
}

/**
 * This can be overridden for individual sites. Add or modify this value in
 * `docroot/sites/{site-name}/settings/includes.settings.php` for the respective
 * site. See related information below.
 */
$settings['config_sync_directory'] = DRUPAL_ROOT . '/profiles/sdss/sdss_profile/config/sync';

// Block the bots when not on production.
if (!EnvironmentDetector::isAhProdEnv()) {
  $settings['nobots'] = TRUE;
}

/**
 * Include settings files in docroot/sites/settings.
 *
 * If instead you want to add settings to a specific site, see BLT's includes
 * file in docroot/sites/{site-name}/settings/default.includes.settings.php.
 */
$additionalSettingsFiles = [
  __DIR__ . '/config.settings.php',
  __DIR__ . '/environment_indicator.settings.php',
  __DIR__ . '/fast404.settings.php',
  __DIR__ . '/google_analytics.settings.php',
  __DIR__ . '/saml.settings.php',
  __DIR__ . '/xmlsitemap.settings.php',
];

foreach ($additionalSettingsFiles as $settingsFile) {
  if (file_exists($settingsFile)) {
    require $settingsFile;
  }
}

if (file_exists(__DIR__ . '/local.settings.php')) {
  require __DIR__ . '/local.settings.php';
}
