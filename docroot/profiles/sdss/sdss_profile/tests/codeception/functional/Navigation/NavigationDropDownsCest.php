<?php

use Faker\Factory;

/**
 * Test for the lockup settings.
 *
 * @group navigation
 */
class NavigationDropDownsCest {

  /**
   * Faker service.
   *
   * @var \Faker\Generator
   */
  protected $faker;

  /**
   * Test constructor.
   */
  public function __construct() {
    $this->faker = Factory::create();
  }

  /**
   * Cleanup after test.
   */
  public function __after(FunctionalTester $I) {
    \Drupal::entityTypeManager()
      ->getStorage('config_pages')
      ->load('stanford_basic_site_settings')
      ->set('su_site_dropdowns', NULL)
      ->save();
  }

  /**
   * Create some content and test the dropdown menu.
   *
   * @group menu_link_weight
   */
  public function testDropdownMenus(FunctionalTester $I) {
    $org_term = $I->createEntity([
      'vid' => 'site_owner_orgs',
      'name' => $this->faker->words(2, TRUE),
    ], 'taxonomy_term');

    $parent_menu_title = $this->faker->word;
    $I->createEntity([
      'title' => $parent_menu_title,
      'menu_name' => 'main',
      'link' => ['uri' => 'route:<front>'],
    ], 'menu_link_content');

    $I->logInWithRole('site_manager');
    $I->resizeWindow(1400, 2000);
    $I->amOnPage('/admin/config/system/basic-site-settings');
    $I->uncheckOption('Use Drop Down Menus');

    $I->amOnPage('/');
    //$I->cantSeeElement('button', ['class' => 'su-nav-toggle']);

    $I->amOnPage('/admin/config/system/basic-site-settings');
    $I->checkOption('Use Drop Down Menus');
    $I->click('Save');
    $I->waitForText('Site Settings has been');
    $I->canSee('Site Settings has been', '.messages-list');

    $node_title = Factory::create()->text(20);

    $I->amOnPage('/node/add/stanford_page');
    $I->fillField('Title', $node_title);
    $I->click('Menu settings');
    $I->checkOption('Provide a menu link');
    $I->fillField('Menu link title', $node_title);
    // The label on the menu parent changes in D9 vs D8
    $I->selectOption('.menu-link-form .select-wrapper--level-0 select', '<main>');
    $I->selectOption('.menu-link-form .select-wrapper--level-1 select', $parent_menu_title);
    $I->waitForText("Change the weight of the links within the $parent_menu_title menu");

    $I->click('Save');
    $I->waitForText($node_title);
    $I->canSeeLink($node_title);

    $I->amOnPage('/');
    $I->waitForElement('button.su-nav-toggle', 2);
    $I->seeElement('button.su-nav-toggle');
  }

}
