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

      /**
       * Open and close on the Newsroom menu
       */

      const newsMenus = $('.sdss-newsroom--menu .menu-item--expanded > a');

      $('.menu-item--expanded > a').on('click', function(e) {
        e.preventDefault();

        if (newsMenus.hasClass('active')) {
          newsMenus.removeClass('active');
        }
        else {
          $(this).toggleClass('active');
        }


        // $(newsMenus).removeClass('active');

      });

// Mobile menu setup.
var overlayMenu =
    '<div class="menu-overlay" role="dialog" aria-labelledby="d-title" aria-describedby="description">' +

    '<p id="dislaimer-intro">Please read the Disclaimer below and indicate your acceptance before.</p>' +

    '<h2 id="d-title"><strong>Menu</strong></h2>' +
    '</div>'

      $('.newsroom-mobile--btn').on('click', function(e) {
        e.preventDefault();

        var mobileMenu = $("#block-newsresearch .sdss-newsroom--menu");

        // $(overlayMenu).insertBefore('#page-content').attr( 'tabindex', '-1');

        $('body').toggleClass('mobile-menu');

      });

    })(jQuery);
  },

  // Detach Example.
  detach() {
    // console.log("Detached.");
  }
};
