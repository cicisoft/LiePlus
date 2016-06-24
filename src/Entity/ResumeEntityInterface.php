<?php

namespace Drupal\lieplus\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Resume entities.
 *
 * @ingroup lieplus
 */
interface ResumeEntityInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Resume name.
   *
   * @return string
   *   Name of the Resume.
   */
  public function getName();

  /**
   * Sets the Resume name.
   *
   * @param string $name
   *   The Resume name.
   *
   * @return \Drupal\lieplus\Entity\ResumeEntityInterface
   *   The called Resume entity.
   */
  public function setName($name);

  /**
   * Gets the Resume creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Resume.
   */
  public function getCreatedTime();

  /**
   * Sets the Resume creation timestamp.
   *
   * @param int $timestamp
   *   The Resume creation timestamp.
   *
   * @return \Drupal\lieplus\Entity\ResumeEntityInterface
   *   The called Resume entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Resume published status indicator.
   *
   * Unpublished Resume are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Resume is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Resume.
   *
   * @param bool $published
   *   TRUE to set this Resume to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\lieplus\Entity\ResumeEntityInterface
   *   The called Resume entity.
   */
  public function setPublished($published);

}
