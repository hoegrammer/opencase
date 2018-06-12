<?php

namespace Drupal\opencase;

/**
 * Manages relations between case types and actor types, or activity types and case types 
 *
 */
class EntityTypeRelationsWidget {

  /**
   * Adds actor type checkboxes to the form, and adds the submit handler
   *
   * $form - the form to be modified (reference)
   */ 
  public function setup(&$form) {
    $actor_types = \Drupal::service('entity_type.bundle.info')->getBundleInfo('oc_actor');
    $options = array();
    foreach($actor_types as $machine_name => $info) {
      $options[$machine_name] = $info['label'];
    }
    $form['actor_types'] = array(
      '#title' => t('Actor types'),
      '#description' => t('Types of people that can be involved in this kind of case.'),
      '#type' => 'checkboxes',
      '#options' => $options
    );
    $form['actions']['submit']['#submit'][] = array($this, 'submit');
  }

  /**
   * Takes a base_field_override configuration, 
   * extracts list of actor types that are allowed for the case type
   * and put these into the default values for the checkboxes
   *
   * $form - the form to be modified (reference)
   * $base_field_override - the config entity
   */ 
  public function populate(&$form, $base_field_override) {
    $form['actor_types']['#default_value'] = array();
    $actor_types =  $base_field_override->getSettings()['handler_settings']['target_bundles'];
    // example of the $actor_types array: ['client' => 'client', 'volunteer' => 0]
    foreach($actor_types as $machine_name => $value) {
      if ($value) {
        $form['actor_types']['#default_value'][] = $machine_name;        
      }
    }
  }

  
  /**
   * Submit callback which takes the data from the actor types field and
   * creates/edits a base_field_override accordingly
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
    $base_field_override->setSetting('handler_settings', ['target_bundles' => $form_state->getValue('actor_types')]);
    $base_field_override->save();
  }
}
