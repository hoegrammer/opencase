<?php

/**
 * @file
 * Contains oc_case.page.inc.
 *
 * Page callback for Case entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Case templates.
 *
 * Default template: oc_case.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_oc_case(array &$variables) {
  // Separate the fields into two sections to be displayed in two columns.
  // Remove the name (title) field as this is displayed anyway.
  $variables['id'] = $variables['elements']['#oc_case']->get('id')[0]->get('value')->getValue();
  $variables['base_fields'] = array();
  $variables['other_fields'] = array();
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
    if (in_array($key, ['created', 'changed', 'files', 'actors_involved', 'status', 'user_id'])) {
      $variables['base_fields'][$key] = $variables['elements'][$key];
    } else {
      $variables['other_fields'][$key] = $variables['elements'][$key];
      unset($variables['other_fields']['name']);
    }
  }
}
