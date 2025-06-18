<?php

/**
 * @file
 * Theme settings for the SDSS Subtheme.
 */

use Drupal\Core\Form\FormStateInterface;

// @codeCoverageIgnoreStart

/**
 * Implements hook_form_system_theme_settings_alter().
 */
function sdss_subtheme_form_system_theme_settings_alter(array &$form, FormStateInterface $form_state) {

  // Header options.
  $form['options_settings']['sdss_subtheme_header_options'] = [
    '#type' => 'fieldset',
    '#title' => t('Header Options'),
  ];

  $form['options_settings']['sdss_subtheme_header_options']['header_layout_variant'] = [
    '#type' => 'select',
    '#title' => t('Header Layout Variant'),
    '#options' => [
      'option_a' => t('Blue header, Right navigation, Logo only'),
    ],
    '#empty_option' => t('Green header, Bottom navigation'),
    '#default_value' => theme_get_setting('header_layout_variant'),
  ];

  $form['options_settings']['sdss_subtheme_header_options']['desktop_hamburger'] = [
    '#type' => 'checkbox',
    '#title' => t('Use Hamburger Menu on Desktop'),
    '#default_value' => theme_get_setting('desktop_hamburger'),
    '#description' => t('Use a hamburger (collapsible) menu on desktop instead of the regular menu with drop-down navigation.'),
  ];
}

// @codeCoverageIgnoreEnd
