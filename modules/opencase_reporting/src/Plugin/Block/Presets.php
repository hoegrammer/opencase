<?php

namespace Drupal\opencase_reporting\Plugin\Block;

use Drupal\opencase\EntityTypeRelations;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Provides a block for loading, saving and creating report presets.
 *
 * @Block(
 *  id = "opencase_reporting_presets",
 *  admin_label = @Translation("OpenCase Reporting Presets"),
 * )
 */
class Presets extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return array(
      '#presets' => array(array('title'=>'me and you', 'basis'=>'actors', 'filter'=>'&f%5B0%5D=actor_type%3Afat&f%5B1%5D=created%3A2018-09'), array('title'=>'them', 'basis'=>'actors')),
      '#theme' => 'opencase_reporting_presets'
    );
  }
}
