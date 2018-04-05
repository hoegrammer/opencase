<?php

namespace Drupal\zencrm_entities;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Profile entity.
 *
 * @see \Drupal\zencrm_entities\Entity\Profile.
 */
class ProfileAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\zencrm_entities\Entity\ProfileInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished profile entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published profile entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit profile entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete profile entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add profile entities');
  }

}
