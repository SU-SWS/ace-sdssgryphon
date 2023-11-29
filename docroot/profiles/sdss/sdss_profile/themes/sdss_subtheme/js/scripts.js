(function ($, Drupal, once) {
  'use strict';
  Drupal.behaviors.sdss_subtheme = {
    attach: function (context, settings) {
      // Add search link button to navigation.
      $('#block-sdss-subtheme-main-navigation').after('<a href="/search" id="sdss-button--search-link" class="su-site-search__submit"><span class="visually-hidden">Search</span></a>');
    }
  };

})(jQuery, Drupal, once);
