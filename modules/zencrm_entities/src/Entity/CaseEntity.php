<?php

namespace Drupal\zencrm_entities\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Case entity entity.
 *
 * @ingroup zencrm_entities
 *
 * @ContentEntityType(
 *   id = "case_entity",
 *   label = @Translation("Case entity"),
 *   bundle_label = @Translation("Case entity type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\zencrm_entities\CaseEntityListBuilder",
 *     "views_data" = "Drupal\zencrm_entities\Entity\CaseEntityViewsData",
 *     "translation" = "Drupal\zencrm_entities\CaseEntityTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\zencrm_entities\Form\CaseEntityForm",
 *       "add" = "Drupal\zencrm_entities\Form\CaseEntityForm",
 *       "edit" = "Drupal\zencrm_entities\Form\CaseEntityForm",
 *       "delete" = "Drupal\zencrm_entities\Form\CaseEntityDeleteForm",
 *     },
 *     "access" = "Drupal\zencrm_entities\CaseEntityAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\zencrm_entities\CaseEntityHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "case_entity",
 *   data_table = "case_entity_field_data",
 *   translatable = TRUE,
 *   admin_permission = "administer case entity entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "bundle" = "type",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/zencrm/case/{case_entity}",
 *     "add-page" = "/zencrm/case/add",
 *     "add-form" = "/zencrm/case/add/{case_entity_type}",
 *     "edit-form" = "/zencrm/case/{case_entity}/edit",
 *     "delete-form" = "/zencrm/case/{case_entity}/delete",
 *     "collection" = "/zencrm/case",
 *   },
 *   bundle_entity_type = "case_entity_type",
 *   field_ui_base_route = "entity.case_entity_type.edit_form"
 * )
 */
class CaseEntity extends ContentEntityBase implements CaseEntityInterface {

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
      ->setDescription(t('The user ID of author of the Case entity entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
     # ->setDisplayOptions('view', [
     #   'label' => 'hidden',
     #   'type' => 'author',
     #   'weight' => 0,
     # ])
     # ->setDisplayOptions('form', [
     #   'type' => 'entity_reference_autocomplete',
     #   'weight' => 5,
     #   'settings' => [
     #     'match_operator' => 'CONTAINS',
     #     'size' => '60',
     #     'autocomplete_type' => 'tags',
     #     'placeholder' => '',
     #   ],
     # ])
      ->setTranslatable(TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Title'))
      ->setDescription(t('A name for this case'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setRequired(TRUE);


    $fields['hats_involved'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Involved Parties'))
      ->setDescription(t('People involved in this case, in their various capacities'))
      ->setSetting('target_type', 'hat')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setCardinality(-1)
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setRequired(TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('A boolean indicating whether the Case entity is published.'))
#      ->setDisplayOptions('form', [
#        'type' => 'boolean_checkbox',
#        'weight' => -3,
#      ])
      ->setDefaultValue(TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => 0,
      ]);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
