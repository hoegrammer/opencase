<?php

namespace Drupal\opencase_reporting;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the OpenCase Report entity.
 *
 * @see \Drupal\opencase_reporting\Entity\OpenCaseReport.
 */
class OpenCaseReportAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\opencase_reporting\Entity\OpenCaseReportInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished opencase report entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published opencase report entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit opencase report entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete opencase report entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add opencase report entities');
  }

}
