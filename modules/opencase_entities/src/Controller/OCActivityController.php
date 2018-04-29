<?php

namespace Drupal\opencase_entities\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\opencase_entities\Entity\OCActivityInterface;

/**
 * Class OCActivityController.
 *
 *  Returns responses for Activity routes.
 */
class OCActivityController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a Activity  revision.
   *
   * @param int $oc_activity_revision
   *   The Activity  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($oc_activity_revision) {
    $oc_activity = $this->entityManager()->getStorage('oc_activity')->loadRevision($oc_activity_revision);
    $view_builder = $this->entityManager()->getViewBuilder('oc_activity');

    return $view_builder->view($oc_activity);
  }

  /**
   * Page title callback for a Activity  revision.
   *
   * @param int $oc_activity_revision
   *   The Activity  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($oc_activity_revision) {
    $oc_activity = $this->entityManager()->getStorage('oc_activity')->loadRevision($oc_activity_revision);
    return $this->t('Revision of %title from %date', ['%title' => $oc_activity->label(), '%date' => format_date($oc_activity->getRevisionCreationTime())]);
  }

  /**
   * Generates an overview table of older revisions of a Activity .
   *
   * @param \Drupal\opencase_entities\Entity\OCActivityInterface $oc_activity
   *   A Activity  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(OCActivityInterface $oc_activity) {
    $account = $this->currentUser();
    $langcode = $oc_activity->language()->getId();
    $langname = $oc_activity->language()->getName();
    $languages = $oc_activity->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $oc_activity_storage = $this->entityManager()->getStorage('oc_activity');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $oc_activity->label()]) : $this->t('Revisions for %title', ['%title' => $oc_activity->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all activity revisions") || $account->hasPermission('administer activity entities')));
    $delete_permission = (($account->hasPermission("delete all activity revisions") || $account->hasPermission('administer activity entities')));

    $rows = [];

    $vids = $oc_activity_storage->revisionIds($oc_activity);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\opencase_entities\OCActivityInterface $revision */
      $revision = $oc_activity_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $oc_activity->getRevisionId()) {
          $link = $this->l($date, new Url('entity.oc_activity.revision', ['oc_activity' => $oc_activity->id(), 'oc_activity_revision' => $vid]));
        }
        else {
          $link = $oc_activity->link($date);
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
              Url::fromRoute('entity.oc_activity.translation_revert', ['oc_activity' => $oc_activity->id(), 'oc_activity_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('entity.oc_activity.revision_revert', ['oc_activity' => $oc_activity->id(), 'oc_activity_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.oc_activity.revision_delete', ['oc_activity' => $oc_activity->id(), 'oc_activity_revision' => $vid]),
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

    $build['oc_activity_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
