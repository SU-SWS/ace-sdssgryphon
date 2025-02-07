<?php

namespace Drupal\stanford_profile_helper\Config;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Config\ConfigFactoryOverrideInterface;
use Drupal\Core\Config\StorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\State\StateInterface;
use Drupal\config_pages\ConfigPagesLoaderServiceInterface;
use Drupal\Core\StreamWrapper\StreamWrapperManagerInterface;

/**
 * Config overrides for stanford profile.
 *
 * @package Drupal\stanford_profile_helper\Config
 */
class ConfigOverrides implements ConfigFactoryOverrideInterface {

  /**
   * State service.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;

  /**
   * Config pages loader service.
   *
   * @var \Drupal\config_pages\ConfigPagesLoaderServiceInterface
   */
  protected $configPagesLoader;

  /**
   * Config factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Config Entity Type Manager Interface.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * StreamWrapperInterface service.
   *
   * @var \Drupal\Core\StreamWrapper\StreamWrapperManagerInterface
   */
  protected $streamWrapperManager;

  /**
   * ConfigOverrides constructor.
   *
   * @param \Drupal\Core\State\StateInterface $state
   *   State service.
   * @param \Drupal\config_pages\ConfigPagesLoaderServiceInterface $config_pages_loader
   *   Config pages service.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   Config factory service.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   Entity type manager interface.
   * @param \Drupal\Core\StreamWrapper\StreamWrapperManagerInterface $stream_wrapper_manager
   *   Stream wrapper manager interface.
   */
  public function __construct(StateInterface $state, ConfigPagesLoaderServiceInterface $config_pages_loader, ConfigFactoryInterface $config_factory, EntityTypeManagerInterface $entity_type_manager, StreamWrapperManagerInterface $stream_wrapper_manager) {
    $this->state = $state;
    $this->configPagesLoader = $config_pages_loader;
    $this->configFactory = $config_factory;
    $this->entityTypeManager = $entity_type_manager;
    $this->streamWrapperManager = $stream_wrapper_manager;
  }

  /**
   * {@inheritDoc}
   */
  public function loadOverrides($names) {
    $overrides = [];

    // Theme settings override.
    $this->setRolePermissionOverrides($names, $overrides);
    $this->setMainMenuOverrides($names, $overrides);
    // Overrides.
    return $overrides;
  }

  /**
   * Add permissions to the role configs.
   *
   * @param array $names
   *   Array of config names.
   * @param array $overrides
   *   Keyed array of config overrides.
   */
  protected function setRolePermissionOverrides(array $names, array &$overrides) {
    if (in_array('user.role.site_manager', $names)) {
      // Arbitrary number that should be larger than the original permission
      // count. This allows the functionality to ADD permissions but not have
      // any effect on existing permissions. If we don't have this number high
      // enough, it will replace permissions instead of adding them.
      $counter = 500;
      foreach (array_keys($this->state->get('stanford_intranet.rids', [])) as $role_id) {
        // We only care about the custom roles.
        if (!str_starts_with($role_id, 'custm_')) {
          continue;
        }
        $overrides['user.role.site_manager']['permissions'][$counter] = "assign $role_id role";
        $counter++;
      }
    }
  }

  /**
   * Add main menu config overrides.
   *
   * @param array $names
   *   Array of config names.
   * @param array $overrides
   *   Keyed array of config overrides.
   */
  protected function setMainMenuOverrides(array $names, array &$overrides) {
    foreach ($names as $name) {
      if (str_starts_with($name, 'block.block.')) {
        $block_plugin = $this->configFactory->getEditable($name)
          ->getOriginal('plugin', FALSE);
        $region = $this->configFactory->getEditable($name)
          ->getOriginal('region', FALSE);

        if ($block_plugin == 'system_menu_block:main' && $region == 'menu') {
          $menu_depth = (int) $this->configPagesLoader->getValue('stanford_basic_site_settings', 'su_site_menu_levels', 0, 'value');
          if ($menu_depth >= 1) {
            $overrides[$name]['settings']['depth'] = $menu_depth;
          }
        }
      }
    }
  }

  /**
   * {@inheritDoc}
   */
  public function createConfigObject($name, $collection = StorageInterface::DEFAULT_COLLECTION) {
    return NULL;
  }

  /**
   * {@inheritDoc}
   */
  public function getCacheSuffix() {
    return 'StanfordProfileHelperConfigOverride';
  }

  /**
   * {@inheritDoc}
   */
  public function getCacheableMetadata($name) {
    return new CacheableMetadata();
  }

}
