<?php

namespace Drupal\opencase_entities\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Case type entity.
 *
 * @ConfigEntityType(
 *   id = "oc_case_type",
 *   label = @Translation("Case type"),
 *   handlers = {
 *     "access" = "Drupal\opencase_entities\OCCaseTypeAccessControlHandler",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\opencase_entities\OCCaseTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\opencase_entities\Form\OCCaseTypeForm",
 *       "edit" = "Drupal\opencase_entities\Form\OCCaseTypeForm",
 *       "delete" = "Drupal\opencase_entities\Form\OCCaseTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\opencase_entities\OCCaseTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "oc_case_type",
 *   admin_permission = "administer opencase entity bundles",
 *   bundle_of = "oc_case",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/opencase/oc_case_type/{oc_case_type}",
 *     "add-form" = "/admin/opencase/oc_case_type/add",
 *     "edit-form" = "/admin/opencase/oc_case_type/{oc_case_type}/edit",
 *     "delete-form" = "/admin/opencase/oc_case_type/{oc_case_type}/delete",
 *     "collection" = "/admin/opencase/oc_case_type"
 *   }
 * )
 */
class OCCaseType extends ConfigEntityBundleBase implements OCCaseTypeInterface {

  /**
   * The Case type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Case type label.
   *
   * @var string
   */
  protected $label;

  /**
   * Activity types that can be attached to this type of case.
   *
   * @var array
   */
  protected $allowedActivityTypes;
}
