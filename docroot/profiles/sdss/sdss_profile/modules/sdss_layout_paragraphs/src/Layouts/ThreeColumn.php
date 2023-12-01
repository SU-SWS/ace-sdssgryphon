<?php

namespace Drupal\sdss_layout_paragraphs\Layouts;

use Drupal\sdss_layout_paragraphs\Plugin\Layout\SdssLayoutParagraphBase;

/**
 * Three column layout class.
 */
class ThreeColumn extends SdssLayoutParagraphBase {

  protected function getWidthOptions() {
    return [
      '100' => '100%',
      // 'offset-100' => 'Offset: 100%',
    ];
  }
}
