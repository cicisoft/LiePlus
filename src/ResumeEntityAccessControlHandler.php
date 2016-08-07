<?php

namespace Drupal\lieplus;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Access controller for the Resume entity.
 *
 * @see \Drupal\lieplus\Entity\ResumeEntity.
 */
class ResumeEntityAccessControlHandler extends EntityAccessControlHandler {

	/**
	 * {@inheritdoc}
	 */
	protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
		/** @var \Drupal\lieplus\Entity\ResumeEntityInterface $entity */
		switch ($operation) {
		case 'view':
			if (!$entity->isPublished()) {
				return AccessResult::allowedIfHasPermission($account, 'view unpublished resume entities');
			}
			return AccessResult::allowedIfHasPermission($account, 'view published resume entities');

		case 'update':
			return AccessResult::allowedIfHasPermission($account, 'edit resume entities');

		case 'delete':
			return AccessResult::allowedIfHasPermission($account, 'delete resume entities');
		}

		// Unknown operation, no opinion.
		return AccessResult::neutral();
	}

	/**
	 * {@inheritdoc}
	 */
	protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
		return AccessResult::allowedIfHasPermission($account, 'add resume entities');
	}

}
