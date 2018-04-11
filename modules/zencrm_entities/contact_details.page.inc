<?php

/**
 * @file
 * Contains contact_details.page.inc.
 *
 * Page callback for Contact details entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Contact details templates.
 *
 * Default template: contact_details.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_contact_details(array &$variables) {
  // Fetch ContactDetails Entity Object.
  $contact_details = $variables['elements']['#contact_details'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
