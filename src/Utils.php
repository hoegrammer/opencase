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
   * $query optionally append a query string to the links (key => value format)
   *
   * returns html markup.
   */
  public static function generateAddLinks($baseEntityType, $query = []) {
    
    $bundles = \Drupal::service('entity_type.bundle.info')->getBundleInfo($baseEntityType);
    $markup = '';
    foreach($bundles as $bundle_id => $bundle) {
      $label = $bundle['label'];
      $url = \Drupal\Core\Url::fromRoute("entity.$baseEntityType.add_form", [$baseEntityType . '_type' => $bundle_id]);
      $url->setOption('query', $query);
      $link = \Drupal\Core\Link::fromTextAndUrl(t("Add $label"), $url)->toString();
      $markup .= "<p>$link</p>";
    }
    return "<div class='opencase_add_links'>$markup</div>";
  }
}
