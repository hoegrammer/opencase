<?php

namespace Drupal\zencrm_entities\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Activity entity.
 *
 * @ingroup zencrm_entities
 *
 * @ContentEntityType(
 *   id = "activity",
 *   label = @Translation("Activity"),
 *   bundle_label = @Translation("Activity type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\zencrm_entities\ActivityListBuilder",
 *     "views_data" = "Drupal\zencrm_entities\Entity\ActivityViewsData",
 *     "translation" = "Drupal\zencrm_entities\ActivityTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\zencrm_entities\Form\ActivityForm",
 *       "add" = "Drupal\zencrm_entities\Form\ActivityForm",
 *       "edit" = "Drupal\zencrm_entities\Form\ActivityForm",
 *       "delete" = "Drupal\zencrm_entities\Form\ActivityDeleteForm",
 *     },
 *     "access" = "Drupal\zencrm_entities\ActivityAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\zencrm_entities\ActivityHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "activity",
 *   data_table = "activity_field_data",
 *   translatable = TRUE,
 *   admin_permission = "administer activity entities",
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
 *     "canonical" = "/zencrm/activity/{activity}",
 *     "add-page" = "/zencrm/activity/add",
 *     "add-form" = "/zencrm/activity/add/{activity_type}",
 *     "edit-form" = "/zencrm/activity/{activity}/edit",
 *     "delete-form" = "/zencrm/activity/{activity}/delete",
 *     "collection" = "/zencrm/activity",
 *   },
 *   bundle_entity_type = "activity_type",
 *   field_ui_base_route = "entity.activity_type.edit_form"
 * )
 */
class Activity extends ContentEntityBase implements ActivityInterface {

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
      ->setDescription(t('The user ID of author of the Activity entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
#      ->setDisplayOptions('view', [
#        'label' => 'hidden',
#        'type' => 'author',
#        'weight' => 0,
#      ])
#      ->setDisplayOptions('form', [
#        'type' => 'entity_reference_autocomplete',
#        'weight' => 5,
#        'settings' => [
#          'match_operator' => 'CONTAINS',
#          'size' => '60',
#          'autocomplete_type' => 'tags',
#          'placeholder' => '',
#        ],
#      ])
#      ->setDisplayConfigurable('form', TRUE)
#      ->setDisplayConfigurable('view', TRUE);
      ->setTranslatable(TRUE);

    // This field is always implied from the context,
    // so has no form or view display.
    $fields['case'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Case'))
      ->setDescription(t('The case this activity belongs to.'))
      ->setSetting('target_type', 'case_entity');

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the Activity entity.'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('A boolean indicating whether the Activity is published.'))
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

    return $fields;
  }

}
