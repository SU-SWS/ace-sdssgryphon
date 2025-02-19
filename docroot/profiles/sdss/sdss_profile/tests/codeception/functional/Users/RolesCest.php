<?php

use Faker\Factory;

/**
 * Class RolesCest.
 *
 * @group users
 */
class RolesCest {

  /**
   * Storage for state settings that get restored after the tests finish.
   *
   * @var array
   */
  protected $state = [];

  /**
   * Faker service.
   *
   * @var \Faker\Generator
   */
  protected $faker;

  /**
   * Save some state values before the tests run.
   *
   * @param \FunctionalTester $I
   *   Tester.
   */
  public function _before(FunctionalTester $I) {
    $this->saveStateValue('sdss_profile.front_page');
  }

  /**
   * Restore any state values that were saved.
   *
   * @param \FunctionalTester $I
   *   Tester.
   */
  public function _after(FunctionalTester $I) {
    foreach ($this->state as $key => $value) {
      \Drupal::state()->set($key, $value);
    }
    drupal_flush_all_caches();
  }

  public function __construct() {
    $this->faker = Factory::create();
  }

  /**
   * Save the current state value to be restored after tests end.
   *
   * @param string $key
   *   State key.
   */
  protected function saveStateValue($key) {
    $this->state[$key] = \Drupal::state()->get($key);
  }

  /**
   * D8CORE-1200 Prevent deleteing the homepage from bulk delete.
   */
  public function testBulkDeleteHomePage(FunctionalTester $I) {
    $test_home = $I->createEntity([
      'type' => 'stanford_page',
      'title' => $this->faker->words(3, true),
    ]);
    $I->createEntity([
      'type' => 'stanford_page',
      'title' => $this->faker->words(3, true),
    ]);
    $test_home_url = $test_home->toUrl()->toString();
    \Drupal::state()->set('sdss_profile.front_page', $test_home_url);
    $I->runDrush('cache-rebuild');
    $I->assertEquals($test_home_url, $this->getFrontPagePath($I));

    $I->logInWithRole('site_manager');
    $I->amOnPage('/admin/content?order=changed&sort=desc');
    $I->checkOption('tbody tr:first-child input[type="checkbox"]');
    $I->checkOption('tbody tr:nth-child(2) input[type="checkbox"]');
    $I->selectOption('Action', 'Delete selected entities');
    $I->click('Apply to selected items');
    $I->click('Execute action');
    $I->waitForText('Delete entities');
    $I->canSee('Access denied (1)');
    $I->runDrush('cache-rebuild');
    $I->amOnPage('/');
    $I->canSee($test_home->label(), 'h1');
  }

  /**
   * Get the machine path of the home page.
   *
   * @param \FunctionalTester $I
   *   Tester.
   *
   * @return string
   *   Uri path.
   */
  protected function getFrontPagePath(FunctionalTester $I) {
    $drush_response = $I->runDrush('config-get system.site page.front --include-overridden --format=json');
    $drush_response = json_decode($drush_response, TRUE);
    return $drush_response['system.site:page.front'];
  }

}
