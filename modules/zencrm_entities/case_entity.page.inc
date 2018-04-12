<?php

/**
 * @file
 * Contains case_entity.page.inc.
 *
 * Page callback for Case entity entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Case entity templates.
 *
 * Default template: case_entity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_case_entity(array &$variables) {
  // Fetch CaseEntity Entity Object.
  $case_entity = $variables['elements']['#case_entity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
