<?php

namespace Drupal\lieplus;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Employer entity.
 *
 * @see \Drupal\lieplus\Entity\EmployerEntity.
 */
class EmployerEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\lieplus\Entity\EmployerEntityInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished employer entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published employer entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit employer entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete employer entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add employer entities');
  }

}
