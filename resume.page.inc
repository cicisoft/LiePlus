<?php

/**
 * @file
 * Contains resume.page.inc.
 *
 * Page callback for Resume entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for Resume templates.
 *
 * Default template: resume.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_resume(array &$variables) {
  // Fetch ResumeEntity Entity Object.
  $resume = $variables['elements']['#resume'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
