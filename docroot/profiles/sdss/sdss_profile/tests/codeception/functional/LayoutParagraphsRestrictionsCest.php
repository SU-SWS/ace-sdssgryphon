<?php

/**
 * Class LayoutParagraphRestrictionsCest.
 * 
 * Only test a few layouts and regions for paragraph restrictions.
 *
 * @group paragraphs
 * @group layout_paragraphs
 */
class LayoutParagraphRestrictionsCest {

  /**
   * Test two column left region paragraph restrictions.
   */
  public function testTwoColumnRestrictions(FunctionalTester $I)  {
    $I->logInWithRole('administrator');
    $I->amOnPage('/node/add/stanford_page');

    // Add a new section and select the 2 column layout.
    $I->click('Add section');
    $I->waitForText('2 Equal Columns', 10);
    $input_id = $I->grabAttributeFrom(['css' => 'input.choose-layout-field[value="layout_paragraphs_sdss_2_column"]'], 'id');
    $I->click(['css' => 'label[for="' . $input_id . '"]']);
    $I->wait(1);
    $I->click('Save', '.ui-dialog-buttonpane');

    // Check for layout.
    $layout_class = '.layout--layout-paragraphs-sdss-two-column';
    $I->waitForElementNotVisible('.ui-dialog', 10);
    $I->waitForElement($layout_class, 10);

    // Test the left region.
    $region_selector = '[data-region="left"]';
    $I->waitForElementVisible($layout_class . ' ' . $region_selector, 10);
    $I->click($layout_class . ' ' . $region_selector . ' .lpb-btn--add');
    $I->waitForElementVisible('.ui-dialog', 10);

    // Determine which restrictions apply.
    $restrictions = [
      'Spotlight',
      'Banner',
      'FAQ',
      'Gallery',
    ];

    // Assert restricted paragraph labels are not present.
    foreach ($restrictions as $restriction_label) {
      $I->dontSee($restriction_label, '.ui-dialog .lpb-component-list__item a');
    }

    // Lists paragraph is allowed.
    $I->canSee('Lists', '.ui-dialog .lpb-component-list__item a');
  }

  /**
   * Test one four one first region paragraph restrictions.
   */
  public function testOneFourOneRestrictions(FunctionalTester $I)  {
    $I->logInWithRole('administrator');
    $I->amOnPage('/node/add/stanford_page');

    // Add a new section and select the 2 column layout.
    $I->click('Add section');
    $I->waitForText('4 Column with Header and Footer', 10);
    $input_id = $I->grabAttributeFrom(['css' => 'input.choose-layout-field[value="layout_paragraphs_sdss_1_4_1"]'], 'id');
    $I->click(['css' => 'label[for="' . $input_id . '"]']);
    $I->wait(1);
    $I->click('Save', '.ui-dialog-buttonpane');

    // Check for layout.
    $layout_class = '.layout--layout-paragraphs-sdss-one-four-one';
    $I->waitForElementNotVisible('.ui-dialog', 10);
    $I->waitForElement($layout_class, 10);

    // Test the left region.
    $region_selector = '[data-region="first"]';
    $I->waitForElementVisible($layout_class . ' ' . $region_selector, 10);
    $I->click($layout_class . ' ' . $region_selector . ' .lpb-btn--add');
    $I->waitForElementVisible('.ui-dialog', 10);

    // Determine which restrictions apply.
    $restrictions = [
      'Spotlight',
      'Banner',
      'FAQ',
      'Gallery',
      'Lists',
    ];

    // Assert restricted paragraph labels are not present.
    foreach ($restrictions as $restriction_label) {
      $I->dontSee($restriction_label, '.ui-dialog .lpb-component-list__item a');
    }
  }
}
