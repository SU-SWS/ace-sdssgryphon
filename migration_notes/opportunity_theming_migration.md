# Step 7: Theming and Templates Migration Notes

## Overview
This document tracks the discovery, decisions, and actions related to migrating theming, templates, and styles for the Opportunity content type from Stanford Profile and Stanford Profile Helper to SDSS Profile.

## Known Relevant Files
- `modules/stanford_profile_styles/lib/scss/component/node/stanford_opportunity.scss` (Stanford Profile Helper)
- `modules/stanford_profile_styles/lib/scss/component/paragraph/entity/index.scss` (Stanford Profile Helper, contains Opportunity Teaser styles)

## Next Steps
- Review the above SCSS files for relevant Opportunity styles to migrate.
- Search for any additional templates, theme files, or JS behaviors related to Opportunity content or its display.
- Identify if only parts of files are needed, or if full files should be copied.
- Document findings and decisions as we proceed.

## Opportunity SCSS and Teaser Styles (Discovery)

### node/stanford_opportunity.scss
- Contains layout and style rules for the Opportunity node display, including responsive spacing, two-column layout, and wrappers for title, icon, and summary.
- Uses mixins and variables from the shared config and stanford_page partials.
- Key selectors: `.node--stanford-opportunity`, `.su-opp-node-container-wrapper`, `.su-opp-title-wrapper`, `.node-stanford-opportunity-su-opp-icon`, `.node-stanford-opportunity-title`, `.node-stanford-opportunity-su-opp-summary`.

### paragraph/entity/index.scss (Opportunity Teasers)
- Contains a section for Opportunity teasers under `.ds-entity--stanford-opportunity`.
- Styles the card contents, hides `.su-opp-type`, and applies heading and spacing styles to the teaser title.
- Key selectors: `.ds-entity--stanford-opportunity .su-card__contents`, `.ds-entity--stanford-opportunity .su-opp-type`, `.ds-entity--stanford-opportunity h3`.

**Next Steps:**
- Review which selectors and rules are already present in SDSS Profile themes.
- Plan to copy or adapt only the necessary SCSS blocks for Opportunity node and teaser display.
- Check for any related Twig templates or JS behaviors that may interact with these styles.

## Theming Strategy and Class Handling (SDSS)

- In Stanford Profile, the `node--stanford-opportunity` class is added to the node wrapper via a preprocess function in `stanford_basic.theme`, ensuring styles target the Opportunity node regardless of Layout Builder usage.
- In SDSS, this class was missing because the equivalent preprocess logic was not present. We will add a similar preprocess function in `sdss_subtheme.theme` to ensure the class is always present for Opportunity nodes.
- Instead of copying styles into a parallel `stanford_profile_styles` module, we are incorporating all Opportunity-specific SCSS directly into the `sdss_subtheme`. This keeps SDSS customizations centralized and easier to maintain, and avoids legacy dependencies.
- All Opportunity node and teaser styles will be reviewed and selectively migrated or adapted into the subtheme, ensuring only what is needed is included and that it fits SDSS design needs.

---
_Last updated: October 23, 2025_
