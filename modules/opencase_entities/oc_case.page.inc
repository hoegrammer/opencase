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
  // Fetch OCCase Entity Object.
  $oc_case = $variables['elements']['#oc_case'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
