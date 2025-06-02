<?php

namespace Drupal\Tests\sdss_workgroup_tagging\Unit;

use Drupal\Core\Config\ImmutableConfig;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\sdss_workgroup_tagging\SdssWgTaggingUtil;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

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
    $config = $this->createMock(ImmutableConfig::class);
    $config_factory = $this->createMock(ConfigFactoryInterface::class);
    $config_factory->method('get')->willReturn($config);

    $logger = $this->createMock(LoggerInterface::class);
    $logger_factory = $this->createMock(LoggerChannelFactoryInterface::class);
    $logger_factory->method('get')->willReturn($logger);    

    $container = new ContainerBuilder();
    $container->set('config.factory', $config_factory);
    $container->set('logger.factory', $logger_factory);    

    \Drupal::setContainer($container);

    $result = SdssWgTaggingUtil::getWgMembers('testgroup');
    $this->assertEquals([], $result['members']);
    $this->assertStringContainsString('credentials have not been set', $result['status']['message']);
  }

}