<?php

namespace Drupal\zencrm_entities;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Case entity entity.
 *
 * @see \Drupal\zencrm_entities\Entity\CaseEntity.
 */
class CaseEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\zencrm_entities\Entity\CaseEntityInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished case entity entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published case entity entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit case entity entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete case entity entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add case entity entities');
  }

}
