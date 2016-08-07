<?php

namespace Drupal\lieplus\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class ResumeController.
 *
 * @package Drupal\lieplus\Controller
 */
class ResumeController extends ControllerBase {

	protected $config;
	public function __construct() {
		$this->config = \Drupal::config('lieplus.settings')->get();
	}

	/**
	 * Home.
	 *
	 * @return string
	 *   Return Hello string.
	 */
	public function home() {
		return [
			'#type' => 'markup',
			'#markup' => $this->t('Implement method: home'),
		];
	}

	/**
	 * search resume
	 * @param  [type] $key [description]
	 * @return [type]      [description]
	 */
	public function search() {
		return [
			'type' => 'markup',
			'#markup' => $this->t('Implement method: search '),
		];
	}

	/**
	 * get job resumes
	 * @param [type] $id [description]
	 */
	public function job() {
		$query = \Drupal::entityQuery('resume');
		// TODO: update conditionbbbbbbbbbbbbbbbb
		$rids = $query->condition('user_id', \Drupal::currentUser()->id())
			->sort('changed', 'DESC')
			->pager($this->config['items_per_page'])
			->execute();

		// Load the selected resumes.
		$storage = \Drupal::entityManager()->getStorage('resume');
		$resumes = $storage->loadMultiple($rids);

		$rows = array();
		foreach ($resumes as $resume) {
			$rows[] = buildResumeListRow($resume);
		}

		$form['table'] = array(
			'#type' => 'table',
			'#header' => buildResumeListHeader(),
			'#rows' => $rows,
			'#attributes' => array(
				'id' => 'block',
				'class' => array('field-multiple-table'),
			),
		);

		$form['table_pager'] = array(
			'#type' => 'pager');
		return $form;
	}

	/**
	 * get my resumes
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function my() {
		$query = \Drupal::entityQuery('resume');
		$rids = $query->condition('user_id', \Drupal::currentUser()->id())
			->sort('changed', 'DESC')
			->pager($this->config['items_per_page'])
			->execute();

		// Load the selected resumes.
		$storage = \Drupal::entityManager()->getStorage('resume');
		$resumes = $storage->loadMultiple($rids);

		$rows = array();
		foreach ($resumes as $resume) {
			$rows[] = buildResumeListRow($resume);
		}

		$form['table'] = array(
			'#type' => 'table',
			'#header' => buildResumeListHeader(),
			'#rows' => $rows,
			'#attributes' => array(
				'id' => 'block',
				'class' => array('field-multiple-table'),
			),
		);

		$form['table_pager'] = array(
			'#type' => 'pager');
		return $form;
	}

}
