(function ($, Drupal) {
  'use strict';
  Drupal.behaviors.sdss_subtheme = {
    attach: function (context) {

      // Add current path as a drupal redirect desitnation to saml_login links.
      // Will redirect the user to the current page after logging in.
      $('a[href="/saml_login"', context).attr("href", "/saml/login?destination=" + window.location.pathname);

    }
  };

})(jQuery, Drupal);
