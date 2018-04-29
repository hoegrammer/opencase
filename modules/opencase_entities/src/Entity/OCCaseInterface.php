<?php

namespace Drupal\opencase_entities\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Case entities.
 *
 * @ingroup opencase_entities
 */
interface OCCaseInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Case name.
   *
   * @return string
   *   Name of the Case.
   */
  public function getName();

  /**
   * Sets the Case name.
   *
   * @param string $name
   *   The Case name.
   *
   * @return \Drupal\opencase_entities\Entity\OCCaseInterface
   *   The called Case entity.
   */
  public function setName($name);

  /**
   * Gets the Case creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Case.
   */
  public function getCreatedTime();

  /**
   * Sets the Case creation timestamp.
   *
   * @param int $timestamp
   *   The Case creation timestamp.
   *
   * @return \Drupal\opencase_entities\Entity\OCCaseInterface
   *   The called Case entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Case published status indicator.
   *
   * Unpublished Case are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Case is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Case.
   *
   * @param bool $published
   *   TRUE to set this Case to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\opencase_entities\Entity\OCCaseInterface
   *   The called Case entity.
   */
  public function setPublished($published);

  /**
   * Gets the Case revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Case revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\opencase_entities\Entity\OCCaseInterface
   *   The called Case entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Case revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Case revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\opencase_entities\Entity\OCCaseInterface
   *   The called Case entity.
   */
  public function setRevisionUserId($uid);

}
