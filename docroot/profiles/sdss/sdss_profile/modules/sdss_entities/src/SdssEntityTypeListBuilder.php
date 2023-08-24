<?php

namespace Drupal\sdss_entities;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of SDSS entity type entities.
 *
 * @see \Drupal\sdss_entities\Entity\SdssEntityType
 */
class SdssEntityTypeListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['title'] = $this->t('Label');

    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['title'] = [
      'data' => $entity->label(),
      'class' => ['menu-label'],
    ];

    return $row + parent::buildRow($entity);
  }

  /**
   * {@inheritdoc}
   */
  public function render() {
    $build = parent::render();

    $build['table']['#empty'] = $this->t(
      'No sdss entity types available. <a href=":link">Add SDSS entity type</a>.',
      [':link' => Url::fromRoute('entity.sdss_entity_type.add_form')->toString()]
    );

    return $build;
  }

}
