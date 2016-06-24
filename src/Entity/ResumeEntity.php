<?php

namespace Drupal\lieplus\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\user\UserInterface;

/**
 * Defines the Resume entity.
 *
 * @ingroup lieplus
 *
 * @ContentEntityType(
 *   id = "resume",
 *   label = @Translation("Resume"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\lieplus\ResumeEntityListBuilder",
 *     "views_data" = "Drupal\lieplus\Entity\ResumeEntityViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\lieplus\Form\ResumeEntityForm",
 *       "add" = "Drupal\lieplus\Form\ResumeEntityForm",
 *       "edit" = "Drupal\lieplus\Form\ResumeEntityForm",
 *       "delete" = "Drupal\lieplus\Form\ResumeEntityDeleteForm",
 *     },
 *     "access" = "Drupal\lieplus\ResumeEntityAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\lieplus\ResumeEntityHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "resume",
 *   admin_permission = "administer resume entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/resume/{resume}",
 *     "add-form" = "/resume/add",
 *     "edit-form" = "/resume/{resume}/edit",
 *     "delete-form" = "/resume/{resume}/delete",
 *     "collection" = "/resume",
 *   },
 *   field_ui_base_route = "resume.settings"
 * )
 */
class ResumeEntity extends ContentEntityBase implements ResumeEntityInterface {

	use EntityChangedTrait;

	/**
	 * {@inheritdoc}
	 */
	public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
		parent::preCreate($storage_controller, $values);
		$values += array(
			'user_id' => \Drupal::currentUser()->id(),
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName() {
		return $this->get('name')->value;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setName($name) {
		$this->set('name', $name);
		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getCreatedTime() {
		return $this->get('created')->value;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setCreatedTime($timestamp) {
		$this->set('created', $timestamp);
		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getOwner() {
		return $this->get('user_id')->entity;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getOwnerId() {
		return $this->get('user_id')->target_id;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setOwnerId($uid) {
		$this->set('user_id', $uid);
		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setOwner(UserInterface $account) {
		$this->set('user_id', $account->id());
		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isPublished() {
		return (bool) $this->getEntityKey('status');
	}

	/**
	 * {@inheritdoc}
	 */
	public function setPublished($published) {
		$this->set('status', $published ? NODE_PUBLISHED : NODE_NOT_PUBLISHED);
		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
		$fields = parent::baseFieldDefinitions($entity_type);

		$fields['user_id'] = BaseFieldDefinition::create('entity_reference')
			->setLabel(t('Authored by'))
			->setDescription(t('The user ID of author of the Resume entity.'))
			->setRevisionable(TRUE)
			->setSetting('target_type', 'user')
			->setSetting('handler', 'default')
			->setDefaultValueCallback('Drupal\node\Entity\Node::getCurrentUserId')
			->setTranslatable(TRUE)
			->setDisplayOptions('view', array(
				'label' => 'hidden',
				'type' => 'author',
				'weight' => 0,
			))
			->setDisplayOptions('form', array(
				'type' => 'entity_reference_autocomplete',
				'weight' => 5,
				'settings' => array(
					'match_operator' => 'CONTAINS',
					'size' => '60',
					'autocomplete_type' => 'tags',
					'placeholder' => '',
				),
			))
			->setDisplayConfigurable('form', TRUE)
			->setDisplayConfigurable('view', TRUE);

		$fields['name'] = BaseFieldDefinition::create('string')
			->setLabel(t('Name'))
			->setDescription(t('The name of the Resume entity.'))
			->setSettings(array(
				'max_length' => 50,
				'text_processing' => 0,
			))
			->setDefaultValue('')
			->setDisplayOptions('view', array(
				'label' => 'above',
				'type' => 'string',
				'weight' => -4,
			))
			->setDisplayOptions('form', array(
				'type' => 'string_textfield',
				'weight' => -4,
			))
			->setDisplayConfigurable('form', TRUE)
			->setDisplayConfigurable('view', TRUE);

		$fields['status'] = BaseFieldDefinition::create('boolean')
			->setLabel(t('Publishing status'))
			->setDescription(t('A boolean indicating whether the Resume is published.'))
			->setDefaultValue(TRUE);

		$fields['created'] = BaseFieldDefinition::create('created')
			->setLabel(t('Created'))
			->setDescription(t('The time that the entity was created.'));

		$fields['changed'] = BaseFieldDefinition::create('changed')
			->setLabel(t('Changed'))
			->setDescription(t('The time that the entity was last edited.'));

		return $fields;
	}

}
