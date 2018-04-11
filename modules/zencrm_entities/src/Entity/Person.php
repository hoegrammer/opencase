<?php

namespace Drupal\zencrm_entities\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Person entity.
 *
 * @ingroup zencrm_entities
 *
 * @ContentEntityType(
 *   id = "person",
 *   label = @Translation("Person"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\zencrm_entities\PersonListBuilder",
 *     "views_data" = "Drupal\zencrm_entities\Entity\PersonViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\zencrm_entities\Form\PersonForm",
 *       "add" = "Drupal\zencrm_entities\Form\PersonForm",
 *       "edit" = "Drupal\zencrm_entities\Form\PersonForm",
 *       "delete" = "Drupal\zencrm_entities\Form\PersonDeleteForm",
 *     },
 *     "access" = "Drupal\zencrm_entities\PersonAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\zencrm_entities\PersonHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "person",
 *   admin_permission = "administer person entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "full_name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/zencrm/person/{person}",
 *     "add-form" = "/zencrm/person/add",
 *     "edit-form" = "/zencrm/person/{person}/edit",
 *     "delete-form" = "/zencrm/person/{person}/delete",
 *     "collection" = "/admin/structure/person",
 *   },
 *   field_ui_base_route = "person.settings"
 * )
 */
class Person extends ContentEntityBase implements PersonInterface {

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
      ->setDescription(t('The user ID of author of the Person entity.'))
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
    #  ->setDisplayOptions('view', [
    #    'label' => 'hidden',
    #    'type' => 'author',
    #    'weight' => 0,
    #  ])
    #  ->setDisplayOptions('form', [
    #    'type' => 'entity_reference_autocomplete',
    #    'weight' => 5,
    #    'settings' => [
    #      'match_operator' => 'CONTAINS',
    #      'size' => '60',
    #      'autocomplete_type' => 'tags',
    #      'placeholder' => '',
    #    ],
    #  ])
      ->setTranslatable(TRUE);


    // This field is computed in a presave hook.
    $fields['full_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Full Name'))
      ->setDescription(t('The full name of the person.'));

    $fields['first_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('First Name'))
      ->setDescription(t('First Name.'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setRequired(TRUE);

    $fields['middle_names'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Middle Names'))
      ->setDescription(t('Middle Names.'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE);


    $fields['last_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Last Name'))
      ->setDescription(t('Last Name.'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setRequired(TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('A boolean indicating whether the Person is published.'))
     # ->setDisplayOptions('form', [
     #   'type' => 'boolean_checkbox',
     #   'weight' => -3,
     # ])
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
