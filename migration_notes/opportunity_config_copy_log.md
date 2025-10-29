# Opportunity Migration: Step 1 & 2 Copy Log

The following configuration files must be copied from stanford_profile/config/sync/ to sdss_profile/config/sync/ to complete steps 1 and 2:

## Content type definition
- node.type.stanford_opportunity.yml

## Field configuration (field.field.*)
- field.field.node.stanford_opportunity.*.yml (all 25+ files)

## Field storage configuration (field.storage.*)
- field.storage.node.su_opp_*.yml (all 20+ files)

## Form and view display configuration
- core.entity_form_display.node.stanford_opportunity.default.yml
- core.entity_view_display.node.stanford_opportunity.*.yml (all 6+ files)

---

_This log tracks the required config files for Opportunity content type and fields. Ensure all are copied before proceeding with import._
