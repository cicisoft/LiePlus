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
 *     "storage" = "Drupal\lieplus\ResumeStorage",
 *     "storage_schema" = "Drupal\lieplus\ResumeStorageSchema",
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
 *   data_table = "resume_field_data",
 *   uri_callback = "resume_uri",
 *   translatable = TRUE,
 *   admin_permission = "administer resume entities",
 *   entity_keys = {
 *     "id" = "rid",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *   },
 *   links = {
 *     "canonical" = "/resume/{resume}",
 *     "add-form" = "/resume/add",
 *     "edit-form" = "/resume/{resume}/edit",
 *     "delete-form" = "/resume/{resume}/delete",
 *     "collection" = "/resume/list",
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
	public function getAge($day) {
		$age = date('Y', time()) - date('Y', strtotime($day)) - 1;
		if (date('m', time()) == date('m', strtotime($day))) {

			if (date('d', time()) > date('d', strtotime($day))) {
				$age++;
			}
		} elseif (date('m', time()) > date('m', strtotime($day))) {
			$age++;
		}
		return $age;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAbstract() {
		return 'TODO: Abstract';
	}

	/**
	 * {@inheritdoc}
	 */
	public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
		$fields = parent::baseFieldDefinitions($entity_type);

		$fields['rid'] = BaseFieldDefinition::create('integer')
			->setLabel(t('Resume ID'))
			->setDescription(t('The resume ID.'))
			->setReadOnly(TRUE)
			->setSetting('unsigned', TRUE);

		$fields['uuid'] = BaseFieldDefinition::create('uuid')
			->setLabel(t('UUID'))
			->setDescription(t('The resume UUID.'))
			->setReadOnly(TRUE);

		$fields['langcode'] = BaseFieldDefinition::create('language')
			->setLabel(t('Language'))
			->setDescription(t('The resume language code.'))
			->setTranslatable(TRUE)
			->setDisplayOptions('view', array(
				'type' => 'hidden',
			))
			->setDisplayOptions('form', array(
				'type' => 'language_select',
				'weight' => 2,
			));

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
				'weight' => 41,
			))
			->setDisplayOptions('form', array(
				'type' => 'entity_reference_autocomplete',
				'weight' => 41,
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

		// Gender field for the candidate.
		// ListTextType with a drop down menu widget.
		// The values shown in the menu are 'male' and 'female'.
		// In the view the field content is shown as string.
		// In the form the choices are presented as options list.
		$fields['gender'] = BaseFieldDefinition::create('list_string')
			->setLabel(t('Gender'))
			->setDescription(t('The gender of the Candidate.'))
			->setSettings(array(
				'allowed_values' => array(
					'female' => t('female'),
					'male' => t('male'),
				),
			))
			->setDisplayOptions('view', array(
				'label' => 'above',
				'type' => 'string',
				'weight' => -4,
			))
			->setDisplayOptions('form', array(
				'type' => 'options_buttons',
				'weight' => -4,
			))
			->setDisplayConfigurable('form', TRUE)
			->setDisplayConfigurable('view', TRUE);

		$fields['telephone'] = BaseFieldDefinition::create('telephone')
			->setLabel(t('Telephone'))
			->setDescription(t('The telephone of the Candidate.'))
			->setDisplayOptions('view', array(
				'label' => 'above',
				'type' => 'telephone_link',
				'weight' => -4,
			))
			->setDisplayOptions('form', array(
				'type' => 'string_textfield',
				'weight' => -4,
			))
			->setDisplayConfigurable('form', TRUE)
			->setDisplayConfigurable('view', TRUE);

		$fields['email'] = BaseFieldDefinition::create('email')
			->setLabel(t('Email'))
			->setDescription(t('The email of Candidate.'))
			->setSettings(array(
				'default_value' => '',
				'max_length' => 255,
				'text_processing' => 0,
			))
			->setDisplayOptions('view', array(
				'label' => 'inline',
				'type' => 'basic_string',
				'weight' => 13,
			))
			->setDisplayOptions('form', array(
				'type' => 'email_default',
				'weight' => 13,
			))
			->setRequired(TRUE)
			->setQueryable(TRUE)
			->addConstraint('UserMailUnique')
			->setDisplayConfigurable('form', TRUE)
			->setDisplayConfigurable('view', TRUE);

		// City.
		$fields['city'] = BaseFieldDefinition::create('address')
			->setLabel(t('City'))
			->setDescription('The city of the Candidate.')
			->setRequired(true)
			->setDisplayOptions('view', array(
				'label' => 'above',
				'type' => 'string',
				'weight' => -4,
			))
			->setDisplayOptions('form', array(
				'type' => 'address',
				'weight' => -4,
				'settings' => array('available_countries' => 'CN: CN',
					'langcode_override' => 'zh-hans'),
			))
			->setDisplayConfigurable('form', TRUE)
			->setDisplayConfigurable('view', TRUE);

		$fields['birthday'] = BaseFieldDefinition::create('datetime')
			->setLabel(t('Birthday'))
			->setDescription(t('Birthday, Format: YYYY-MM-DD'))
			->setRequired(TRUE)
			->setTranslatable(TRUE)
			->setSettings(array(
				'datetime_type' => 'date',
			))
			->setDefaultValue(array('year' => 2000,
				'month' => 1,
				'day' => 1))
			->setDisplayOptions('view', array(
				'label' => 'inline',
				'type' => 'datetime_custom',
				'weight' => 14,
				'settings' => array('timezone_override' => 'Asia/Shanghai',
					'date_format' => 'Y-m-d'),
				'weight' => -4,
			))
			->setDisplayOptions('form', array(
				'type' => 'options_select',
				'weight' => -4,
			))
			->setDisplayConfigurable('view', TRUE)
			->setDisplayConfigurable('form', TRUE);

		// Date.
		$fields['startwork'] = BaseFieldDefinition::create('datetime')
			->setLabel(t('Start Work Date'))
			->setDescription('Date, #type = date')
			->setSettings(array(
				'datetime_type' => 'date',
			))
			->setDefaultValue(array('year' => 2000,
				'month' => 1,
				'day' => 1))
			->setRequired(true)
			->setDisplayOptions('view', array(
				'label' => 'above',
				'type' => 'datetime_custom',
				'settings' => array('timezone_override' => 'Asia/Shanghai',
					'date_format' => 'Y-m-d'),
				'weight' => -4,
			))
			->setDisplayOptions('form', array(
				'type' => 'options_select',
				'weight' => -4,
			))
			->setDisplayConfigurable('form', TRUE)
			->setDisplayConfigurable('view', TRUE);

		$fields['degree'] = BaseFieldDefinition::create('list_string')
			->setLabel(t('Degree'))
			->setDescription(t('The Degree of candidate.'))
			->setSettings(array(
				'allowed_values' => array(
					'College degree' => t('College degree'),
					'Bachelor degree' => t('Bachelor degree'),
					'Master degree' => t('Master degree'),
					'Doctor degree' => t('Doctor degree'),
				),
			))
			->setDisplayOptions('view', array(
				'label' => 'inline',
				'type' => 'string',
				'weight' => -4,
			))
			->setDisplayOptions('form', array(
				'type' => 'options_select',
				'weight' => -4,
			))
			->setRequired(TRUE)
			->setQueryable(TRUE)
			->setDisplayConfigurable('form', TRUE)
			->setDisplayConfigurable('view', TRUE);

		$fields['employmentstatus'] = BaseFieldDefinition::create('list_string')
			->setLabel(t('Employment Status'))
			->setDescription(t('Employment Status of candidate.'))
			->setSettings(array(
				'allowed_values' => array(
					'in-service' => t('in-service'),
					'dimission' => t('dimission'),
				),
			))
			->setDisplayOptions('view', array(
				'label' => 'inline',
				'type' => 'string',
				'weight' => -4,
			))
			->setDisplayOptions('form', array(
				'type' => 'options_select',
				'weight' => -4,
			))
			->setRequired(TRUE)
			->setQueryable(TRUE)
			->setDisplayConfigurable('form', TRUE)
			->setDisplayConfigurable('view', TRUE);

		$fields['position'] = BaseFieldDefinition::create('string')
			->setLabel(t('Position'))
			->setDescription(t('The position of the candidate.'))
			->setSettings(array(
				'default_value' => '',
				'max_length' => 255,
				'text_processing' => 0,
			))
			->setDisplayOptions('view', array(
				'label' => 'inline',
				'type' => 'string',
				'weight' => -4,
			))
			->setDisplayOptions('form', array(
				'type' => 'string_textfield',
				'weight' => 19,
				'settings' => array(
					'sizes' => 60,
				),
			))
			->setDisplayConfigurable('form', TRUE)
			->setDisplayConfigurable('view', TRUE);

		$fields['industry'] = BaseFieldDefinition::create('string')
			->setLabel(t('Industry'))
			->setDescription(t('The industry of the candidate.'))
			->setSettings(array(
				'default_value' => '',
				'max_length' => 255,
				'text_processing' => 0,
			))
			->setDisplayOptions('view', array(
				'label' => 'inline',
				'type' => 'string',
				'weight' => -4,
			))
			->setDisplayOptions('form', array(
				'type' => 'string',
				'weight' => -4,
			))
			->setDisplayConfigurable('form', TRUE)
			->setDisplayConfigurable('view', TRUE);

		$fields['monthlysalary'] = BaseFieldDefinition::create('list_string')
			->setLabel(t('Expect Monthly Salary'))
			->setDescription(t('The expect monthly salary of the Candidate.'))
			->setSettings(array(
				'allowed_values' => array(
					'discuss personally' => t('discuss personally'),
					'< 10000' => t('< 10000'),
					'10000 ~ 20000' => t('10000 ~ 20000'),
					'20000 ~ 30000' => t('20000 ~ 30000'),
					'>= 30000' => t('>= 30000'),
				),
			))
			->setDisplayOptions('view', array(
				'label' => 'inline',
				'type' => 'string',
				'weight' => -4,
			))
			->setDisplayOptions('form', array(
				'type' => 'options_select',
				'weight' => -4,
			))
			->setRequired(TRUE)
			->setDisplayConfigurable('form', TRUE)
			->setDisplayConfigurable('view', TRUE);

		$fields['other'] = BaseFieldDefinition::create('text_long')
			->setLabel(t('Other'))
			->setDescription(t('The other of the Candidate'))
			->setDisplayOptions('view', array(
				'label' => 'above',
				'type' => 'text_default',
				'weight' => 40,
			))
			->setDisplayOptions('form', array(
				'type' => 'text_textfield',
				'weight' => 40,
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
