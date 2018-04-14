<?php

namespace Drupal\zencrm_entities\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Activity type entity.
 *
 * @ConfigEntityType(
 *   id = "activity_type",
 *   label = @Translation("Activity type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\zencrm_entities\ActivityTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\zencrm_entities\Form\ActivityTypeForm",
 *       "edit" = "Drupal\zencrm_entities\Form\ActivityTypeForm",
 *       "delete" = "Drupal\zencrm_entities\Form\ActivityTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\zencrm_entities\ActivityTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "activity_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "activity",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/zencrm/activity_type/{activity_type}",
 *     "add-form" = "/zencrm/activity_type/add",
 *     "edit-form" = "/zencrm/activity_type/{activity_type}/edit",
 *     "delete-form" = "/zencrm/activity_type/{activity_type}/delete",
 *     "collection" = "/zencrm/activity_type"
 *   }
 * )
 */
class ActivityType extends ConfigEntityBundleBase implements ActivityTypeInterface {

  /**
   * The Activity type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Activity type label.
   *
   * @var string
   */
  protected $label;

}
