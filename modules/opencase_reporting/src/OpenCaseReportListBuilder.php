<?php

namespace Drupal\opencase_reporting;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of OpenCase Report entities.
 *
 * @ingroup opencase_reporting
 */
class OpenCaseReportListBuilder extends EntityListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('OpenCase Report ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\opencase_reporting\Entity\OpenCaseReport */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.opencase_report.edit_form',
      ['opencase_report' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
