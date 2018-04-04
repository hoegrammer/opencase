<?php

namespace Drupal\zencrm_entities;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\zencrm_entities\Entity\PersonInterface;

/**
 * Defines the storage handler class for Person entities.
 *
 * This extends the base storage class, adding required special handling for
 * Person entities.
 *
 * @ingroup zencrm_entities
 */
interface PersonStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Person revision IDs for a specific Person.
   *
   * @param \Drupal\zencrm_entities\Entity\PersonInterface $entity
   *   The Person entity.
   *
   * @return int[]
   *   Person revision IDs (in ascending order).
   */
  public function revisionIds(PersonInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Person author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Person revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\zencrm_entities\Entity\PersonInterface $entity
   *   The Person entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(PersonInterface $entity);

  /**
   * Unsets the language for all Person with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
