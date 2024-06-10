<?php

namespace Drupal\sdss_layout_paragraphs\Layouts;

use Drupal\sdss_layout_paragraphs\Plugin\Layout\SdssLayoutParagraphBase;

/**
 * Two column layout class.
 */
class TwoColumn extends SdssLayoutParagraphBase {
    protected function getWidthOptions() {
        return [
          '50-50' => 'Equal Columns',
          '33-67' => 'Larger Right Column',
          '67-33' => 'Larger Left Column',
        ];
      }
}
