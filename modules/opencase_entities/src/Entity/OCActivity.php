<?php

namespace Drupal\opencase_entities\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\RevisionableContentEntityBase;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Activity entity.
 *
 * @ingroup opencase_entities
 *
 * @ContentEntityType(
 *   id = "oc_activity",
 *   label = @Translation("Activity"),
 *   bundle_label = @Translation("Activity type"),
 *   handlers = {
 *     "storage" = "Drupal\opencase_entities\OCActivityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\opencase_entities\OCActivityListBuilder",
 *     "views_data" = "Drupal\opencase_entities\Entity\OCActivityViewsData",
 *     "translation" = "Drupal\opencase_entities\OCActivityTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\opencase_entities\Form\OCActivityForm",
 *       "add" = "Drupal\opencase_entities\Form\OCActivityForm",
 *       "edit" = "Drupal\opencase_entities\Form\OCActivityForm",
 *       "delete" = "Drupal\opencase_entities\Form\OCActivityDeleteForm",
 *     },
 *     "access" = "Drupal\opencase_entities\OCActivityAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\opencase_entities\OCActivityHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "oc_activity",
 *   data_table = "oc_activity_field_data",
 *   revision_table = "oc_activity_revision",
 *   revision_data_table = "oc_activity_field_revision",
 *   translatable = TRUE,
 *   admin_permission = "administer activity entities",
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
 *     "canonical" = "/opencase/oc_activity/{oc_activity}",
 *     "add-page" = "/opencase/oc_activity/add",
 *     "add-form" = "/opencase/oc_activity/add/{oc_activity_type}",
 *     "edit-form" = "/opencase/oc_activity/{oc_activity}/edit",
 *     "delete-form" = "/opencase/oc_activity/{oc_activity}/delete",
 *     "version-history" = "/opencase/oc_activity/{oc_activity}/revisions",
 *     "revision" = "/opencase/oc_activity/{oc_activity}/revisions/{oc_activity_revision}/view",
 *     "revision_revert" = "/opencase/oc_activity/{oc_activity}/revisions/{oc_activity_revision}/revert",
 *     "revision_delete" = "/opencase/oc_activity/{oc_activity}/revisions/{oc_activity_revision}/delete",
 *     "translation_revert" = "/opencase/oc_activity/{oc_activity}/revisions/{oc_activity_revision}/revert/{langcode}",
 *     "collection" = "/opencase/oc_activity",
 *   },
 *   bundle_entity_type = "oc_activity_type",
 *   field_ui_base_route = "entity.oc_activity_type.edit_form"
 * )
 */
class OCActivity extends RevisionableContentEntityBase implements OCActivityInterface {

  use EntityChangedTrait;

  /**
   * When creating an activity, it sets the case id from the URL.
   */
  public static function defaultVal() {
    return array(\Drupal::request()->query->get('case_id'));
  }

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

    // If no revision author has been set explicitly, make the oc_activity owner the
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

    // not currently in use. Will set view and form settings when ready
    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('A boolean indicating whether the Activity is published.'))
      ->setRevisionable(TRUE)
      ->setDefaultValue(TRUE);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Entered by'))
      ->setDescription(t('The user ID of author of the Activity entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'author',
        'weight' => -4,
      ]);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Subject'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -3,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -3,
      ])
      ->setRequired(TRUE);

    $fields['oc_case'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Case'))
      ->setDescription(t('The case this activity belongs to.'))
      ->setSetting('target_type', 'oc_case')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setCardinality(1)
      ->setDefaultValueCallback('Drupal\opencase_entities\Entity\OCActivity::defaultVal')
      ->setRequired(TRUE);

    $fields['description'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Description'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'basic_string',
        'weight' => -1,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textarea',
        'weight' => -1,
      ])
      ->setRequired(FALSE);

    $fields['time_taken'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Time taken'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'suffix' => 'minutes',
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'number_unformatted',
        'weight' => -3,
      ])
      ->setDisplayOptions('form', [
        'type' => 'number_unformatted',
        'weight' => -3,
      ]);

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
