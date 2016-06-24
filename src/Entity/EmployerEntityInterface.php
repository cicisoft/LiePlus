<?php

namespace Drupal\lieplus\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Employer entities.
 *
 * @ingroup lieplus
 */
interface EmployerEntityInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Employer name.
   *
   * @return string
   *   Name of the Employer.
   */
  public function getName();

  /**
   * Sets the Employer name.
   *
   * @param string $name
   *   The Employer name.
   *
   * @return \Drupal\lieplus\Entity\EmployerEntityInterface
   *   The called Employer entity.
   */
  public function setName($name);

  /**
   * Gets the Employer creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Employer.
   */
  public function getCreatedTime();

  /**
   * Sets the Employer creation timestamp.
   *
   * @param int $timestamp
   *   The Employer creation timestamp.
   *
   * @return \Drupal\lieplus\Entity\EmployerEntityInterface
   *   The called Employer entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Employer published status indicator.
   *
   * Unpublished Employer are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Employer is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Employer.
   *
   * @param bool $published
   *   TRUE to set this Employer to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\lieplus\Entity\EmployerEntityInterface
   *   The called Employer entity.
   */
  public function setPublished($published);

}
