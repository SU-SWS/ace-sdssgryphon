(function ($, Drupal, once) {
  'use strict';
  Drupal.behaviors.sdss_subtheme = {
    attach: function (context) {

      // Add search link button to navigation.
      $('#block-sdss-subtheme-main-navigation', context).after('<a href="/search" id="sdss-button--search-link" class="su-site-search__submit"><span class="visually-hidden">Search</span></a>');

      // Add current path as a drupal redirect desitnation to saml_login links.
      // Will redirect the user to the current page after logging in.
      $('a[href="/saml_login"', context).attr('href', '/saml/login?destination=' + window.location.pathname);

      $(once('banner-video', '.ds-entity--stanford-banner video', context)).each(function (i) {
        const video = this;
        const videoId = `video-${i}`;
        const $playPauseLabel = $('<span>').addClass('visually-hidden');

        const $playPauseButton = $('<button>')
          .html($playPauseLabel)
          .addClass('fas play-pause-button')
          .attr('aria-controls', videoId)
          .attr('aria-describedby', `${videoId}-description`)
          .click(function () {
            const $button = $(this);
            if (video.paused) {
              $button.toggleClass('fa-pause').toggleClass('fa-play');
              $playPauseLabel.text('Pause');
              video.play();
            } else {
              $button.toggleClass('fa-pause').toggleClass('fa-play');
              $playPauseLabel.text('Play');
              video.pause();
            }
          });
        $($playPauseButton).after($playPauseLabel);

        if ($(video).attr('autoplay') === 'autoplay') {
          $playPauseButton.addClass('fa-pause');
          $playPauseLabel.text('Pause');
        } else {
          $playPauseButton.addClass('fa-play');
          $playPauseLabel.text('Play');
        }

        const $container = $('<div>').addClass('video-info')
          .append($playPauseButton)
          .append($('<p>').attr('id', `${videoId}-description`).text('This video does not contain audio'));

        $(video).attr('id', videoId).after($container);
      });
    },
  };

})(jQuery, Drupal, once);
