<?php

namespace Drupal\zencrm_entities\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Contact details entities.
 */
class ContactDetailsViewsData extends EntityViewsData {

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
