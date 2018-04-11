<?php

namespace Drupal\zencrm_entities\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Person entities.
 *
 * @ingroup zencrm_entities
 */
interface PersonInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Person name.
   *
   * @return string
   *   Name of the Person.
   */
  public function getName();

  /**
   * Sets the Person name.
   *
   * @param string $name
   *   The Person name.
   *
   * @return \Drupal\zencrm_entities\Entity\PersonInterface
   *   The called Person entity.
   */
  public function setName($name);

  /**
   * Gets the Person creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Person.
   */
  public function getCreatedTime();

  /**
   * Sets the Person creation timestamp.
   *
   * @param int $timestamp
   *   The Person creation timestamp.
   *
   * @return \Drupal\zencrm_entities\Entity\PersonInterface
   *   The called Person entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Person published status indicator.
   *
   * Unpublished Person are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Person is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Person.
   *
   * @param bool $published
   *   TRUE to set this Person to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\zencrm_entities\Entity\PersonInterface
   *   The called Person entity.
   */
  public function setPublished($published);

}
