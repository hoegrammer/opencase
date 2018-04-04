<?php

/**
 * @file
 * Contains person.page.inc.
 *
 * Page callback for Person entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Person templates.
 *
 * Default template: person.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_person(array &$variables) {
  // Fetch Person Entity Object.
  $person = $variables['elements']['#person'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
