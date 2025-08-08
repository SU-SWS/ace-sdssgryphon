<?php

namespace Drupal\sdss_person_title_override\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\Field\Attribute\FieldFormatter;

/**
 * Plugin implementation of the 'person_title_override' formatter.
 */
#[FieldFormatter(
  id: "person_title_override",
  label: new TranslatableMarkup("Person Title Override"),
  field_types: ["string", "string_long", "text", "text_long"],
)]
class PersonTitleOverrideFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    $entity = $items->getEntity();
    $field_name = $items->getFieldDefinition()->getName();
    $service = \Drupal::service('sdss_person_title_override.sdss_person_title');
    $value = '';
    if ($field_name === 'su_person_full_title') {
      $value = $service->getDisplayFullTitle($entity);
    }
    elseif ($field_name === 'su_person_short_title') {
      $value = $service->getDisplayShortTitle($entity);
    }
    else {
      \Drupal::messenger()->addWarning($this->t('PersonTitleOverrideFormatter: This formatter is intended for Full or Short title fields. Default field value will be used.'));
      $value = $items[0]->value ?? '';
    }
    $elements[0] = [
      '#markup' => $value,
    ];
    return $elements;
  }
}
