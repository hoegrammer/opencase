<?php

namespace Drupal\opencase_reporting\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the OpenCase Report entity.
 *
 * @ConfigEntityType(
 *   id = "opencase_report",
 *   label = @Translation("OpenCase Report"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\opencase_reporting\OpenCaseReportListBuilder",
 *     "form" = {
 *       "add" = "Drupal\opencase_reporting\Form\OpenCaseReportForm",
 *       "edit" = "Drupal\opencase_reporting\Form\OpenCaseReportForm",
 *       "delete" = "Drupal\opencase_reporting\Form\OpenCaseReportDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\opencase_reporting\OpenCaseReportHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "opencase_report",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/opencase/reporting/opencase_report/{opencase_report}",
 *     "add-form" = "/opencase/reporting/opencase_report/add",
 *     "edit-form" = "/opencase/reporting/opencase_report/{opencase_report}/edit",
 *     "delete-form" = "/opencase/reporting/opencase_report/{opencase_report}/delete",
 *     "collection" = "/opencase/reporting/opencase_report"
 *   }
 * )
 */
class OpenCaseReport extends ConfigEntityBase implements OpenCaseReportInterface {

  /**
   * The OpenCase Report ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The OpenCase Report label.
   *
   * @var string
   */
  protected $label;

}
