<?php

namespace Drupal\sdss_person_title_override\Plugin\Field\FieldType;

use Drupal\Core\Field\Attribute\FieldType;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\Field\FieldItemList;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\TypedData\ComputedItemListTrait;

/**
 * Computed field for full title with alt override.
 */
#[FieldType(
  id: "sdss_computed_full_title",
  label: new TranslatableMarkup("Full Title (with Alt Override)"),
  description: new TranslatableMarkup("Displays alt_title if set, otherwise full_title."),
  default_widget: "string_textfield",
  default_formatter: "full_title_override",
  constraints: [
    "ValidReference" => [],
  ],
)]
class ComputedFullTitle extends FieldItemList implements FieldItemListInterface {
  use ComputedItemListTrait;

  /**
   * Computes the value for the full title field.
   *
   * Uses the alt_title field if present; otherwise falls back to full_title.
   * Throws LogicException if required fields are missing.
   */
  protected function computeValue() {
    $node = $this->getEntity();
    if ($node->bundle() == 'stanford_person') {
      /** @var \Drupal\sdss_profile\Service\SdssPersonTitleService $title_service */
      $title_service = \Drupal::service('sdss_profile.person_title_service');
      $title = $title_service->getDisplayFullTitle($node);
      $this->list[0] = $this->createItem(0, $title);
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultStorageSettings() {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultFieldSettings() {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(\Drupal\Core\Field\FieldStorageDefinitionInterface $field_definition) {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public static function mainPropertyName() {
    return 'value';
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(\Drupal\Core\Field\FieldStorageDefinitionInterface $field_definition) {
    $properties['value'] = \Drupal\Core\TypedData\DataDefinition::create('string')
      ->setLabel(t('Text value'));
    return $properties;
  }
}
