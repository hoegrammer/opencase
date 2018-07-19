<?php

namespace Drupal\opencase_entities;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Activity entity.
 *
 * @see \Drupal\opencase_entities\Entity\OCActivity.
 */
class OCActivityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\opencase_entities\Entity\OCActivityInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished activity entities');
        }
        return AccessResult::allowedIf(
            $account->hasPermission('view published case entities')  // activity permissions are inherited from case
            || CaseInvolvement::userIsInvolved_activity($account, $entity)
        );
      case 'update':  // allowed only if a) they can see the case the activity is on and b) they can edit cases
        return AccessResult::allowedIf(
            $account->hasPermission('edit case entities')
            && ($account->hasPermission('view published case entities') || CaseInvolvement::userIsInvolved_activity($account, $entity))
        );
      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete activity entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add activity entities');
  }

}
