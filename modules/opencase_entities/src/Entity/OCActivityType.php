<?php

namespace Drupal\opencase_entities\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Activity type entity.
 *
 * @ConfigEntityType(
 *   id = "oc_activity_type",
 *   label = @Translation("Activity type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\opencase_entities\OCActivityTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\opencase_entities\Form\OCActivityTypeForm",
 *       "edit" = "Drupal\opencase_entities\Form\OCActivityTypeForm",
 *       "delete" = "Drupal\opencase_entities\Form\OCActivityTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\opencase_entities\OCActivityTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "oc_activity_type",
 *   admin_permission = "administer opencase entity bundles",
 *   bundle_of = "oc_activity",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/opencase/oc_activity_type/{oc_activity_type}",
 *     "add-form" = "/admin/opencase/oc_activity_type/add",
 *     "edit-form" = "/admin/opencase/oc_activity_type/{oc_activity_type}/edit",
 *     "delete-form" = "/admin/opencase/oc_activity_type/{oc_activity_type}/delete",
 *     "collection" = "/admin/opencase/oc_activity_type"
 *   }
 * )
 */
class OCActivityType extends ConfigEntityBundleBase implements OCActivityTypeInterface {

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
