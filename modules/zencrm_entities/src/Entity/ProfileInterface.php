<?php

namespace Drupal\zencrm_entities\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Profile entities.
 *
 * @ingroup zencrm_entities
 */
interface ProfileInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Profile name.
   *
   * @return string
   *   Name of the Profile.
   */
  public function getName();

  /**
   * Sets the Profile name.
   *
   * @param string $name
   *   The Profile name.
   *
   * @return \Drupal\zencrm_entities\Entity\ProfileInterface
   *   The called Profile entity.
   */
  public function setName($name);

  /**
   * Gets the Profile creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Profile.
   */
  public function getCreatedTime();

  /**
   * Sets the Profile creation timestamp.
   *
   * @param int $timestamp
   *   The Profile creation timestamp.
   *
   * @return \Drupal\zencrm_entities\Entity\ProfileInterface
   *   The called Profile entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Profile published status indicator.
   *
   * Unpublished Profile are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Profile is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Profile.
   *
   * @param bool $published
   *   TRUE to set this Profile to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\zencrm_entities\Entity\ProfileInterface
   *   The called Profile entity.
   */
  public function setPublished($published);

  /**
   * Gets the Profile revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Profile revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\zencrm_entities\Entity\ProfileInterface
   *   The called Profile entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Profile revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Profile revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\zencrm_entities\Entity\ProfileInterface
   *   The called Profile entity.
   */
  public function setRevisionUserId($uid);

}
