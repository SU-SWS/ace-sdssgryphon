<?php

/**
 * @file
 * Contains any config overrides.
 */

use Acquia\Blt\Robo\Common\EnvironmentDetector;

if (!EnvironmentDetector::isProdEnv()) {
  $config['domain_301_redirect.settings']['enabled'] = FALSE;
//  $config['stanford_syndication.settings']['enabled'] = FALSE;
}

// Set stage_file_proxy URL on non-production environments.
if (!EnvironmentDetector::isProdEnv()) {

  // Get the site name and replace:
  // - Underscores "_" with dashes "-".
  // - Double underscores "__" with dots ".".
  $site_alias = str_replace('_', '-', str_replace('__', '.', $site_name));

  // Set stage_file_proxy settings.
  $config['stage_file_proxy.settings']['origin'] = "https://$site_alias-prod.stanford.edu";
  $config['stage_file_proxy.settings']['origin_dir'] = "sites/$site_name/files";
}
