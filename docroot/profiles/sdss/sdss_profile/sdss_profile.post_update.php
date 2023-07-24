<?php

/**
 * @file
 * sdss_profile.post_update
 */

use Drupal\block_content\Entity\BlockContent;
use Drupal\block\Entity\Block;

/**
 * Disable the core search module.
 */
function sdss_profile_post_update_8200() {
  \Drupal::service('module_installer')->uninstall(['search']);
}

/**
 * Add the main anchor block to the search page.
 */
function sdss_profile_post_update_8202() {
  $theme_name = \Drupal::config('system.theme')->get('default');
  if (!in_array($theme_name, [
    'sdss_subtheme',
    'stanford_basic',
    'minimally_branded_subtheme',
  ])) {
    Block::create([
      'id' => "{$theme_name}_main_anchor",
      'theme' => $theme_name,
      'region' => 'content',
      'weight' => -10,
      'plugin' => 'jumpstart_ui_skipnav_main_anchor',
      'settings' => [
        'id' => 'jumpstart_ui_skipnav_main_anchor',
        'label' => 'Main content anchor target',
        'label_display' => 0,
      ],
      'visibility' => [
        'request_path' => [
          'id' => 'request_path',
          'negate' => FALSE,
          'pages' => '/search',
        ],
      ],
    ])->save();
  }
}
