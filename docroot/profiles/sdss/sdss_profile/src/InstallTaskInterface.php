<?php

namespace Drupal\sdss_profile;

/**
 * Install task plugin interface.
 *
 * @package Drupal\sdss_profile
 */
interface InstallTaskInterface {

  /**
   * Perform some install tasks.
   *
   * @param array $install_state
   *   Current install state.
   *
   * @see hook_install_tasks()
   */
  public function runTask(array &$install_state);

}
