<?php

namespace Drupal\zencrm_entities\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Contact details entity.
 *
 * @ingroup zencrm_entities
 *
 * @ContentEntityType(
 *   id = "contact_details",
 *   label = @Translation("Contact details"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\zencrm_entities\ContactDetailsListBuilder",
 *     "views_data" = "Drupal\zencrm_entities\Entity\ContactDetailsViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\zencrm_entities\Form\ContactDetailsForm",
 *       "add" = "Drupal\zencrm_entities\Form\ContactDetailsForm",
 *       "edit" = "Drupal\zencrm_entities\Form\ContactDetailsForm",
 *       "delete" = "Drupal\zencrm_entities\Form\ContactDetailsDeleteForm",
 *     },
 *     "access" = "Drupal\zencrm_entities\ContactDetailsAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\zencrm_entities\ContactDetailsHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "contact_details",
 *   admin_permission = "administer contact details entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "type",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/contact_details/{contact_details}",
 *     "add-form" = "/admin/structure/contact_details/add",
 *     "edit-form" = "/admin/structure/contact_details/{contact_details}/edit",
 *     "delete-form" = "/admin/structure/contact_details/{contact_details}/delete",
 *     "collection" = "/admin/structure/contact_details",
 *   },
 *   field_ui_base_route = "contact_details.settings"
 * )
 */
class ContactDetails extends ContentEntityBase implements ContactDetailsInterface {

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
      ->setDescription(t('The user ID of author of the Contact Details entity.'))
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
     # ->setDisplayOptions('view', [
     #   'label' => 'inline',
     #   'type' => 'author',
     #   'weight' => 100,
     # ])
     # ->setDisplayOptions('form', [
     #   'type' => 'entity_reference_autocomplete',
     #   'weight' => 100,
     #   'settings' => [
     #     'match_operator' => 'CONTAINS',
     #     'size' => '60',
     #     'autocomplete_type' => 'tags',
     #     'placeholder' => '',
     #   ],
     # ])
      ->setTranslatable(TRUE);


    // Type field is used in entity reference fields etc
    // so it is not exposed to user configuration. 
    $fields['type'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Type'))
      ->setDescription(t('E.g. Home, Business, Temporary'))
      ->setDisplayOptions('form', [
        'label' => 'hidden',
        'type' => 'text',
        'weight' => 0,
      ])
      ->setDisplayOptions('view', array(
        'label' => 'hidden',
        'type' => 'string',
        'weight' => 0,
      ))
      ->setRequired(TRUE);


    // Person field is always set from the context so no form or display required.
    $fields['person'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Person'))
      ->setDescription(t('The person this profile is of.'))
      ->setSetting('target_type', 'person')
      ->setRequired(TRUE);

    // Type field is used for mailings, 
    // so it is not exposed to user configuration. 
    $fields['email'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Email Address'))
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 30,
        'text_processing' => 0,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'hidden',
        'type' => 'string',
        'weight' => 0,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => 0,
      ));

    $fields['phone'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Main Phone Number'))
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE)
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 20,
        'text_processing' => 0,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => 2,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => 2,
      ));

    $fields['phone2'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Alternative Phone Number'))
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 20,
        'text_processing' => 0,
      ))
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => 3,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => 3,
      ));

    $fields['postal_address'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Postal Address'))
      ->setDescription(t('Full address, apart from post code.'))
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE)
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 255,
        'text_processing' => 0,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'text',
        'weight' => 5,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textarea',
        'weight' => 5,
      ));

    $fields['post_code'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Post Code'))
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 10,
        'text_processing' => 0,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => 6,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => 6,
      ));

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Enabled'))
      ->setDescription(t('If this is ticked then this set of contact details is active.'))
   #   ->setDisplayOptions('form', [
   #     'type' => 'boolean_checkbox',
   #     'weight' => -3,
   #   ])
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
