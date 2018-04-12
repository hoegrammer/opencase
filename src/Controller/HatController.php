<?php

namespace Drupal\zencrm\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class HatController.
 */
class HatController extends ControllerBase {

  /**
   * Displays a form for creating a hat. 
   * The type of hat and the person are prepopulated.
   *
   * @return form for creating a hat
   */
  public function createHatForPerson($person_id, $hat_type_id) {
    $values = array(
      'type' => $hat_type_id,
      'person' =>  $person_id
    );

    $hat = \Drupal::entityTypeManager()
      ->getStorage('hat')
      ->create($values);

    $form = \Drupal::entityTypeManager()
      ->getFormObject('hat', 'default')
      ->setEntity($hat);
    return \Drupal::formBuilder()->getForm($form);
  }

  /**
   * Displays a form for editing a hat. 
   * The reason it is here is that the URL needs to have the person id in it  
   * in order to filter the contact details entity reference view to only show ones for that person.
   * (The intuitive way to bring this about - changing the edit route for the entity itself - causes problems with the delete route)
   *
   * @return form for editing a hat
   */
  public function editHatForPerson($person_id, $hat_id) {
    error_log("hjhjhjhj");

    $hat = \Drupal::entityTypeManager()
      ->getStorage('hat')
      ->load($hat_id);

    $form = \Drupal::entityTypeManager()
      ->getFormObject('hat', 'default')
      ->setEntity($hat);
    return \Drupal::formBuilder()->getForm($form);
  }

}
