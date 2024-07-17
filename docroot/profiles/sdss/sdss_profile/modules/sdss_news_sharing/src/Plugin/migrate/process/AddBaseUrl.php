<?php

namespace Drupal\sdss_news_sharing\Plugin\migrate\process;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

/**
 * Add the base url from the current feed url to a relative URL path value.
 *
 * Examples:
 *
 * @code
 * process:
 *   plugin: add_base_url
 *   source: some_relative_url
 * @endcode
 *
 * @MigrateProcessPlugin(
 *   id = "add_base_url"
 * )
 */
class AddBaseUrl extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    $url = $value;
    if ($feed_url = $row->get('current_feed_url')) {
      $url_info = parse_url($feed_url);
      $base_url = $url_info['scheme'] . '://' . $url_info['host'];
      $url = $base_url . $url;
    }
    return $url;
  }
}
