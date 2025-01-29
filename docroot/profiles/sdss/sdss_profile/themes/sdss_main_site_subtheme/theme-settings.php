<?php

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Link;

// @codeCoverageIgnoreStart

/**
 * Implements hook_form_system_theme_settings_alter().
 */
function sdss_main_sitesubtheme_form_system_theme_settings_alter(array &$form, FormStateInterface $form_state)
{

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
}

// @codeCoverageIgnoreEnd
