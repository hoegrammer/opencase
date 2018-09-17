<?php

namespace Drupal\opencase_reporting\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'ReportForm' block.
 *
 * @Block(
 *  id = "opencase_report_form",
 *  admin_label = @Translation("Report form"),
 * )
 */
class ReportForm extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $report  = \Drupal::entityManager()->getStorage('opencase_report')->create();
    $form = \Drupal::service('entity.form_builder')->getForm($report, 'add');
    return $form;
  }

}
