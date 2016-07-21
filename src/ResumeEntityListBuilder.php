<?php

namespace Drupal\lieplus;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of Resume entities.
 *
 * @ingroup lieplus
 */
class ResumeEntityListBuilder extends EntityListBuilder {

	use LinkGeneratorTrait;

	/**
	 * {@inheritdoc}
	 */
	public function buildHeader() {
		$header['date'] = $this->t('Date');
		$header['name'] = $this->t('Name');
		$header['base'] = array('data' => $this->t('Base Info'), 'colspan' => 3);
		$header['abstract'] = $this->t('Abstract');
		$header['owner'] = $this->t('Owner');
		$header['feedback'] = $this->t('Detail Feedback');
		$header['modify'] = $this->t('Modified By');
		return $header + parent::buildHeader();
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildRow(EntityInterface $entity) {
		/* @var $entity \Drupal\lieplus\Entity\ResumeEntity */
		$row['date'] = $this->t(date('Y-m-d H:i:s', $entity->changed->value));

		$row['name'] = $this->l(
			$entity->label(),
			new Url(
				'entity.resume.edit_form', array(
					'resume' => $entity->id(),
				)
			)
		);
		//dpm($entity);
		$row['base'] = $this->t($entity->gender->value);
		$row['age'] = $this->t('%age years-old', array('%age' => $entity->getAge($entity->birthday->value)));
		$row['workage'] = $this->t('worked %work years', array('%work' => $entity->getAge($entity->startwork->value)));
		$row['abstract'] = $entity->getAbstract();
		$row['owner'] = array('data' => array(
			'#theme' => 'username',
			'#account' => $entity->getOwner(),
		));
		$row['feedback'] = array('data' => array(
			'#theme' => 'input',
		));
		//$row['feedback'] = "TODO：feedback";
		$row['modify'] = array('data' => array(
			'#theme' => 'username',
			'#account' => $entity->getOwner(),
		));
		return $row + parent::buildRow($entity);
	}

	public function render() {
		$build['markup'] = array(
			'#type' => 'markup',
			'#markup' => $this->t('Implement method: index with parameter(s): ' . $id),
		);

		$links = array(new link(
			'view',
			new Url(
				'entity.resume.edit_form', array(
					'resume' => 1,
				)
			)),
			new link(
				'test',
				new Url(
					'entity.resume.edit_form', array(
						'resume' => 1,
					)
				)),
		);
		$build['breadcrumb'] = array(
			'#theme' => 'breadcrumb',
			'#links' => $links,
		);
		return $build + parent::render();

	}

}
