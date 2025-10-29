# Opportunity content type and field configuration files to migrate

## Content type definition
- node.type.stanford_opportunity.yml

## Field configuration (field.field.*)
- field.field.node.stanford_opportunity.body.yml
- field.field.node.stanford_opportunity.layout_builder__layout.yml
- field.field.node.stanford_opportunity.su_opp_application_deadline.yml
- field.field.node.stanford_opportunity.su_opp_card_footer.yml
- field.field.node.stanford_opportunity.su_opp_components.yml
- field.field.node.stanford_opportunity.su_opp_contact_email.yml
- field.field.node.stanford_opportunity.su_opp_contact_name.yml
- field.field.node.stanford_opportunity.su_opp_contact_phone.yml
- field.field.node.stanford_opportunity.su_opp_contact_url.yml
- field.field.node.stanford_opportunity.su_opp_course_code.yml
- field.field.node.stanford_opportunity.su_opp_cta_url.yml
- field.field.node.stanford_opportunity.su_opp_eligibility.yml
- field.field.node.stanford_opportunity.su_opp_icon.yml
- field.field.node.stanford_opportunity.su_opp_image.yml
- field.field.node.stanford_opportunity.su_opp_learn_more.yml
- field.field.node.stanford_opportunity.su_opp_open_date.yml
- field.field.node.stanford_opportunity.su_opp_prerequisites.yml
- field.field.node.stanford_opportunity.su_opp_source.yml
- field.field.node.stanford_opportunity.su_opp_sponsor.yml
- field.field.node.stanford_opportunity.su_opp_start_date.yml
- field.field.node.stanford_opportunity.su_opp_status.yml
- field.field.node.stanford_opportunity.su_opp_summary.yml
- field.field.node.stanford_opportunity.su_opp_tags.yml
- field.field.node.stanford_opportunity.su_opp_type.yml
- field.field.node.stanford_opportunity.su_opp_units.yml

## Field storage configuration (field.storage.*)
- field.storage.node.su_opp_application_deadline.yml
- field.storage.node.su_opp_card_footer.yml
- field.storage.node.su_opp_components.yml
- field.storage.node.su_opp_contact_email.yml
- field.storage.node.su_opp_contact_name.yml
- field.storage.node.su_opp_contact_phone.yml
- field.storage.node.su_opp_contact_url.yml
- field.storage.node.su_opp_course_code.yml
- field.storage.node.su_opp_cta_url.yml
- field.storage.node.su_opp_eligibility.yml
- field.storage.node.su_opp_icon.yml
- field.storage.node.su_opp_image.yml
- field.storage.node.su_opp_learn_more.yml
- field.storage.node.su_opp_open_date.yml
- field.storage.node.su_opp_prerequisites.yml
- field.storage.node.su_opp_source.yml
- field.storage.node.su_opp_sponsor.yml
- field.storage.node.su_opp_start_date.yml
- field.storage.node.su_opp_status.yml
- field.storage.node.su_opp_summary.yml
- field.storage.node.su_opp_tags.yml
- field.storage.node.su_opp_type.yml
- field.storage.node.su_opp_units.yml

## Form and view display configuration
- core.entity_form_display.node.stanford_opportunity.default.yml
- core.entity_view_display.node.stanford_opportunity.default.yml
- core.entity_view_display.node.stanford_opportunity.full.yml
- core.entity_view_display.node.stanford_opportunity.teaser.yml
- core.entity_view_display.node.stanford_opportunity.stanford_card.yml
- core.entity_view_display.node.stanford_opportunity.stanford_h3_card.yml
- core.entity_view_display.node.stanford_opportunity.search_indexing.yml

## Additional config to migrate for full functionality
- core.base_field_override.node.stanford_opportunity.promote.yml
- core.base_field_override.node.stanford_opportunity.status.yml
- core.entity_form_display.taxonomy_term.opportunity_sponsor.default.yml
- core.entity_form_display.taxonomy_term.opportunity_tag_filters.default.yml
- core.entity_form_display.taxonomy_term.opportunity_type.default.yml
- core.entity_form_display.taxonomy_term.opportunity_units.default.yml
- metatag.metatag_defaults.node__stanford_opportunity.yml
- pathauto.pattern.opportunity_nodes.yml
- rabbit_hole.behavior_settings.node_type_stanford_opportunity.yml
- rabbit_hole.behavior_settings.taxonomy_vocabulary_opportunity_tag_filters.yml
- rabbit_hole.behavior_settings.taxonomy_vocabulary_opportunity_sponsor.yml
- rabbit_hole.behavior_settings.taxonomy_vocabulary_opportunity_type.yml
- rabbit_hole.behavior_settings.taxonomy_vocabulary_opportunity_units.yml

---

## Additional considerations
- Ensure all referenced fields have both field and field storage config.
- If any fields reference vocabularies, media types, or other entities, migrate those as well.
- Check for dependencies on modules (e.g., layout_builder, media, taxonomy, etc.).
- Review for any field group or widget settings that may need to be ported.
- After migration, update permissions, roles, and test the content type thoroughly.

- Ensure the following vocabularies exist and are configured: `opportunity_sponsor`, `opportunity_tag_filters`, `opportunity_type`, `opportunity_units`.
- Confirm that the `metatag`, `pathauto`, and `rabbit_hole` modules are enabled in the target site.
- Review any token usage in metatag/pathauto configs for compatibility.

---

_This file lists all configuration files to copy from stanford_profile to sdss_profile for the Opportunity content type and fields._
