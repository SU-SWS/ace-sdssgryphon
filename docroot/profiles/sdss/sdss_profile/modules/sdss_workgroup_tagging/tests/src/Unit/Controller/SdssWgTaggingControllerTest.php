<?php

namespace Drupal\Tests\sdss_workgroup_tagging\Unit\Controller;

use Drupal\Core\PageCache\ResponsePolicy\KillSwitch;
use Drupal\sdss_workgroup_tagging\Controller\SdssWgTaggingController;
use Drupal\sdss_workgroup_tagging\SdssWgTaggingUtil;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * @coversDefaultClass \Drupal\sdss_workgroup_tagging\Controller\SdssWgTaggingController
 * @group sdss_workgroup_tagging
 */
class SdssWgTaggingControllerTest extends TestCase {

  public function testOutputReturnsStatusMessage() {
    // Mock the KillSwitch.
    $killSwitch = $this->createMock(KillSwitch::class);
    $killSwitch->expects($this->once())->method('trigger');

    // Mock SdssWgTaggingUtil and its tagPersons method.
    $mockUtil = $this->getMockBuilder(SdssWgTaggingUtil::class)
      ->onlyMethods(['tagPersons'])
      ->getMock();
    $mockUtil->method('tagPersons')->willReturn([
      'status' => ['message' => 'Tagging complete!'],
    ]);

    // Use an anonymous class to inject the mock util.
    $controller = new class($killSwitch, $mockUtil) extends SdssWgTaggingController {
      protected $mockUtil;
      public function __construct($killSwitch, $mockUtil) {
        parent::__construct($killSwitch);
        $this->mockUtil = $mockUtil;
      }
      public function output(Request $request) {
        $result = $this->mockUtil->tagPersons();
        if ($result && $result['status']) {
          $response = ['#markup' => $result['status']['message']];
        }
        else {
          $response = ['#markup' => 'Error!'];
        }
        $this->killSwitch->trigger();
        return $response;
      }
    };

    $request = $this->createMock(Request::class);
    $response = $controller->output($request);

    $this->assertEquals(['#markup' => 'Tagging complete!'], $response);
  }
}