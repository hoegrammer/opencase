<?php

namespace Drupal\zencrm_entities\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Hat entity.
 *
 * @ingroup zencrm_entities
 *
 * @ContentEntityType(
 *   id = "hat",
 *   label = @Translation("Hat"),
 *   bundle_label = @Translation("Hat type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\zencrm_entities\HatListBuilder",
 *     "views_data" = "Drupal\zencrm_entities\Entity\HatViewsData",
 *     "translation" = "Drupal\zencrm_entities\HatTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\zencrm_entities\Form\HatForm",
 *       "add" = "Drupal\zencrm_entities\Form\HatForm",
 *       "edit" = "Drupal\zencrm_entities\Form\HatForm",
 *       "delete" = "Drupal\zencrm_entities\Form\HatDeleteForm",
 *     },
 *     "access" = "Drupal\zencrm_entities\HatAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\zencrm_entities\HatHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "hat",
 *   data_table = "hat_field_data",
 *   translatable = TRUE,
 *   admin_permission = "administer hat entities",
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
 *     "canonical" = "/admin/structure/hat/{hat}",
 *     "add-page" = "/admin/structure/hat/add",
 *     "add-form" = "/admin/structure/hat/add/{hat_type}",
 *     "edit-form" = "/admin/structure/hat/{hat}/edit",
 *     "delete-form" = "/admin/structure/hat/{hat}/delete",
 *     "collection" = "/admin/structure/hat",
 *   },
 *   bundle_entity_type = "hat_type",
 *   field_ui_base_route = "entity.hat_type.edit_form"
 * )
 */
class Hat extends ContentEntityBase implements HatInterface {

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
      ->setDescription(t('The user ID of the author.'))
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

    // This field is always implied from the context,
    // so has no form or view display.
    $fields['person'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Person'))
      ->setDescription(t('The person this hat is of.'))
      ->setSetting('target_type', 'person');

    // This field is computed in a presave hook, and used for entity reference
    // options when selecting a person for involvement in a case etc.
    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of this hat instance as it appears in entity reference fields.'));

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('A boolean indicating whether the Hat is published.'))
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
