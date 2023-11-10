<?php

namespace Drupal\sdss_layout_paragraphs\Layouts;

use Drupal\sdss_layout_paragraphs\Plugin\Layout\SdssLayoutParagraphBase;

/**
 * Two column layout class.
 */
class TwoColumn extends SdssLayoutParagraphBase {

  /**
   * {@inheritDoc}
   */
  protected function getWidthOptions() {
    return [
      '50-50' => '50% - 50%',
      // 'offset-50-50' => 'Offset: 50% - 50%',
      // '33-67' => '33% - 67%',
      // '67-33' => '67% - 33%',
    ];
  }

}
