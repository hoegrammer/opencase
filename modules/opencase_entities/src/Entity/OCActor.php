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
 * Defines the Actor entity.
 *
 * @ingroup opencase_entities
 *
 * @ContentEntityType(
 *   id = "oc_actor",
 *   label = @Translation("Person"),
 *   bundle_label = @Translation("Person type"),
 *   handlers = {
 *     "storage" = "Drupal\opencase_entities\OCActorStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\opencase_entities\OCActorListBuilder",
 *     "views_data" = "Drupal\opencase_entities\Entity\OCActorViewsData",
 *     "translation" = "Drupal\opencase_entities\OCActorTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\opencase_entities\Form\OCActorForm",
 *       "add" = "Drupal\opencase_entities\Form\OCActorForm",
 *       "edit" = "Drupal\opencase_entities\Form\OCActorForm",
 *       "delete" = "Drupal\opencase_entities\Form\OCActorDeleteForm",
 *     },
 *     "access" = "Drupal\opencase_entities\OCActorAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\opencase_entities\OCActorHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "oc_actor",
 *   data_table = "oc_actor_field_data",
 *   revision_table = "oc_actor_revision",
 *   revision_data_table = "oc_actor_field_revision",
 *   translatable = TRUE,
 *   admin_permission = "administer actor entities",
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
 *     "canonical" = "/opencase/oc_actor/{oc_actor}",
 *     "add-page" = "/opencase/oc_actor/add",
 *     "add-form" = "/opencase/oc_actor/add/{oc_actor_type}",
 *     "edit-form" = "/opencase/oc_actor/{oc_actor}/edit",
 *     "delete-form" = "/opencase/oc_actor/{oc_actor}/delete",
 *     "version-history" = "/opencase/oc_actor/{oc_actor}/revisions",
 *     "revision" = "/opencase/oc_actor/{oc_actor}/revisions/{oc_actor_revision}/view",
 *     "revision_revert" = "/opencase/oc_actor/{oc_actor}/revisions/{oc_actor_revision}/revert",
 *     "revision_delete" = "/opencase/oc_actor/{oc_actor}/revisions/{oc_actor_revision}/delete",
 *     "translation_revert" = "/opencase/oc_actor/{oc_actor}/revisions/{oc_actor_revision}/revert/{langcode}",
 *     "collection" = "/opencase/oc_actor",
 *   },
 *   bundle_entity_type = "oc_actor_type",
 *   field_ui_base_route = "entity.oc_actor_type.edit_form"
 * )
 */
class OCActor extends RevisionableContentEntityBase implements OCActorInterface {

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
    
    $name = $this->get('first_name')->value . ' ';
    if ($this->get('middle_names')->value) $name .= $this->get('middle_names')->value . ' ';
    $name .= $this->get('last_name')->value . ' ';
    $name .= '(' . $this->bundle() . ')';
  
    $this->setName($name);

    // If no revision author has been set explicitly, make the oc_actor owner the
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

    // Currently not using this, but will add form and view settings when ready.
    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('Whether this record is published.'))
      ->setRevisionable(TRUE)
      ->setDefaultValue(TRUE);

    // The name gets set on preSave, from the first middle and last
    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setSettings([
        'max_length' => 100,
        'text_processing' => 0,
      ]); 

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Entered by'))
      ->setDescription(t('The user ID of author of the Actor entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'author',
        'weight' => -10,
      ]);

    $fields['first_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('First Name'))
      ->setDescription(t("The person's first name."))
      ->setRevisionable(TRUE)
      ->setSettings([
        'max_length' => 20,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -9,
      ])
      ->setRequired(TRUE);

    $fields['middle_names'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Middle Names'))
      ->setDescription(t("The person's middle names, if any."))
      ->setRevisionable(TRUE)
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -8,
      ])
      ->setRequired(FALSE);

    $fields['last_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Last Name'))
      ->setDescription(t("The person's last name"))
      ->setRevisionable(TRUE)
      ->setSettings([
        'max_length' => 20,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -7,
      ])
      ->setRequired(TRUE);

    $fields['consent'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Consent to data storage'))
      ->setDescription(t('Has this person explicitly consented to having their personal data stored on this system?'))
      ->setRevisionable(TRUE)
      ->setDefaultValue(FALSE)
      ->setRequired(TRUE)
      ->setDisplayOptions('form', array(
        'type' => 'boolean_checkbox',
        'weight' => -6,
      ));

    // Contact details.
    // so it is not exposed to user configuration. 
    $fields['email'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Email Address'))
      ->setRevisionable(TRUE)
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 30,
        'text_processing' => 0,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => -5,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -5,
      ));
    $fields['phone'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Main Phone Number'))
      ->setRevisionable(TRUE)
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 20,
        'text_processing' => 0,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -4,
      ));
    $fields['phone2'] = BaseFieldDefinition::create('string')
      ->setRevisionable(TRUE)
      ->setLabel(t('Alternative Phone Number'))
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 20,
        'text_processing' => 0,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => -3,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -3,
      ));
    $fields['postal_address'] = BaseFieldDefinition::create('string_long')
      ->setRevisionable(TRUE)
      ->setLabel(t('Postal Address'))
      ->setDescription(t('Full address, apart from post code.'))
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 255,
        'text_processing' => 0,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'basic_string',
        'weight' => -2,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textarea',
        'weight' => -2,
      ));
    $fields['post_code'] = BaseFieldDefinition::create('string')
      ->setRevisionable(TRUE)
      ->setLabel(t('Post Code'))
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 10,
        'text_processing' => 0,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => -1,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -1,
      ));

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
