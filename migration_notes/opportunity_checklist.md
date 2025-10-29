# Opportunity Content Type Migration Checklist

This checklist will help you bring the Opportunity content type and its related functionality from the `stanford_profile` (and possibly `stanford_profile_helper`) into the `ace-sdssgryphon` codebase.

---

## 1. Content Type Configuration
- Copy `node.type.stanford_opportunity.yml` and any related config (e.g., field configs, field storage, display/view modes) into the appropriate config location in `ace-sdssgryphon`.
- Ensure dependencies (`menu_ui`, `node_revision_delete`, `scheduler`) are enabled in your target site.

## 2. Field Configuration
- Export/copy all `field.field.node.stanford_opportunity.*.yml` and `field.storage.node.stanford_opportunity.*.yml` files.
- Copy any field group, display, or form display configuration.

## 3. Permissions and Roles
- Check for any permissions related to the Opportunity content type in `stanford_profile.permissions.yml` or custom code.
- Update roles/permissions in `ace-sdssgryphon` as needed.

## 4. Custom Code
- Look for custom code in `stanford_profile` and `stanford_profile_helper` that references the Opportunity content type:
  - Event subscribers, hooks, plugins, or services that interact with `stanford_opportunity`.
  - Any custom forms, blocks, or controllers.
- Compare with the `sdss_profile` to avoid conflicts or duplications.

## 5. Helper Module
- If `stanford_profile_helper` provides features for Opportunity, check if you need to port or adapt these to `ace-sdssgryphon`.
- If `sdss_profile` has a customized helper, compare and decide if you need to merge or override functionality.

## 6. Menus, Taxonomies, and Relationships
- If the Opportunity content type uses specific menus, vocabularies, or entity references, ensure these are also brought over.

## 7. Theming and Templates
- Check for any custom templates or theme suggestions for Opportunity nodes in `stanford_profile` or its themes.
- Copy or adapt these as needed.

## 8. Tests
- If there are automated tests (e.g., in `tests/` or `codeception/`), consider bringing these over to ensure the content type works as expected.

## 9. Update Documentation
- Update any README or documentation in `ace-sdssgryphon` to reflect the addition of the Opportunity content type.

---

## Summary Table

| Area                | What to Copy/Check                                              |
|---------------------|----------------------------------------------------------------|
| Content Type        | `node.type.stanford_opportunity.yml` and related config         |
| Fields              | Field config/storage, display, form display                     |
| Permissions         | Permissions config and custom code                              |
| Custom Code         | Hooks, plugins, services, event subscribers, forms, blocks      |
| Helper Module       | Features in `stanford_profile_helper` and compare with SDSS     |
| Menus/Taxonomies    | Menu, taxonomy, entity reference config                         |
| Theming             | Templates, theme suggestions                                   |
| Tests               | Automated tests for Opportunity                                |
| Documentation       | README, usage docs                                             |

---

### Next Steps
- Inventory all related config and code in `stanford_profile` and `stanford_profile_helper` for the Opportunity content type.
- Compare with what’s already in `ace-sdssgryphon` (especially `sdss_profile`).
- Plan for any necessary adaptations due to differences between Stanford and SDSS profiles.

---

_Reference this checklist as you proceed with the migration._

---

**Note:** The correct configuration sync directory for the sdss_profile in this repository is:
`docroot/profiles/sdss/sdss_profile/config/sync`

All configuration files for the Opportunity content type should be copied here, not to the root-level config/sync or config/sync/sdss_profile folders.
