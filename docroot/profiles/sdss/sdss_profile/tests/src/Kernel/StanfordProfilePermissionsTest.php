<?php

namespace Drupal\Tests\sdss_profile\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\layout_builder\Entity\LayoutBuilderEntityViewDisplay;
use Drupal\node\Entity\NodeType;
use Drupal\sdss_profile\StanfordProfilePermissions;

/**
 * Class StanfordProfilePermissionsTest.
 *
 * @group sdss_profile
 * @coversDefaultClass \Drupal\sdss_profile\StanfordProfilePermissions
 */
class StanfordProfilePermissionsTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'system',
    'node',
    'layout_discovery',
    'layout_builder',
    'layout_library',
    'path_alias',
    'user',
    'field',
  ];

  /**
   * {@inheritdoc}
   */
  public function setup(): void {
    parent::setUp();

    $this->installEntitySchema('user');
    $this->installEntitySchema('node');
    $this->installEntitySchema('entity_view_display');

    NodeType::create(['type' => 'article', 'name' => 'Article'])->save();
    /** @var \Drupal\Core\Entity\Display\EntityViewDisplayInterface $display */
    $display = LayoutBuilderEntityViewDisplay::create([
      'targetEntityType' => 'node',
      'bundle' => 'article',
      'mode' => 'default',
      'status' => TRUE,
    ])
      ->enableLayoutBuilder()
      ->setOverridable();
    $display->setThirdPartySetting('layout_library', 'enable', TRUE);
    $display->save();
  }

  /**
   * Test permissions are returned.
   */
  public function testPermissions() {
    $permission_class = StanfordProfilePermissions::create(\Drupal::getContainer());
    $permissions = $permission_class->permissions();
    $this->assertCount(1, $permissions);
    $this->assertArrayHasKey('choose layout for node article', $permissions);
  }

}
