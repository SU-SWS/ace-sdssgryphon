<?php

namespace Drupal\sdss_person_title_override\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemList;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\TypedData\ComputedItemListTrait;

/**
 * Computed field for short title with alt override.
 *
 * @FieldType(
 *   id = "sdss_computed_short_title",
 *   label = @Translation("Short Title (with Alt Override)"),
 *   description = @Translation("Displays alt_title if set, otherwise short_title."),
 *   category = @Translation("Custom"),
 *   default_widget = "string_textfield",
 *   default_formatter = "string"
 * )
 */
class ComputedShortTitle extends FieldItemList implements FieldItemListInterface {
  use ComputedItemListTrait;

  /**
   * Computes the value for the short title field.
   *
   * Uses the alt_title field if present; otherwise falls back to short_title.
   * Throws LogicException if required fields are missing.
   */
  protected function computeValue() {
    $node = $this->getEntity();
    if ($node->bundle() == 'stanford_person') {
      if (
        !$node->hasField('su_sdss_person_alt_title') ||
        !$node->hasField('su_person_short_title')
      ) {
        throw new \LogicException('Required fields su_sdss_person_alt_title or su_person_short_title are missing from stanford_person.');
      }
      $alt = $node->get('su_sdss_person_alt_title')->value;
      $short = $node->get('su_person_short_title')->value;
      $this->list[0] = $this->createItem(0, !empty($alt) ? $alt : $short);
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
