<?php

namespace Drupal\opencase_entities\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Actor type entity.
 *
 * @ConfigEntityType(
 *   id = "oc_actor_type",
 *   label = @Translation("Actor type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\opencase_entities\OCActorTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\opencase_entities\Form\OCActorTypeForm",
 *       "edit" = "Drupal\opencase_entities\Form\OCActorTypeForm",
 *       "delete" = "Drupal\opencase_entities\Form\OCActorTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\opencase_entities\OCActorTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "oc_actor_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "oc_actor",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/opencase/oc_actor_type/{oc_actor_type}",
 *     "add-form" = "/admin/opencase/oc_actor_type/add",
 *     "edit-form" = "/admin/opencase/oc_actor_type/{oc_actor_type}/edit",
 *     "delete-form" = "/admin/opencase/oc_actor_type/{oc_actor_type}/delete",
 *     "collection" = "/admin/opencase/oc_actor_type"
 *   }
 * )
 */
class OCActorType extends ConfigEntityBundleBase implements OCActorTypeInterface {

  /**
   * The Actor type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Actor type label.
   *
   * @var string
   */
  protected $label;

}
