<?php

namespace Drupal\opencase_entities\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\opencase_entities\Entity\OCActorInterface;

/**
 * Class OCActorController.
 *
 *  Returns responses for Actor routes.
 */
class OCActorController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a Actor  revision.
   *
   * @param int $oc_actor_revision
   *   The Actor  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($oc_actor_revision) {
    $oc_actor = $this->entityManager()->getStorage('oc_actor')->loadRevision($oc_actor_revision);
    $view_builder = $this->entityManager()->getViewBuilder('oc_actor');

    return $view_builder->view($oc_actor);
  }

  /**
   * Page title callback for a Actor  revision.
   *
   * @param int $oc_actor_revision
   *   The Actor  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($oc_actor_revision) {
    $oc_actor = $this->entityManager()->getStorage('oc_actor')->loadRevision($oc_actor_revision);
    return $this->t('Revision of %title from %date', ['%title' => $oc_actor->label(), '%date' => format_date($oc_actor->getRevisionCreationTime())]);
  }

  /**
   * Generates an overview table of older revisions of a Actor .
   *
   * @param \Drupal\opencase_entities\Entity\OCActorInterface $oc_actor
   *   A Actor  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(OCActorInterface $oc_actor) {
    $account = $this->currentUser();
    $langcode = $oc_actor->language()->getId();
    $langname = $oc_actor->language()->getName();
    $languages = $oc_actor->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $oc_actor_storage = $this->entityManager()->getStorage('oc_actor');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $oc_actor->label()]) : $this->t('Revisions for %title', ['%title' => $oc_actor->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all actor revisions") || $account->hasPermission('administer actor entities')));
    $delete_permission = (($account->hasPermission("delete all actor revisions") || $account->hasPermission('administer actor entities')));

    $rows = [];

    $vids = $oc_actor_storage->revisionIds($oc_actor);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\opencase_entities\OCActorInterface $revision */
      $revision = $oc_actor_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $oc_actor->getRevisionId()) {
          $link = $this->l($date, new Url('entity.oc_actor.revision', ['oc_actor' => $oc_actor->id(), 'oc_actor_revision' => $vid]));
        }
        else {
          $link = $oc_actor->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => \Drupal::service('renderer')->renderPlain($username),
              'message' => ['#markup' => $revision->getRevisionLogMessage(), '#allowed_tags' => Xss::getHtmlTagList()],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entity.oc_actor.translation_revert', ['oc_actor' => $oc_actor->id(), 'oc_actor_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('entity.oc_actor.revision_revert', ['oc_actor' => $oc_actor->id(), 'oc_actor_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.oc_actor.revision_delete', ['oc_actor' => $oc_actor->id(), 'oc_actor_revision' => $vid]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['oc_actor_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
