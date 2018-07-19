<?php

namespace Drupal\opencase_entities;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\opencase_entities\CaseInvolvement;

/**
 * Access controller for the Case entity.
 *
 * @see \Drupal\opencase_entities\Entity\OCCase.
 */
class OCCaseAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\opencase_entities\Entity\OCCaseInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished case entities');
        }
        return AccessResult::allowedIf(
            $account->hasPermission('view published case entities')
            || CaseInvolvement::userIsInvolved($account, $entity)
        );
      case 'update':   // you can edit the case only if a) you can see it and b) you have the permission to edit cases.
        return AccessResult::allowedIf(
            $account->hasPermission('edit case entities')
            && ($account->hasPermission('view published case entities') || CaseInvolvement::userIsInvolved($account, $entity))
        );
      case 'delete':   // you can delete the case only if a) you can see it and b) you have the permission to delete cases.
        return AccessResult::allowedIf(
            $account->hasPermission('delete case entities')
            && ($account->hasPermission('view published case entities') || CaseInvolvement::userIsInvolved($account, $entity))
        );
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add case entities');
  }

}
