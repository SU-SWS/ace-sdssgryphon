<?php

namespace Drupal\Tests\sdss_profile\Kernel\Plugin\InstallTask;

use Drupal\config_pages\Entity\ConfigPagesType;
use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\KernelTests\KernelTestBase;
use Drupal\user\Entity\Role;
use Drupal\sdss_profile\Plugin\InstallTask\SiteSettings;

/**
 * Class SiteSettingsTest.
 *
 * @coversDefaultClass \Drupal\sdss_profile\Plugin\InstallTask\SiteSettings
 */
class SiteSettingsTest extends KernelTestBase {

  /**
   * {@inheritDoc}
   */
  protected static $modules = [
    'system',
    'config_pages',
    'config_pages_overrides',
    'externalauth',
    'path_alias',
    'user',
    'field',
    'node',
  ];

  /**
   * {@inheritDoc}
   */
  public function setup(): void {
    parent::setUp();
    $this->setInstallProfile('sdss_profile');

    $this->installEntitySchema('user');
    $this->installEntitySchema('user_role');
    $this->installEntitySchema('field_storage_config');
    $this->installEntitySchema('field_config');
    $this->installEntitySchema('config_pages_type');
    $this->installEntitySchema('config_pages');
    $this->installEntitySchema('node');
    $this->installSchema('externalauth', 'authmap');
    $this->installSchema('system', ['sequences']);
    $this->installConfig('system');

    Role::create(['label' => 'Owner', 'id' => "site_manager"])->save();

    $config_page_type = ConfigPagesType::create([
      'id' => 'stanford_basic_site_settings',
      'menu' => [],
      'context' => [],
    ]);
    $config_page_type->setThirdPartySetting('config_pages_overrides', $this->randomMachineName(), [
      'field' => 'su_site_name',
      'delta' => '0',
      'column' => 'value',
      'config_name' => 'system.site',
      'config_item' => 'name',
    ]);
    $config_page_type->setThirdPartySetting('config_pages_overrides', $this->randomMachineName(), [
      'field' => 'su_site_email',
      'delta' => '0',
      'column' => 'value',
      'config_name' => 'system.site',
      'config_item' => 'mail',
    ]);

    $config_page_type->save();

    $field_storage = FieldStorageConfig::create([
      'field_name' => 'su_site_email',
      'entity_type' => 'config_pages',
      'type' => 'email',
    ]);
    $field_storage->save();
    FieldConfig::create([
      'entity_type' => 'config_pages',
      'field_storage' => $field_storage,
      'bundle' => 'stanford_basic_site_settings',
      'label' => 'Email',
    ])->save();

    $field_storage = FieldStorageConfig::create([
      'field_name' => 'su_site_name',
      'entity_type' => 'config_pages',
      'type' => 'string',
    ]);
    $field_storage->save();
    FieldConfig::create([
      'entity_type' => 'config_pages',
      'field_storage' => $field_storage,
      'bundle' => 'stanford_basic_site_settings',
      'label' => 'Name',
    ])->save();

    drupal_flush_all_caches();
  }

  /**
   * Test fields.
   */
  public function testValidInstallTasks() {
    $this->assertNotEquals('foo bar', \Drupal::config('system.site')
      ->get('name'));
    $this->assertEmpty(\Drupal::config('system.site')->get('name'));
    $this->assertEmpty(\Drupal::config('system.site')->get('mail'));
  }

}

/**
 * Test class.
 */
class TestSiteSettings extends SiteSettings {

  /**
   * {@inheritDoc}
   */
  protected static function isAhEnv() {
    return TRUE;
  }

}
