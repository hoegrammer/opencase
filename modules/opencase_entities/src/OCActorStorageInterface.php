<?php

namespace Drupal\opencase_entities;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\opencase_entities\Entity\OCActorInterface;

/**
 * Defines the storage handler class for Actor entities.
 *
 * This extends the base storage class, adding required special handling for
 * Actor entities.
 *
 * @ingroup opencase_entities
 */
interface OCActorStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Actor revision IDs for a specific Actor.
   *
   * @param \Drupal\opencase_entities\Entity\OCActorInterface $entity
   *   The Actor entity.
   *
   * @return int[]
   *   Actor revision IDs (in ascending order).
   */
  public function revisionIds(OCActorInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Actor author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Actor revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\opencase_entities\Entity\OCActorInterface $entity
   *   The Actor entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(OCActorInterface $entity);

  /**
   * Unsets the language for all Actor with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
