<?php

namespace Drupal\opencase_reporting\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the OpenCase Report entity.
 *
 * @ingroup opencase_reporting
 *
 * @ContentEntityType(
 *   id = "opencase_report",
 *   label = @Translation("OpenCase Report"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\opencase_reporting\OpenCaseReportListBuilder",
 *     "views_data" = "Drupal\opencase_reporting\Entity\OpenCaseReportViewsData",
 *     "translation" = "Drupal\opencase_reporting\OpenCaseReportTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\opencase_reporting\Form\OpenCaseReportForm",
 *       "add" = "Drupal\opencase_reporting\Form\OpenCaseReportForm",
 *       "edit" = "Drupal\opencase_reporting\Form\OpenCaseReportForm",
 *       "delete" = "Drupal\opencase_reporting\Form\OpenCaseReportDeleteForm",
 *     },
 *     "access" = "Drupal\opencase_reporting\OpenCaseReportAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\opencase_reporting\OpenCaseReportHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "opencase_report",
 *   data_table = "opencase_report_field_data",
 *   translatable = TRUE,
 *   admin_permission = "administer opencase report entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/opencase_report/{opencase_report}",
 *     "add-form" = "/admin/structure/opencase_report/add",
 *     "edit-form" = "/admin/structure/opencase_report/{opencase_report}/edit",
 *     "delete-form" = "/admin/structure/opencase_report/{opencase_report}/delete",
 *     "collection" = "/admin/structure/opencase_report",
 *   },
 *   field_ui_base_route = "opencase_report.settings"
 * )
 */
class OpenCaseReport extends ContentEntityBase implements OpenCaseReportInterface {

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
      ->setDescription(t('The user ID of author of the OpenCase Report entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the OpenCase Report entity.'))
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
      ->setRequired(TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('A boolean indicating whether the OpenCase Report is published.'))
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
