<?php

namespace Drupal\opencase_reporting\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for OpenCase Report entities.
 */
class OpenCaseReportViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.

    return $data;
  }

}
