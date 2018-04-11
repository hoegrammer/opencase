<?php

namespace Drupal\zencrm\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class HatController.
 */
class HatController extends ControllerBase {

  /**
   * Hello.
   *
   * @return string
   *   Return Hello string.
   */
  public function createHatForPerson($type, $person_id) {
    $values = array(
      'type' => $type,
      'person' =>  $person_id
    );

    $node = \Drupal::entityTypeManager()
      ->getStorage('hat')
      ->create($values);

    $form = \Drupal::entityTypeManager()
      ->getFormObject('hat', 'default')
      ->setEntity($node);
    return \Drupal::formBuilder()->getForm($form);
  }

}
