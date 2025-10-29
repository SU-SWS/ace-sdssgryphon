# Opportunity Migration: Additional Dependencies and Considerations

## Module Dependencies
- The Opportunity content type references the following modules in its configuration:
  - menu_ui
  - node_revision_delete
  - scheduler
- Ensure these modules are enabled in your target site.

## Field References
- All fields listed in the config_files_to_migrate checklist have both field and field storage configuration in the source profile.
- No direct references to vocabularies (taxonomy terms) or media types were found in the field storage config, but you should review field settings for entity reference fields after import.

## Layout Builder and Field Group
- The field configuration includes a layout_builder__layout field, indicating Layout Builder is used. Ensure the Layout Builder module is enabled.
- No field_group or widget-specific configuration files were found, but review form and view display configs for custom widgets or groupings after import.

## Next Steps
- After copying, import configuration and resolve any missing dependencies.
- Review the Opportunity content type in the UI for any missing field widgets, display settings, or referenced entities.
- Update permissions and roles as needed.

---

_This note summarizes additional dependencies and considerations for the Opportunity content type migration._
