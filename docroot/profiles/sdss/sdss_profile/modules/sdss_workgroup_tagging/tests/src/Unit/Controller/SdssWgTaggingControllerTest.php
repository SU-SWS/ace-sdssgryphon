<?php

namespace Drupal\Tests\sdss_workgroup_tagging\Unit\Controller;

use Drupal\Core\PageCache\ResponsePolicy\KillSwitch;
use Drupal\sdss_workgroup_tagging\Controller\SdssWgTaggingController;
use Drupal\sdss_workgroup_tagging\SdssWgTaggingUtil;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;

/**
 * @coversDefaultClass \Drupal\sdss_workgroup_tagging\Controller\SdssWgTaggingController
 * @group sdss_workgroup_tagging
 */
class SdssWgTaggingControllerTest extends TestCase {

  public function testOutputReturnsStatusMessage() {
    // Mock the KillSwitch.
    $killSwitch = $this->createMock(KillSwitch::class);
    $killSwitch->expects($this->once())->method('trigger');

    // Mock all dependencies for SdssWgTaggingUtil.
    $http_client = $this->createMock(ClientInterface::class);
    $config_factory = $this->createMock(ConfigFactoryInterface::class);
    $entity_type_manager = $this->createMock(EntityTypeManagerInterface::class);
    $logger = $this->createMock(LoggerInterface::class);
    $logger_factory = $this->createMock(LoggerChannelFactoryInterface::class);
    $logger_factory->method('get')->willReturn($logger);

    // Mock tagPersons() to return a known value.
    $mockUtil = $this->getMockBuilder(SdssWgTaggingUtil::class)
      ->setConstructorArgs([
        $http_client,
        $config_factory,
        $entity_type_manager,
        $logger_factory,
      ])
      ->onlyMethods(['tagPersons'])
      ->getMock();
    $mockUtil->method('tagPersons')->willReturn([
      'status' => ['message' => 'Tagging complete!'],
    ]);

    // Instantiate the real controller with the mocked util.
    $controller = new SdssWgTaggingController($killSwitch, $mockUtil);

    $request = $this->createMock(Request::class);
    $response = $controller->output($request);

    $this->assertEquals(['#markup' => 'Tagging complete!'], $response);
  }
}
