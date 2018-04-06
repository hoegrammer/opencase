<?php

namespace Drupal\zencrm_entities\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Contact Details entities.
 *
 * @ingroup zencrm_entities
 */
interface ContactDetailsInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Contact Details name.
   *
   * @return string
   *   Name of the Contact Details.
   */
  public function getName();

  /**
   * Sets the Contact Details name.
   *
   * @param string $name
   *   The Contact Details name.
   *
   * @return \Drupal\zencrm_entities\Entity\ContactDetailsInterface
   *   The called Contact Details entity.
   */
  public function setName($name);

  /**
   * Gets the Contact Details creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Contact Details.
   */
  public function getCreatedTime();

  /**
   * Sets the Contact Details creation timestamp.
   *
   * @param int $timestamp
   *   The Contact Details creation timestamp.
   *
   * @return \Drupal\zencrm_entities\Entity\ContactDetailsInterface
   *   The called Contact Details entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Contact Details published status indicator.
   *
   * Unpublished Contact Details are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Contact Details is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Contact Details.
   *
   * @param bool $published
   *   TRUE to set this Contact Details to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\zencrm_entities\Entity\ContactDetailsInterface
   *   The called Contact Details entity.
   */
  public function setPublished($published);

  /**
   * Gets the Contact Details revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Contact Details revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\zencrm_entities\Entity\ContactDetailsInterface
   *   The called Contact Details entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Contact Details revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Contact Details revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\zencrm_entities\Entity\ContactDetailsInterface
   *   The called Contact Details entity.
   */
  public function setRevisionUserId($uid);

}
