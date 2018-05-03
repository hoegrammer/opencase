<?php

namespace Drupal\opencase\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\opencase\Utils;

/**
 * Provides a 'GlobalMenu' block.
 *
 * @Block(
 *  id = "global_menu",
 *  admin_label = @Translation("OpenCase Global Menu"),
 * )
 */
class GlobalMenu extends BlockBase {

  /**
   * - Links for adding various types of actor. 
   */
  public function build() {

    $build = [];
    $markup .= Utils::generateAddLinks('oc_actor', 'Add new');
    $build['global_menu'] = [
      '#markup' => "<div id='opencase_global_menu'>$markup</div",
      '#cache' => ['max-age' => 0]
    ];
    return $build;
  }
}
