<?php

namespace Drupal\lieplus;

use Drupal\Core\Entity\ContentEntityTypeInterface;
use Drupal\Core\Entity\Sql\SqlContentEntityStorageSchema;
use Drupal\Core\Field\FieldStorageDefinitionInterface;

/**
 * Defines the node schema handler.
 */
class ResumeStorageSchema extends SqlContentEntityStorageSchema {

	/**
	 * {@inheritdoc}
	 */
	protected function getEntitySchema(ContentEntityTypeInterface $entity_type, $reset = FALSE) {
		$schema = parent::getEntitySchema($entity_type, $reset);

		// $schema['node_field_data']['indexes'] += array(
		// 	'node__frontpage' => array('promote', 'status', 'sticky', 'created'),
		// 	'node__status_type' => array('status', 'type', 'nid'),
		// 	'node__title_type' => array('title', array('type', 4)),
		// );

		return $schema;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getSharedTableFieldSchema(FieldStorageDefinitionInterface $storage_definition, $table_name, array $column_mapping) {
		$schema = parent::getSharedTableFieldSchema($storage_definition, $table_name, $column_mapping);
		$field_name = $storage_definition->getName();

		if ($table_name == 'resume') {
			switch ($field_name) {
			case 'langcode':
				$this->addSharedTableFieldIndex($storage_definition, $schema, TRUE);
				break;
			case 'name':
				$this->addSharedTableFieldIndex($storage_definition, $schema, TRUE);
				break;
			}
		}

		if ($table_name == 'resume_field_data') {
			switch ($field_name) {
			case 'name':
			case 'email':
			case 'telephone':
			case 'birthday':
			case 'startwork':
			case 'industry':
			case 'position':
				// Improves the performance of the indexes defined
				// in getEntitySchema().
				$schema['fields'][$field_name]['not null'] = TRUE;
				break;

			case 'changed':
			case 'created':
				// @todo Revisit index definitions:
				//   https://www.drupal.org/node/2015277.
				$this->addSharedTableFieldIndex($storage_definition, $schema, TRUE);
				break;
			}
		}

		return $schema;
	}

}
