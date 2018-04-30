<?php

namespace Drupal\opencase\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Link;

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
    error_log($route_name);
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
      '#markup' => $markup,
      '#cache' => ['max-age' => 0]
    ];
    return $build;
  }

  
  /**
   * Contextual menu for Actor page
   */
  private function actorPage() {
    $actor = \Drupal::routeMatch()->getParameter('oc_actor');
    $linkText = 'Case List';
    $url = '/opencase/oc_actor/'.$actor->id().'/case_list';
    return "<a href='$url'>$linkText</a>";
  }

  /**
   * Contextual menu for Case list page
   */
  private function caseListPage() {
    $actor_id = \Drupal::routeMatch()->getParameter('actor_id');
    $actor = \Drupal::entityTypeManager()->getStorage('oc_actor')->load($actor_id);
    return $actor->toLink()->toString();
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
