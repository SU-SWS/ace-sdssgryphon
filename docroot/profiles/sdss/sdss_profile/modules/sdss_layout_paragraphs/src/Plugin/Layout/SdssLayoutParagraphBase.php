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
abstract class SdssLayoutParagraphBase extends LayoutDefault implements PluginFormInterface {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    $configuration = parent::defaultConfiguration();
    return $configuration + [
      'bg_color' => $this->getDefaultBgColor(),
      'bg_image' => $this->getDefaultBgImage(),
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
    $form['bg_image'] = [
      '#type' => 'select',
      '#title' => $this->t('Background image'),
      '#default_value' => $this->configuration['bg_image'],
      '#options' => $this->getBgImageOptions(),
      '#description' => $this->t('Choose the background image for this layout.'),
    ];
    return parent::buildConfigurationForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);
    $this->configuration['bg_color'] = $form_state->getValue('bg_color');
    $this->configuration['bg_image'] = $form_state->getValue('bg_image');
  }

  /**
   * {@inheritdoc}
   */
  public function build(array $regions) {
    $build = parent::build($regions);
    if ($this->configuration['bg_color'] !== 'none') {
      $build['#attributes']['class'][] = 'layout-paragraphs-sdss-bgcolor';
      $build['#attributes']['class'][] = 'layout-paragraphs-sdss-bgcolor--' . $this->configuration['bg_color'];
    }
    if ($this->configuration['bg_image'] !== 'none') {
      $build['#attributes']['class'][] = 'layout-paragraphs-sdss-bgimage';
      $build['#attributes']['class'][] = 'layout-paragraphs-sdss-bgimage--' . $this->configuration['bg_image'];
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
  protected function getBgColorOptions() {
    return [
      'none' => '- None -',
      'green' => 'Green',
      'green-stat' => 'Green Stat Card',
      'grey' => 'Light Grey',
      'grey-border' => 'Grey Border',
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

  /**
   * Gets the background image options for the configuration form.
   *
   * The first option will be used as the default 'bg_color' configuration
   * value.
   *
   * @return string[]
   *   The background image options array where the keys are strings that will
   *   be added to the CSS classes and the values are the human readable labels.
   */
  protected function getBgImageOptions() {
    return [
      'none' => '- None -',
      'circles' => 'Circles',
    ];
  }

  /**
   * Provides a default value for the background image options.
   *
   * @return string
   *   A key from the array returned by ::getBgImageOptions().
   */
  protected function getDefaultBgImage() {
    // Return the first available key from the list of options.
    $bg_image_classes = array_keys($this->getBgImageOptions());
    return array_shift($bg_image_classes);
  }

}
