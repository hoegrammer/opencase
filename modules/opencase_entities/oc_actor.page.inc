<?php

/**
 * @file
 * Contains oc_actor.page.inc.
 *
 * Page callback for Actor entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Actor templates.
 *
 * Default template: oc_actor.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_oc_actor(array &$variables) {
  // Make the id available to the template and also separate the fields
  // into two sections to be displayed in two columns.
  $variables['id'] = $variables['elements']['#oc_actor']->get('id')[0]->get('value')->getValue();
  $variables['contact_details'] = array();
  $variables['fields_other_than_contact_details'] = array();
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
    if (in_array($key, ['email', 'phone', 'phone2', 'postal_address', 'post_code'])) {
      $variables['contact_details'][$key] = $variables['elements'][$key];
    } else {
      $variables['fields_other_than_contact_details'][$key] = $variables['elements'][$key];
    }
  }
}
