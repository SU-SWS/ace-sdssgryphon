# Opportunity Content Type and Fields: Migration Notes

## What to Copy
- Content type definition: `node.type.stanford_opportunity.yml`
- All field configuration: `field.field.node.stanford_opportunity.*.yml`
- All field storage: `field.storage.node.su_opp_*.yml`
- Form and view display: `core.entity_form_display.node.stanford_opportunity.default.yml`, `core.entity_view_display.node.stanford_opportunity.*.yml`

## Additional Considerations
- If any fields reference vocabularies (taxonomy), media types, or other entities, you must migrate those as well.
- Check for dependencies on modules (e.g., `layout_builder`, `media`, `taxonomy`, etc.).
- Review for any field group or widget settings that may need to be ported.
- After migration, update permissions, roles, and test the content type thoroughly.

## Next Steps
1. Copy the listed files from `stanford_profile/config/sync/` to `sdss_profile/config/sync/`.
2. Import configuration in your target site and resolve any missing dependencies.
3. Test the Opportunity content type end-to-end.

---

_This file summarizes the migration scope and additional steps for Opportunity content type and fields._
