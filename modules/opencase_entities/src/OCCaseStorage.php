<?php

namespace Drupal\opencase_entities;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
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
class OCCaseStorage extends SqlContentEntityStorage implements OCCaseStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(OCCaseInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {oc_case_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {oc_case_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(OCCaseInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {oc_case_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('oc_case_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
