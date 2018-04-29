<?php

namespace Drupal\opencase_entities;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\opencase_entities\Entity\OCActivityInterface;

/**
 * Defines the storage handler class for Activity entities.
 *
 * This extends the base storage class, adding required special handling for
 * Activity entities.
 *
 * @ingroup opencase_entities
 */
interface OCActivityStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Activity revision IDs for a specific Activity.
   *
   * @param \Drupal\opencase_entities\Entity\OCActivityInterface $entity
   *   The Activity entity.
   *
   * @return int[]
   *   Activity revision IDs (in ascending order).
   */
  public function revisionIds(OCActivityInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Activity author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Activity revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\opencase_entities\Entity\OCActivityInterface $entity
   *   The Activity entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(OCActivityInterface $entity);

  /**
   * Unsets the language for all Activity with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
