<?php

namespace Drupal\zencrm_entities\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Hat entities.
 *
 * @ingroup zencrm_entities
 */
interface HatInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Hat name.
   *
   * @return string
   *   Name of the Hat.
   */
  public function getName();

  /**
   * Sets the Hat name.
   *
   * @param string $name
   *   The Hat name.
   *
   * @return \Drupal\zencrm_entities\Entity\HatInterface
   *   The called Hat entity.
   */
  public function setName($name);

  /**
   * Gets the Hat creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Hat.
   */
  public function getCreatedTime();

  /**
   * Sets the Hat creation timestamp.
   *
   * @param int $timestamp
   *   The Hat creation timestamp.
   *
   * @return \Drupal\zencrm_entities\Entity\HatInterface
   *   The called Hat entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Hat published status indicator.
   *
   * Unpublished Hat are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Hat is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Hat.
   *
   * @param bool $published
   *   TRUE to set this Hat to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\zencrm_entities\Entity\HatInterface
   *   The called Hat entity.
   */
  public function setPublished($published);

}
