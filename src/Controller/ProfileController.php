<?php

namespace Drupal\zencrm\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class ProfileController.
 */
class ProfileController extends ControllerBase {

  /**
   * Hello.
   *
   * @return string
   *   Return Hello string.
   */
  public function createProfileForPerson($type, $person_id) {
    $values = array(
      'type' => $type,
      'person' =>  $person_id
    );

    $node = \Drupal::entityTypeManager()
      ->getStorage('profile')
      ->create($values);

    $form = \Drupal::entityTypeManager()
      ->getFormObject('profile', 'default')
      ->setEntity($node);
    return \Drupal::formBuilder()->getForm($form);
  }

}
