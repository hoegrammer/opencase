<?php

namespace Drupal\opencase_entities;

class CaseInvolvement {

  public static function getLinkedActorId($account) {
    return \Drupal\user\Entity\User::load($account->id())->get('field_linked_opencase_actor')->target_id;
  }

  public static function userIsInvolved($account, $case) {
    $actorId = self::getLinkedActorId($account);        
    $involvedIds = array_column($case->actors_involved->getValue(), 'target_id');
    return in_array($actorId, $involvedIds);
  }

  public static function userIsInvolved_activity($account, $activity) {
    $case_id = $activity->oc_case->target_id;
    $case = \Drupal::entityTypeManager()->getStorage('oc_case')->load($case_id);
    return self::userIsInvolved($account, $case);
  }
}
