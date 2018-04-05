<?php

namespace Drupal\zencrm_entities\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\zencrm_entities\Entity\ProfileInterface;

/**
 * Class ProfileController.
 *
 *  Returns responses for Profile routes.
 */
class ProfileController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a Profile  revision.
   *
   * @param int $profile_revision
   *   The Profile  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($profile_revision) {
    $profile = $this->entityManager()->getStorage('profile')->loadRevision($profile_revision);
    $view_builder = $this->entityManager()->getViewBuilder('profile');

    return $view_builder->view($profile);
  }

  /**
   * Page title callback for a Profile  revision.
   *
   * @param int $profile_revision
   *   The Profile  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($profile_revision) {
    $profile = $this->entityManager()->getStorage('profile')->loadRevision($profile_revision);
    return $this->t('Revision of %title from %date', ['%title' => $profile->label(), '%date' => format_date($profile->getRevisionCreationTime())]);
  }

  /**
   * Generates an overview table of older revisions of a Profile .
   *
   * @param \Drupal\zencrm_entities\Entity\ProfileInterface $profile
   *   A Profile  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(ProfileInterface $profile) {
    $account = $this->currentUser();
    $langcode = $profile->language()->getId();
    $langname = $profile->language()->getName();
    $languages = $profile->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $profile_storage = $this->entityManager()->getStorage('profile');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $profile->label()]) : $this->t('Revisions for %title', ['%title' => $profile->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all profile revisions") || $account->hasPermission('administer profile entities')));
    $delete_permission = (($account->hasPermission("delete all profile revisions") || $account->hasPermission('administer profile entities')));

    $rows = [];

    $vids = $profile_storage->revisionIds($profile);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\zencrm_entities\ProfileInterface $revision */
      $revision = $profile_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $profile->getRevisionId()) {
          $link = $this->l($date, new Url('entity.profile.revision', ['profile' => $profile->id(), 'profile_revision' => $vid]));
        }
        else {
          $link = $profile->link($date);
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
              Url::fromRoute('entity.profile.translation_revert', ['profile' => $profile->id(), 'profile_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('entity.profile.revision_revert', ['profile' => $profile->id(), 'profile_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.profile.revision_delete', ['profile' => $profile->id(), 'profile_revision' => $vid]),
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

    $build['profile_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
