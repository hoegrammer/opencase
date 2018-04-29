<?php

namespace Drupal\opencase_entities;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\opencase_entities\Entity\OCCaseInterface;

/**
 * Defines the storage handler class for Case entities.
 *
 * This extends the base storage class, adding required special handling for
 * Case entities.
 *
 * @ingroup opencase_entities
 */
interface OCCaseStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Case revision IDs for a specific Case.
   *
   * @param \Drupal\opencase_entities\Entity\OCCaseInterface $entity
   *   The Case entity.
   *
   * @return int[]
   *   Case revision IDs (in ascending order).
   */
  public function revisionIds(OCCaseInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Case author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Case revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\opencase_entities\Entity\OCCaseInterface $entity
   *   The Case entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(OCCaseInterface $entity);

  /**
   * Unsets the language for all Case with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
