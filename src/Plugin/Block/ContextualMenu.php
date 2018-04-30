<?php

namespace Drupal\opencase\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\opencase\Utils;

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
      case 'entity.oc_case.canonical':
        $markup = $this->casePage();
        break;
      case 'view.activities.page_1':
        $markup = $this->activityListPage();
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
    return "<div class='opencase_nav_links'><p>$link</p></div>";
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
    $markup = "<div class='opencase_nav_links'><p>$link</p></div>";
    $markup .= Utils::generateAddLinks('oc_case', ['actor_id' => $actor_id]);
    return $markup; 
  }

  /**
   * Contextual menu for Case page
   *    - Link to Activity list
   */
  private function casePage() {
    $case = \Drupal::routeMatch()->getParameter('oc_case');
    $url = Url::fromRoute('view.activities.page_1', array('case_id' => $case->id()));
    $link = Link::fromTextAndUrl(t("Activity List"), $url)->toString();
    return "<div class='opencase_nav_links'><p>$link</p></div>";
  }

  /**
   * Contextual menu for Activity list page
   *     - Link to case
   *     - Links to add activities of various types
   */
  private function activityListPage() {
    $case_id = \Drupal::routeMatch()->getParameter('case_id');
    $case = \Drupal::entityTypeManager()->getStorage('oc_case')->load($case_id);
    $link = $case->toLink()->toString();
    $markup = "<div class='opencase_nav_links'><p>$link</p></div>";
    $markup .= Utils::generateAddLinks('oc_activity', ['case_id' => $case_id]);
    return $markup; 
  }
}
