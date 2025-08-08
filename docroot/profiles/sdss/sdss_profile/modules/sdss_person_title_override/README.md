# SDSS Person Title Override

This module provides computed fields for Stanford Person nodes, allowing display of alternative or original title values based on business logic. It includes:

- `sdss_computed_full_title`: Displays the alternative title if set, otherwise the full title.
- `sdss_computed_short_title`: Displays the alternative title if set, otherwise the short title.
- A custom field formatter (`Alt Title Override`) for both computed fields.
- Centralized override logic via the `SdssPersonTitleService`.

## Features
- Computed fields are available for display configuration in Manage Display, Layout Builder and Views.
- Fields do not store data in the database; values are generated dynamically.
- Service-based logic for maintainability and consistency.

## Installation
1. Enable the module via the Drupal admin UI or with Drush:
   ```
   drush en sdss_person_title_override
   ```
2. Add the computed fields to your desired displays (Manage Display, Layout Builder, Views) for the `stanford_person` content type.
3. Select the `Alt Title Override` formatter if needed.

## Uninstalling
**Important:** Before uninstalling this module, manually remove the computed fields (`sdss_computed_full_title`, `sdss_computed_short_title`) from all displays, Layout Builder sections, and Views. Failure to do so may result in broken blocks, fields, or errors in the UI after uninstall.

Recommended uninstall steps:
1. Remove the computed fields from all entity displays, Layout Builder, and Views.
2. Clear caches:
   ```
   drush cr
   ```
3. Uninstall the module:
   ```
   drush pmu sdss_person_title_override
   ```

## Troubleshooting
- If you see broken blocks or fields after uninstall, manually edit the affected displays or views to remove references to the computed fields.

## Maintainers
- SU-SWS Drupal Team

## License
- GPL-2.0-or-later
