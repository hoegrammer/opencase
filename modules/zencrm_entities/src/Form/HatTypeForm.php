<?php

namespace Drupal\zencrm_entities\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class HatTypeForm.
 */
class HatTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $hat_type = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $hat_type->label(),
      '#description' => $this->t("Label for the Hat type."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $hat_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\zencrm_entities\Entity\HatType::load',
      ],
      '#disabled' => !$hat_type->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $hat_type = $this->entity;
    $status = $hat_type->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Hat type.', [
          '%label' => $hat_type->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Hat type.', [
          '%label' => $hat_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($hat_type->toUrl('collection'));
  }

}
