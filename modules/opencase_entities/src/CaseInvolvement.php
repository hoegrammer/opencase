<?php

namespace Drupal\opencase_entities;

class CaseInvolvement {

  private function getLinkedActorId($userId) {
    return \Drupal\user\Entity\User::load($userId)->get('field_linked_opencase_actor')->target_id;
  }

  public function userIsInvolved($account, $case) {
    $actorId = $this->getLinkedActorId($account->id());        
    $involvedIds = array_column($case->actors_involved->getValue(), 'target_id');
    return in_array($actorId, $involvedIds);
  }
}
