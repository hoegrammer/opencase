<?php

namespace Drupal\opencase;

/**
 * Stuff to do with the relationship of case types to actor types, and activity types to case types
 *
 */
class EntityTypeRelations {


  public static function getAllowedParentBundles($childEntityType, $childBundle) {
    $field = $childEntityType == 'oc_case' ? 'actors_involved' : 'oc_case';
    $base_field_override = \Drupal\Core\Field\Entity\BaseFieldOverride::load("$childEntityType.$childBundle.$field");
    $allowedBundles = array();
    if ($base_field_override) { 
      $actor_types =  $base_field_override->getSettings()['handler_settings']['target_bundles'];
      // example of the $actor_types array: ['client' => 'client', 'volunteer' => 0]
      foreach($actor_types as $machine_name => $value) {
        if ($value) {
          $allowedBundles[] = $machine_name;        
        }
      }
    }
    return $allowedBundles; // NB. this is an array of machine names only, indexed numerically.
  }

  public static function getAllowedChildBundles($parentEntityType, $parentBundle) {
    $childEntityType = $parentEntityType == 'oc_case' ? 'oc_activity' : 'oc_case';
    // get all the child bundles
    $childBundles = \Drupal::service('entity_type.bundle.info')->getBundleInfo($childEntityType);
    // $childBundles is array where the key is the machine name and the value is array containing label
    $allowedChildBundles = array();
    foreach(array_keys($childBundles) as $childBundle) {
      if (in_array($parentBundle, self::getAllowedParentBundles($childEntityType, $childBundle))) {
        $allowedChildBundles[$childBundle] = $childBundles[$childBundle]['label'];
      }
    }
    return $allowedChildBundles; // NB. this is an array of labels, indexed by machine name.
    
  }
}
