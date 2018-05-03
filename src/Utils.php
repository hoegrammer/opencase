<?php

namespace Drupal\opencase;

/**
 * Shared functions for the opencase module
 *
 */
class Utils {


  /**
   * Generates a set of links for adding different types of a base entity
   *
   * $baseEntityType the type of entity to generate the links for (it will generate one for each bundle of the base type)
   * $title the title to be placed above the set of links)
   * $query optionally append a query string to the links (key => value format
   *
   * returns html markup.
   */
  public static function generateAddLinks($baseEntityType, $title, $query = []) {
    
    $bundles = \Drupal::service('entity_type.bundle.info')->getBundleInfo($baseEntityType);
    $title = t($title); 
    $markup = "<h1>$title: </h1>";
    foreach($bundles as $bundle_id => $bundle) {
      $label = t($bundle['label']);
      $url = \Drupal\Core\Url::fromRoute("entity.$baseEntityType.add_form", [$baseEntityType . '_type' => $bundle_id]);
      $url->setOption('query', $query);
      $link = \Drupal\Core\Link::fromTextAndUrl($label, $url)->toString();
      $markup .= "<p>$link</p>";
    }
    return "<div class='opencase_add_links'>$markup</div>";
  }
}
