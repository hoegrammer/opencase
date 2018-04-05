<?php

/**
 * @file
 * Contains profile.page.inc.
 *
 * Page callback for Profile entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Profile templates.
 *
 * Default template: profile.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_profile(array &$variables) {
  // Fetch Profile Entity Object.
  $profile = $variables['elements']['#profile'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
