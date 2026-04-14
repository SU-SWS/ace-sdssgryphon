<?php

// @codingStandardsIgnoreFile

/**
 * @file
 * Maps local development domains to multisite directories.
 *
 * Uses *.sdss.lndo.site (Lando wildcard proxy).
 * Copied to docroot/sites/local.sites.php during Lando build.
 */

$settings = glob(__DIR__ . '/*/settings.php');
foreach ($settings as $settings_file) {
  $site_dir = str_replace(__DIR__ . '/', '', $settings_file);
  $site_dir = str_replace('/settings.php', '', $site_dir);

  if ($site_dir == 'default') {
    continue;
  }

  // Convert directory name to domain-safe name:
  // double underscores -> periods first, then single underscores -> dashes.
  $sitename = str_replace('_', '-', str_replace('__', '.', $site_dir));

  // Lando wildcard proxy domain (no /etc/hosts needed).
  $sites["$sitename.sdss.lndo.site"] = $site_dir;
}
