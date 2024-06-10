<?php

namespace Drupal\sdss_layout_paragraphs\Plugin\Layout;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\PluginFormInterface;
use Drupal\Core\Layout\LayoutDefault;


/**
 * Base class of layouts with configurable options.
 *
 * @internal
 *   Plugin classes are internal.
 */
abstract class SdssLayoutParagraphBaseTwoCol extends SdssLayoutParagraphBase implements PluginFormInterface {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    $configuration = parent::defaultConfiguration();
    return $configuration + [
      'col_width' => $this->getWidthOptions(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form['col_width'] = [
      '#type' => 'select',
      '#title' => $this->t('Column widths'),
      '#default_value' => $this->configuration['col_width'],
      '#options' => $this->getWidthOptions(),
      '#description' => $this->t('Choose the column widths for this layout.'),
    ];
    return parent::buildConfigurationForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);
    $this->configuration['col_width'] = $form_state->getValue('col_width');
  }

  /**
   * {@inheritdoc}
   */
  public function build(array $regions) {
    $build = parent::build($regions);
    if($this->configuration['col_width'] !== 'none') {
      $build['#attributes']['class'][] = 'layout--layout-paragraphs-sdss-two-column';
      $build['#attributes']['class'][] = 'layout--layout-paragraphs-sdss-two-column--' . $this->configuration['col_width'];
    }
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
  protected function getWidthOptions() {
    return $array = [
      '50-50' => 'Equal Columns',
      '33-67' => 'Larger Right Column',
      '67-33' => 'Larger Left Column',
    ];
  }

  /**
   * Provides a default value for the background color options.
   *
   * @return string
   *   A key from the array returned by ::getBgColorOptions().
   */
  protected function getDefaultWidthOptions() {
    // Return the first available key from the list of options.
    $col_width_classes = array_keys($this->getWidthOptions());
    return array_shift($col_width_classes);
  }
}
