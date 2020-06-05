<?php

/**
 * @file
 * Contains oc_activity.page.inc.
 *
 * Page callback for Activity entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Activity templates.
 *
 * Default template: oc_activity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_oc_activity(array &$variables) {
  // Fetch OCActivity Entity Object.
  $oc_activity = $variables['elements']['#oc_activity'];
  $variables['id'] = $oc_activity->get('id')[0]->get('value')->getValue();

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
  // Separate the fields into two sections to be displayed in two columns.
  // Remove the name (title) field as this is displayed anyway.
  $variables['base_fields'] = array();
  $variables['other_fields'] = array();
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
    if (in_array($key, ['activity_date_time', 'time_taken', 'description', 'user_id'])) {
      $variables['base_fields'][$key] = $variables['elements'][$key];
    } else {
      $variables['other_fields'][$key] = $variables['elements'][$key];
      unset($variables['other_fields']['name']);
    }
  }
}
