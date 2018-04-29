<?php

namespace Drupal\opencase_entities\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Actor entities.
 *
 * @ingroup opencase_entities
 */
interface OCActorInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Actor name.
   *
   * @return string
   *   Name of the Actor.
   */
  public function getName();

  /**
   * Sets the Actor name.
   *
   * @param string $name
   *   The Actor name.
   *
   * @return \Drupal\opencase_entities\Entity\OCActorInterface
   *   The called Actor entity.
   */
  public function setName($name);

  /**
   * Gets the Actor creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Actor.
   */
  public function getCreatedTime();

  /**
   * Sets the Actor creation timestamp.
   *
   * @param int $timestamp
   *   The Actor creation timestamp.
   *
   * @return \Drupal\opencase_entities\Entity\OCActorInterface
   *   The called Actor entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Actor published status indicator.
   *
   * Unpublished Actor are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Actor is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Actor.
   *
   * @param bool $published
   *   TRUE to set this Actor to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\opencase_entities\Entity\OCActorInterface
   *   The called Actor entity.
   */
  public function setPublished($published);

  /**
   * Gets the Actor revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Actor revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\opencase_entities\Entity\OCActorInterface
   *   The called Actor entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Actor revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Actor revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\opencase_entities\Entity\OCActorInterface
   *   The called Actor entity.
   */
  public function setRevisionUserId($uid);

}
