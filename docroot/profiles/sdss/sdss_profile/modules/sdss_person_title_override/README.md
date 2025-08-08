# SDSS Person Title Override

This module provides a custom field formatter for Stanford Person title fields, allowing display of alternative or original title values based on business logic.

## Features
- **PersonTitleOverrideFormatter**: A field formatter for `su_person_full_title` and `su_person_short_title` fields.
- Uses centralized logic via the `SdssPersonTitleService` to determine which value to display (alt title, full title, or short title).
- Formatter automatically detects the field and applies the correct override logic.
- If used on any other field, a warning is shown and the default field value is used.

## Installation
1. Enable the module via the Drupal admin UI or with Drush:
   ```
   drush en sdss_person_title_override
   ```
2. Go to Manage Display or Layout Builder for your `stanford_person` content type.
3. For the `su_person_full_title` or `su_person_short_title` field, select the **Person Title Override** formatter.

The formatter is also available for views fields.

## Usage Notes
- The formatter is available for all string and text fields, but is intended only for the two title fields above.
- If applied to other fields, a warning will be shown and the default value will be used.
- No custom field types or computed fields are provided by this module.

## Uninstalling
- Uninstalling the module will remove the formatter, but will not affect your fields or displays.
- If you used the formatter on other fields, you may need to manually change the formatter after uninstall.

## Maintainers
- SU-SWS Drupal Team

## License
- GPL-2.0-or-later
