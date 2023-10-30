# ACE SDSSGryphon

2.2.0
--------------------------------------------------------------------------------
_Release Date: 2023-09-27_

- 493dd2a SDSS-973: Change dash to underscore in blt.yml for abc_hub (#244)
- e08da09 SDSS-969: Disabled JSON API module (#242)
- 2466773 SDSS-888: Swapping abchub with abc-hub (#239)
- f06dade SDSS-943: Pointed techtransferfordefense to hackingfordefense site (#240)
- d0dc2c2 SDSS-594-885: Added media contacts entity, field, styles and displays (#217) (#219)
- 06d6b78 SDSS-843: Added the stanford_earth_r25 module to sdssroom site only. (#237)
- bee9507 SDSS-937: NEWS | Added top margin to related news section (#235)
- b8d1b0b SDSS-851: A11y | Added decorative blue text color for improved accessibility (#234)
- 8a4c240 SDSS-842: Provision SDSS Rooms (#236)
- f061137 SDSS-477: A11Y | Fixed button text color on Event pages (#233)
- 3091c40 SDSS-451, SDSS-452: Adjust margin and padding for color variant paragraph rows (#232)
- 3bacc1e SDSS-175: Reduce font-size on default list filter by menu (#231)
- d4f8729 SDSS-900: Fix callout quote symbols for Windows (#230)
- 88953c6 SDSS-888: Provisioned abchub (#229)
- f89bf12 SDSS-000: DEV | Add ddev configuration for local environments (#228)
- fb0303d SDSS-520: Updated Localist migration to import images (#227)
- 916ea46 SDSS-814: News Article Design QA Updates (#193)
- 849dddd SDSS-901: News | Vertical Teaser Card QA Updates (#222)
- 5f99b4c SDSS-902: NEWS | Update byline and date font size on News node displays (#224)
- e2d4b03 SDSS-906: Lock seleinium chrome-driver version (#226)
- 45416f9 SDSS-886: Added nid data to RSS feed as unique identifier (#225)
- 80ac02f SDSS-900: Callout Quote | Use serif font and smart quotes (#221)
- efecbfe SDSS-846: A11y | Updates to News Vertical Card component (#220)
- e498542 SDSS-877: Fix News article top text alignment issue (#218)
- 857eec2 SDSS-827: Adjusted Callout Quote component alignment (#209)
- ed710e2 SDSS-875: News article node display updates. (#216)
- 91ee2b3 SDSS-821: Adjusted dek line height on News nodes (#213)


2.1.1
--------------------------------------------------------------------------------
_Release Date: 2023-08-17_

- 96b95cc SDSS-862: Adjusted component paragraph reference fields include/exclude settings (#210)
- 6bfe6a9 SDSS-860: Updated news node metadata. (#212)
- 7a59cd5 SDSS-840: BUG | dbupdate | Move unsectioned News paragraphs into layout_paragraph sections (#205)
- 02a3493 SDSS-844: A11y | Accessibility improvements for Callout Quote (#208)
- ded9332 SDSS-854: Provisioned sesur (#211)
- 5399220 SDSS-839: Allow spacer paragraph in News component section. (#206)
- 34665c7 SDSS-000: Added missing chosen library and updated dependencies. (#204)


2.1.0
--------------------------------------------------------------------------------
_Release Date: 2023-08-03_

- aba953c SDSS-763: Updated stanford_profile_helper (#201)
- ed94648 SDSS-793: Added icons for new Sidebar and Callout paragraph types (#198)
- 305180a SDSS-823: Added bottom margin to News nodes to add space before footer (#199)
- bbfa616 SDSS-824: Fixed Firefox wrapping bug with caption text (#197)
- 84269c7 SDSS-613: Adjusted Event and News taxonomy text to accessible colors (#196)
- e5a5490 SDSS-822: Adjusted spacing for news article top component without byline (#195)
- 14548e4 SDSS-816: Use serif font for block quote styles, updated decanter. (#194)
- 8490284 SDSS-780: Ignore the earth_news_importer module and migration configuration. (#192)
- 7b92799 SDSS-787: Provisioned witw and gfi. (#191)
- fafbae1 Added path_redirect_import patch fix for migratetools. (#190)
- 823af82 SDSS-788: Added and configured stage file proxy (#183)
- 98b6184 SDSS-786: Article page tweaks (#184)
- d69e84c SDSS-592: Add sidebar component (#172)
- e2d38de SDSS-591: Add Newsroom Callout component (#170)
- 7cddff6 SDSS-589: News node top component (#168)
- cfd1cfc SDSS-784: Point custom hopkinsmarinestation aliases. (#179)
- eeadfdd SDSS-778: Set article body width for newsroom only (#177)
- b03600c SDSS-731: Added custom sdss_layout_paragraphs module (#163)
- 85046a4 Hide all data capture fields in default displays.
- 305b27c SDSS-731: Layout paragraphs updates (#155)
- 173aee5 SDSS-600: Changed News components field to layout_paragraphs (#125)


2.0.5
--------------------------------------------------------------------------------
_Release Date: 2023-06-28_

- SDSS-732: Updated earth_news_importer. (#162)
  - SDSS-732: Updated earth_news_importer to latest version with new Banner Caption field.
- SDSS-732: Added new Banner Caption field to News content type (#156)
- SDSS-730: Maintenance and adding layout paragraphs (#152)
  - Updated dependencies and configuration.
  - Upgraded components module to ^3.0.
  - Updated CI Cache string with current date.
  - Added and enabled layout_paragraphs.
  - Added path_alias service to SiteSettingsTest.
- SDSS-639: Set up gitpod. (#154)
- SDSS-585: Create generic related content field. (#135)
  - SDSS-585: Create generic related content field and added to 5 main content types.
- SDSS-000: Updated earth_news_importer to latest version. (#151)
- SDSS-632: Allow site editors to use contextual links. (#149)
- SDSS-599: Added aliases for understandenergy site (#147)
- SDSS-532: Hid superhead field from editing interface on banner paragraph. (#148)
- SDSS-638: Added additional fields to the Events XML feed. (#146)
- SDSS-000: Added safe.directory git config step to github actions. (#145)
- SDSS-634: Updated events this week RSS feed (#144)
  - SDSS-634: Swapped description with alt_location field in events this week RSS feed view.
- SDSS-625: Resolved menu scroll jump bug (#138)
  - SDSS-625: Swapped scroll-padding-top on the html for scroll-margin-top on the :target to resolve menu scroll jump bug.


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
