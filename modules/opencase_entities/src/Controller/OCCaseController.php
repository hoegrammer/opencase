<?php

namespace Drupal\opencase_entities\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\opencase_entities\Entity\OCCaseInterface;

/**
 * Class OCCaseController.
 *
 *  Returns responses for Case routes.
 */
class OCCaseController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a Case  revision.
   *
   * @param int $oc_case_revision
   *   The Case  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($oc_case_revision) {
    $oc_case = $this->entityManager()->getStorage('oc_case')->loadRevision($oc_case_revision);
    $view_builder = $this->entityManager()->getViewBuilder('oc_case');

    return $view_builder->view($oc_case);
  }

  /**
   * Page title callback for a Case  revision.
   *
   * @param int $oc_case_revision
   *   The Case  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($oc_case_revision) {
    $oc_case = $this->entityManager()->getStorage('oc_case')->loadRevision($oc_case_revision);
    return $this->t('Revision of %title from %date', ['%title' => $oc_case->label(), '%date' => format_date($oc_case->getRevisionCreationTime())]);
  }

  /**
   * Generates an overview table of older revisions of a Case .
   *
   * @param \Drupal\opencase_entities\Entity\OCCaseInterface $oc_case
   *   A Case  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(OCCaseInterface $oc_case) {
    $account = $this->currentUser();
    $langcode = $oc_case->language()->getId();
    $langname = $oc_case->language()->getName();
    $languages = $oc_case->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $oc_case_storage = $this->entityManager()->getStorage('oc_case');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $oc_case->label()]) : $this->t('Revisions for %title', ['%title' => $oc_case->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all case revisions") || $account->hasPermission('administer case entities')));
    $delete_permission = (($account->hasPermission("delete all case revisions") || $account->hasPermission('administer case entities')));

    $rows = [];

    $vids = $oc_case_storage->revisionIds($oc_case);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\opencase_entities\OCCaseInterface $revision */
      $revision = $oc_case_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $oc_case->getRevisionId()) {
          $link = $this->l($date, new Url('entity.oc_case.revision', ['oc_case' => $oc_case->id(), 'oc_case_revision' => $vid]));
        }
        else {
          $link = $oc_case->link($date);
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
              Url::fromRoute('entity.oc_case.translation_revert', ['oc_case' => $oc_case->id(), 'oc_case_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('entity.oc_case.revision_revert', ['oc_case' => $oc_case->id(), 'oc_case_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.oc_case.revision_delete', ['oc_case' => $oc_case->id(), 'oc_case_revision' => $vid]),
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

    $build['oc_case_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
