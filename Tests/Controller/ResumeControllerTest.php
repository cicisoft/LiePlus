<?php

namespace Drupal\lieplus\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Provides automated tests for the lieplus module.
 */
class ResumeControllerTest extends WebTestBase {


  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return array(
      'name' => "lieplus ResumeController's controller functionality",
      'description' => 'Test Unit for module lieplus and controller ResumeController.',
      'group' => 'Other',
    );
  }

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
  }

  /**
   * Tests lieplus functionality.
   */
  public function testResumeController() {
    // Check that the basic functions of module lieplus.
    $this->assertEquals(TRUE, TRUE, 'Test Unit Generated via Drupal Console.');
  }

}
