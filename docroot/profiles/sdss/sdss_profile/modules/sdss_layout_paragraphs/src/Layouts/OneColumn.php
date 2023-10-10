<?php

namespace Drupal\sdss_layout_paragraphs\Layouts;

use Drupal\sdss_layout_paragraphs\Plugin\Layout\SdssLayoutParagraphBase;


/**
 * One column layout class.
 */
class OneColumn extends SdssLayoutParagraphBase {

  protected function getWidthOptions() {
    return [
      '100' => '100%',
      'offset-100' => 'Offset: 100%',
    ];
  }
}
