<?php

namespace Drupal\lieplus\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for Employer entities.
 */
class EmployerEntityViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['employer']['table']['base'] = array(
      'field' => 'id',
      'title' => $this->t('Employer'),
      'help' => $this->t('The Employer ID.'),
    );

    return $data;
  }

}
