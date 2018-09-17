<?php

namespace Drupal\opencase_reporting\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining OpenCase Report entities.
 *
 * @ingroup opencase_reporting
 */
interface OpenCaseReportInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the OpenCase Report name.
   *
   * @return string
   *   Name of the OpenCase Report.
   */
  public function getName();

  /**
   * Sets the OpenCase Report name.
   *
   * @param string $name
   *   The OpenCase Report name.
   *
   * @return \Drupal\opencase_reporting\Entity\OpenCaseReportInterface
   *   The called OpenCase Report entity.
   */
  public function setName($name);

  /**
   * Gets the OpenCase Report creation timestamp.
   *
   * @return int
   *   Creation timestamp of the OpenCase Report.
   */
  public function getCreatedTime();

  /**
   * Sets the OpenCase Report creation timestamp.
   *
   * @param int $timestamp
   *   The OpenCase Report creation timestamp.
   *
   * @return \Drupal\opencase_reporting\Entity\OpenCaseReportInterface
   *   The called OpenCase Report entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the OpenCase Report published status indicator.
   *
   * Unpublished OpenCase Report are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the OpenCase Report is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a OpenCase Report.
   *
   * @param bool $published
   *   TRUE to set this OpenCase Report to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\opencase_reporting\Entity\OpenCaseReportInterface
   *   The called OpenCase Report entity.
   */
  public function setPublished($published);

}
