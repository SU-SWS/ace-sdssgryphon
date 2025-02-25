<?php

namespace Drupal\sdss_profile\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Install Task annotation.
 *
 * @Annotation
 */
class InstallTask extends Plugin {

  /**
   * Array of tasks that must run before the current plugin.
   *
   * @var array
   */
  protected $dependencies = [];

}
