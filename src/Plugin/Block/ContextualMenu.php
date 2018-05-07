<?php

namespace Drupal\opencase\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\opencase\Utils;

/**
 * Provides a 'ContextualMenu' block.
 *
 * Displays contextual links on certain pages. 
 * The block is forbidden by hook_block_access on other pages, so if more are added they need adding there too.
 *
 * @Block(
 *  id = "opencase_contextual_menu",
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
   *    - Link to person record whose case list this is
   *    - Links to add cases of various types
   */
  private function caseListPage() {
    $actor_id = \Drupal::routeMatch()->getParameter('actor_id');
    $link = \Drupal::entityTypeManager()->getStorage('oc_actor')->load($actor_id)->toLink()->toString();
    $markup = $this->asNavLinks([$link]);
    $current_path =  \Drupal::service('path.current')->getPath();
    $markup .= Utils::generateAddLinks('oc_case', "Add new case", ['actor_id' => $actor_id, 'destination' => $current_path]);
    return $markup; 
  }

  /**
   * Contextual menu for Case page
   *    - Link to Activity list for that case
   */
  private function casePage() {
    $case = \Drupal::routeMatch()->getParameter('oc_case');
    $link = $this->getActivityListLink($case);
    return $this->asNavLinks([$link]); 
  }

  /**
   * Contextual menu for Activity list page
   *     - Link to the case that the activity list is for
   *     - Links to add activities of various types
   */
  private function activityListPage() {
    $case_id = \Drupal::routeMatch()->getParameter('case_id');
    $case = \Drupal::entityTypeManager()->getStorage('oc_case')->load($case_id);
    $link = Link::fromTextAndUrl(t($case->getName() .": Case Details and Files"), $case->toUrl())->toString();
    $markup = $this->asNavLinks([$link]);
    $current_path = \Drupal::service('path.current')->getPath();
    return $markup . Utils::generateAddLinks('oc_activity', "Add activity", ['case_id' => $case_id, 'destination' => $current_path]);
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
    return Link::fromTextAndUrl(t($case->getName() .": Activities"), $url)->toString();
  }

  /**
   * Given an actor entity, returns a link to their case list 
   */
  private function getCaseListLink($actor) {
    $url = Url::fromRoute('view.cases.page_1', array('actor_id' => $actor->id()));
    return Link::fromTextAndUrl(t($actor->getName(). ": Cases"), $url)->toString();
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
    return "<div class='opencase_nav_links'><h1>$title</h1>$markup</div>";
  }
}
