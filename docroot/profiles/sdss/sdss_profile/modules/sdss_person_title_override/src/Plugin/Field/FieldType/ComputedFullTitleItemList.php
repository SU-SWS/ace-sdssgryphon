<?php

namespace Drupal\sdss_person_title_override\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemList;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\TypedData\ComputedItemListTrait;

/**
 * Computed item list for full title with alt override.
 */
class ComputedFullTitleItemList extends FieldItemList implements FieldItemListInterface {
  use ComputedItemListTrait;

  /**
   * Computes the value for the full title field.
   */
  protected function computeValue() {
    $node = $this->getEntity();
    if ($node->bundle() == 'stanford_person') {
      /** @var \Drupal\sdss_profile\Service\SdssPersonTitleService $title_service */
      $title_service = \Drupal::service('sdss_person_title_override.sdss_person_title');
      $title = $title_service->getDisplayFullTitle($node);
      $this->list[0] = $this->createItem(0, $title);
    }
  }
}
