<?php

// @codingStandardsIgnoreFile

/**
 * @file
 * Configuration file for multi-site support and directory aliasing feature.
 *
 * This file is required for multi-site support and also allows you to define a
 * set of aliases that map hostnames, ports, and pathnames to configuration
 * directories in the sites directory. These aliases are loaded prior to
 * scanning for directories, and they are exempt from the normal discovery
 * rules. See default.settings.php to view how Drupal discovers the
 * configuration directory when no alias is found.
 *
 * Aliases are useful on development servers, where the domain name may not be
 * the same as the domain of the live server. Since Drupal stores file paths in
 * the database (files, system table, etc.) this will ensure the paths are
 * correct when the site is deployed to a live server.
 *
 * To activate this feature, copy and rename it such that its path plus
 * filename is 'sites/sites.php'.
 *
 * Aliases are defined in an associative array named $sites. The array is
 * written in the format: '<port>.<domain>.<path>' => 'directory'. As an
 * example, to map https://www.drupal.org:8080/mysite/test to the configuration
 * directory sites/example.com, the array should be defined as:
 * @code
 * $sites = [
 *   '8080.www.drupal.org.mysite.test' => 'example.com',
 * ];
 * @endcode
 * The URL, https://www.drupal.org:8080/mysite/test/, could be a symbolic link
 * or an Apache Alias directive that points to the Drupal root containing
 * index.php. An alias could also be created for a subdomain. See the
 * @link https://www.drupal.org/documentation/install online Drupal
 *   installation guide @endlink for more information on setting up domains,
 *   subdomains, and subdirectories.
 *
 * The following examples look for a site configuration in sites/example.com:
 * @code
 * URL: http://dev.drupal.org
 * $sites['dev.drupal.org'] = 'example.com';
 *
 * URL: http://localhost/example
 * $sites['localhost.example'] = 'example.com';
 *
 * URL: http://localhost:8080/example
 * $sites['8080.localhost.example'] = 'example.com';
 *
 * URL: https://www.drupal.org:8080/mysite/test/
 * $sites['8080.www.drupal.org.mysite.test'] = 'example.com';
 * @endcode
 *
 * @see default.settings.php
 * @see \Drupal\Core\DrupalKernel::getSitePath()
 * @see https://www.drupal.org/documentation/install/multi-site
 */

// Create an array of all the site directories in /sites with a settings.php
// file.
$sites_settings = glob(__DIR__ . '/*/settings.php');

// Loop through the site directories in the multi-site to point all possible
// domains to the correct site directory. The domains to point are based on the
// site directory naming conventions. If a domain and path are not correctly
// named, they will have to be added to this file manually.
foreach ($sites_settings as $settings_file) {
  $site_dir = str_replace(__DIR__ . '/', '', $settings_file);
  $site_dir = str_replace('/settings.php', '', $site_dir);

  if ($site_dir == 'default') {
    continue;
  }

  // Get the site name to use for domains from the directory and replace:
  // - Underscores "_" with dashes "-".
  // - Double underscores "__" with dots ".".
  $sitename = str_replace('_', '-', str_replace('__', '.', $site_dir));
  $sites[$sitename] = $site_dir;
  $sites["$sitename.stanford.edu"] = $site_dir;

  $sitename = explode('.', $sitename);

  // Use the sitename to point all possible domains to the site directory.
  foreach (['-dev', '-test', '-prod'] as $environment) {
    $environment_sitename = $sitename;
    $environment_sitename[0] .= $environment;
    $sites[implode('.', $environment_sitename) . '.stanford.edu'] = $site_dir;
  }
}

// Manually point LIVE domains that don't fit naming conventions here.
// E.g., $sites['<domain>'] = '<directory>';
// E.g., $sites['mysite.stanford.edu'] = 'my_site';
$sites['earthsystems.stanford.edu'] = 'esys';
$sites['eep.stanford.edu'] = 'gep';
$sites['energypostdoc.stanford.edu'] = 'sepf';
$sites['epsci.stanford.edu'] = 'gs';
$sites['esos.stanford.edu'] = 'environmentalsocialsci';
$sites['integrative-design-for-radical-energy-efficiency.stanford.edu'] = 'extreme_energy_efficiency';
$sites['nzea.stanford.edu'] = 'nza';
$sites['ssip.stanford.edu'] = 'sustainpathways';
$sites['sustainabilityleadership.stanford.edu'] = 'changeleadership';
$sites['understand-energy.stanford.edu'] = 'understandenergy';

// Manually point dev/test domains here.
$sites['understand-energy-dev.stanford.edu'] = 'understandenergy';
$sites['understand-energy-test.stanford.edu'] = 'understandenergy';

// Hopkins Marine Station dev, test, and prod URL's currently exist on another
// ACE stack. We need to point custom aliases to build the site.
$sites['hms-sdss-dev.stanford.edu'] = 'hopkinsmarinestation';
$sites['hms-sdss-test.stanford.edu'] = 'hopkinsmarinestation';
$sites['hms-sdss-prod.stanford.edu'] = 'hopkinsmarinestation';


// Include local sites.
if (file_exists(__DIR__ . '/local.sites.php')) {
  require __DIR__ . '/local.sites.php';
}

// Include fallback option for site specification.
$file = '/mnt/files/' . getenv('AH_SITE_GROUP') . '.' . getenv('AH_SITE_ENVIRONMENT') . '/sdssgryphon-sites.php';
if (file_exists($file)) {
  require $file;
}
