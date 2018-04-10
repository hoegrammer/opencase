<?php

namespace Drupal\zencrm_entities\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\RevisionableContentEntityBase;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Profile entity.
 *
 * @ingroup zencrm_entities
 *
 * @ContentEntityType(
 *   id = "profile",
 *   label = @Translation("Profile"),
 *   bundle_label = @Translation("Profile type"),
 *   handlers = {
 *     "storage" = "Drupal\zencrm_entities\ProfileStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\zencrm_entities\ProfileListBuilder",
 *     "views_data" = "Drupal\zencrm_entities\Entity\ProfileViewsData",
 *     "translation" = "Drupal\zencrm_entities\ProfileTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\zencrm_entities\Form\ProfileForm",
 *       "add" = "Drupal\zencrm_entities\Form\ProfileForm",
 *       "edit" = "Drupal\zencrm_entities\Form\ProfileForm",
 *       "delete" = "Drupal\zencrm_entities\Form\ProfileDeleteForm",
 *     },
 *     "access" = "Drupal\zencrm_entities\ProfileAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\zencrm_entities\ProfileHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "profile",
 *   data_table = "profile_field_data",
 *   revision_table = "profile_revision",
 *   revision_data_table = "profile_field_revision",
 *   translatable = TRUE,
 *   admin_permission = "administer profile entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "vid",
 *     "bundle" = "type",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/zencrm/profile/{profile}",
 *     "add-page" = "/zencrm/profile/add",
 *     "add-form" = "/zencrm/profile/add/{profile_type}",
 *     "edit-form" = "/zencrm/profile/{profile}/edit",
 *     "delete-form" = "/zencrm/profile/{profile}/delete",
 *     "version-history" = "/zencrm/profile/{profile}/revisions",
 *     "revision" = "/zencrm/profile/{profile}/revisions/{profile_revision}/view",
 *     "revision_revert" = "/zencrm/profile/{profile}/revisions/{profile_revision}/revert",
 *     "revision_delete" = "/zencrm/profile/{profile}/revisions/{profile_revision}/delete",
 *     "translation_revert" = "/zencrm/profile/{profile}/revisions/{profile_revision}/revert/{langcode}",
 *     "collection" = "/zencrm/profile",
 *   },
 *   bundle_entity_type = "profile_type",
 *   field_ui_base_route = "entity.profile_type.edit_form"
 * )
 */
class Profile extends RevisionableContentEntityBase implements ProfileInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  protected function urlRouteParameters($rel) {
    $uri_route_parameters = parent::urlRouteParameters($rel);

    if ($rel === 'revision_revert' && $this instanceof RevisionableInterface) {
      $uri_route_parameters[$this->getEntityTypeId() . '_revision'] = $this->getRevisionId();
    }
    elseif ($rel === 'revision_delete' && $this instanceof RevisionableInterface) {
      $uri_route_parameters[$this->getEntityTypeId() . '_revision'] = $this->getRevisionId();
    }

    return $uri_route_parameters;
  }

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage) {
    parent::preSave($storage);

    foreach (array_keys($this->getTranslationLanguages()) as $langcode) {
      $translation = $this->getTranslation($langcode);

      // If no owner has been set explicitly, make the anonymous user the owner.
      if (!$translation->getOwner()) {
        $translation->setOwnerId(0);
      }
    }

    // If no revision author has been set explicitly, make the profile owner the
    // revision author.
    if (!$this->getRevisionUser()) {
      $this->setRevisionUserId($this->getOwnerId());
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * {@inheritdoc}
   */
  public function setPublished($published) {
    $this->set('status', $published ? TRUE : FALSE);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Profile entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE);
    #  ->setDisplayOptions('form', [
    #    'type' => 'entity_reference_autocomplete',
    #    'weight' => 5,
    #    'settings' => [
    #      'match_operator' => 'CONTAINS',
    #      'size' => '60',
    #      'autocomplete_type' => 'tags',
    #      'placeholder' => '',
    #    ],
    #  ]);

    $fields['person'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Person'))
      ->setDescription(t('The person this profile is of.'))
      ->setSetting('target_type', 'person');

    // This field is computed in a presave hook.
    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of this profile instance.'))
      ->setRevisionable(TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('A boolean indicating whether the Profile is published.'))
      ->setRevisionable(TRUE)
    #  ->setDisplayOptions('form', [
    #    'type' => 'boolean_checkbox',
    #    'weight' => -3,
    #  ])
      ->setDefaultValue(TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    $fields['revision_translation_affected'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Revision translation affected'))
      ->setDescription(t('Indicates if the last edit of a translation belongs to current revision.'))
      ->setReadOnly(TRUE)
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE);

    return $fields;
  }

}
