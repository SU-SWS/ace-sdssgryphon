<?php

namespace Drupal\Tests\stanford_intranet\Kernel;

use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\KernelTests\KernelTestBase;
use Drupal\node\Entity\NodeType;

/**
 * Intranet Kernel Test Class.
 */
abstract class IntranetKernelTestBase extends KernelTestBase {

  /**
   * @var \Drupal\field\FieldStorageConfigInterface
   */
  protected $fieldStorage;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'system',
    'field',
    'node',
    'file',
    'user',
    'config_pages',
    'stanford_profile_helper',
    'options',
    'image',
    'rabbit_hole',
    'stanford_intranet',
  ];

  /**
   * {@inheritdoc}
   */
  public function setup(): void {
    parent::setUp();
    $this->installEntitySchema('node');
    $this->installEntitySchema('user');
    $this->installEntitySchema('file');
    $this->installEntitySchema('image_style');
    $this->installConfig('system');
    $this->installSchema('system', ['sequences']);
    $this->installSchema('node', ['node_access']);
    $this->installSchema('file', ['file_usage']);

    $this->fieldStorage = FieldStorageConfig::create([
      'field_name' => 'field_foo',
      'entity_type' => 'node',
      'type' => 'file',
    ]);
    $this->fieldStorage->save();

    NodeType::create(['type' => 'page'])->save();

    // Create stanford_intranet__access field because hook_install does not run
    // in Kernel tests.
    $node_types = NodeType::loadMultiple();
    $field_storage = FieldStorageConfig::create([
      'entity_type' => 'node',
      'field_name' => 'stanford_intranet__access',
      'type' => 'entity_access',
      'locked' => TRUE,
    ]);
    $field_storage->save();

    /** @var \Drupal\node\NodeTypeInterface $node_type */
    foreach ($node_types as $node_type) {
      FieldConfig::create([
        'field_storage' => $field_storage,
        'bundle' => $node_type->id(),
        'label' => t('Access'),
      ])->save();
    }
  }

}
