<?php

namespace Drupal\opencase_entities;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

class OpenCaseEntityPermissions implements ContainerInjectionInterface {

  use StringTranslationTrait;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructor for MyModulePermissions.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('entity_type.manager'));
  }

  /**
   * Get permissions for MyModule.
   *
   * @return array
   *   Permissions array.
   */
  public function permissions() {
    $permissions = [];

    foreach ($this->entityTypeManager->getStorage('oc_actor_type')->loadMultiple() as $id => $type) {
      $permissions += [
        "add $id entities" => [
          'title' => $this->t('Create new %type entities', array('%type' => $type->label())),
        ]
      ];

      $permissions += [
        "edit $id entities" => [
          'title' => $this->t('Edit %type entities', array('%type' => $type->label())),
        ]
      ];

      $permissions += [
        "delete $id entities" => [
          'title' => $this->t('Delete %type entities', array('%type' => $type->label())),
        ]
      ];

      $permissions += [
        "view published $id entities" => [
          'title' => $this->t('View published %type entities', array('%type' => $type->label())),
        ]
      ];

      $permissions += [
        "view all $id revisions" => [
          'title' => $this->t('View %type revisions', array('%type' => $type->label())),
        ]
      ];

      $permissions += [
        "revert all $id revisions" => [
          'title' => $this->t('Revert %type revisions', array('%type' => $type->label())),
        ]
      ];

      $permissions += [
        "delete all $id revisions" => [
          'title' => $this->t('Delete %type revisions', array('%type' => $type->label())),
        ]
      ];

    }
    return $permissions;
  }
}
