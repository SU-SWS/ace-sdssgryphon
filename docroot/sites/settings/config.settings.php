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
  // Set stage_file_proxy settings.
  $config['stage_file_proxy.settings']['origin'] = "https://$site_name.stanford.edu";
  $config['stage_file_proxy.settings']['origin_dir'] = "sites/$site_name/files";
}
