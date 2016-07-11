<?php

namespace Drupal\lieplus;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines the storage handler class for nodes.
 *
 * This extends the base storage class, adding required special handling for
 * node entities.
 */
class ResumeStorage extends SqlContentEntityStorage implements ResumeStorageInterface {

	/**
	 * The current user.
	 *
	 * @var \Drupal\Core\Session\AccountInterface
	 */
	protected $currentUser;

	/**
	 * Constructs a CommentStorage object.
	 *
	 * @param \Drupal\Core\Entity\EntityTypeInterface $entity_info
	 *   An array of entity info for the entity type.
	 * @param \Drupal\Core\Database\Connection $database
	 *   The database connection to be used.
	 * @param \Drupal\Core\Entity\EntityManagerInterface $entity_manager
	 *   The entity manager.
	 * @param \Drupal\Core\Session\AccountInterface $current_user
	 *   The current user.
	 * @param \Drupal\Core\Cache\CacheBackendInterface $cache
	 *   Cache backend instance to use.
	 * @param \Drupal\Core\Language\LanguageManagerInterface $language_manager
	 *   The language manager.
	 */
	// public function __construct(EntityTypeInterface $entity_info, Connection $database, EntityManagerInterface $entity_manager, AccountInterface $current_user, CacheBackendInterface $cache, LanguageManagerInterface $language_manager) {
	// 	parent::__construct($entity_info, $database, $entity_manager, $cache, $language_manager);
	// 	$this->currentUser = $current_user;
	// }

	/**
	 * {@inheritdoc}
	 */
	public function revisionIds(NodeInterface $node) {
		return $this->database->query(
			'SELECT vid FROM {node_revision} WHERE nid=:nid ORDER BY vid',
			array(':nid' => $node->id())
		)->fetchCol();
	}

	/**
	 * {@inheritdoc}
	 */
	public function userRevisionIds(AccountInterface $account) {
		return $this->database->query(
			'SELECT vid FROM {node_field_revision} WHERE uid = :uid ORDER BY vid',
			array(':uid' => $account->id())
		)->fetchCol();
	}

	/**
	 * {@inheritdoc}
	 */
	public function countDefaultLanguageRevisions(NodeInterface $node) {
		return $this->database->query('SELECT COUNT(*) FROM {node_field_revision} WHERE nid = :nid AND default_langcode = 1', array(':nid' => $node->id()))->fetchField();
	}

	/**
	 * {@inheritdoc}
	 */
	public function updateType($old_type, $new_type) {
		return $this->database->update('node')
			->fields(array('type' => $new_type))
			->condition('type', $old_type)
			->execute();
	}

	/**
	 * {@inheritdoc}
	 */
	public function clearRevisionsLanguage(LanguageInterface $language) {
		return $this->database->update('node_revision')
			->fields(array('langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED))
			->condition('langcode', $language->getId())
			->execute();
	}

}
