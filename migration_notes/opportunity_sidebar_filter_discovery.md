# Opportunity Sidebar Filter Discovery Context

## Summary
This file documents the discovery and analysis of the sidebar filter functionality for Stanford Opportunities in Stanford Profile and Stanford Profile Helper. It is intended as a living context for migration and future development decisions.

## What We Found
- Stanford Profile and Stanford Profile Helper provide a sidebar on certain pages that lists Stanford Opportunities.
- The sidebar includes a set of filters that users/site editors can apply to the list of opportunities.
- Filtering is managed by adding terms to a specific "filtering" taxonomy. Site editors create a term that matches a taxonomy on the Opportunity content type, and then add child terms for the filter options.
- This mechanism allows for flexible, taxonomy-driven filtering of opportunities in the sidebar.

## Technical Discovery (as of October 23, 2025)

- The sidebar and filter functionality appears to be implemented using taxonomy terms and menu/taxonomy relationships.
- There are references to AJAX views, taxonomy menu labels, and argument helpers in the `stanford_profile_helper` changelog and JS files.
- Layout builder blocks and menu options are modified to support taxonomy-driven filtering and display.
- AJAX is used to update/filter views based on taxonomy selections.
- There are hooks and cache tag improvements related to views and filters.
- The Opportunity content type is configured to use `menu_ui` and has available menus, but the sidebar filter logic is not directly in the content type config.
- The filtering taxonomy is likely managed via custom forms and UI enhancements in `stanford_profile_helper`.

### Next Steps for Discovery
- Identify specific modules, blocks, or views that render the sidebar and filters.
- Locate any custom code (PHP, JS, or Twig) that builds the filter UI and processes filter logic.
- Track any dependencies or impacts on other features.
- Document what needs to be removed or replaced for SDSS.

## User Guide & Documentation Findings (October 23, 2025)

- The sidebar filter is implemented as a general-purpose "Filtered Lists Paragraph" feature in Stanford Sites, not as a dedicated Opportunities filter.
- Filtered Lists Paragraph allows site editors to create lists of content (currently only Opportunities) with interactive filters for users.
- Filters are built using taxonomy vocabularies, specifically "Opportunity Filters" for Opportunities. Site editors create parent terms (Labels) and child terms (Categories) in the taxonomy to define filter options.
- The Filtered Lists Paragraph is only available on Basic Pages and automatically switches the layout to Full Width to accommodate the sidebar.
- Only one Filtered List should be added per page for clarity and usability.
- The filtering mechanism is flexible and can be adapted for other content types in the future.
- The Opportunity Type, Units, and Sponsor taxonomies are used for tagging and sidebar display, but only Opportunity Filters are used for interactive filtering in the Filtered Lists Paragraph.
- The user guide provides step-by-step instructions for setting up filters, adding the paragraph, and best practices for usability.

### Conclusion
- The filter sidebar for Opportunities is a specific use of the more general Filtered Lists Paragraph feature, which is taxonomy-driven and configurable by site editors.
- For SDSS, we will NOT migrate this feature, but may develop our own filter process in the future.

## Migration Decision (October 23, 2025)

- The Opportunity Filters taxonomy (`opportunity_tag_filters`) and the `su_opp_tags` field on the Opportunity content type were migrated from Stanford Profile.
- These are part of the sidebar filter/Filtered Lists Paragraph feature, which is **not** being migrated to SDSS Profile.
- As a result, both the taxonomy vocabulary and the field have been **removed** from the SDSS Profile config sync directory to avoid carrying over unwanted filter functionality.
- The Opportunity Type field and taxonomy remain, as they are used for display and tagging, not interactive filtering.

**Summary:**
- No sidebar filter or Filtered Lists Paragraph functionality will exist in SDSS Profile.
- No Opportunity Filters taxonomy or su_opp_tags field will remain in the migrated config.
- This ensures only necessary config is present and avoids confusion for future development.

## Files Removed from SDSS Profile Config (October 23, 2025)

The following configuration files related to the sidebar filter/Filtered Lists Paragraph feature were removed from `docroot/profiles/sdss/sdss_profile/config/sync`:

- taxonomy.vocabulary.opportunity_tag_filters.yml
- field.field.node.stanford_opportunity.su_opp_tags.yml
- field.storage.node.su_opp_tags.yml
- core.entity_form_display.taxonomy_term.opportunity_tag_filters.default.yml
- rabbit_hole.behavior_settings.taxonomy_vocabulary_opportunity_tag_filters.yml

These files are associated with the Opportunity Filters taxonomy and the su_opp_tags field, which powered the sidebar filter functionality in Stanford Profile. Their removal ensures that no unwanted filter-related config remains in SDSS Profile.

## Additional Configuration Updates

In addition to removing the taxonomy and field configuration for filtering, the following configuration files were updated to remove dependencies, display settings, and filters related to the removed taxonomy and field:

| Configuration File                                                      | Action  |
|-------------------------------------------------------------------------|---------|
| core.entity_form_display.node.stanford_opportunity.default              | Update  |
| core.entity_view_display.node.stanford_opportunity.teaser               | Update  |
| core.entity_view_display.node.stanford_opportunity.stanford_card        | Update  |
| core.entity_view_display.node.stanford_opportunity.search_indexing      | Update  |
| core.entity_view_display.node.stanford_opportunity.full                 | Update  |
| views.view.stanford_opportunities                                       | Update  |
| core.entity_view_display.node.stanford_opportunity.default              | Update  |

These updates ensure that all references to the removed taxonomy and field are fully cleaned from the SDSS Profile configuration, preventing dependency errors during config import and keeping the configuration consistent.

## How Filtering Works in Filtered Lists Paragraph (Stanford Sites)

Filtered Lists Paragraph uses taxonomy-driven filtering as follows:

- Site editors create filter Labels (e.g., "Quarter") and Categories (e.g., "Autumn", "Winter", "Spring") as terms in the `opportunity_tag_filters` taxonomy.
- The Opportunity content type has a field called `su_opp_tags`, which is an entity reference field pointing to terms in the `opportunity_tag_filters` taxonomy.
- When creating or editing an Opportunity, editors tag it with one or more relevant terms (Categories) from this taxonomy.
- The Filtered Lists Paragraph displays a filter UI based on the taxonomy structure. When a user selects a Category, only Opportunities tagged with that term (via `su_opp_tags`) are shown in the list.

**In summary:**
- The filter values are taxonomy terms.
- The field being filtered is `su_opp_tags` on the Opportunity content type.
- Removing the taxonomy and field disables this filtering mechanism in SDSS Profile.

For more details, see the [Stanford Sites User Guide: Set Filters for Filtered List Paragraph](https://sitesuserguide.stanford.edu/build/paragraphs/filtered-lists-paragraph/set-filters-filtered-list-paragraph).

## Manual Taxonomy Tagging for Filtering (Clarified)

The Opportunity Filters taxonomy is a manually curated list of filter Labels and Categories. When creating or editing an Opportunity node, editors select the relevant filter terms from this taxonomy using the `su_opp_tags` field. This tagging is done on a node-by-node basis and is independent of other field values on the node.

**Key points:**
- The taxonomy is not automatically linked to other node fields (e.g., Sponsor, Quarter, etc.).
- If you want to filter by a field like Sponsor, you must manually create a "Sponsor" label and child terms in the filters taxonomy, then tag each node with the appropriate term in addition to setting the Sponsor field value.
- Filtering in the Filtered Lists Paragraph only works based on the terms assigned from the filters taxonomy, not on other node data.

This approach requires editors to keep taxonomy terms and node field values in sync if they want filtering to reflect actual field data.

## Pros and Cons of Manual Taxonomy Filtering

**Advantages:**
- Editors have complete control over which filters are available and how content is grouped for filtering.
- You can create custom filter categories and labels that may not directly correspond to existing node fields.
- Filtering can be tailored to editorial needs, not just data structure.

**Disadvantages:**
- Editors must manually keep taxonomy terms and node field values in sync if they want filters to reflect actual data points (e.g., Sponsor, Quarter).
- There is duplication of effort: tagging nodes for filtering and also setting field values.
- You cannot filter by existing node field data unless you create and assign matching taxonomy terms for each value.
- Risk of inconsistency if taxonomy tags and field values diverge.

This tradeoff should be considered when designing future filtering solutions for SDSS Profile.

## Comparison: Manual Taxonomy Filtering vs. Field-Based Views

### Manual Taxonomy Filtering (Stanford Sites)
**Pros:**
- Maximum editorial flexibility; site editors can create any filter structure they need.
- Filters can be customized independently of node field data.
- Scales well for thousands of self-service sites with diverse needs.

**Cons:**
- Requires manual tagging of nodes, duplicating data entry.
- Editors must keep taxonomy terms and field values in sync for accurate filtering.
- Filtering does not automatically reflect actual node field data.
- Risk of inconsistency and increased maintenance effort.

### Field-Based Views with Exposed Filters (SDSS Profile)
**Pros:**
- Filters are always based on real node field data—no duplication or manual syncing required.
- Simpler editorial workflow; less risk of inconsistency.
- Easier to maintain and customize for a smaller, stakeholder-driven multi-site platform.
- Filtering can be tailored to each site's needs as requested.

**Cons:**
- Less flexibility for ad-hoc filter structures not tied to existing fields.
- Requires development effort for each new filter or view requested.

**Note:**
Another advantage of using exposed filters on fields instead of taxonomy-based filtering is that you can filter by a wider range of data types, such as dates, numeric ranges, or other complex field values—not just strict term matches. This enables more flexible and powerful filtering options beyond simple labels or categories.

### Contextual Decision
Stanford Sites uses manual taxonomy filtering to support thousands of self-service sites, where flexibility and editor autonomy are critical. For SDSS Profile, which serves about 60 sites with direct stakeholder involvement, building custom Views with exposed filters based on actual field data is more efficient, maintainable, and better suited to your workflow.

## How the Filtering Functionality Works

The filtering functionality in the original Stanford Profile/Helper implementation relied on manually assigned taxonomy terms. Editors would tag Opportunity nodes with specific taxonomy terms from a dedicated vocabulary. The Filtered Lists Paragraph would then display a list of Opportunities, allowing users to filter the results by these taxonomy tags. This approach provided flexible, editorially controlled filtering, but required editors to maintain taxonomy assignments for each node. In contrast, the SDSS Profile implementation uses field-based Views with exposed filters, enabling users to filter Opportunities directly by field values (such as type, status, or date) without relying on separate taxonomy tagging.

---
_Last updated: October 23, 2025_

**Action Item:**
Since taxonomy terms were used to display custom data (like Quarter) and enable filtering in the sidebar, removing this mechanism means we may need to add dedicated fields (e.g., Quarter, Sponsor) to the Opportunity content type in SDSS Profile to support structured data display and filtering going forward.
