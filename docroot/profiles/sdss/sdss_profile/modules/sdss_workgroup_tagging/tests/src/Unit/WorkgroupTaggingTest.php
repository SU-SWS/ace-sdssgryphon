<?php

namespace Drupal\Tests\sdss_workgroup_tagging\Unit;

use Drupal\Core\Config\ImmutableConfig;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\sdss_workgroup_tagging\SdssWgTaggingUtil;
use Drupal\Tests\UnitTestCase;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\StreamInterface;
use Psr\Log\LoggerInterface;

/**
 * Basic tests for SDSS Workgroup Tagging module.
 *
 * @coversDefaultClass \Drupal\sdss_workgroup_tagging\SdssWgTaggingUtil
 * @group sdss_workgroup_tagging
 */
class WorkgroupTaggingTest extends UnitTestCase {

  /**
   * Helper to instantiate SdssWgTaggingUtil with mocked dependencies.
   *
   * @param \GuzzleHttp\ClientInterface|null $http_client
   *   The HTTP client mock.
   * @param \Drupal\Core\Config\ConfigFactoryInterface|null $config_factory
   *   The config factory mock.
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface|null $entity_field_manager
   *   The entity field manager mock.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface|null $entity_type_manager
   *   The entity type manager mock.
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface|null $logger_factory
   *   The logger factory mock.
   *
   * @return \Drupal\sdss_workgroup_tagging\SdssWgTaggingUtil
   *   The utility instance.
   */
  protected function getUtil(
    $http_client = null,
    $config_factory = null,
    $entity_field_manager = null,
    $entity_type_manager = null,
    $logger_factory = null
  ) {
    if (!$http_client) {
      $http_client = $this->createMock(ClientInterface::class);
    }
    if (!$config_factory) {
      $config_factory = $this->createMock(ConfigFactoryInterface::class);
    }
    if (!$entity_field_manager) {
      $entity_field_manager = $this->createMock(EntityFieldManagerInterface::class);
    }
    if (!$entity_type_manager) {
      $entity_type_manager = $this->createMock(EntityTypeManagerInterface::class);
    }
    if (!$logger_factory) {
      $logger_factory = $this->createMock(LoggerChannelFactoryInterface::class);
      $logger = $this->createMock(LoggerInterface::class);
      $logger_factory->method('get')->willReturn($logger);
    }
    return new SdssWgTaggingUtil(
      $http_client,
      $config_factory,
      $entity_field_manager,
      $entity_type_manager,
      $logger_factory,
    );
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

    $util = $this->getUtil(null, $config_factory, null, null, null);
    $result = $util->tagPersons();
    $this->assertStringContainsString('disabled', strtolower($result['status']['message']));
  }
    
  /**
   * Test getWgMembers() returns empty array if no cert/key.
   */
  public function testGetWgMembersNoCert() {
    $config = $this->createMock(ImmutableConfig::class);
    $config->method('get')->willReturnMap([
      ['role_mapping.workgroup_api.cert', null],
      ['role_mapping.workgroup_api.key', null],
    ]);
    $config_factory = $this->createMock(ConfigFactoryInterface::class);
    $config_factory->method('get')->willReturn($config);

    $util = $this->getUtil(null, $config_factory, null, null, null);
    $result = $util->getWgMembers('testgroup');
    $this->assertEquals([], $result['members']);
    $this->assertStringContainsString('credentials have not been set', $result['status']['message']);
  }

  /**
   * Test getWgMembers() returns members array on success.
   */
  public function testGetWgMembersSuccess() {
    $xml = <<<XML
      <root>
        <members>
          <member id="jsmith" name="John Smith"/>
          <member id="adoe" name="Alice Doe"/>
        </members>
      </root>
    XML;
    $cert_path = tempnam(sys_get_temp_dir(), 'cert');
    $key_path = tempnam(sys_get_temp_dir(), 'key');
    file_put_contents($cert_path, 'dummy');
    file_put_contents($key_path, 'dummy');

    $config = $this->createMock(ImmutableConfig::class);
    $config->method('get')->willReturnMap([
      ['role_mapping.workgroup_api.cert', $cert_path],
      ['role_mapping.workgroup_api.key', $key_path],
    ]);
    $config_factory = $this->createMock(ConfigFactoryInterface::class);
    $config_factory->method('get')->willReturn($config);

    // Mock the HTTP client to return the XML response.
    $stream = $this->createMock(StreamInterface::class);
    $stream->method('__toString')->willReturn($xml);
    $stream->method('getContents')->willReturn($xml);

    $response = $this->createMock(Response::class);
    $response->method('getStatusCode')->willReturn(200);
    $response->method('getBody')->willReturn($stream);

    $http_client = $this->createMock(ClientInterface::class);
    $http_client->method('request')->willReturn($response);

    // Mock is_file to always return true for cert/key.
    $util = $this->getUtil($http_client, $config_factory, null, null, null);
    // Use runkit or uopz to mock is_file if needed, or refactor code for testability.

    $result = $util->getWgMembers('testgroup');
    $this->assertEquals(['jsmith' => 'John Smith', 'adoe' => 'Alice Doe'], $result['members']);
    $this->assertEquals('okay', $result['status']['message']);

    // Clean up temp files
    unlink($cert_path);
    unlink($key_path);
  }

  /**
   * Test tagPersons() when su_person_sunetid field is missing.
   */
  public function testTagPersonsNoFields() {
    // Simulate missing su_person_sunetid field.
    $config = $this->createMock(ImmutableConfig::class);
    $config->method('get')->willReturnMap([
      ['enabled', TRUE],
    ]);
    $config_factory = $this->createMock(ConfigFactoryInterface::class);
    $config_factory->method('get')->willReturn($config);

    $entity_type_manager = $this->createMock(EntityTypeManagerInterface::class);

    $entity_type_manager = $this->createMock(EntityTypeManagerInterface::class);
    $entity_field_manager = $this->createMock(EntityFieldManagerInterface::class);
    $entity_field_manager->method('getFieldDefinitions')
      ->with('node', 'stanford_person')->willReturn([]);

    $util = $this->getUtil(null, $config_factory, $entity_field_manager, $entity_type_manager, null);
    $result = $util->tagPersons();
    $this->assertStringContainsString('missing su_person_sunetid', strtolower($result['status']['message']));
  }

}