<?php

namespace Drupal\zencrm_entities\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Case entity type entity.
 *
 * @ConfigEntityType(
 *   id = "case_entity_type",
 *   label = @Translation("Case entity type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\zencrm_entities\CaseEntityTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\zencrm_entities\Form\CaseEntityTypeForm",
 *       "edit" = "Drupal\zencrm_entities\Form\CaseEntityTypeForm",
 *       "delete" = "Drupal\zencrm_entities\Form\CaseEntityTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\zencrm_entities\CaseEntityTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "case_entity_type",
 *   admin_permission = "administer case types",
 *   bundle_of = "case_entity",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/case_entity_type/{case_entity_type}",
 *     "add-form" = "/admin/structure/case_entity_type/add",
 *     "edit-form" = "/admin/structure/case_entity_type/{case_entity_type}/edit",
 *     "delete-form" = "/admin/structure/case_entity_type/{case_entity_type}/delete",
 *     "collection" = "/admin/structure/case_entity_type"
 *   }
 * )
 */
class CaseEntityType extends ConfigEntityBundleBase implements CaseEntityTypeInterface {

  /**
   * The Case entity type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Case entity type label.
   *
   * @var string
   */
  protected $label;

}
