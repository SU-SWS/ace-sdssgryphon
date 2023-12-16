<?php

namespace Drupal\sdss_layout_paragraphs\Layouts;

use Drupal\sdss_layout_paragraphs\Plugin\Layout\SdssLayoutParagraphBase;

/**
 * Four column layout class.
 */
class FourColumn extends SdssLayoutParagraphBase {

  protected function getWidthOptions() {
    return [
      '25-25-25-25' => '25%',
    ];
  }
}
