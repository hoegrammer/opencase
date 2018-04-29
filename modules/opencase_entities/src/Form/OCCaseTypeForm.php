<?php

namespace Drupal\opencase_entities\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class OCCaseTypeForm.
 */
class OCCaseTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $oc_case_type = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $oc_case_type->label(),
      '#description' => $this->t("Label for the Case type."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $oc_case_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\opencase_entities\Entity\OCCaseType::load',
      ],
      '#disabled' => !$oc_case_type->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $oc_case_type = $this->entity;
    $status = $oc_case_type->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Case type.', [
          '%label' => $oc_case_type->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Case type.', [
          '%label' => $oc_case_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($oc_case_type->toUrl('collection'));
  }

}
