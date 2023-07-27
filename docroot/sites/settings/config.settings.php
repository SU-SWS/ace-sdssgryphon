<?php

/**
 * @file
 * Contains any config overrides.
 */

use Acquia\Blt\Robo\Common\EnvironmentDetector;

if (!EnvironmentDetector::isProdEnv()) {
  $config['domain_301_redirect.settings']['enabled'] = FALSE;
}

// Set stage_file_proxy URL on non-production environments.
if (!EnvironmentDetector::isProdEnv()) {
  // Get the site path.
  $site_path = \Drupal\Core\DrupalKernel::findSitePath(\Drupal::request());
  // Get site alias.
  $site_path = explode('/', $site_path);
  $site_alias = $site_path[1];
  // Set stage_filx_proxy settings.
  $config['stage_file_proxy.settings']['origin'] = "https://$site_alias.stanford.edu";
  $config['stage_file_proxy.settings']['origin_dir'] = "sites/$site_alias/files";
}
