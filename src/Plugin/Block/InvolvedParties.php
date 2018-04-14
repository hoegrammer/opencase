<?php

namespace Drupal\zencrm\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Involved Parties' block.
 * Block contains links to the people involved in a case
 *
 * @Block(
 *  id = "involved_parties",
 *  admin_label = @Translation("Involved Parties"),
 * )
 */
class InvolvedParties extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $case_id = \Drupal::routeMatch()->getParameter('case_entity')->id();
    $markup = "";    
    
    $case = $entity = \Drupal::entityTypeManager()->getStorage('case_entity')->load($case_id);
    $hats_involved = $case->hats_involved->referencedEntities();
    foreach($hats_involved as $hat) {
      $person_id = $hat->person->first()->getValue()['target_id'];
      $markup .= "<p><a href='/zencrm/person/$person_id'>" . $hat->name->getString() . "</a></p>";
    }
    return [
      '#cache' => [
         'max-age' => 0,
       ],
      '#markup' => "<div class='zencrm_links'>$markup</div>"
    ];

  }

}
