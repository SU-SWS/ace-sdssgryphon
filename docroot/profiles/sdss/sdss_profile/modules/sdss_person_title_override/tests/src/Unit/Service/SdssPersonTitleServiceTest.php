<?php

declare(strict_types=1);

namespace Drupal\sdss_person_title_override\Tests\Service;

use Drupal\sdss_person_title_override\Service\SdssPersonTitleService;
use PHPUnit\Framework\TestCase;

class SdssPersonTitleServiceTest extends TestCase {
    private function getNodeMock($bundle, $fields) {
        $node = $this->getMockBuilder('Drupal\\node\\NodeInterface')
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

    public function testGetDisplayFullTitleReturnsExpectedValue() {
        $service = new SdssPersonTitleService();
        $node = (object) [
            'field_title' => [0 => ['value' => 'Professor']],
            'field_alt_title' => [0 => ['value' => '']],
        ];
        $result = $service->getDisplayFullTitle($node);
        $this->assertEquals('Professor', $result);
    }

    public function testGetDisplayShortTitleReturnsExpectedValue() {
        $service = new SdssPersonTitleService();
        $node = (object) [
            'field_title' => [0 => ['value' => 'Professor']],
            'field_alt_title' => [0 => ['value' => 'Dr.']],
        ];
        $result = $service->getDisplayShortTitle($node);
        $this->assertEquals('Dr.', $result);
    }

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

    public function testGetDisplayFullTitleReturnsFullTitleIfNoAltTitle() {
        $service = new SdssPersonTitleService();
        $fields = [
            'su_sdss_person_alt_title' => '',
            'su_person_full_title' => 'Professor',
        ];
        $node = $this->getNodeMock('stanford_person', $fields);
        $result = $service->getDisplayFullTitle($node);
        $this->assertEquals('Professor', $result);
    }

    public function testGetDisplayShortTitleReturnsAltTitleIfPresent() {
        $service = new SdssPersonTitleService();
        $fields = [
            'su_sdss_person_alt_title' => 'Dr.',
            'su_person_short_title' => 'Prof.',
        ];
        $node = $this->getNodeMock('stanford_person', $fields);
        $result = $service->getDisplayShortTitle($node);
        $this->assertEquals('Dr.', $result);
    }

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

    public function testGetDisplayShortTitleThrowsExceptionForMissingFields() {
        $service = new SdssPersonTitleService();
        $fields = [
            'su_sdss_person_alt_title' => 'Dr.'
            // su_person_short_title missing
        ];
        $node = $this->getNodeMock('stanford_person', $fields);
        $this->expectException(\LogicException::class);
        $service->getDisplayShortTitle($node);
    }
}
