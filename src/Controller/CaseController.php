<?php

namespace Drupal\zencrm\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class CaseController.
 */
class CaseController extends ControllerBase {

  /**
   * Displays a form for creating a case. 
   * The type of case and the hat are prepopulated.
   *
   * @return form for creating a case
   */
  public function createCaseForHat($hat_id, $case_type_id) {
    $values = array(
      'type' => $case_type_id,
    );

    $case = \Drupal::entityTypeManager()
      ->getStorage('case_entity')
      ->create($values);

    $case->hats_involved->appendItem($hat_id);  

    $form = \Drupal::entityTypeManager()
      ->getFormObject('case_entity', 'default')
      ->setEntity($case);
    return \Drupal::formBuilder()->getForm($form);
  }
}
