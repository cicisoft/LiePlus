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
		$header = buildResumeListHeader();
		return $header + parent::buildHeader();
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildRow(EntityInterface $entity) {
		/* @var $entity \Drupal\lieplus\Entity\ResumeEntity */
		$row = buildResumeListRow($entity);
		return $row + parent::buildRow($entity);
	}

	public function render() {
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

/*		$build['nav'] = array(
'#theme' => 'resume_nav',
);*/
		return $build + parent::render();

	}

}
