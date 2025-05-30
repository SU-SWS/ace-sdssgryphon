<?php

namespace Drupal\Tests\sdss_workgroup_tagging\Unit;

use Drupal\sdss_workgroup_tagging\SdssWgTaggingUtil;
use PHPUnit\Framework\TestCase;

/**
 * Basic tests for SDSS Workgroup Tagging module.
 *
 * @coversDefaultClass \Drupal\sdss_workgroup_tagging\SdssWgTaggingUtil
 * @group sdss_workgroup_tagging
 */
class WorkgroupTaggingTest extends TestCase {

  /**
   * Test getWgMembers() returns empty array if no cert/key.
   */
  public function testGetWgMembersNoCert() {
    // Mock the configFactory and logger.
    $config = $this->createMock(\Drupal\Core\Config\ImmutableConfig::class);
    $config->method('get')->willReturn(null);

    $configFactory = $this->createMock(\Drupal\Core\Config\ConfigFactoryInterface::class);
    $configFactory->method('get')->willReturn($config);

    $logger = $this->createMock(\Psr\Log\LoggerInterface::class);

    // Inject mocks into Drupal static service container.
    $container = new \Drupal\Core\DependencyInjection\ContainerBuilder();
    $container->set('config.factory', $configFactory);
    $container->set('logger.factory', $this->createMock(\Drupal\Core\Logger\LoggerChannelFactoryInterface::class));
    \Drupal::setContainer($container);

    $result = SdssWgTaggingUtil::getWgMembers('testgroup');
    $this->assertEquals([], $result['members']);
    $this->assertStringContainsString('credentials have not been set', $result['status']['message']);
  }

}