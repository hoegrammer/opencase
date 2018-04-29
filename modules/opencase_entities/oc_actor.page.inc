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
  // Fetch OCActor Entity Object.
  $oc_actor = $variables['elements']['#oc_actor'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
