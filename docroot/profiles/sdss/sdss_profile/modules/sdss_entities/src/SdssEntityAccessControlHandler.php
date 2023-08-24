<?php

namespace Drupal\sdss_entities;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines the access control handler for the sdss entity entity type.
 */
class SdssEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {

    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view sdss entity');

      case 'update':
        return AccessResult::allowedIfHasPermissions(
          $account,
          ['edit sdss entity', 'administer sdss entity'],
          'OR',
        );

      case 'delete':
        return AccessResult::allowedIfHasPermissions(
          $account,
          ['delete sdss entity', 'administer sdss entity'],
          'OR',
        );

      default:
        // No opinion.
        return AccessResult::neutral();
    }

  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermissions(
      $account,
      ['create sdss entity', 'administer sdss entity'],
      'OR',
    );
  }

}
