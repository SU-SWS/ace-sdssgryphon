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

      // Adding extra tag line if lockup in a or b.
      var option_a = $( "#block-sdss-subtheme-branding.su-lockup--option-a" ).length;
      var option_b = $( "#block-sdss-subtheme-branding.su-lockup--option-b" ).length;

      if (option_a || option_b) {
        $('.su-brand-bar__container').append('<span><a class="sdss-brand-bar__text" href="https://stanford.edu">Stanford</a>  <a class="sdss-brand-bar__text" href="https://sustainability.stanford.edu/">Doerr School of Sustainability</a></span>');
      }

    })(jQuery);
  },

  // Detach Example.
  detach() {
    // console.log("Detached.");
  }
};
