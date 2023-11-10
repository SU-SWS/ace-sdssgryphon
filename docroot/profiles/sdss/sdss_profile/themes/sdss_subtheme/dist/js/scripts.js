/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
/**
 * Behavior Example that works with Webpack.
 *
 * @see: https://www.npmjs.com/package/drupal-behaviors-loader
 *
 * Webpack wraps everything in enclosures and hides the global variables from
 * scripts so special handling is needed.
 */

/* unused harmony default export */ var __WEBPACK_DEFAULT_EXPORT__ = ({
  // Attach Drupal Behavior.
  attach: function attach(context, settings) {
    // console.log("Attached.");

    (function ($) {
      // Add search link button to navigation.
      $('#block-sdss-subtheme-main-navigation').after('<a href="/search" id="sdss-button--search-link" class="su-site-search__submit"><span class="visually-hidden">Search</span></a>');
    })(jQuery);
  },
  // Detach Example.
  detach: function detach() {
    // console.log("Detached.");
  }
});
/******/ })()
;