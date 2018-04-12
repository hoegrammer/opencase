<?php

namespace Drupal\zencrm_entities\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Case entity entities.
 *
 * @ingroup zencrm_entities
 */
interface CaseEntityInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Case entity name.
   *
   * @return string
   *   Name of the Case entity.
   */
  public function getName();

  /**
   * Sets the Case entity name.
   *
   * @param string $name
   *   The Case entity name.
   *
   * @return \Drupal\zencrm_entities\Entity\CaseEntityInterface
   *   The called Case entity entity.
   */
  public function setName($name);

  /**
   * Gets the Case entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Case entity.
   */
  public function getCreatedTime();

  /**
   * Sets the Case entity creation timestamp.
   *
   * @param int $timestamp
   *   The Case entity creation timestamp.
   *
   * @return \Drupal\zencrm_entities\Entity\CaseEntityInterface
   *   The called Case entity entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Case entity published status indicator.
   *
   * Unpublished Case entity are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Case entity is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Case entity.
   *
   * @param bool $published
   *   TRUE to set this Case entity to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\zencrm_entities\Entity\CaseEntityInterface
   *   The called Case entity entity.
   */
  public function setPublished($published);

}
