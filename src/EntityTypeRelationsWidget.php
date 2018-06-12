<?php

namespace Drupal\opencase;

/**
 * Manages GUI for configuring relations between case types and actor types, or activity types and case types 
 *
 */
class EntityTypeRelationsWidget {

  /**
   * Adds actor type checkboxes to the case type form, and adds the submit handler
   *
   * $form - the form to be modified (reference)
   */ 
  public function setup_for_case_type(&$form) {
    $actor_types = \Drupal::service('entity_type.bundle.info')->getBundleInfo('oc_actor');
    $options = array();
    foreach($actor_types as $machine_name => $info) {
      $options[$machine_name] = $info['label'];
    }
    $form['allowed_parent_bundles'] = array(
      '#title' => t('Actor types'),
      '#description' => t('Types of people that can be involved in this kind of case.'),
      '#type' => 'checkboxes',
      '#options' => $options
    );
    $form['actions']['submit']['#submit'][] = array($this, 'submit_case_type');
  }

  /**
   * Adds case type checkboxes to the activity type form, and adds the submit handler
   *
   * $form - the form to be modified (reference)
   */ 
  public function setup_for_activity_type(&$form) {
    $case_types = \Drupal::service('entity_type.bundle.info')->getBundleInfo('oc_case');
    $options = array();
    foreach($case_types as $machine_name => $info) {
      $options[$machine_name] = $info['label'];
    }
    $form['allowed_parent_bundles'] = array(
      '#title' => t('Case types'),
      '#description' => t('Types of cases in which this activity can appear.'),
      '#type' => 'checkboxes',
      '#options' => $options
    );
    $form['actions']['submit']['#submit'][] = array($this, 'submit_activity_type');
  }

  /**
   * Populates with existing case/activity types if they exist
   *
   * $form - the form to be modified (reference)
   */ 
  public function populate(&$form, $entityType) {
    $bundle = $form['id']['#default_value'];
    $allowedParentBundles = EntityTypeRelations::getAllowedParentBundles($entityType, $bundle); 
    $form['allowed_parent_bundles']['#default_value'] = $allowedParentBundles;
  }
  
  /**
   * Submit callback which takes the data from the actor types field and
   * creates/edits a base_field_override accordingly
   *
   * $form - the form that is being submitted
   * $form_state - the data in the form
   */ 
  public function submit_case_type($form, $form_state) {
    $case_type_machine_name = $form['id']['#default_value'] ? $form['id']['#default_value'] : $form_state->getValue('id');
    $base_field_override = \Drupal\Core\Field\Entity\BaseFieldOverride::load("oc_case.$case_type_machine_name.actors_involved");
    if (!$base_field_override) {
      $entity_fields = \Drupal::service('entity_field.manager')->getBaseFieldDefinitions('oc_case');
      $field_definition = $entity_fields['actors_involved'];
      $base_field_override = \Drupal\Core\field\Entity\BaseFieldOverride::createFromBaseFieldDefinition($field_definition, $case_type_machine_name);
    }
    $base_field_override->setSetting('handler_settings', ['target_bundles' => $form_state->getValue('allowed_parent_bundles')]);
    $base_field_override->save();
  }

  /**
   * Submit callback which takes the data from the case types field and
   * creates/edits a base_field_override accordingly
   *
   * $form - the form that is being submitted
   * $form_state - the data in the form
   */ 
  public function submit_activity_type($form, $form_state) {
    $activity_type_machine_name = $form['id']['#default_value'] ? $form['id']['#default_value'] : $form_state->getValue('id');
    $base_field_override = \Drupal\Core\Field\Entity\BaseFieldOverride::load("oc_activity.$activity_type_machine_name.oc_case");
    if (!$base_field_override) {
      $entity_fields = \Drupal::service('entity_field.manager')->getBaseFieldDefinitions('oc_activity');
      $field_definition = $entity_fields['oc_case'];
      $base_field_override = \Drupal\Core\field\Entity\BaseFieldOverride::createFromBaseFieldDefinition($field_definition, $activity_type_machine_name);
    }
    $base_field_override->setSetting('handler_settings', ['target_bundles' => $form_state->getValue('allowed_parent_bundles')]);
    $base_field_override->save();
  }
}
