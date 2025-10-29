# Opportunity Migration: Vocabulary Migration Checklist

The following vocabularies referenced by the Opportunity content type are missing in sdss_profile and must be migrated from stanford_profile:

- opportunity_tag_filters
- opportunity_type
- opportunity_sponsor

## Migration Steps
1. Copy the following files from stanford_profile/config/sync/ to sdss_profile/config/sync/:
   - taxonomy.vocabulary.opportunity_tag_filters.yml
   - taxonomy.vocabulary.opportunity_type.yml
   - taxonomy.vocabulary.opportunity_sponsor.yml
2. Import configuration in your target site.
3. Verify that the vocabularies appear and are functional.
4. Test content creation and editing for Opportunity nodes to ensure references resolve.

---

_This checklist ensures all required vocabularies for Opportunity are present in sdss_profile._
