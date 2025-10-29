# Opportunity Migration: Additional Taxonomy Checklist

- All referenced vocabularies for Opportunity (opportunity_tag_filters, opportunity_type, opportunity_sponsor) have already been migrated.
- The view configuration references an additional vocabulary: opportunity_units.

## Action Items
- Check if taxonomy.vocabulary.opportunity_units.yml exists in sdss_profile/config/sync/.
- If not, copy it from stanford_profile/config/sync/ and import configuration.
- Test any views or fields that reference this vocabulary.

---

_This checklist ensures all taxonomies referenced by Opportunity and its views are present._
