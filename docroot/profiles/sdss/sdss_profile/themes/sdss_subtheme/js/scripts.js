(function ($, Drupal, once) {
  'use strict';
  
  Drupal.behaviors.sdss_subtheme = {
    attach: function (context, settings) {

      // Add search link button to navigation, wrapped in a div for styling.
      $('#block-sdss-subtheme-main-navigation', context).after('<div class="su-site-search__wrapper"><a href="/search" id="sdss-button--search-link" class="su-site-search__submit"><span class="visually-hidden">Search</span></a></div>');

      // Add current path as a drupal redirect destination to saml_login links.
      // Will redirect the user to the current page after logging in.
      $('a[href="/saml_login"', context).attr('href', '/saml/login?destination=' + window.location.pathname);

      $(once('banner-video', '.ds-entity--stanford-banner video', context)).each(function (i) {
        const $video = $(this);
        $video.closest('.ds-entity--stanford-banner').addClass('video-banner');

        const video = this;
        const videoId = `video-${i}`;
        const $playPauseLabel = $('<span>').addClass('visually-hidden');

        const $playPauseButton = $('<button>')
          .html($playPauseLabel)
          .addClass('fas play-pause-button')
          .attr('aria-controls', videoId);

        $playPauseButton.click(() => video.paused ? video.play() : video.pause());
        $($playPauseButton).after($playPauseLabel);

        if (!video.paused) {
          $playPauseButton.addClass('fa-pause');
          $playPauseLabel.text('Pause');
          $playPauseButton.attr('aria-label', 'Pause video');
        } else {
          $playPauseButton.addClass('fa-play');
          $playPauseLabel.text('Play');
          $playPauseButton.attr('aria-label', 'Play video');
        }

        const $container = $('<div>').addClass('video-info')
          .append($playPauseButton);

        $video.attr('id', videoId).after($container);

        $video.on('play', function () {
          $playPauseButton.toggleClass('fa-pause').toggleClass('fa-play');
          $playPauseLabel.text('Pause');
          $playPauseButton.attr('aria-label', 'Pause video');
        }).on('pause', function () {
          $playPauseButton.toggleClass('fa-pause').toggleClass('fa-play');
          $playPauseLabel.text('Play');
          $playPauseButton.attr('aria-label', 'Play video');
        });
      });

      once('view-mode-ajax', '.view-mode-switch a', context).forEach(function (element) {
      $(element).on('click', function (e) {
        e.preventDefault();

        const $view = $(this).closest('.view');
        // Find the dom_id class
        const domIdClass = $view.attr('class').split(' ').find(cls => cls.indexOf('js-view-dom-id-') === 0);
        if (!domIdClass) return;
        
        const domId = domIdClass.replace('js-view-dom-id-', '');
        const url = new URL(this.href, window.location.origin);
        const mode = url.searchParams.get('view_mode_toggle');

        // DEFENSIVE CHECK: Try to find the settings by DOM ID, or fallback to the first available view setting
        let viewSettings = drupalSettings.views.ajaxViews['views_dom_id:' + domId];
        
        if (!viewSettings) {
          // Fallback: search the object for any view matching our view name
          const allViews = drupalSettings.views.ajaxViews;
          const key = Object.keys(allViews).find(k => allViews[k].view_name === 'projects');
          viewSettings = allViews[key];
        }

        if (viewSettings) {
          const ajaxUpdate = Drupal.ajax({
            url: Drupal.url('views/ajax'),
            base: domId,
            element: element,
            submit: {
              view_name: viewSettings.view_name,
              view_display_id: viewSettings.view_display_id,
              view_dom_id: domId,
              view_mode_toggle: mode 
            }
          });

          console.log(ajaxUpdate);

          ajaxUpdate.execute();
        } else {
          console.error('Drupal Views AJAX settings not found for DOM ID: ' + domId);
        }
      });
    });
    },
  };

})(jQuery, Drupal, once);
