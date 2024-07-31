(function ($, Drupal) {
  'use strict';
  Drupal.behaviors.sdss_subtheme = {
    attach: function (context) {

      // Add search link button to navigation.
      $('#block-sdss-subtheme-main-navigation', context).after('<a href="/search" id="sdss-button--search-link" class="su-site-search__submit"><span class="visually-hidden">Search</span></a>');

      // Add current path as a drupal redirect desitnation to saml_login links.
      // Will redirect the user to the current page after logging in.
      $('a[href="/saml_login"', context).attr("href", "/saml/login?destination=" + window.location.pathname);

    }
  };

})(jQuery, Drupal);
