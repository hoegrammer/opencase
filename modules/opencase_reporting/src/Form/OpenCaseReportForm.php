<?php

namespace Drupal\opencase_reporting\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class OpenCaseReportForm.
 */
class OpenCaseReportForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $opencase_report = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Report Title'),
      '#maxlength' => 255,
      '#default_value' => $opencase_report->label(),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $opencase_report->id(),
      '#machine_name' => [
        'exists' => '\Drupal\opencase_reporting\Entity\OpenCaseReport::load',
      ],
      '#disabled' => !$opencase_report->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $opencase_report = $this->entity;
    $status = $opencase_report->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created report: %label.', [
          '%label' => $opencase_report->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved report: %label.', [
          '%label' => $opencase_report->label(),
        ]));
    }
    $form_state->setRedirectUrl($opencase_report->toUrl('collection'));
  }

}
