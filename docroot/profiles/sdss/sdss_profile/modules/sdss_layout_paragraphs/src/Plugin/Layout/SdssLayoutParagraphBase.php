<?php

namespace Drupal\sdss_layout_paragraphs\Plugin\Layout;

use Drupal\Core\Form\FormStateInterface;
use Drupal\layout_builder\Plugin\Layout\MultiWidthLayoutBase;

/**
 * Base class of layouts with configurable options.
 *
 * @internal
 *   Plugin classes are internal.
 */
abstract class SdssLayoutParagraphBase extends MultiWidthLayoutBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    $configuration = parent::defaultConfiguration();
    return $configuration + [
      'bg_color' => $this->getDefaultBgColor(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form['bg_color'] = [
      '#type' => 'select',
      '#title' => $this->t('Background Color'),
      '#default_value' => $this->configuration['bg_color'],
      '#options' => $this->getBgColorOptions(),
      '#description' => $this->t('Choose the background color for this layout.'),
    ];
    return parent::buildConfigurationForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);
    $this->configuration['bg_color'] = $form_state->getValue('bg_color');
  }

  /**
   * {@inheritdoc}
   */
  public function build(array $regions) {
    $build = parent::build($regions);
    $build['#attributes']['class'] = [
      'layout',
      'sdss-layout-bgcolor--' . $this->configuration['bg_color'],
      $this->getPluginDefinition()->getTemplate(),
    ];
    return $build;
  }

  /**
   * Gets the background color options for the configuration form.
   *
   * The first option will be used as the default 'bg_color' configuration
   * value.
   *
   * @return string[]
   *   The background color options array where the keys are strings that will
   *   be added to the CSS classes and the values are the human readable labels.
   */
  protected function getBgColorOptions() {
    return $array = [
      'default' => '- Default -',
    ];
  }

  /**
   * Provides a default value for the background color options.
   *
   * @return string
   *   A key from the array returned by ::getBgColorOptions().
   */
  protected function getDefaultBgColor() {
    // Return the first available key from the list of options.
    $bg_color_classes = array_keys($this->getBgColorOptions());
    return array_shift($bg_color_classes);
  }
}
