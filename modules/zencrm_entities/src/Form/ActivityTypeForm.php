<?php

namespace Drupal\zencrm_entities\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class ActivityTypeForm.
 */
class ActivityTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $activity_type = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $activity_type->label(),
      '#description' => $this->t("Label for the Activity type."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $activity_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\zencrm_entities\Entity\ActivityType::load',
      ],
      '#disabled' => !$activity_type->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $activity_type = $this->entity;
    $status = $activity_type->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Activity type.', [
          '%label' => $activity_type->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Activity type.', [
          '%label' => $activity_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($activity_type->toUrl('collection'));
  }

}
