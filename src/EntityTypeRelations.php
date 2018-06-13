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
      $targetBundleConfig =  $base_field_override->getSettings()['handler_settings']['target_bundles'];
      if ($targetBundleConfig) {
        // example of $targetBundleConfig: ['client' => 'client', 'volunteer' => 0]
        foreach($targetBundleConfig as $machine_name => $value) {
          if ($value) {
            $allowedActorTypes[] = $machine_name;        
          }
        }
      } 
    }
    return $allowedActorTypes; // NB. this is an array of machine names only, indexed numerically.
  }

  public static function getAllowedCaseTypesForActorType($actor_type) {
    $allCaseTypes = \Drupal::service('entity_type.bundle.info')->getBundleInfo('oc_case');
    // $allCaseTypes is array where the key is the machine name and the value is array containing label
    $allowedCaseTypes = array();
    foreach(array_keys($allCaseTypes) as $caseType) {
      if (in_array($actor_type, self::getAllowedActorTypesForCaseType($caseType))) {
        $allowedCaseTypes[$caseType] = $allCaseTypes[$caseType]['label'];
      }
    }
    return $allowedCaseTypes; // NB. this is an array of labels, indexed by machine name.
    
  }
}
