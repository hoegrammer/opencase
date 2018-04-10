<?php

namespace Drupal\zencrm\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'ProfileCreator' block.
 *
 * @Block(
 *  id = "profile_creator",
 *  admin_label = @Translation("Profile creator"),
 * )
 */
class ProfileCreator extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $person_id = \Drupal::routeMatch()->getParameter('person')->id();
    $markup = "";    

    // Only offer profile creation on profiles they don't already have.
    $profile_types = \Drupal::service('entity_type.bundle.info')->getBundleInfo('profile');
    foreach($profile_types as $id => $type) {
      $profiles = \Drupal::entityTypeManager()
        ->getStorage('profile')
        ->loadByProperties(['type' => $id, 'person' => $person_id]);
      if (!reset($profiles)) {
        $label = $type['label'];
        $markup .= "<p><a class='use-ajax' data-dialog-type='modal' href='/zencrm/profile/add/$id/$person_id'>Create $label Profile</a></p>";
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
