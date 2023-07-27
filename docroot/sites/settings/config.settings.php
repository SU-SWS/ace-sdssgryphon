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

  // Get the site name and replace:
  // - Underscores "_" with dashes "-".
  // - Double underscores "__" with dots ".".
  $sitename = str_replace('_', '-', str_replace('__', '.', $site_name));

  // Set stage_file_proxy settings.
  $config['stage_file_proxy.settings']['origin'] = "https://$sitename-prod.stanford.edu";
  $config['stage_file_proxy.settings']['origin_dir'] = "sites/$sitename/files";
}
