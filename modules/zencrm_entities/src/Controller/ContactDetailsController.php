<?php

namespace Drupal\zencrm_entities\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\zencrm_entities\Entity\ContactDetailsInterface;

/**
 * Class ContactDetailsController.
 *
 *  Returns responses for Contact Details routes.
 */
class ContactDetailsController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a Contact Details  revision.
   *
   * @param int $contact_details_revision
   *   The Contact Details  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($contact_details_revision) {
    $contact_details = $this->entityManager()->getStorage('contact_details')->loadRevision($contact_details_revision);
    $view_builder = $this->entityManager()->getViewBuilder('contact_details');

    return $view_builder->view($contact_details);
  }

  /**
   * Page title callback for a Contact Details  revision.
   *
   * @param int $contact_details_revision
   *   The Contact Details  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($contact_details_revision) {
    $contact_details = $this->entityManager()->getStorage('contact_details')->loadRevision($contact_details_revision);
    return $this->t('Revision of %title from %date', ['%title' => $contact_details->label(), '%date' => format_date($contact_details->getRevisionCreationTime())]);
  }

  /**
   * Generates an overview table of older revisions of a Contact Details .
   *
   * @param \Drupal\zencrm_entities\Entity\ContactDetailsInterface $contact_details
   *   A Contact Details  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(ContactDetailsInterface $contact_details) {
    $account = $this->currentUser();
    $langcode = $contact_details->language()->getId();
    $langname = $contact_details->language()->getName();
    $languages = $contact_details->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $contact_details_storage = $this->entityManager()->getStorage('contact_details');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $contact_details->label()]) : $this->t('Revisions for %title', ['%title' => $contact_details->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all contact details revisions") || $account->hasPermission('administer contact details entities')));
    $delete_permission = (($account->hasPermission("delete all contact details revisions") || $account->hasPermission('administer contact details entities')));

    $rows = [];

    $vids = $contact_details_storage->revisionIds($contact_details);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\zencrm_entities\ContactDetailsInterface $revision */
      $revision = $contact_details_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $contact_details->getRevisionId()) {
          $link = $this->l($date, new Url('entity.contact_details.revision', ['contact_details' => $contact_details->id(), 'contact_details_revision' => $vid]));
        }
        else {
          $link = $contact_details->link($date);
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
              Url::fromRoute('entity.contact_details.translation_revert', ['contact_details' => $contact_details->id(), 'contact_details_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('entity.contact_details.revision_revert', ['contact_details' => $contact_details->id(), 'contact_details_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.contact_details.revision_delete', ['contact_details' => $contact_details->id(), 'contact_details_revision' => $vid]),
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

    $build['contact_details_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
