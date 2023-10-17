<?php

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Link;

// @codeCoverageIgnoreStart

// Set theme name to use in the key values.
$theme_name = \Drupal::theme()->getActiveTheme()->getName();

/**
 * Implements hook_form_system_theme_settings_alter().
 */
function sdss_subtheme_form_system_theme_settings_alter(array &$form, FormStateInterface $form_state)
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
      'default' => t('Green header, Bottom navigation'),
      'option_a' => t('Blue header, Right navigation, Logo only'),
    ],
    '#default_value' => theme_get_setting('header_layout_variant'),
  ];
}

// @codeCoverageIgnoreEnd
