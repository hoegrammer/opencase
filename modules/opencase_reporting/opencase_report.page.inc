<?php

/**
 * @file
 * Contains opencase_report.page.inc.
 *
 * Page callback for OpenCase Report entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for OpenCase Report templates.
 *
 * Default template: opencase_report.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_opencase_report(array &$variables) {
  // Fetch OpenCaseReport Entity Object.
  $opencase_report = $variables['elements']['#opencase_report'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
