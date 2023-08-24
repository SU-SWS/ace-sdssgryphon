<?php

namespace Drupal\sdss_entities;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a sdss entity entity type.
 */
interface SdssEntityInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

}
