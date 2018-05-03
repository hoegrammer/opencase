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
      case 'entity.oc_activity.canonical':
        $markup = $this->activityPage();
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
    $link = $this->getCaseListLink($actor);
    return $this->asNavLinks([$link]);
  }

  /**
   * Contextual menu for Case list page
   *    - Links to add cases of various types
   */
  private function caseListPage() {
    $actor_id = \Drupal::routeMatch()->getParameter('actor_id');
    $current_path =  \Drupal::service('path.current')->getPath();
    $markup = Utils::generateAddLinks('oc_case', "Add new case", ['actor_id' => $actor_id, 'destination' => $current_path]);
    return $markup; 
  }

  /**
   * Contextual menu for Case page
   *    - Link to case list if user has just come from there
   *    - Link to Activity list for that case
   */
  private function casePage() {

    $links = [];

    // Ascertain if user has come from a case list and if so link back
    // This is fragle code, it needs doing better.
    $referer = \Drupal::request()->headers->get('referer');
    $parts = parse_url($referer);
    $path_parts= explode('/', $parts[path]);
    if ($path_parts[4] == 'case_list') {
      $actor = \Drupal::entityTypeManager()->getStorage('oc_actor')->load($path_parts[3]);
      $links[] = $this->getCaseListLink($actor);  
    } 
    // Now get the link to the activity list for the case.
    $case = \Drupal::routeMatch()->getParameter('oc_case');
    $links[] = $this->getActivityListLink($case);
    return $this->asNavLinks($links); 
  }

  /**
   * Contextual menu for Activity list page
   *     - Links to add activities of various types
   */
  private function activityListPage() {
    $case_id = \Drupal::routeMatch()->getParameter('case_id');
    $current_path = \Drupal::service('path.current')->getPath();
    return Utils::generateAddLinks('oc_activity', "Add activity", ['case_id' => $case_id, 'destination' => $current_path]);
  }

  /**
   * Contextual menu for Activity page
   *     - Links to the activity list for the case
   */
  private function activityPage() {
    $activity = \Drupal::routeMatch()->getParameter('oc_activity');
    $case = $activity->oc_case->entity;
    $link = $this->getActivityListLink($case);
    return $this->asNavLinks([$link]);
  }


  /**
   * Given an case entity, returns a link to the activity list 
   */
  private function getActivityListLink($case) {
    $url = Url::fromRoute('view.activities.page_1', array('case_id' => $case->id()));
    return Link::fromTextAndUrl(t("Activity List for " . $case->getName()), $url)->toString();
  }

  /**
   * Given an case entity, returns a link to the activity list 
   */
  private function getCaseListLink($actor) {
    $url = Url::fromRoute('view.cases.page_1', array('actor_id' => $actor->id()));
    return Link::fromTextAndUrl(t("Case List for "  . $actor->getName()), $url)->toString();
  }

  /**
   * Render given links as nav links div with heading
   */ 
  private function asNavLinks(array $links) {
    $markup = '';
    foreach($links as $link) {
      $markup .= "<p>$link</p>";
    }
    $title = t("Go to:");
    return "<div class='opencase_nav_links'><h1>$title</h1><p>$link</p></div>";
  }
}
