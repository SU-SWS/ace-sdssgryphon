<?php

namespace Drupal\sdss_entities\Entity;

use Drupal\Core\Entity\ContentEntityBase;
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
 *       "html" = "Drupal\sdss_entities\Routing\SdssEntityHtmlRouteProvider",
 *     }
 *   },
 *   base_table = "sdss_entity",
 *   admin_permission = "administer sdss entity types",
 *   entity_keys = {
 *     "id" = "id",
 *     "bundle" = "bundle",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *   },
 *   links = {
 *     "collection" = "/admin/content/sdss-entity",
 *     "add-form" = "/sdss-entity/add/{sdss_entity_type}",
 *     "add-page" = "/sdss-entity/add",
 *     "canonical" = "/sdss-entity/{sdss_entity}",
 *     "edit-form" = "/sdss-entity/{sdss_entity}",
 *     "delete-form" = "/sdss-entity/{sdss_entity}/delete",
 *   },
 *   bundle_entity_type = "sdss_entity_type",
 *   field_ui_base_route = "entity.sdss_entity_type.edit_form",
 * )
 */
class SdssEntity extends ContentEntityBase implements SdssEntityInterface {

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['label'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Label'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

}
