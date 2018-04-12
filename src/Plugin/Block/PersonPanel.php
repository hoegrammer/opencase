<?php

namespace Drupal\zencrm\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'PersonPanel' block.
 * If the person has no contact details it advises to create them.
 * Hats cannot be created without contact details.
 * If they have contact details but no hats, it advises to create a hat.
 * Otherwise display the case view for each hat, and links to create new cases.
 *
 * @Block(
 *  id = "person_panel",
 *  admin_label = @Translation("Person Panel"),
 * )
 */
class PersonPanel extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $person_id = \Drupal::routeMatch()->getParameter('person')->id();
    $markup = "";    
    $person = \Drupal::entityTypeManager()->getStorage('person')->load($person_id);
        

    // If the person has no contact details, suggest they create some
    $link_to_add = "/zencrm/contact_details/$person_id/add?destination=/zencrm/person/$person_id";
    $contact_details = \Drupal::entityTypeManager()
      ->getStorage('contact_details')
      ->loadByProperties(['person' => $person_id]);
    if (!reset($contact_details)) {
      $markup .= "<p>This person has no contact details yet. To get started, ";
      $markup .= "<a class='use-ajax' data-dialog-type='modal' href = $link_to_add>Add a set of contact details</a>";
      $markup .= "</p>";

    } else {
      // They have contact details, so they are able to create hats.
      // If the person has no hats, suggest they create one, by rendering the hat creator block
      $link_to_add = "/zencrm/hat/$person_id/add?destination=/zencrm/person/$person_id";
      $hats = \Drupal::entityTypeManager()
        ->getStorage('hat')
        ->loadByProperties(['person' => $person_id]);
      if (!reset($hats)) {
        $markup .= "<p>This person has no hats yet. A hat is a role that the person plays in the organisation. To get started, add a hat for this person. </p>";
        $plugin_manager = \Drupal::service('plugin.manager.block');
        $block = $plugin_manager->createInstance('hat_creator', array());
        $markup .= render($block->build());
      } else {
        
        // they have hats, so display the case view for each hat.
        foreach($hats as $hat) {
          $markup .= $this->show_cases_for_hat($hat); 
        }
      }
    }

    return [
      '#cache' => [
         'max-age' => 0,
       ],
      '#markup' => "<div id = 'zencrm_personpanel'>$markup</div>"
    ];

  }

  private function show_cases_for_hat($hat) {
    $markup = '';
    $markup .= "<p class='zencrm_hat_name'>" . $hat->name->getString() . "</p>";
    $markup .= drupal_render(views_embed_view('this_hat_s_cases', 'block_1', $hat->id()));
    error_log(print_r(views_embed_view('this_hat_s_cases', 'block_1', $hat->id()), true));
    return $markup;
  }
}
