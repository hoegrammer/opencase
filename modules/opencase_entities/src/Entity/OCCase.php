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
 * Defines the Case entity.
 *
 * @ingroup opencase_entities
 *
 * @ContentEntityType(
 *   id = "oc_case",
 *   label = @Translation("Case"),
 *   bundle_label = @Translation("Case type"),
 *   handlers = {
 *     "storage" = "Drupal\opencase_entities\OCCaseStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\opencase_entities\OCCaseListBuilder",
 *     "views_data" = "Drupal\opencase_entities\Entity\OCCaseViewsData",
 *     "translation" = "Drupal\opencase_entities\OCCaseTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\opencase_entities\Form\OCCaseForm",
 *       "add" = "Drupal\opencase_entities\Form\OCCaseForm",
 *       "edit" = "Drupal\opencase_entities\Form\OCCaseForm",
 *       "delete" = "Drupal\opencase_entities\Form\OCCaseDeleteForm",
 *     },
 *     "access" = "Drupal\opencase_entities\OCCaseAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\opencase_entities\OCCaseHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "oc_case",
 *   data_table = "oc_case_field_data",
 *   revision_table = "oc_case_revision",
 *   revision_data_table = "oc_case_field_revision",
 *   translatable = TRUE,
 *   admin_permission = "administer case entities",
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
 *     "canonical" = "/opencase/oc_case/{oc_case}",
 *     "add-page" = "/opencase/oc_case/add",
 *     "add-form" = "/opencase/oc_case/add/{oc_case_type}",
 *     "edit-form" = "/opencase/oc_case/{oc_case}/edit",
 *     "delete-form" = "/opencase/oc_case/{oc_case}/delete",
 *     "version-history" = "/opencase/oc_case/{oc_case}/revisions",
 *     "revision" = "/opencase/oc_case/{oc_case}/revisions/{oc_case_revision}/view",
 *     "revision_revert" = "/opencase/oc_case/{oc_case}/revisions/{oc_case_revision}/revert",
 *     "revision_delete" = "/opencase/oc_case/{oc_case}/revisions/{oc_case_revision}/delete",
 *     "translation_revert" = "/opencase/oc_case/{oc_case}/revisions/{oc_case_revision}/revert/{langcode}",
 *     "collection" = "/opencase/oc_case",
 *   },
 *   bundle_entity_type = "oc_case_type",
 *   field_ui_base_route = "entity.oc_case_type.edit_form"
 * )
 */
class OCCase extends RevisionableContentEntityBase implements OCCaseInterface {

  use EntityChangedTrait;

  /**
   * When creating a case, it sets the first involved party to the actor
   * id from the URL, and the second to the author's linked actor 
   * (if it exists and is different)
   */
  public static function defaultVal() {
    $author_linked_actor_id = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id())->get('field_linked_opencase_actor')->target_id;
    $currently_viewed_actor_id = \Drupal::request()->query->get('actor_id');
    return array_unique([$currently_viewed_actor_id, $author_linked_actor_id]);
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

    // If no revision author has been set explicitly, make the oc_case owner the
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

    // not currently used. Will add form and view settings when ready
    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('A boolean indicating whether the Case is published.'))
      ->setRevisionable(TRUE)
      ->setDefaultValue(TRUE);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Created by'))
      ->setDescription(t('The user ID of author of the Case entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'author',
      ]);
    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Case Title'))
      ->setDescription(t('A short phrase summing up what this case is about.'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -3,
      ])
      ->setRequired(TRUE);

    $fields['actors_involved'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Involved Parties'))
      ->setDescription(t('People involved in this case. To add one, start typing their name.'))
      ->setSetting('target_type', 'oc_actor')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setCardinality(-1)
      ->setDisplayOptions('form', [
        'label' => 'above',
        'type' => 'entity_reference_autocomplete',
        'weight' => -2,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
      ])
      ->setDefaultValueCallback('Drupal\opencase_entities\Entity\OCCase::defaultVal')
      ->setRequired(TRUE);

    $fields['files'] = BaseFieldDefinition::create('file')
      ->setLabel(t('Files'))
      ->setDescription(t('Files attached to this case'))
      ->setSetting('file_directory', '[date:custom:Y]-[date:custom:m]')
      ->setSetting('handler', 'default:file')
      ->setSetting('file_extensions', 'txt jpg jpeg gif rtf xls xlsx doc swf png pdf docx csv')
      ->setSetting('description_field', 'true')
      ->setSetting('uri_scheme', 'private')
      ->setCardinality(-1)
      ->setDisplayOptions('form', [
        'type' => 'file_generic',
        'weight' => -1,
        'settings' => [
          'progress_indicator' => 'throbber',
        ],
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'settings' => ['use_description_as_link_text' => 'true']
      ]);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created on'))
      ->setDescription(t('When the case was created.'))
      ->setDisplayOptions('view', [
        'label' => 'above',
      ]);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Last updated'))
      ->setDescription(t('When the case was last edited.'))
      ->setDisplayOptions('view', [
        'label' => 'above',
      ]);

    $fields['revision_translation_affected'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Revision translation affected'))
      ->setDescription(t('Indicates if the last edit of a translation belongs to current revision.'))
      ->setReadOnly(TRUE)
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE);

    return $fields;
  }
}
