<?php

namespace Drupal\zencrm_entities\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Contact details entities.
 *
 * @ingroup zencrm_entities
 */
interface ContactDetailsInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Contact details name.
   *
   * @return string
   *   Name of the Contact details.
   */
  public function getName();

  /**
   * Sets the Contact details name.
   *
   * @param string $name
   *   The Contact details name.
   *
   * @return \Drupal\zencrm_entities\Entity\ContactDetailsInterface
   *   The called Contact details entity.
   */
  public function setName($name);

  /**
   * Gets the Contact details creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Contact details.
   */
  public function getCreatedTime();

  /**
   * Sets the Contact details creation timestamp.
   *
   * @param int $timestamp
   *   The Contact details creation timestamp.
   *
   * @return \Drupal\zencrm_entities\Entity\ContactDetailsInterface
   *   The called Contact details entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Contact details published status indicator.
   *
   * Unpublished Contact details are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Contact details is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Contact details.
   *
   * @param bool $published
   *   TRUE to set this Contact details to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\zencrm_entities\Entity\ContactDetailsInterface
   *   The called Contact details entity.
   */
  public function setPublished($published);

}
