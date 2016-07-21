<?php

namespace Drupal\lieplus\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\user\UserInterface;

/**
 * Defines the Employer entity.
 *
 * @ingroup lieplus
 *
 * @ContentEntityType(
 *   id = "employer",
 *   label = @Translation("Employer"),
 *   handlers = {
 *     "storage" = "Drupal\lieplus\EmployerStorage",
 *     "storage_schema" = "Drupal\lieplus\EmployerStorageSchema",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\lieplus\EmployerEntityListBuilder",
 *     "views_data" = "Drupal\lieplus\Entity\EmployerEntityViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\lieplus\Form\EmployerEntityForm",
 *       "add" = "Drupal\lieplus\Form\EmployerEntityForm",
 *       "edit" = "Drupal\lieplus\Form\EmployerEntityForm",
 *       "delete" = "Drupal\lieplus\Form\EmployerEntityDeleteForm",
 *     },
 *     "access" = "Drupal\lieplus\EmployerEntityAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\lieplus\EmployerEntityHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "employer",
 *   data_table = "employer_field_data",
 *   uri_callback = "employert_uri",
 *   translatable = TRUE,
 *   admin_permission = "administer employer entities",
 *   entity_keys = {
 *     "id" = "eid",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *   },
 *   links = {
 *     "canonical" = "/employer/{employer}",
 *     "add-form" = "/employer/add",
 *     "edit-form" = "/employer/{employer}/edit",
 *     "delete-form" = "/employer/{employer}/delete",
 *     "collection" = "/employer",
 *   },
 *   field_ui_base_route = "employer.settings"
 * )
 */
class EmployerEntity extends ContentEntityBase implements EmployerEntityInterface {

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
	public function getIntroduction() {
		return $this->get('introduction')->value;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setIntroduction($introduction) {
		$this->set('introduction', $introduction);
		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getFormat() {
		return $this->get('introduction')->format;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setFormat($format) {
		$this->get('introduction')->format = $format;
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

		$fields['eid'] = BaseFieldDefinition::create('integer')
			->setLabel(t('Employer ID'))
			->setDescription(t('The employer ID.'))
			->setReadOnly(TRUE)
			->setSetting('unsigned', TRUE);

		$fields['uuid'] = BaseFieldDefinition::create('uuid')
			->setLabel(t('UUID'))
			->setDescription(t('The employer UUID.'))
			->setReadOnly(TRUE);

		$fields['langcode'] = BaseFieldDefinition::create('language')
			->setLabel(t('Language'))
			->setDescription(t('The employer language code.'))
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
			->setDescription(t('The user ID of author of the Employer.'))
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
			->setDescription(t('The name of the Employer.'))
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

		$fields['web'] = BaseFieldDefinition::create('uri')
			->setLabel(t('Web Address'))
			->setDescription(t('The Web Address of the Employer.'))
			->setSetting('max_length', 255)
			->setDisplayOptions('view', array(
				'label' => 'above',
				'type' => 'uri_link',
				'weight' => -4,
			))
			->setDisplayOptions('form', array(
				'type' => 'string_textfield',
				'weight' => -4,
			))
			->setDisplayConfigurable('form', TRUE)
			->setDisplayConfigurable('view', TRUE);

		$fields['telephone'] = BaseFieldDefinition::create('telephone')
			->setLabel(t('Telephone'))
			->setDescription(t('The telephone of the Employer'))
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

		$fields['contact'] = BaseFieldDefinition::create('string')
			->setLabel(t('Contact'))
			->setDescription(t('The contact of the Employer'))
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

		$fields['nature'] = BaseFieldDefinition::create('list_string')
			->setLabel(t('Nature'))
			->setDescription(t('The nature of the Contact entity.'))
			->setSettings(array(
				'allowed_values' => array(
					'whooly foreign-owned' => t('whooly foreign-owned'),
					'sino-foreign joint venture' => t('sino-foreign joint venture'),
					'private enterprise' => t('private enterprise'),
					'state-owned enterprise' => t('state-owned enterprise'),
					'listed company in China' => t('listed company in China'),
					'government sector' => t('government sector'),
					'public institution' => t('public institution'),
					'other' => t('other'),
				),
			))
			->setDisplayOptions('view', array(
				'label' => 'above',
				'type' => 'string',
				'weight' => -4,
			))
			->setDisplayOptions('form', array(
				'type' => 'options_select',
				'weight' => -4,
			))
			->setDisplayConfigurable('form', TRUE)
			->setDisplayConfigurable('view', TRUE);

		$fields['industry'] = BaseFieldDefinition::create('list_string')
			->setLabel(t('Industry'))
			->setDescription(t('The industry of the Contact entity.'))
			->setSettings(array(
				'allowed_values' => array(
					'internet' => t('internet'),
					'net games' => t('net games'),
					'computer software' => t('computer software'),
					'IT service' => t('IT service'),
					'real estate' => t('real estate'),
					'consumption goods' => t('consumption goods'),
					'finance' => t('finance'),
					'other' => t('other'),
				),
			))
			->setDisplayOptions('view', array(
				'label' => 'above',
				'type' => 'string',
				'weight' => -4,
			))
			->setDisplayOptions('form', array(
				'type' => 'options_select',
				'weight' => -4,
			))
			->setDisplayConfigurable('form', TRUE)
			->setDisplayConfigurable('view', TRUE);

		$fields['city'] = BaseFieldDefinition::create('list_string')
			->setLabel(t('City'))
			->setDescription(t('The city of the Contact entity.'))
			->setSettings(array(
				'allowed_values' => array(
					'beijing' => t('beijing'),
					'shanghai' => t('shanghai'),
					'shenzhen' => t('shenzhen'),
					'chongqing' => t('chongqing'),
					'wuhan' => t('wuhan'),
					'nanjing' => t('nanjing'),
					'chengdu' => t('chengdu'),
					'other' => t('other'),
				),
			))
			->setDisplayOptions('view', array(
				'label' => 'above',
				'type' => 'string',
				'weight' => -4,
			))
			->setDisplayOptions('form', array(
				'type' => 'options_select',
				'weight' => -4,
			))
			->setDisplayConfigurable('form', TRUE)
			->setDisplayConfigurable('view', TRUE);

		$fields['size'] = BaseFieldDefinition::create('list_string')
			->setLabel(t('Size'))
			->setDescription(t('The size of the Contact entity.'))
			->setSettings(array(
				'allowed_values' => array(
					'1 to 20 person' => t('1 to 20 person'),
					'21 to 50 person' => t('21 to 50 person'),
					'51 to 100 person' => t('51 to 100 person'),
					'101 to 500 person' => t('101 to 500 person'),
					'501 to 1000 person' => t('501 to 1000 person'),
					'1001 to 5000 person' => t('1001 to 5000 person'),
					'5001 to 10000 person' => t('5001 to 10000 person'),
					'more than 10001 person' => t('more than 10001 person'),
				),
			))
			->setDisplayOptions('view', array(
				'label' => 'above',
				'type' => 'string',
				'weight' => -4,
			))
			->setDisplayOptions('form', array(
				'type' => 'options_select',
				'weight' => -4,
			))
			->setDisplayConfigurable('form', TRUE)
			->setDisplayConfigurable('view', TRUE);

		$fields['fund'] = BaseFieldDefinition::create('list_string')
			->setLabel(t('Fund'))
			->setDescription(t('The fund of the Contact entity.'))
			->setSettings(array(
				'allowed_values' => array(
					'full' => t('full'),
					'ratio' => t('ratio'),
					'ratio and no fund' => t('ratio and no fund'),
					'none' => t('none'),
					'other' => t('other'),
				),
			))
			->setDisplayOptions('view', array(
				'label' => 'above',
				'type' => 'string',
				'weight' => -4,
			))
			->setDisplayOptions('form', array(
				'type' => 'options_select',
				'weight' => -4,
			))
			->setDisplayConfigurable('form', TRUE)
			->setDisplayConfigurable('view', TRUE);

		/*$fields['address'] = BaseFieldDefinition::create('string')
			->setLabel(t('Detail Address'))
			->setDescription(t('The detail address of the Employer'))
			->setSettings(array(
				'max_length' => 255,
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
			->setDisplayConfigurable('view', TRUE);*/

		// Address.
		$fields['address'] = BaseFieldDefinition::create('address')
			->setLabel(t('Address'))
			->setDescription('Date, #type = date')
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

		$fields['introduction'] = BaseFieldDefinition::create('text_long')
			->setLabel(t('Introduction'))
			->setDescription(t('The introduction of the Employer'))
			->setDisplayOptions('view', array(
				'label' => 'above',
				'type' => 'text_default',
				'weight' => -4,
			))
			->setDisplayOptions('form', array(
				'type' => 'text_textfield',
				'weight' => -4,
			))
			->setDisplayConfigurable('form', TRUE)
			->setDisplayConfigurable('view', TRUE);

		$fields['boon'] = BaseFieldDefinition::create('text_long')
			->setLabel(t('Boon'))
			->setDescription(t('The boon of the Employer'))
			->setDisplayOptions('view', array(
				'label' => 'above',
				'type' => 'text_default',
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
			->setDescription(t('A boolean indicating whether the Employer is published.'))
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
