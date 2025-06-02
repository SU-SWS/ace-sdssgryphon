<?php

namespace Drupal\Tests\sdss_workgroup_tagging\Unit;

use Drupal\Core\Config\ImmutableConfig;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\sdss_workgroup_tagging\SdssWgTaggingUtil;
use GuzzleHttp\ClientInterface;
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

    $entity_type_manager = $this->createMock(EntityTypeManagerInterface::class);
    $logger = $this->createMock(LoggerInterface::class);
    $logger_factory = $this->createMock(LoggerChannelFactoryInterface::class);
    $logger_factory->method('get')->willReturn($logger);
    $http_client = $this->createMock(ClientInterface::class);   

    $wg_tagging_util = new SdssWgTaggingUtil(
      $http_client,
      $config_factory,
      $entity_type_manager,
      $logger_factory
    );
    $result = $wg_tagging_util->getWgMembers('testgroup');
    $this->assertEquals([], $result['members']);
    $this->assertStringContainsString('credentials have not been set', $result['status']['message']);
  }

  /**
   * Test tagPersons() is disabled.
   */
  public function testTagPersonsDisabled() {
    $config = $this->createMock(ImmutableConfig::class);
    $config->method('get')->willReturnMap([
        ['enabled', FALSE],
    ]);
    $config_factory = $this->createMock(ConfigFactoryInterface::class);
    $config_factory->method('get')->willReturn($config);

    $entity_type_manager = $this->createMock(EntityTypeManagerInterface::class);
    $logger = $this->createMock(LoggerInterface::class);
    $logger_factory = $this->createMock(LoggerChannelFactoryInterface::class);
    $logger_factory->method('get')->willReturn($logger);
    $http_client = $this->createMock(ClientInterface::class);

    $wg_tagging_util = new SdssWgTaggingUtil(
      $http_client,
      $config_factory,
      $entity_type_manager,
      $logger_factory
    );
    $result = $wg_tagging_util->tagPersons();
    $this->assertStringContainsString('disabled', strtolower($result['status']['message']));
  }

}