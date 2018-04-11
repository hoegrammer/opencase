<?php

namespace Drupal\zencrm\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'HatCreator' block.
 *
 * @Block(
 *  id = "hat_creator",
 *  admin_label = @Translation("Hat creator"),
 * )
 */
class HatCreator extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $person_id = \Drupal::routeMatch()->getParameter('person')->id();
    $markup = "";    

    // Only offer hat creation on hats they don't already have.
    $hat_types = \Drupal::service('entity_type.bundle.info')->getBundleInfo('hat');
    foreach($hat_types as $id => $type) {
      $hats = \Drupal::entityTypeManager()
        ->getStorage('hat')
        ->loadByProperties(['type' => $id, 'person' => $person_id]);
      if (!reset($hats)) {
        $label = $type['label'];
        $markup .= "<p><a class='use-ajax' data-dialog-type='modal' href='/zencrm/hat/add/$id/$person_id'>Create $label Hat</a></p>";
      }
    }
    return [
      '#cache' => [
         'max-age' => 0,
       ],
      '#markup' => "<div>$markup</div>"
    ];

  }

}
