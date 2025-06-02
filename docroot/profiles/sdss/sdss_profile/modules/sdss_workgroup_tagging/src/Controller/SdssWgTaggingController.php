<?php

namespace Drupal\sdss_workgroup_tagging\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\PageCache\ResponsePolicy\KillSwitch;
use Drupal\sdss_workgroup_tagging\SdssWgTaggingUtil;

/**
 * Tag imported person content using workgroup membership.
 */
class SdssWgTaggingController extends ControllerBase {

    /**
   * Page cache kill switch.
   *
   * @var Drupal\Core\PageCache\ResponsePolicy\KillSwitch
   *   The kill switch service.
   */
  protected $killSwitch;

  protected $taggingUtil;

  /**
   * StanfordEarthExportNewsController constructor.
   */
  public function __construct(KillSwitch $killSwitch, SdssWgTaggingUtil $taggingUtil) {
    $this->killSwitch = $killSwitch;
    $this->taggingUtil = $taggingUtil;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('page_cache_kill_switch'),
      $container->get('sdss_workgroup_tagging.util')
    );
  }

  /**
   * Returns stuff.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The currently processing request.
   */
  public function output(Request $request) {
    $result = $this->taggingUtil->tagPersons();
    if (
      $result
      && $result['status']
    ) {
      $response = ['#markup' => $result['status']['message']];
    }
    else {
      $response = ['#markup' => 'Error!'];
    }
    $this->killSwitch->trigger();
    return $response;
  }

}
