<?php

/**
 * Acceptance tests for sdss_subtheme header options.
 * @group header_options
 * @group sdss_subtheme
 */
class HeaderOptionsCest {

  /**
   * Test toggling the desktop hamburger and utility navigation options.
   */
  public function testHeaderOptions(AcceptanceTester $I) {
    // Log in as administrator.
    $I->logInWithRole('administrator');

    // Ensure there are two menu links in the utility-navigation menu.
    $link1 = $I->createEntity([
        'title' => 'Utility Link 1',
        'link' => ['uri' => 'route:<front>'],
        'menu_name' => 'utility-navigation',
    ], 'menu_link_content');
    $link2 = $I->createEntity([
        'title' => 'Utility Link 2',
        'link' => ['uri' => 'route:<front>'],
        'menu_name' => 'utility-navigation',
    ], 'menu_link_content');

    // Go to the theme settings page.
    $I->amOnPage('/admin/appearance/settings/sdss_subtheme');

    // Enable Desktop Hamburger and Utility Navigation.
    $I->checkOption('#edit-desktop-hamburger');
    $I->checkOption('#edit-display-utility-navigation');
    $I->click('Save configuration');
    $I->see('The configuration options have been saved.');

    // Go to the homepage and check for the utility navigation region.
    $I->amOnPage('/');
    $I->canSeeElement('.menu--utility-navigation');
    $I->canSee('Utility Link 1');
    $I->canSee('Utility Link 2');

    // Disable Utility Navigation only.
    $I->amOnPage('/admin/appearance/settings/sdss_subtheme');
    $I->uncheckOption('#edit-display-utility-navigation');
    $I->click('Save configuration');
    $I->see('The configuration options have been saved.');
    $I->amOnPage('/');
    $I->dontSeeElement('.menu--utility-navigation');

    // Disable Desktop Hamburger (Utility Navigation option should disappear 
    // and region should not render).
    $I->amOnPage('/admin/appearance/settings/sdss_subtheme');
    $I->uncheckOption('#edit-desktop-hamburger');
    $I->click('Save configuration');
    $I->see('The configuration options have been saved.');
    $I->amOnPage('/');
    $I->dontSeeElement('.menu--utility-navigation');

    // Cleanup: Delete the menu links.
    $link1->delete();
    $link2->delete();
  }

}
