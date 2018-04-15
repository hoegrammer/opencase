<?php

namespace Drupal\zencrm\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Case Details' block.
 * It displays the involved parties with links to their persons (as opposed to hats)
 * and then the content in default view, and then an edit link.
 *
 * @Block(
 *  id = "case_details",
 *  admin_label = @Translation("Case Details"),
 * )
 */
class CaseDetails extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $case_id = \Drupal::routeMatch()->getParameter('case_entity')->id();
    $case = $entity = \Drupal::entityTypeManager()->getStorage('case_entity')->load($case_id);
    $markup .= $this->renderInvolvedParties($case);  
    $markup .= $this->renderEntity($case);  
    $markup .= $this->renderEditLink($case_id);
    return [
      '#cache' => [
         'max-age' => 0,
       ],
      '#markup' => $markup
    ];

  }
    
  private function renderEditLink($case_id) {
    return "<p class = 'zencrm_editlink'><a class='use-ajax' data-dialog-type='modal' href='/zencrm/case/$case_id/edit?destination=/zencrm/case/$case_id'>Edit</a></p>";
  }

  private function renderEntity($case) {
    $view_builder = \Drupal::entityTypeManager()->getViewBuilder('case_entity');
    $build = $view_builder->view($case, 'default');
    $markup = render($build);
    return "<div class='zencrm_inner_sidebar_block'>$markup</div>";
  }
  
  private function renderInvolvedParties($case) {
    $markup = "<p class='zencrm_title'>Involved Parties</p>";
    $hats_involved = $case->hats_involved->referencedEntities();
    $links_markup = "";
    foreach($hats_involved as $hat) {
      $person_id = $hat->person->first()->getValue()['target_id'];
      $links_markup .= "<p><a href='/zencrm/person/$person_id'>" . $hat->name->getString() . "</a></p>";
    }
    $markup .= "<div class='zencrm_links'>$links_markup</div>";
    return "<div class='zencrm_inner_sidebar_block'>$markup</div>";
  }

}
