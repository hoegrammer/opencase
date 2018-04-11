<?php

namespace Drupal\zencrm\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class ContactDetailsController.
 */
class ContactDetailsController extends ControllerBase {

  /**
   * Outputs a form for creating contact details, with the person
   * already populated (person is not shown on the form).
   */
  public function createContactDetailsForPerson($person_id) {
    $values = array(
      'person' =>  $person_id
    );

    $node = \Drupal::entityTypeManager()
      ->getStorage('contact_details')
      ->create($values);

    $form = \Drupal::entityTypeManager()
      ->getFormObject('contact_details', 'default')
      ->setEntity($node);
    return \Drupal::formBuilder()->getForm($form);
  }

}
