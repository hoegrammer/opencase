<?php

/**
 * @file
 * Contains hat.page.inc.
 *
 * Page callback for Hat entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Hat templates.
 *
 * Default template: hat.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_hat(array &$variables) {
  // Fetch Hat Entity Object.
  $hat = $variables['elements']['#hat'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
