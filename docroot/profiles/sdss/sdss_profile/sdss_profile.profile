<?php

/**
 * @file
 * sdss_profile.profile
 */

use Drupal\config_pages\Entity\ConfigPages;
use Drupal\Core\Installer\InstallerKernel;

/**
 * Implements hook_install_tasks().
 */
function sdss_profile_install_tasks(&$install_state) {
  return ['sdss_profile_final_task' => []];
}

/**
 * Perform final tasks after the profile has completed installing.
 *
 * @param array $install_state
 *   Current install state.
 */
function sdss_profile_final_task(array &$install_state) {
  \Drupal::service('plugin.manager.install_tasks')->runTasks($install_state);
}

/**
 * Implements hook_ENTITY_TYPE_presave().
 */
function sdss_profile_config_pages_presave(ConfigPages $config_page) {
  // During install, rebuild the router when saving a config page. This prevents
  // an error if the config page route doesn't exist for it yet. Event
  // subscriber doesn't work for this since it's during installation.
  if (InstallerKernel::installationAttempted()) {
    \Drupal::service('router.builder')->rebuild();
  }
}

/**
 * Implements hook_entity_reference_field_options_alter().
 *
 * Limit the available options for the su_page_components field on stanford_page
 * nodes to only allow certain paragraph types on the front page node.
 */
function sdss_profile_entity_reference_field_options_alter(array &$options, array $context) {
  dpm('test');
  if (
    $context['field_definition']->getName() === 'su_page_components' &&
    $context['entity']->bundle() === 'stanford_page'
  ) {
    $node = $context['entity'];
    dpm($node);
    // Get the front page node ID from config.
    $front = \Drupal::config('system.site')->get('page.front');
    dpm($front);
    $front_nid = NULL;
    if ($front && preg_match('/^node\/(\d+)$/', $front, $matches)) {
      $front_nid = $matches[1];
    }
    dpm($front_nid);
    // Restrict if not the front page node.
    if ($node->id() != $front_nid) {
      unset($options['paragraphs.paragraphs_type.su_pinned_background_section']);
      unset($options['paragraphs.paragraphs_type.su_image_cta']);
      unset($options['paragraphs.paragraphs_type.su_departments_slider']);
      unset($options['paragraphs.paragraphs_type.su_horizontal_bar']);
      unset($options['paragraphs.paragraphs_type.su_header_with_link']);
    }
  }
}
