<?php

/**
 * Class RoleMappingsCest.
 *
 * @group users
 */
class RoleMappingsCest {

  /**
   * Test that the correct role mappings exist on the SAML config page.
   */
  public function testRoleMappingInConfig(AcceptanceTester $I) {
    // Log in as an administrator.
    $I->logInWithRole('administrator');
    $I->amOnPage('/admin/config/people/stanford-saml');
    // Check that the mapping is visible on the page.
    $I->canSee('uit:sws');
    $I->canSee('sdss:webdev');
  }

}
