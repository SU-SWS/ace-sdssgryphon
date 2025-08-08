<?php

namespace Drupal\sdss_person_title_override\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\Field\Attribute\FieldType;

/**
 * Computed field item for full title with alt override.
 */
#[FieldType(
  id: "sdss_computed_full_title",
  label: new TranslatableMarkup("Full Title (with Alt Override)"),
  description: new TranslatableMarkup("Displays alt_title if set, otherwise full_title."),
  default_widget: "string_textfield",
  default_formatter: "alt_title_override",
  list_class: ComputedFullTitleItemList::class,
)]
class ComputedFullTitleItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['value'] = DataDefinition::create('string')
      ->setLabel(t('Text value'));
    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [
      'columns' => [
        'value' => [
          'type' => 'varchar',
          'length' => 255,
          'not null' => FALSE,
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $value = $this->get('value')->getValue();
    return $value === NULL || $value === '';
  }

}
