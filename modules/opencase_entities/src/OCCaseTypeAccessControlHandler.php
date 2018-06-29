<?php

namespace Drupal\opencase_entities;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Defines the access control handler for the OCCaseType Config Entity.
 * Always allows viewing the label of the bundle.
 *
 * @see Drupal\opencase_entities\Entity\OCCaseType
 */
class OCCaseTypeAccessControlHandler extends EntityAccessControlHandler {

  protected $viewLabelOperation = TRUE;

  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    if ($operation == 'view label') {
      return AccessResult::allowed();
    }
    return parent::checkAccess($entity, $operation, $account);
  }
}
