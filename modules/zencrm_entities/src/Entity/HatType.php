<?php

namespace Drupal\zencrm_entities\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Hat type entity.
 *
 * @ConfigEntityType(
 *   id = "hat_type",
 *   label = @Translation("Hat type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\zencrm_entities\HatTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\zencrm_entities\Form\HatTypeForm",
 *       "edit" = "Drupal\zencrm_entities\Form\HatTypeForm",
 *       "delete" = "Drupal\zencrm_entities\Form\HatTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\zencrm_entities\HatTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "hat_type",
 *   admin_permission = "add hat types",
 *   bundle_of = "hat",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/zencrm/hat_type/{hat_type}",
 *     "add-form" = "/zencrm/hat_type/add",
 *     "edit-form" = "/zencrm/hat_type/{hat_type}/edit",
 *     "delete-form" = "/zencrm/hat_type/{hat_type}/delete",
 *     "collection" = "/zencrm/hat_type"
 *   }
 * )
 */
class HatType extends ConfigEntityBundleBase implements HatTypeInterface {

  /**
   * The Hat type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Hat type label.
   *
   * @var string
   */
  protected $label;

}
