<?php

namespace Drupal\opencase_entities;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Actor entity.
 *
 * @see \Drupal\opencase_entities\Entity\OCActor.
 */
class OCActorAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   * Permissions are assigned by bundle.
   * 
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\opencase_entities\Entity\OCActorInterface $entity */
    $bundle = $entity->bundle();
    $route_name = \Drupal::routeMatch()->getRouteName();
    $case_routes = ['entity.oc_case.canonical', 'entity.oc_case.edit_form', 'view.cases.page_1', 'entity.oc_case.add_form'];
    $is_case_context = in_array($route_name, $case_routes);

    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIf(
            $account->hasPermission("view unpublished $bundle entities")
            or ($is_case_context && $account->hasPermission("view unpublished $bundle entities"))
          );
        }
        return AccessResult::allowedIf(
          $account->hasPermission("view published $bundle entities")
          or ($is_case_context && $account->hasPermission("view $bundle involvement in cases"))
        );

      case "update":
        return AccessResult::allowedIfHasPermission($account, "edit $bundle entities");

      case "delete":
        return AccessResult::allowedIfHasPermission($account, "delete $bundle entities");
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, "add $entity_bundle entities");
  }

}
