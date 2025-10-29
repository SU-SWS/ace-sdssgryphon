# Opportunity Migration: Views and Relationships Checklist

## Views to Migrate
The following views in stanford_profile reference the Opportunity content type or its vocabularies:
- views.view.stanford_opportunities.yml
- views.view.stanford_opportunities_filtered.yml
- views.view.manage_content.yml

## Action Items
1. Copy the above view configuration files from stanford_profile/config/sync/ to sdss_profile/config/sync/.
2. Review each view for dependencies on fields, relationships, or filters related to Opportunity.
3. After import, test each view to ensure it displays Opportunity content as expected.

## Menus
- No menu or menu link configuration referencing Opportunity was found.

## Relationships
- No additional entity reference or relationship configuration (outside of fields already migrated) was found.

---

_This checklist ensures all relevant views and relationships for Opportunity are migrated and tested._
