<?php

/**
 * @file
 * Contains employer.page.inc.
 *
 * Page callback for Employer entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for Employer templates.
 *
 * Default template: employer.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_employer(array &$variables) {
  // Fetch EmployerEntity Entity Object.
  $employer = $variables['elements']['#employer'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
