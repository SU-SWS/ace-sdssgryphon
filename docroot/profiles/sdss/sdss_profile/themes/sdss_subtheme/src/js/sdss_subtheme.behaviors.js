/**
 * Behavior Example that works with Webpack.
 *
 * @see: https://www.npmjs.com/package/drupal-behaviors-loader
 *
 * Webpack wraps everything in enclosures and hides the global variables from
 * scripts so special handling is needed.
 */

 export default {

  // Attach Drupal Behavior.
  attach (context, settings) {
    // console.log("Attached.");

    (function ($) {

      $('.su-brand-bar,.su-masthead').wrapAll('<div class="fixed-header">');

      // Moving around classes for header display
      var sdss_logo_classes = $('#block-sdss-subtheme-branding').attr('class');
      $('.fixed-header').addClass(sdss_logo_classes);

    })(jQuery);
  },

  // Detach Example.
  detach() {
    // console.log("Detached.");
  }
};
