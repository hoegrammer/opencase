<?php

namespace Drupal\opencase_reporting\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for OpenCase Report edit forms.
 *
 * @ingroup opencase_reporting
 */
class OpenCaseReportForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\opencase_reporting\Entity\OpenCaseReport */
    $form = parent::buildForm($form, $form_state);

    $entity = $this->entity;

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;

    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label OpenCase Report.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label OpenCase Report.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.opencase_report.canonical', ['opencase_report' => $entity->id()]);
  }

}
