<?php

/**
 * @file
 * Stanford Basic theme settings.
 */

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Link;

// @codeCoverageIgnoreStart
// Set theme name to use in the key values.
$theme_name = \Drupal::theme()->getActiveTheme()->getName();

/**
 * Implements hook_form_system_theme_settings_alter().
 */
function stanford_basic_form_system_tsheme_settings_alter(array &$form, FormStateInterface $form_state) {

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

  $img = '<img src="' . base_path() . \Drupal::service('extension.list.theme')
    ->getPath('stanford_basic') . '/dist/assets/img/lockup-example.png" />';
  $decanter = Link::fromTextAndUrl('Decanter Lockup Component', Url::fromUri('https://decanter.stanford.edu/component/identity-lockup/'))
    ->toString();

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

}

// @codeCoverageIgnoreEnd
