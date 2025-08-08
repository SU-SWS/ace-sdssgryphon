<?php

namespace Drupal\sdss_person_title_override\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\Attribute\FieldFormatter;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Plugin implementation of the 'alt_title_override' formatter.
 */
#[FieldFormatter(
  id: "alt_title_override",
  label: new TranslatableMarkup("Alt Title Override"),
  field_types: ["sdss_computed_full_title", "sdss_computed_short_title"],
)]
class AltTitleOverrideFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    foreach ($items as $delta => $item) {
      $elements[$delta] = [
        '#markup' => $item->value,
      ];
    }
    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    return [];
  }

}
