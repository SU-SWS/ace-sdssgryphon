<?php

declare(strict_types=1);

namespace Drupal\Tests\sdss_person_title_override\Unit\Service;

use Drupal\sdss_person_title_override\Service\SdssPersonTitleService;
use Drupal\node\NodeInterface;
use PHPUnit\Framework\TestCase;

/**
 * Test the SDSS Person Title Override Service.
 *
 * @group sdss_person_title_override
 * @coversDefaultClass \Drupal\sdss_person_title_override\Service\SdssPersonTitleService
 */
class SdssPersonTitleServiceTest extends TestCase {

  /**
   * Creates a NodeInterface mock with the given bundle and field values.
   *
   * @param string $bundle
   *   The node bundle name.
   * @param array $fields
   *   An associative array of field names and their values.
   *
   * @return \Drupal\node\NodeInterface
   *   The mocked node object.
   */
  private function getNodeMock($bundle, $fields) {
    $node = $this->getMockBuilder(NodeInterface::class)
      ->disableOriginalConstructor()
      ->getMock();
    $node->method('bundle')->willReturn($bundle);
    $node->method('hasField')->willReturnCallback(function($field) use ($fields) {
      return array_key_exists($field, $fields);
    });
    $node->method('get')->willReturnCallback(function($field) use ($fields) {
      return (object) ['value' => $fields[$field]];
    });
    return $node;
  }

  /**
   * Tests that getDisplayFullTitle returns the full title when alt title is 
   * empty.
   */
  public function testGetDisplayFullTitleReturnsExpectedValue() {
    $service = new SdssPersonTitleService();
    $fields = [
      'su_sdss_person_alt_title' => '',
      'su_person_full_title' => 'Professor',
    ];
    $node = $this->getNodeMock('stanford_person', $fields);
    $result = $service->getDisplayFullTitle($node);
    $this->assertEquals('Professor', $result);
  }

  /**
   * Tests that getDisplayFullTitle returns the alt title if present.
   */
  public function testGetDisplayFullTitleReturnsAltTitleIfPresent() {
    $service = new SdssPersonTitleService();
    $fields = [
      'su_sdss_person_alt_title' => 'Dr.',
      'su_person_full_title' => 'Professor',
    ];
    $node = $this->getNodeMock('stanford_person', $fields);
    $result = $service->getDisplayFullTitle($node);
    $this->assertEquals('Dr.', $result);
  }

  /**
   * Tests that getDisplayShortTitle returns the alt title when present.
   */
  public function testGetDisplayShortTitleReturnsExpectedValue() {
    $service = new SdssPersonTitleService();
    $fields = [
      'su_sdss_person_alt_title' => 'Dr.',
      'su_person_short_title' => 'Prof.',
    ];
    $node = $this->getNodeMock('stanford_person', $fields);
    $result = $service->getDisplayShortTitle($node);
    $this->assertEquals('Dr.', $result);
  }

  /**
   * Tests that getDisplayShortTitle returns the short title if alt title is
   * empty.
   */
  public function testGetDisplayShortTitleReturnsShortTitleIfNoAltTitle() {
    $service = new SdssPersonTitleService();
    $fields = [
      'su_sdss_person_alt_title' => '',
      'su_person_short_title' => 'Prof.',
    ];
    $node = $this->getNodeMock('stanford_person', $fields);
    $result = $service->getDisplayShortTitle($node);
    $this->assertEquals('Prof.', $result);
  }

  /**
   * Tests that getDisplayFullTitle throws an exception for a
   * non-stanford_person bundle.
   */
  public function testGetDisplayFullTitleThrowsExceptionForWrongBundle() {
    $service = new SdssPersonTitleService();
    $fields = [
      'su_sdss_person_alt_title' => 'Dr.',
      'su_person_full_title' => 'Professor',
    ];
    $node = $this->getNodeMock('other_bundle', $fields);
    $this->expectException(\LogicException::class);
    $service->getDisplayFullTitle($node);
  }

  /**
   * Tests that getDisplayShortTitle throws an exception when required fields
   * are missing.
   */
  public function testGetDisplayShortTitleThrowsExceptionForMissingFields() {
    $service = new SdssPersonTitleService();
    $fields = [
      'su_sdss_person_alt_title' => 'Dr.'
    ];
    $node = $this->getNodeMock('stanford_person', $fields);
    $this->expectException(\LogicException::class);
    $service->getDisplayShortTitle($node);
  }
}
