<?php

use Drupal\Core\Form\FormStateInterface;

// @codeCoverageIgnoreStart

// Set theme name to use in the key values.
$theme_name = \Drupal::theme()->getActiveTheme()->getName();

/**
 * Implements hook_form_system_theme_settings_alter().
 */
function stanford_basic_form_system_theme_settings_alter(array &$form, FormStateInterface $form_state) {

  $form['options_settings'] = [
    '#type' => 'fieldset',
    '#title' => t('Theme Specific Settings'),
  ];

  // Brand bar support.
  $form['options_settings']['stanford_basic_brand_bar'] = [
    '#type' => 'fieldset',
    '#title' => t('Brand Bar Settings'),
  ];

  $form['options_settings']['stanford_basic_brand_bar']['brand_bar_variant'] = [
    '#type' => 'select',
    '#title' => t('Brand Bar Variant'),
    '#options' => [
      'default' => '- Default -',
      'bright' => t('Bright'),
      'dark' => t('Dark'),
      'white' => t('White'),
    ],
    '#default_value' => theme_get_setting('brand_bar_variant'),
  ];

  // Global footer support.
  $form['options_settings']['stanford_basic_global_footer'] = [
    '#type' => 'fieldset',
    '#title' => t('Global Footer Settings'),
  ];

  $form['options_settings']['stanford_basic_global_footer']['global_footer_variant'] = [
    '#type' => 'select',
    '#title' => t('Global Footer Variant'),
    '#options' => [
      'dark' => t('Dark'),
    ],
    '#empty_option' => t('- Default -'),
    '#default_value' => theme_get_setting('global_footer_variant'),
  ];

  $form['options_settings']['stanford_basic_lockup'] = [
    '#type' => 'fieldset',
    '#title' => t('Lockup Settings'),
    '#field_prefix' => "",
  ];

  $form['options_settings']['stanford_basic_lockup']['lockup']['#tree'] = TRUE;

  $form['options_settings']['stanford_basic_lockup']['lockup']['option'] = [
    '#type' => 'select',
    '#title' => t('Lockup Options'),
    '#options' => [
      'none' => t('Logo Only'),
      'a' => t('Option A'),
      'b' => t('Option B'),
    ],
    '#default_value' => theme_get_setting('lockup.option') ?? 'a',
    '#description' => t("Layout options."),
  ];

  $form['options_settings']['stanford_basic_lockup']['lockup']['line1'] = [
    '#type' => 'textfield',
    '#title' => t('Line 1'),
    '#default_value' => theme_get_setting('lockup.line1'),
    '#description' => t("Site title line."),
    '#states' => [
      'invisible' => [
        [':input[name="lockup[option]"]' => ['value' => 'none']],
      ],
    ],
  ];

  $form['options_settings']['stanford_basic_lockup']['lockup']['line2'] = [
    '#type' => 'textfield',
    '#title' => t('Line 2'),
    '#default_value' => theme_get_setting('lockup.line2'),
    '#description' => t("Secondary title line."),
    '#states' => [
      'invisible' => [
        [':input[name="lockup[option]"]' => ['value' => 'none']],
        [':input[name="lockup[option]"]' => ['value' => 'a']],
      ],
    ],
  ];

  // BrowserSync support.
  $form['options_settings']['stanford_basic_browser_sync'] = [
    '#type' => 'fieldset',
    '#title' => t('BrowserSync Settings'),
  ];
  $form['options_settings']['stanford_basic_browser_sync']['browser_sync']['#tree'] = TRUE;
  $form['options_settings']['stanford_basic_browser_sync']['browser_sync']['enabled'] = [
    '#type' => 'checkbox',
    '#title' => t('Enable BrowserSync support for theme'),
    '#default_value' => theme_get_setting('browser_sync.enabled'),
    '#description' => t("Checking this box will automatically add the BrowserSync JS to your theme for development."),
  ];

  $form['options_settings']['stanford_basic_browser_sync']['browser_sync']['host'] = [
    '#type' => 'textfield',
    '#title' => t('BrowserSync host'),
    '#default_value' => theme_get_setting('browser_sync.host'),
    '#description' => t("Default: localhost. Enter 'HOST' which will be replaced by your site's hostname."),
    '#states' => [
      'visible' => [':input[name="browser_sync[enabled]"]' => ['checked' => TRUE]],
    ],
  ];

  $form['options_settings']['stanford_basic_browser_sync']['browser_sync']['port'] = [
    '#type' => 'number',
    '#title' => t('Enable BrowserSync support for theme'),
    '#default_value' => theme_get_setting('browser_sync.port'),
    '#description' => t("Default: '3000'."),
    '#states' => [
      'visible' => [':input[name="browser_sync[enabled]"]' => ['checked' => TRUE]],
    ],
  ];

  // Menu options.
  $form['options_settings']['stanford_basic_menu_options'] = [
    '#type' => 'fieldset',
    '#title' => t('Menu Options'),
  ];

  $form['options_settings']['stanford_basic_menu_options']['hamburger_menu'] = [
    '#type' => 'select',
    '#title' => t('Hamburger Menu'),
    '#options' => [
      'mobile_only' => t('Mobile Only'),
      'all_widths' => t('All widths'),
    ],
    '#default_value' => theme_get_setting('hamburger_menu') ?? 'mobile_only',
  ];

}

// @codeCoverageIgnoreEnd
