<?php

namespace Drupal\opencase\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Provides a 'ContextualMenu' block.
 *
 * @Block(
 *  id = "contextual_menu",
 *  admin_label = @Translation("OpenCase Contextual Menu"),
 * )
 */
class ContextualMenu extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $route_name = \Drupal::routeMatch()->getRouteName();
    switch ($route_name) {
      case 'entity.oc_actor.canonical':
        $markup = $this->actorPage();
        break;
      case 'view.cases.page_1':
        $markup = $this->caseListPage();
        break;
    }

    $build = [];
    $build['contextual_menu'] = [
      '#markup' => "<div id='opencase_contextual_menu'>$markup</div",
      '#cache' => ['max-age' => 0]
    ];
    return $build;
  }

  
  /**
   * Contextual menu for Actor page
   *    - Link to case list for that actor
   */
  private function actorPage() {
    $actor = \Drupal::routeMatch()->getParameter('oc_actor');
    $url = Url::fromRoute('view.cases.page_1', array('actor_id' => $actor->id()));
    $link = Link::fromTextAndUrl(t("Case List"), $url)->toString();
    return "<div id='opencase_contextual_menu_nav'><p>$link</p></div>";
  }

  /**
   * Contextual menu for Case list page
   *    - Link to the actor that the case list is for
   *    - Links to add cases of various types
   */
  private function caseListPage() {
    $actor_id = \Drupal::routeMatch()->getParameter('actor_id');
    $actor = \Drupal::entityTypeManager()->getStorage('oc_actor')->load($actor_id);
    $link = $actor->toLink()->toString();
    $markup = "<div id='opencase_contextual_menu_nav'><p>$link</p></div>";
    
    $case_types = \Drupal::service('entity_type.bundle.info')->getBundleInfo('oc_case');
    $add_links = '';
    foreach($case_types as $case_type_id => $type) {
      $label = $type['label'];
      $url = Url::fromRoute('entity.oc_case.add_form', ['oc_case_type' => $case_type_id]);
      $url->setOption('query', ['actor_id' => $actor_id]);
      $link = Link::fromTextAndUrl(t("Add a $label case"), $url)->toString();
      $add_links .= "<p>$link</p>";
    }
    $markup .= "<div id='opencase_contextual_menu_add'>$add_links</div>";
    return $markup; 
  }

  /**
   * Contextual menu for Case page
   */
  private function casePage($case_id) {

  }

  /**
   * Contextual menu for Activity list page
   */
  private function activityListPage($case_id) {

  }
}
