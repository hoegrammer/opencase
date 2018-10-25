<?php

namespace Drupal\opencase\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Derivative class that provides the menu links adding various types of actors
 */
class AddActorsMenuLink extends DeriverBase implements ContainerDeriverInterface {

  /**
   * @var EntityTypeManagerInterface $entityTypeManager.
   */

  protected $entityTypeManager;
  
  /**
   * Creates a AddActorsMenuLink instance.
   *
   * @param $base_plugin_id
   * @param EntityTypeManagerInterface $entity_type_manager
   */
  public function __construct($base_plugin_id, EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

 /**
   * {@inheritdoc}
   */
 public static function create(ContainerInterface $container, $base_plugin_id) {
   return new static(
    $base_plugin_id,
    $container->get('entity_type.manager')
   );
 }
  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $links = [];
    $actorTypes = $this->entityTypeManager->getStorage('oc_actor_type')->loadMultiple();
    foreach ($actorTypes as $id => $actorType) {
      $links[$id] = [
        'title' => "Add ". $actorType->label(),
        'route_name' => "entity.oc_actor.add_form",
        'route_parameters' => ['oc_actor_type' => $actorType->id()]
      ] + $base_plugin_definition;
    }
    return $links;
  }
}
