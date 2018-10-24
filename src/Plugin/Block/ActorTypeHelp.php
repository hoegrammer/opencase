<?php

namespace Drupal\opencase\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a Block with some help text about actor type fields
 *
 * @Block(
 *   id = "actor_type_help",
 *   admin_label = @Translation("Actor Type Help"),
 *   category = @Translation("Help"),
 * )
 */
class ActorTypeHelp extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return array(
      '#markup' => "
        <h3>All actor types have contact details fields by default. Other fields can be added and managed here.</h3>

      "
    );
  }

}
