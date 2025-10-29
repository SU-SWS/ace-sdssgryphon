# Opportunity Migration: Field Reference Analysis

## Entity Reference Fields in Opportunity Content Type

The following fields in the Opportunity content type may reference other entities (taxonomy, media, etc.):

- su_opp_tags (taxonomy_term: opportunity_tag_filters) **MUST MIGRATE**
- su_opp_type (taxonomy_term: opportunity_type) **MUST MIGRATE**
- su_opp_image (media: image) **ALREADY PRESENT**
- su_opp_sponsor (taxonomy_term: opportunity_sponsor) **MUST MIGRATE**
- su_opp_components (paragraph: stanford_banner, stanford_card, stanford_entity, stanford_faq, stanford_gallery, stanford_layout, stanford_lists, stanford_media_caption, stanford_spacer, stanford_wysiwyg) **ALREADY PRESENT**

## Action Items
- After importing, verify that referenced vocabularies and media types exist in the target site.
- If missing, export and migrate the relevant configuration from the source profile.
- Test content creation to ensure all references resolve.

---

_This note highlights fields that may require additional configuration migration for referenced entities._
