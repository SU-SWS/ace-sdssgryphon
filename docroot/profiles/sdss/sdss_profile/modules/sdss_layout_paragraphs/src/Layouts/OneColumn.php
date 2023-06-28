<?php

namespace Drupal\sdss_layout_paragraphs\Layouts;

use Drupal\layout_builder\Plugin\Layout\MultiWidthLayoutBase;


/**
 * One column layout class.
 */
class OneColumn extends MultiWidthLayoutBase {

  protected function getWidthOptions() {
    return [
      '100' => '100%',
      'offset-100' => 'Offset: 100%',
    ];
  }
}
