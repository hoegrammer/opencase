<?php

namespace Drupal\opencase\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a Block with some help text about actor type fields
 *
 * @Block(
 *   id = "activity_type_help",
 *   admin_label = @Translation("Activity Type Help"),
 *   category = @Translation("Help"),
 * )
 */
class ActivityTypeHelp extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return array(
      '#markup' => "
        <h3>All activity types have default fields such as description and time taken. Other fields can be added and managed here.</h3>

      "
    );
  }

}
