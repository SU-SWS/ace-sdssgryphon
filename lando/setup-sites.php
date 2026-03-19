#!/usr/bin/env php
<?php

/**
 * @file
 * Lando build script: patches local.settings.php files for Lando compatibility.
 *
 * Run after `blt blt:init:settings` to update database credentials and
 * fix the database name for each multisite. BLT generates all sites with
 * the same hardcoded database name; this script sets it to drupal_SITENAME.
 */

$sites_dir = '/app/docroot/sites';
$files = glob($sites_dir . '/*/settings/local.settings.php');

if (empty($files)) {
  echo "No local.settings.php files found. Ensure blt:init:settings has run.\n";
  exit(0);
}

$count = 0;
foreach ($files as $file) {
  $content = file_get_contents($file);
  if ($content === false) {
    echo "Warning: Could not read $file\n";
    continue;
  }

  // Determine the site directory name (e.g., "sustainability", "woods").
  $site_dir = basename(dirname($file, 2));
  $db_name = 'drupal_' . $site_dir;

  $updated = $content;

  // Fix database credentials.
  $updated = str_replace(
    ["'username' => 'root'", "'password' => 'password'", "'host' => 'localhost'"],
    ["'username' => 'drupal'", "'password' => 'drupal'", "'host' => 'database'"],
    $updated
  );

  // Migration from old Lando setup which used a per-site MySQL service.
  $updated = str_replace("'host' => 'sustainability'", "'host' => 'database'", $updated);

  // Fix the database name. BLT hardcodes the same name for all sites;
  // we need each site to use its own database (drupal_SITENAME).
  // Match the $db_name assignment regardless of what BLT set it to.
  $updated = preg_replace(
    '/\$db_name\s*=\s*[\'"][^\'"]+[\'"];/',
    "\$db_name = '$db_name';",
    $updated
  );

  // Update deprecated database driver namespace for Drupal 10+.
  $updated = str_replace(
    ["Drupal\\\\Core\\\\Database\\\\Driver\\\\mysql", "'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql'"],
    ["Drupal\\\\mysql\\\\Driver\\\\Database\\\\mysql", "'namespace' => 'Drupal\\mysql\\Driver\\Database\\mysql'"],
    $updated
  );

  if ($updated !== $content) {
    if (file_put_contents($file, $updated) !== false) {
      $count++;
    } else {
      echo "Warning: Could not write $file\n";
    }
  }
}

echo "Updated $count local.settings.php file(s) for Lando.\n";
