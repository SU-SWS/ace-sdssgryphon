(function ($, Drupal, once) {
    'use strict';

    Drupal.behaviors.sdss_grid_list_toggle = {
        attach: function (context, settings) {

            $(once('grid-list-toggle', '.project-grid-header .toggle-icons', context)).each(function () {
                const $toggleIcons = $(this);
                const $view = $toggleIcons.closest('.view');

                // Load icons from module.
                const modulePath = settings.sdss_grid_list_toggle && settings.sdss_grid_list_toggle.module_path ? settings.sdss_grid_list_toggle.module_path : '/profiles/sdss/sdss_profile/modules/sdss_grid_list_toggle';
                const gridIcon = modulePath + '/img/toggle-grid.svg';
                const listIcon = modulePath + '/img/toggle-list.svg';

                // Inject the buttons.
                const $gridButton = $('<button type="button" class="toggle-grid" aria-label="Switch to List View"><img src="' + gridIcon + '" alt="" aria-hidden="true"></button>');
                const $listButton = $('<button type="button" class="toggle-list" aria-label="Switch to Grid View"><img src="' + listIcon + '" alt="" aria-hidden="true"></button>');

                $toggleIcons.append($gridButton).append($listButton);

                $gridButton.on('click', function () {
                    $view.find('input[name="grid_list_toggle"][value="list"]').click();
                });

                $listButton.on('click', function () {
                    $view.find('input[name="grid_list_toggle"][value="grid"]').click();
                });
            });

        },
    };

})(jQuery, Drupal, once);
