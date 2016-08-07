<?php

namespace Drupal\lieplus\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for Resume entities.
 */
class ResumeEntityViewsData extends EntityViewsData implements EntityViewsDataInterface {

	/**
	 * {@inheritdoc}
	 */
	public function getViewsData() {
		$data = parent::getViewsData();

		$data['resume']['table']['base'] = array(
			'field' => 'id',
			'title' => $this->t('Resume'),
			'help' => $this->t('The Resume ID.'),
		);

		return $data;
	}

}
