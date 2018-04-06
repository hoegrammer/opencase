<?php

namespace Drupal\zencrm_entities;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Contact Details entity.
 *
 * @see \Drupal\zencrm_entities\Entity\ContactDetails.
 */
class ContactDetailsAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\zencrm_entities\Entity\ContactDetailsInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished contact details entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published contact details entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit contact details entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete contact details entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add contact details entities');
  }

}
