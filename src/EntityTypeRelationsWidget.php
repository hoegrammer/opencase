<?php

namespace Drupal\opencase;

/**
 * Manages GUI for configuring relations between case types and actor types, or activity types and case types 
 *
 */
class EntityTypeRelationsWidget {

  /**
   * Adds actor type and activity type checkboxes to the case type form, and adds the submit handler
   *
   * $form - the form to be modified (reference)
   */ 
  public function setup(&$form) {
    $actor_types = \Drupal::service('entity_type.bundle.info')->getBundleInfo('oc_actor');
    $options = array();
    foreach($actor_types as $machine_name => $info) {
      $options[$machine_name] = $info['label'];
    }
    $form['allowed_actor_types'] = array(
      '#title' => t('Actor types'),
      '#description' => t('Types of people that can be involved in this kind of case.'),
      '#type' => 'checkboxes',
      '#options' => $options
    );
    $activity_types = \Drupal::service('entity_type.bundle.info')->getBundleInfo('oc_activity');
    $options = array();
    foreach($activity_types as $machine_name => $info) {
      $options[$machine_name] = $info['label'];
    }
    $form['allowed_activity_types'] = array(
      '#title' => t('Activity types'),
      '#description' => t('Types of activities that can be logged against this case.'),
      '#type' => 'checkboxes',
      '#options' => $options
    );
    $form['actions']['submit']['#submit'][] = array($this, 'submit');
  }

  /**
   * Populates the form with actor/activity types that are already set
   *
   * $form - the form to be modified (reference)
   */ 
  public function populate(&$form) {
    $case_type = $form['id']['#default_value'];
    $allowedActorTypes = EntityTypeRelations::getAllowedActorTypesForCaseType($case_type); 
    $form['allowed_actor_types']['#default_value'] = $allowedActorTypes;
    $allowedActivityTypes = EntityTypeRelations::getAllowedActivityTypesForCaseType($case_type); 
    $form['allowed_activity_types']['#default_value'] = $allowedActivityTypes;
  }
  
  /**
   * Submit callback which takes the data from the actor types and activity types fields and
   * creates/edits the relevant config objects
   *
   * $form - the form that is being submitted
   * $form_state - the data in the form
   */ 
  public function submit($form, $form_state) {
    $case_type_machine_name = $form['id']['#default_value'] ? $form['id']['#default_value'] : $form_state->getValue('id');
    $base_field_override = \Drupal\Core\Field\Entity\BaseFieldOverride::load("oc_case.$case_type_machine_name.actors_involved");
    if (!$base_field_override) {
      $entity_fields = \Drupal::service('entity_field.manager')->getBaseFieldDefinitions('oc_case');
      $field_definition = $entity_fields['actors_involved'];
      $base_field_override = \Drupal\Core\field\Entity\BaseFieldOverride::createFromBaseFieldDefinition($field_definition, $case_type_machine_name);
    }
    $base_field_override->setSetting('handler_settings', ['target_bundles' => $form_state->getValue('allowed_actor_types')]);
    $base_field_override->save();
    $caseTypeConfig = \Drupal::entityTypeManager()->getStorage('oc_case_type')->load($case_type_machine_name);
    $caseTypeConfig->set('allowedActivityTypes', $form_state->getValue('allowed_activity_types'));
    $caseTypeConfig->save();
  }
}
