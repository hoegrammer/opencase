<?php

namespace Drupal\zencrm_entities;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
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
class PersonStorage extends SqlContentEntityStorage implements PersonStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(PersonInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {person_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {person_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(PersonInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {person_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('person_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
