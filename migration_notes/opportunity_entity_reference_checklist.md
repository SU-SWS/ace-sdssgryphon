# Opportunity Migration: Entity Reference Fields Checklist

## Purpose
This checklist will help you verify if any fields in the Opportunity content type reference vocabularies (taxonomy), media types, or other entities that must also be migrated.

## Steps
1. After importing the configuration, review the field settings for all fields on the Opportunity content type.
2. For each field of type 'entity_reference', check if the referenced entity type (e.g., taxonomy_term, media) exists in the target site.
3. If not, export and migrate the referenced vocabulary, media type, or entity configuration from the source profile.
4. Test content creation to ensure all references resolve correctly.

---

_This checklist should be used after the initial config import to catch any missing entity references._
