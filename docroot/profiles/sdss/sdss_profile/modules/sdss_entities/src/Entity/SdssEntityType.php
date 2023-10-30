<?php

namespace Drupal\sdss_entities\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the SDSS Entity type configuration entity.
 *
 * @ConfigEntityType(
 *   id = "sdss_entity_type",
 *   label = @Translation("SDSS Entity type"),
 *   label_collection = @Translation("SDSS Entity types"),
 *   label_singular = @Translation("sdss entity type"),
 *   label_plural = @Translation("sdss entities types"),
 *   label_count = @PluralTranslation(
 *     singular = "@count sdss entities type",
 *     plural = "@count sdss entities types",
 *   ),
 *   handlers = {
 *     "form" = {
 *       "add" = "Drupal\sdss_entities\Form\SdssEntityTypeForm",
 *       "edit" = "Drupal\sdss_entities\Form\SdssEntityTypeForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm",
 *     },
 *     "list_builder" = "Drupal\sdss_entities\SdssEntityTypeListBuilder",
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     }
 *   },
 *   admin_permission = "administer sdss entity types",
 *   bundle_of = "sdss_entity",
 *   config_prefix = "sdss_entity_type",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/sdss_entity_types/add",
 *     "edit-form" = "/admin/structure/sdss_entity_types/manage/{sdss_entity_type}",
 *     "delete-form" = "/admin/structure/sdss_entity_types/manage/{sdss_entity_type}/delete",
 *     "collection" = "/admin/structure/sdss_entity_types"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "uuid",
 *   }
 * )
 */
class SdssEntityType extends ConfigEntityBundleBase {

  /**
   * The machine name of this sdss entity type.
   *
   * @var string
   */
  protected $id;

  /**
   * The human-readable name of the sdss entity type.
   *
   * @var string
   */
  protected $label;

}
