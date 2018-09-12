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
      '#presets' => array(array('title'=>'me', 'url'=>'you'), array('title'=>'them', 'url'=>'us')),
      '#theme' => 'opencase_reporting_presets'
    );
  }
}
