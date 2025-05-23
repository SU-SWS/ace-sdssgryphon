<?php

use Faker\Factory;
use Drupal\config_pages\Entity\ConfigPages;

/**
 * Test the external link module functionality.
 *
 * @group contrib
 * @group ext_links
 */
class ExtLinkCest {

  /**
   * @var Faker
   */
  protected $faker;

  /**
   * Start with a clean config page.
   *
   * @param \AcceptanceTester $I
   *   Tester.
   */
  public function _before(FunctionalTester $I) {
    $this->_after($I);
  }

  /**
   * Test Constructor.
   */
  public function __construct() {
    $this->faker = Factory::create();
  }

  /**
   * Always cleanup the config after testing.
   *
   * @param \AcceptanceTester $I
   *   Tester.
   */
  public function _after(FunctionalTester $I) {
    if ($config_page = ConfigPages::load('stanford_local_footer')) {
      $config_page->delete();
    }
    if ($config_page = ConfigPages::load('stanford_basic_site_settings')) {
      $config_page->delete();
    }
  }

  /**
   * Test external links get the added class and svg.
   */
  public function testExtLink(FunctionalTester $I) {
    $I->logInWithRole('site_manager');
    $I->amOnPage('/admin/config/system/basic-site-settings');
    $I->uncheckOption('Hide External Link Icons');

    $I->amOnPage('/admin/config/system/local-footer');
    $I->checkOption('#edit-su-footer-enabled-value');
    $I->click('#edit-group-primary-links summary');
    $I->click('#edit-group-secondary-links summary');

    $I->fillField('su_local_foot_primary[0][uri]', 'https://google.com');
    $I->fillField('su_local_foot_primary[0][title]', 'Primary Link');
    $I->click('Add another item', '.field--name-su-local-foot-primary');
    $I->waitForElement('[name="su_local_foot_primary[1][uri]"]');
    $I->fillField('su_local_foot_primary[1][uri]', 'https://stanford.edu');
    $I->fillField('su_local_foot_primary[1][title]', 'Another primary link');

    $I->fillField('su_local_foot_second[0][uri]', 'https://stanford.edu');
    $I->fillField('su_local_foot_second[0][title]', 'Secondary Link');
    $I->click('Add another item', '.field--name-su-local-foot-second');
    $I->waitForElement('[name="su_local_foot_second[1][uri]"]');
    $I->fillField('su_local_foot_second[1][uri]', 'https://google.com');
    $I->fillField('su_local_foot_second[1][title]', 'Another secondary link');

    $I->click('Save');
    $I->waitForText('Local Footer has been');
    $I->see('Local Footer has been', '.messages-list');

    $text = $this->faker->paragraph;
    $paragraph = $I->createEntity([
      'type' => 'stanford_wysiwyg',
      'su_wysiwyg_text' => [
        'format' => 'stanford_html',
        'value' => $text . '<a href="mailto:foo@bar.com">email link</a> <a href="/foobar">local link</a> <a href="https://stanford.edu">external link</a>',
      ],
    ], 'paragraph');

    $page = $I->createEntity([
      'type' => 'stanford_page',
      'title' => $this->faker->words(4, TRUE),
      'su_page_components' => [
        'target_id' => $paragraph->id(),
        'entity' => $paragraph,
      ],
    ]);

    $I->amOnPage($page->toUrl()->toString());
    $I->waitForElementVisible('#page-content a.su-link--external');

    // External Links in the page-content region.
    $I->canSeeNumberOfElements('#page-content a.su-link--external', 2);
    $I->canSeeNumberOfElements('#page-content a.mailto.su-link--external', 1);
    // External links in the local footer.
    $I->canSeeNumberOfElements('.su-local-footer__cell2 a.su-link--external', 4);
  }
}
