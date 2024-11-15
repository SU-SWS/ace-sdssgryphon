<?php

/**
 * @file
 * sdss_profile.post_update
 */

/**
 * Implements hook_removed_post_updates().
 */
function sdss_profile_removed_post_updates() {
  return [
    'sdss_profile_post_update_8200' => '4.7.0',
    'sdss_profile_post_update_8202' => '4.7.0',
    'sdss_profile_post_update_update_field_defs' => '4.7.0',
    'sdss_profile_post_update_samlauth' => '4.7.0',
    'sdss_profile_post_update_site_orgs' => '4.7.0',
  ];
}
