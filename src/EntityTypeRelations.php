<?php

namespace Drupal\opencase;

/**
 * Stuff to do with the relationship of case types to actor types, and activity types to case types
 *
 */
class EntityTypeRelations {


  public static function getAllowedActorTypesForCaseType($case_type) {
    $base_field_override = \Drupal\Core\Field\Entity\BaseFieldOverride::load("oc_case.$case_type.actors_involved");
    $allowedActorTypes = array();
    if ($base_field_override) { 
      $allowedActorTypes =  $base_field_override->getSettings()['handler_settings']['target_bundles'];
    }
    return $allowedActorTypes; // // format: ['volunteer' => 0, 'client' => 'client'] 
  }

  public static function getAllowedActivityTypesForCaseType($case_type) {
    $caseTypeConfig = \Drupal::entityTypeManager()->getStorage('oc_case_type')->load($case_type);
    $allowedActivityTypes = $caseTypeConfig->get('allowedActivityTypes');  // format: ['application' => 'application', 'interview' => 0]
    if (!$allowedActivityTypes) $allowedActivityTypes = array();
    return $allowedActivityTypes;
  }    
}
