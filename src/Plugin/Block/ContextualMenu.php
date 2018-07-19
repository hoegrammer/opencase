<?php

namespace Drupal\opencase\Plugin\Block;

use Drupal\opencase\EntityTypeRelations;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

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
      case 'entity.oc_actor.edit_form':
        $markup = $this->actorPage();
        break;
      case 'view.cases.page_1':
        $markup = $this->caseListPage();
        break;
      case 'entity.oc_case.canonical':
      case 'entity.oc_case.edit_form':
        $markup = $this->casePage();
        break;
      case 'entity.oc_case.add_form':
        $markup = $this->caseAddPage();
        break;
      case 'view.activities.page_1':
        $markup = $this->activityListPage();
        break;
      case 'entity.oc_activity.canonical':
      case 'entity.oc_activity.edit_form':
        $markup = $this->activityPage();
        break;
      case 'entity.oc_activity.add_form':
        $markup = $this->activityAddPage();
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
   *    - Link to actor whose case list this is
   *    - Links to add cases of various types
   *    - Store the actor id in the session, so that the user experiences
   * a hierachy actor->case->activities which they can navigate
   */
  private function caseListPage() {
    $actor_id = \Drupal::routeMatch()->getParameter('actor_id');
    \Drupal::service('user.private_tempstore')->get('opencase')->set('actor_id', $actor_id);
    $actor = \Drupal::entityTypeManager()->getStorage('oc_actor')->load($actor_id);
    $link = $actor->toLink()->toString();
    $markup = $this->asNavLinks([$link]);
    $current_path =  \Drupal::service('path.current')->getPath();
    $title = "Add new case";
    $query = ['actor_id' => $actor_id,  'destination' => $current_path];
    $markup .= $this->generateLinksForAddingNewCases($actor, $title, $query);
    return $markup; 
  }

  /**
   * Contextual menu for Case page
   *    - Link either the case list for the actor stored in the session (because their case list page was previously loaded)
   *           or the home page
   *    - Link to Activity list for that case
   */
  private function casePage() {
    $case = \Drupal::routeMatch()->getParameter('oc_case');
    $actor_id = \Drupal::service('user.private_tempstore')->get('opencase')->get('actor_id');
    if ($actor_id) {   // there is not always one stored.
      $actor = \Drupal::entityTypeManager()->getStorage('oc_actor')->load($actor_id);
      if ($actor) {  // actor may have been deleted.
        $caseListLink = $this->getCaseListLink($actor);
      } else {
        $caseListLink = $this->getCaseListLinkAll();
      }
    } else {
      $caseListLink = $this->getCaseListLinkAll();
    }
    $links = [$caseListLink, $this->getActivityListLink($case)];
    return $this->asNavLinks($links); 
  }

  /**
   * Contextual menu for Add-New-Case page
   *    - Link to Case list for the actor that is stored in the session
   */
  private function caseAddPage() {
    $actor_id = \Drupal::service('user.private_tempstore')->get('opencase')->get('actor_id');
    $actor = \Drupal::entityTypeManager()->getStorage('oc_actor')->load($actor_id);
    $link = $this->getCaseListLink($actor);
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
    $url =  $case->toUrl();
    $link = Link::fromTextAndUrl(t($case->getName() .": Case Details and Files"), $url)->toString();
    $markup = $this->asNavLinks([$link]);
    $current_path = \Drupal::service('path.current')->getPath();
    return $markup . $this->generateLinksForAddingNewActivities($case, "Add activity", ['case_id' => $case_id, 'destination' => $current_path]);
  }

  /**
   * Contextual menu for Activity page
   *     - Link to the activity list for the case
   */
  private function activityPage() {
    $activity = \Drupal::routeMatch()->getParameter('oc_activity');
    $case = $activity->oc_case->entity;
    $link = $this->getActivityListLink($case);
    return $this->asNavLinks([$link]);
  }


  /**
   * Contextual menu for Add-New-Activity page
   *     - Link to the activity list for the case
   */
  private function activityAddPage() {
    $case_id = \Drupal::request()->query->get('case_id');
    $case = \Drupal::entityTypeManager()->getStorage('oc_case')->load($case_id);
    $link = $this->getActivityListLink($case);
    return $this->asNavLinks([$link]);
  }


  /**
   * Given an case entity, returns a link to the activity list 
   */
  private function getActivityListLink($case) {
    $url = Url::fromRoute('view.activities.page_1', ['case_id' => $case->id()]);
    return Link::fromTextAndUrl(t($case->getName() .": Activities"), $url)->toString();
  }

  /**
   * Given an actor entity, returns a link to their case list 
   */
  private function getCaseListLink($actor) {
    $url = Url::fromRoute('view.cases.page_1', ['actor_id' => $actor->id()]);
    return Link::fromTextAndUrl(t($actor->getName(). ": Cases"), $url)->toString();
  }

  /**
   * Returns a link to the list of all cases
   */
  private function getCaseListLinkAll() {
    $url = Url::fromRoute('view.cases.page_2');
    return Link::fromTextAndUrl(t("All cases"), $url)->toString();
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

  /**
   * returns html markup.
   */
  private function generateLinksForAddingNewCases($actor, $title, $query = []) {
    $actor_type = $actor->bundle();
    $allCaseTypes = \Drupal::service('entity_type.bundle.info')->getBundleInfo('oc_case');
    // $allCaseTypes is array where the key is the machine name and the value is array containing label
    // Now we pick just the allowed ones and produced an array of labels keyed by machine name
    $allowedCaseTypes = array();
    foreach(array_keys($allCaseTypes) as $caseType) {
      if (in_array($actor_type, EntityTypeRelations::getAllowedActorTypesForCaseType($caseType))) {
        $allowedCaseTypes[$caseType] = $allCaseTypes[$caseType]['label'];
      }
    }
    $title = t($title); 
    $markup = "<h1>$title: </h1>";
    foreach($allowedCaseTypes as $machine_name => $label) {
      $url = \Drupal\Core\Url::fromRoute("entity.oc_case.add_form", ['oc_case_type' => $machine_name]);
      $url->setOption('query', $query);
      $link = \Drupal\Core\Link::fromTextAndUrl($label, $url)->toString();
      $markup .= "<p>$link</p>";
    }
    return "<div class='opencase_add_links'>$markup</div>";
  }

  /**
   * returns html markup.
   */
  private function generateLinksForAddingNewActivities($case, $title, $query = []) {
    $title = t($title); 
    $markup = "<h1>$title: </h1>";
    $caseType = $case->bundle();
    $allActivityTypes = \Drupal::service('entity_type.bundle.info')->getBundleInfo('oc_activity');
    // $allActivityTypes is array where the key is the machine name and the value is array containing label
    // Now we pick just the allowed ones and produced an array of labels keyed by machine name
    $allowedActivityTypes = EntityTypeRelations::getAllowedActivityTypesForCaseType($caseType);
    foreach($allowedActivityTypes as $machine_name => $is_allowed) {
      if ($is_allowed) {
        $label = $allActivityTypes[$machine_name]['label'];
        $url = \Drupal\Core\Url::fromRoute("entity.oc_activity.add_form", ['oc_activity_type' => $machine_name]);
        $url->setOption('query', $query);
        $link = \Drupal\Core\Link::fromTextAndUrl($label, $url)->toString();
        $markup .= "<p>$link</p>";  
      }
    }
    return "<div class='opencase_add_links'>$markup</div>";
  }
}
