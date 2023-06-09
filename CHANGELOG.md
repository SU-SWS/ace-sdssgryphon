# ACE SDSSGryphon

2.0.4
--------------------------------------------------------------------------------
_Release Date: 2023-06-01_

- SDSS-628: Replaced sdss-intro-text WYSIWYG style class with su-intro-text class (#141)


2.0.3
--------------------------------------------------------------------------------
_Release Date: 2023-05-31_

- SDSS-576-577-578: Updated WYSIWYG text styles (#122)
- SDSS-576: Updated Intro Text and Display text WYSIWYG styles.
- SDSS-577: Updated block quote lg, md, sm styles and font sizes.
- SDSS-578: Updated the Display text style and font size.
- SDSS-606: Added import source and related people fields to News content type. (#128)
- SDSS-606: Cleaned up News node edit form.
- SDSS-354: Added filter by taxonomy RSS view (#120)
- SDSS-532: Hide body field in banner paragraph edit form, since it is not used/displayed. (#121)
- SDSS-530: Hide banner caption field from default display (#137)
- SDSS-601: Provisioned 17 sites (#136)
- SDSS-626: Changed earth_news_importer module type to store in untested contrib directory.(#133)
- SDSS-624: Replaced Dek field with Dek (Long) field in RSS feed (#131)
- SDSS-621: Added dev version of earth_news_importer to the stack.
- SDSS-464: Set the sdss_subtheme as the default theme and updated tests to reflect differences.. (#127)
- Dropped and deprecated public protection and theme viewer roles.
- SDSS-599: Provision understand-energy (#126)
- SDSS-530: Added caption field to banner paragraph (#118)
- SDSS-562: Disable configuration read-only on config capture staging site. (#117)


2.0.2
--------------------------------------------------------------------------------
_Release Date: 2023-04-26_

- SDSS-563: Updated path_redirect_import module and updated corresponding test (#116)
- SDSS-418: Created 3 taxonomies and added them as Basic Page fields (#113)
- SDSS-567: Pointed energypostdoc alias to sepf site (#112)
- SDSS-563: Dependency and stanford_profile updates (#111)
- SDSS-563: Updated dependencies.
- SDSS-563: Removed ECK.
- SDSS-563: Replaced log entity type.
- SDSS-563: Updated profile core requirement.
- SDSS-563: Added autoservices package so it can be uninstalled properly.
- SDSS-563: Added default block configs for sdss_subtheme.
- SDSS-563: Updated SHS widget field tests from selectOption to fillField.
- SDSS-569: Added events RSS feed. (#114)
- SDSS-565: Added ckeditor.scss to subtheme (#110)
- SDSS-538: Updated caption styles to be consistent across paragraphs (#107)
- SDSS-000: Updated pull request template (#108)
- SDSS-276: Updated footer heading styles (#106)


2.0.1
--------------------------------------------------------------------------------
_Release Date: 2023-03-28_

- SDSS-225: Updated text colors on green row variant (#103)
- SDSS-482: Added Event Topics and Shared Tags to Event List paragraph Arguments (#79)
- SDSS-428: Adjusted permissions for roles to modify new taxonomy terms (#83)
- SDSS-000: Updated BLT cron creation command. (#102)
- SDSS-445: Updated button padding. (#82)
- SDSS-456: Added scroll-padding-top style to account for sticky header (#84)
- SDSS-228: Changed focus order for Event card display for improved accessibility (#92)
- SDSS-84: Updated WYSIWYG style options (#93)
- SDSS-498: Added view for RSS feed of events (#95)
- SDSS-512: Updated font size units from px to rem (#94)
- SDSS-525: Added featured media field to Event content type (#98)
- SDSS-430: Removed unused logo asset from sdss_subtheme (#52)
- SDSS-487: Pointed epsci.stanford.edu to gs site in sites.php. (#77)
- SDSS-527: Updated sdss_subtheme npm packages including Decanter. (#99)
- SDSS-528: Adjusted github actions workflows and resolved testViewRevisions failure. (#100)
- SDSS-528: Adjusted dev branch actions workflow pull_request event activity types.
- SDSS-528: Filled headline field in card for BasicPageParagraphsCest:testViewRevisions test now that field is required.
- SDSS-423: Updated heading on list paragraphs from h3 to h2 (#85)
- SDSS-499: Fixed min-height bug on mobile banner (#86)
- SDSS-234: Updated decanter templates and non-discrimination footer link (#87)
- SDSS-480: Required headline field on Card paragraph (#97)
- SDSS-417: Added Focal Area field to Event content type (#80)
- SDSS-251: Added three new taxonomies for opportunities (#78)
- Updated dependencies. (#76)
- SDSS-479: Set the default card type style to the Topic card (#74)
- SDSS-488: Added page_cache_query_ignore patch to fix event display bug. (#75)
- SDSS-488: Updated su-sws/drupal-patches to get page_cache_query_ignore patch which fixes metatag bug affecting event list display.
- SDSS-369: Removed display of shortTitle from person pages (#68)
- SDSS-474: Added auto deploy and improved test workflow (#58)
- backtodev-2.0.1


2.0.0
--------------------------------------------------------------------------------
_Release Date: 2023-03-08_

- Cancel previous github actions test workflow (#63)
- SDSS-409: Added earthsystems domain. (#64)
- SDSS-421: Added Banner Height Variant (#45)
- SDSS-434: Updated responsive styles for header and lock-up options (#55)
- SDSS-453: Updated gradient percentage for banners with headlines (#56)
- SDSS-475: Removed SDSS text from brand bar (#61)
- SDSS-432: Use class instead of ID for search icon CSS (#53)
- SDSS-441-444: Fix nav wrapping and drop down access (#47)
- SDSS-415-446: Provisioned SEPF and sdss_config_capture sites (#60)
- Added lando setup (#59)
- SDSS-000: Moved sdss_profile to own sdss profile directory (#44)
- Updated gitignore (#43)
- 2023-01-19 updated dependencies (#38)
- Updated test cache key names in composer.json to reflect branch name.
- Added conflict for ui_patterns >= 1.5.
- Updated db info in ci.settings.php.
- Updated blt.yml
- Dropped .circleci.
- Updated test configurations.
- Updated config_sync_directory to new profile location.
- Consolidated sdss_profile into stack.


1.0.7
--------------------------------------------------------------------------------
_Release Date: 2022-12-19_

- SDSS-435: fast 404 page cache query ignore stack (#35)
- Added fast404 settings.
- Updated tests.
- Updated dependencies.


1.0.6
--------------------------------------------------------------------------------
_Release Date: 2022-12-15_

- Hotfix for doerr typo.


1.0.5
--------------------------------------------------------------------------------
_Release Date: 2022-12-15_

- Adjusted config_readonly_whitelist_patterns. (#30)
- Updated dependencies. (#28)
- Removed resolved conflicts.
- Updated and locked drupal core to ~9.4.0.
- Added conflict for real_aes 2.5.
- Updated acquia_connector to 4.0.
- Point sustainabilityleadership.stanford.edu to changeleadership site. (#29)
- Locked drupal/google_tag to 1.5 for this release only; 1.6 came out when cutting this release.


1.0.4
--------------------------------------------------------------------------------
_Release Date: 2022-11-21_

- Updated sdss_profile and dependencies. (#25)


1.0.3
--------------------------------------------------------------------------------
_Release Date: 2022-10-27_

- Updated sdss_profile and dependencices.
- Updated dependencies. (#23)
- SDSS-285: Provisioned earthsystemscience. (#19)
- Update dependencies 20221014 (#22)
- Added conflict for Drupal core to mitigate Acquia issue.
- Updated ui_patterns patch.
- Updated dependencies.
- Added consolidation/site_alias conflict. Updated acquia_connector and sdss_profile constraints.
- Fixed circleci container image.


1.0.2
--------------------------------------------------------------------------------
_Release Date: 2022-09-21_

- Updated sdss_profile and dependencies.
- SDSS-285: Provisioned esys, eiper, changeleadership, ese, haiwaii, farm, and climatechange sites. (#17)
- Provisioned sandbox and userguide sites. (#16)


1.0.1
--------------------------------------------------------------------------------
_Release Date: 2022-09-07_

- Updated sdss_profile and dependencies.


1.0.0
--------------------------------------------------------------------------------
_Release Date: 2022-08-30_

- First official release for ace-sdssgryphon.
