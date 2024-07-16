<?php

namespace Drupal\sdss_news_sharing\Overrides;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Config\ConfigFactoryOverrideInterface;
use Drupal\Core\Config\StorageInterface;

/**
 * Class ConfigOverrides.
 *
 * @package Drupal\sdss_news_sharing\Overrides
 */
class ConfigOverrides implements ConfigFactoryOverrideInterface {

  /**
   * Config factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * ConfigOverrides constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   Config factory service.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   *
   * Override
   */
  public function loadOverrides($names) {
    $overrides = [];

    if (!in_array('migrate_plus.migration.sdss_news_sharing', $names)) {
      return $overrides;
    }

    $config = $this->configFactory->get('sdss_news_sharing.settings');
    if ($urls = $config->get('urls')) {
      $overrides['migrate_plus.migration.sdss_news_sharing'] = [
        'source' => [
          'urls' => array_values($urls),
        ],
      ];
    }

    if ($status = $config->get('status')) {
      $overrides['migrate_plus.migration.sdss_news_sharing'] = [
        'status' => $status,
      ];
    }

    return $overrides;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheSuffix() {
    return 'ConfigOverrider';
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheableMetadata($name) {
    return new CacheableMetadata();
  }

  /**
   * {@inheritdoc}
   */
  public function createConfigObject($name, $collection = StorageInterface::DEFAULT_COLLECTION) {
    return NULL;
  }
}
