<?php

namespace Drupal\sdss_entities\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\sdss_entities\SdssEntityInterface;

/**
 * Defines the sdss entity entity class.
 *
 * @ContentEntityType(
 *   id = "sdss_entity",
 *   label = @Translation("SDSS Entity"),
 *   label_collection = @Translation("SDSS Entities"),
 *   label_singular = @Translation("sdss entity"),
 *   label_plural = @Translation("sdss entities"),
 *   label_count = @PluralTranslation(
 *     singular = "@count sdss entities",
 *     plural = "@count sdss entities",
 *   ),
 *   bundle_label = @Translation("SDSS Entity type"),
 *   handlers = {
 *     "list_builder" = "Drupal\sdss_entities\SdssEntityListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "access" = "Drupal\sdss_entities\SdssEntityAccessControlHandler",
 *     "form" = {
 *       "add" = "Drupal\sdss_entities\Form\SdssEntityForm",
 *       "edit" = "Drupal\sdss_entities\Form\SdssEntityForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     }
 *   },
 *   base_table = "sdss_entity",
 *   admin_permission = "administer sdss entity types",
 *   entity_keys = {
 *     "id" = "id",
 *     "bundle" = "bundle",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *     "owner" = "uid",
 *   },
 *   links = {
 *     "collection" = "/admin/content/sdss-entity",
 *   },
 *   bundle_entity_type = "sdss_entity_type",
 *   field_ui_base_route = "entity.sdss_entity_type.edit_form",
 * )
 */
class SdssEntity extends ContentEntityBase implements SdssEntityInterface {

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage) {
    parent::preSave($storage);
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Status'))
      ->setDefaultValue(TRUE)
      ->setSetting('on_label', 'Enabled')
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'settings' => [
          'display_label' => FALSE,
        ],
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'boolean',
        'label' => 'above',
        'weight' => 0,
        'settings' => [
          'format' => 'enabled-disabled',
        ],
      ])
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

}
