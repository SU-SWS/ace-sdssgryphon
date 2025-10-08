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

  // SDSS subtheme hard-codes the brand bar to a specific variant. Remove the
  // options for the brand bar settings from the stanford_basic theme.
  unset($form['options_settings']['stanford_basic_brand_bar']);

  // SDSS subtheme hard-codes the footer to a specific variant. Remove the
  // options for the footer settings from the stanford_basic theme.
  unset($form['options_settings']['stanford_basic_global_footer']);

  // Hide the logo upload field and show info message only if lockup config and
  // menu.path are available.
  $lockup_config = \Drupal::config('config_pages.type.lockup_settings');
  $lockup_path = $lockup_config->get('menu.path');
  if ($lockup_config && !empty($lockup_path)) {
    // Logo options are managed on the Lockup Settings page and values are
    // overridden. Hide the field and unset values to prevent confusion.
    if (isset($form['logo'])) {
      $form['logo']['#access'] = FALSE;
    }
    if (isset($form['options_settings']['logo'])) {
      $form['options_settings']['logo']['#access'] = FALSE;
    }
    $form['options_settings']['logo_lockup'] = [
      '#type' => 'fieldset',
      '#title' => t('Logo and Lockup Settings'),
    ];
    $form['options_settings']['logo_lockup']['sdss_subtheme_logo_info'] = [
      '#type' => 'item',
      '#markup' => t('Logo and lockup options are managed on the <a href=":url">Lockup Settings</a> config page.', [':url' => $lockup_path]),
      '#description' => t('Configure the logo and lockup options using the Lockup Settings page.'),
    ];
  }

  // Header options.
  $form['options_settings']['sdss_subtheme_header_options'] = [
    '#type' => 'fieldset',
    '#title' => t('Header Options'),
  ];

  $form['options_settings']['sdss_subtheme_header_options']['header_layout_variant'] = [
    '#type' => 'select',
    '#title' => t('Header Layout Variant'),
    '#options' => [
      'option_a' => t('White header, logo only, right navigation (for select sites only)'),
    ],
    '#empty_option' => t('Green header, logo + site title, bottom navigation (recommended/default)'),
    '#default_value' => theme_get_setting('header_layout_variant'),
  ];

  $dropdowns_enabled = theme_get_setting('nav_dropdown_enabled', 'stanford_basic');
  $form['options_settings']['sdss_subtheme_header_options']['desktop_hamburger'] = [
    '#type' => 'checkbox',
    '#title' => t('Use Hamburger Menu on Desktop'),
    '#default_value' => theme_get_setting('desktop_hamburger'),
    '#description' => t('Use a hamburger (collapsible) menu on desktop instead of the regular menu with drop-down navigation. <strong>Note: You must also enable the "Use Drop Down Menus" option on the <a href=":url">Basic Site Settings</a> page.</strong>', [':url' => '/admin/config/system/basic-site-settings']),
    '#disabled' => !$dropdowns_enabled,
  ];

  $form['options_settings']['sdss_subtheme_header_options']['display_utility_navigation'] = [
    '#type' => 'checkbox',
    '#title' => t('Display Utility Navigation'),
    '#default_value' => theme_get_setting('display_utility_navigation'),
    '#description' => t('Display the utility navigation menu in the header.'),
    '#states' => [
      'visible' => [
        ':input[name="desktop_hamburger"]' => ['checked' => TRUE],
      ],
    ],
  ];

  // Add checkbox for homepage body class.
  $form['options_settings']['add_homepage_body_class'] = [
    '#type' => 'checkbox',
    '#title' => t('Add Site Specific Homepage Body Class'),
    '#default_value' => theme_get_setting('add_homepage_body_class'),
    '#description' => t('Add a special body class to the front page of this site, using the site name (e.g., <code>sitename-homepage</code>).'),
  ];
}

// @codeCoverageIgnoreEnd
