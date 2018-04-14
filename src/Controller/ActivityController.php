<?php

namespace Drupal\zencrm\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class ActivityController.
 */
class ActivityController extends ControllerBase {

  /**
   * Displays a form for creating a activity. 
   * The type of activity and the case are prepopulated.
   *
   * @return form for creating a activity
   */
  public function createActivityForCase($case_id, $activity_type_id) {
    error_log( "$case_id, $activity_type_id");
    $values = array(
      'type' => $activity_type_id,
      'case_entity' => $case_id,
    );

    $activity = \Drupal::entityTypeManager()
      ->getStorage('activity')
      ->create($values);

    $form = \Drupal::entityTypeManager()
      ->getFormObject('activity', 'default')
      ->setEntity($activity);

    return \Drupal::formBuilder()->getForm($form);
  }
}
