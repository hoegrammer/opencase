<?php

namespace Drupal\zencrm_entities\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Profile type entity.
 *
 * @ConfigEntityType(
 *   id = "profile_type",
 *   label = @Translation("Profile type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\zencrm_entities\ProfileTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\zencrm_entities\Form\ProfileTypeForm",
 *       "edit" = "Drupal\zencrm_entities\Form\ProfileTypeForm",
 *       "delete" = "Drupal\zencrm_entities\Form\ProfileTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\zencrm_entities\ProfileTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "profile_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "profile",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/zencrm/profile_type/{profile_type}",
 *     "add-form" = "/admin/zencrm/profile_type/add",
 *     "edit-form" = "/admin/zencrm/profile_type/{profile_type}/edit",
 *     "delete-form" = "/admin/zencrm/profile_type/{profile_type}/delete",
 *     "collection" = "/admin/zencrm/profile_type"
 *   }
 * )
 */
class ProfileType extends ConfigEntityBundleBase implements ProfileTypeInterface {

  /**
   * The Profile type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Profile type label.
   *
   * @var string
   */
  protected $label;

}
