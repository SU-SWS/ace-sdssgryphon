# SDSS Profile


1.1.4
--------------------------------------------------------------------------------
_Release Date: 2022-12-19_

- SDSS-435: Configured fast_404 and page_cache_query_ignore (#103)
- Added page_cache_query_ignore module.
- Updated smart_trim and editoria11y module constraints.
- Updated editoria11y configuration.
- Enabled page_cache_query_ignore module and exported configuration.
- SDSS-247: Removed trim from external url field on news card display. (#102)


1.1.3
--------------------------------------------------------------------------------
_Release Date: 2022-12-15_

- Doerr header typo fix.


1.1.2
--------------------------------------------------------------------------------
_Release Date: 2022-12-15_

- SDSS-385-386-391: Changes to the masthead and header. (#90)
- SDSS-386: Styling of the local header on the main school site
- SDSS-385: Styling and interaction of the "site search" icon needs to be updated
- SDSS-391: Styling of the local header on multi-sites
- Implement staging requests and prep for prod deploy (#93)
- Added codeCoverageIgnoreEnd to stanford_basic/theme-settings.php to fix test.
- Removed more patches moved to drupal-patches.
- Dropped source label from news source field displays.
- Allow auto-creating news source terms from edit page.
- SDSS-363: Replaced dek with dek long in news list view pattern. (#84)
- SDSS-363: Updated Dek field to Dek (Long) in News List display.
- SDSS-412: Updated styling on News List and Teaser display to use the new Dek (Long) field .
- SDSS-381: Front-end styling to News item. reorder date field, add News source field
- SDSS-398: Updated stanford_circle mask image path. (#91)
- Added jsonapi_hypermedia module (#92)
- Added jsonapi_hypermedia module to fix dependency issue.
- Removed patches added to su-sws/drupal-patches.
- Removed outdated decoupled_router patch.


1.1.1
--------------------------------------------------------------------------------
_Release Date: 2022-11-21_

- SDSS-371: Added focal areas field to person node. (#82)


1.1.0
--------------------------------------------------------------------------------
_Release Date: 2022-11-21_

- SDSS-292: Added News Source vocabulary and field to News. (#83)
- SDSS-300: Use position sticky for sticky header (#80)
- Dropped composer autoloads for removed modules. (#79)
- SDSS-364: Sync people list displays with stanford_profile upstream (#78)
- SDSS-362: Latest updates from stanford profile, included restructing of modules to stanford_profile_helper.


1.0.3
--------------------------------------------------------------------------------
_Release Date: 2022-10-27_

- SDSS-337: Added 3 new fields to the Publication node: Organization, Research Areas/Expertise, and Focal Areas. (#75)
- SDSS-336: Added 3 new fields to the News node: Organization, Research Areas/Expertise, and Focal Areas. (#74)
- SDSS-334: Added 5 new fields to the Person node: Organization, Research Area/Expertise, Research Statement, Alternative Title, and Alternative Bio. (#70)
- SDSS-305: Adjusted Event and Magazine Topic field displays. (#72)
- SDSS-301: Added new long dek field to news replacing current dek field (#71)
- SDSS-344: Fixed z-index and position for banner images and anonymous users.
- SDSS-300: Reduce sticky header and banner gap spacing.
- SDSS-305: Added new vocabularies. (#69)
- Move automation from CircleCI to github actions (#67)


1.0.2
--------------------------------------------------------------------------------
_Release Date: 2022-09-21_

- SDSS-270: Added news RSS (#64)
- SDSS-257: Updated hover and focus styles on embeddable localist events


1.0.1
--------------------------------------------------------------------------------
_Release Date: 2022-09-07_

- SDSS-247: Added class for animated gif workaround. (#59)
- Updated config_split configuration.
- Updated composer.json constraints including config_ignore, config_split, and drupal-patches.


1.0.0
--------------------------------------------------------------------------------
_Release Date: 2022-08-30_

- First official release for sdss_profile.
