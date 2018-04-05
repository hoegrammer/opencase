<?php

namespace Drupal\zencrm_entities;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\zencrm_entities\Entity\ProfileInterface;

/**
 * Defines the storage handler class for Profile entities.
 *
 * This extends the base storage class, adding required special handling for
 * Profile entities.
 *
 * @ingroup zencrm_entities
 */
interface ProfileStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Profile revision IDs for a specific Profile.
   *
   * @param \Drupal\zencrm_entities\Entity\ProfileInterface $entity
   *   The Profile entity.
   *
   * @return int[]
   *   Profile revision IDs (in ascending order).
   */
  public function revisionIds(ProfileInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Profile author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Profile revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\zencrm_entities\Entity\ProfileInterface $entity
   *   The Profile entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(ProfileInterface $entity);

  /**
   * Unsets the language for all Profile with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
