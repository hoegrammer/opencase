<?php

namespace Drupal\zencrm_entities\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class CaseEntityTypeForm.
 */
class CaseEntityTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $case_entity_type = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $case_entity_type->label(),
      '#description' => $this->t("Label for the Case entity type."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $case_entity_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\zencrm_entities\Entity\CaseEntityType::load',
      ],
      '#disabled' => !$case_entity_type->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $case_entity_type = $this->entity;
    $status = $case_entity_type->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Case entity type.', [
          '%label' => $case_entity_type->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Case entity type.', [
          '%label' => $case_entity_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($case_entity_type->toUrl('collection'));
  }

}
