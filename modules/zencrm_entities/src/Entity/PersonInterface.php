<?php

namespace Drupal\zencrm_entities\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Person entities.
 *
 * @ingroup zencrm_entities
 */
interface PersonInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

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

  /**
   * Gets the Person revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Person revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\zencrm_entities\Entity\PersonInterface
   *   The called Person entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Person revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Person revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\zencrm_entities\Entity\PersonInterface
   *   The called Person entity.
   */
  public function setRevisionUserId($uid);

}
