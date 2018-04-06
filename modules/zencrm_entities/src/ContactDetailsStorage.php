<?php

namespace Drupal\zencrm_entities;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
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
class ContactDetailsStorage extends SqlContentEntityStorage implements ContactDetailsStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(ContactDetailsInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {contact_details_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {contact_details_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(ContactDetailsInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {contact_details_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('contact_details_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
