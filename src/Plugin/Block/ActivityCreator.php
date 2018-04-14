<?php

namespace Drupal\zencrm\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'ActivityCreator' block.
 * Block contains links for creating activities with.
 * The links open an entity create form in a popup, passing in the activity type and case id in the url.
 *
 * @Block(
 *  id = "activity_creator",
 *  admin_label = @Translation("Activity creator"),
 * )
 */
class ActivityCreator extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $case_id = \Drupal::routeMatch()->getParameter('case_entity')->id();
    $markup = "";    

    $activity_types = \Drupal::service('entity_type.bundle.info')->getBundleInfo('activity');
    foreach($activity_types as $activity_type_id => $type) {
      $label = $type['label'];
      $markup .= "<p><a class='use-ajax' data-dialog-type='modal' href='/zencrm/activity/$case_id/add/$activity_type_id?destination=/zencrm/case/$case_id'>Add a $label Activity</a></p>";
    }
    return [
      '#cache' => [
         'max-age' => 0,
       ],
      '#markup' => "<div class='zencrm_creation_links'>$markup</div>"
    ];

  }

}
