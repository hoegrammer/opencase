<?php

namespace Drupal\zencrm_entities;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\zencrm_entities\Entity\ContactDetailsInterface;

/**
 * Defines the storage handler class for Contact Details entities.
 *
 * This extends the base storage class, adding required special handling for
 * Contact Details entities.
 *
 * @ingroup zencrm_entities
 */
interface ContactDetailsStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Contact Details revision IDs for a specific Contact Details.
   *
   * @param \Drupal\zencrm_entities\Entity\ContactDetailsInterface $entity
   *   The Contact Details entity.
   *
   * @return int[]
   *   Contact Details revision IDs (in ascending order).
   */
  public function revisionIds(ContactDetailsInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Contact Details author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Contact Details revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\zencrm_entities\Entity\ContactDetailsInterface $entity
   *   The Contact Details entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(ContactDetailsInterface $entity);

  /**
   * Unsets the language for all Contact Details with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
