# ACE SDSSGryphon

# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [4.14.0] - 2025-09-22

### Added
a7eebac Sustainability Homepage 2025 (#662)
e5bb9ae SDSS-1562: Point ssip.stanford.edu to sustainpathways site (#669)
f19acbe Add branch reference to dev branch actions (#664)
e0c2146 Add auto-deploy to pull request workflow (#663)
6274cfe SDSS-1550: SDSS Person Title Override Field Formatter Implementation (#639)
255c9bf SDSS-1549: Added and refactored header tests (#650)
286c51a Add automated dependency updates workflow (#646)

### Changed
b554eeb SDSS-1565: Pull request template changes (#667)
292f89d SDSS-0000: Update room reservation module to 8.1.22 (#657)
255c9bf SDSS-1549: Added and refactored header tests (#650)
0d30f24 SDSS-1549: Refactor header and logo sizes (#649)

### Deprecated
None.

### Removed
None.

### Fixed
None.

### Security
None.

### Maintenance
e0c2146 Add auto-deploy to pull request workflow (#663)
61bd3b3 Automated Dependency Updates 20250905 (#656)
6aacf30 Automated Dependency Updates 20250829 (#652)
2e513f3 Automated Dependency Updates 20250822 (#648)
286c51a Add automated dependency updates workflow (#646)
6100f99 SDSS-1552: Routine maintenance (#645)


## [4.13.1] - 2025-09-09

### Added
2b8c419 SDSS-1558: Provision ese2025 (#655)
8cff579 SDSS-1557: Provision sustainpathways (#653)

### Changed
e5ed2c1 SDSS-0000: Room reservation module update to 8.1.21 (#651)

### Deprecated
None.

### Removed
None.

### Fixed
None.

### Security
None.

### Maintenance
None.


## [4.13.0] - 2025-08-13

### Added
c2818dc SDSS-1537: Add Research Statement field to Stanford Person page display (#638)
7727aa8 SDSS-1535: Implement accordion and faq paragraph (#636)

### Changed
91eef71 SDSS-1536: Streamline Lockup Settings and Fix Theme Configuration Bugs (#614)
09bcbf5 Updated custom scripts to executable location. Updated scripts README.md.
627647d Make scripts executable.
f229481 SDSS-1542: Update events XML feed (#626)

### Deprecated
6a13818 SDSS-0000: Deprecated colors (#637)
d8f7e99 SDSS-1502: Deprecate and remove sdss_main_site_subtheme from codebase (#629)
91eef71 SDSS-1536: Streamline Lockup Settings and Fix Theme Configuration Bugs (#614)

### Removed
91eef71 SDSS-1536: Streamline Lockup Settings and Fix Theme Configuration Bugs (#614)
a1adfde SDSS-1530: Remove cron job step from provision documentation (#627)


### Fixed
5c06ad9 Added config_readonly patch.
3297d32 SDSS-0000: Bug fix for room reservations module (#642)
b884389 Fix custom logo fallback when Site Title is empty in Lockup Setting (#640)
30c7f2e SDSS-1523: Add aria-label to the video banner play and pause button (#635)
674f869 SDSS-1546: Fix full-width background for three and two-column layouts at large breakpoint (#633)
120e176 SDSS-1530: Fix permission denied errors by moving cron scripts to hooks directory (#628)

### Security
fff4b8d SDSS-1541: Routine maintenance (#634)

### Maintenance
32dac0d SDSS-0000: Room Reservation module updates for Stanford Educational Farm (#641)
fff4b8d SDSS-1541: Routine maintenance (#634)
91eef71 SDSS-1536: Streamline Lockup Settings and Fix Theme Configuration Bugs (#614)
d8f7e99 SDSS-1502: Deprecate and remove sdss_main_site_subtheme from codebase (#629)


## [4.12.1] - 2025-07-23

### Added
None.

### Changed
None.

### Deprecated
None.

### Removed
None.

### Fixed
7e7399b Updated views_taxonomy_term_name_depth module and multiple value patch.

### Security
None.

### Maintenance
None.


## [4.12.0] - 2025-07-17

### Added
f7f6cfe SDSS-1539: Map sdss:webdev Workgroup to Administrator Role for All Sites (#622)
79a482e Update room reservations module to latest version 8.1.16 (#617)
057b708 SDSS-1499: Add option and styles to SDSS theme to use Desktop Hamburger menu (#605)
dff05b2 SDSS-1500: Add utility nav to sdss_subtheme (#613)
e1fdd03 SDSS-1501: Hide utility nav up until the large breakpoint (#616)
c4a0ad9 SDSS-1503: Set default theme and enable theme settings for sustainability site via database update (#618)
b4ed4e6 SDSS-1530: Add drupal cron and drupal cron scheduler commands and scripts (#611)

### Changed
fb0749a SDSS-1527: Full-width video banner (#623)

### Deprecated
None.

### Removed
None.

### Fixed
f7f6cfe SDSS-1539: Map sdss:webdev Workgroup to Administrator Role for All Sites (#622)
ff9fc36 SDSS-1498: Add min-height to masthead inner to prevent menu overlap (#621)
2db63d9 SDSS-0000: Room reservation fixups (#620)
a644a43 Fix PHPCS issues.

### Security
0cb5b86 SDSS-1510,SDSS-1533: 10.4.x core upgrade and routine maintenance (#612)

### Maintenance
0cb5b86 SDSS-1510,SDSS-1533: 10.4.x core upgrade and routine maintenance (#612)


## [4.11.0] - 2025-06-24

### Added
e347324 SDSS-0000: Update stanford_earth_r25 room reservation module to 8.1.15 (#606)
5dce56e SDSS-856: Add field to conditionally hide the editorial sidebar title (#604)
855abb4 SDSS-1524, SDSS-1525, SDSS-1526: Provisioned fuels, gese, and sunproject (#601)
2b3b9e7 SDSS-1347 Add sdss_workgroup_tagging.settings to config_readonly whitelist (#600)
b2218df SDSS-1347: Workgroup auto-tagging (#581)
9d7335f Added database and branch clean-up workflows (#591)
4e1ca9c SDSS-1489, SDSS-1509: Create All News feed and add date/time to News This Week feed (#598)
ec02925 Add site retirement documentation (#597)
e5306b4 Added provisioning and requirements documentation (#595)
d0695ef SDSS-1518: Enable Better Exposed Filters module (#594)
5fc81ea SDSS-1518: Add and enable Better Exposed Filters module (#592)

### Changed
0fc670e SDSS-1525,SDSS-1526: Re-provision sites with new aliases (nuclear, energynexus) (#607)

### Deprecated
None.

### Removed
9de1463 SDSS-1493, SDSS-1494, SDSS-1495: Removed mce2050, seca, and carbon_removal sites (#596)

### Fixed
05f28e7 SDSS-1189: Hide original newsroom menu when JS is enabled (#603)
b8a4b1b SDSS-1444, SDSS-1189: A11y | Fix newsroom search button; Fix newsroom menu page load flashing (#602)
1030948 Fix clean-up workflow ssh_key variable.

### Security
755b230 SDSS-1517: Routine maintenance (#599)

### Maintenance
0d588b4 SDSS-1513: Add Akamai SureRoute test objects (#608)
755b230 SDSS-1517: Routine maintenance (#599)


## [4.10.1] - 2025-05-21

### Added
eb9463e Allow exclusion of sites in database copy workflow (#586)
b84172f Added database copying automation (#585)
b84172f Added ADR's (#585)
0a644d5 Add filter_format dependencies (#577)

### Changed
d261f66 Update code for getting Acquia API access token in blt commands (#587)
1b77fec Point integrative-design long alias to extreme eneregy efficiency site (#579)

### Deprecated
None.

### Removed
None.

### Fixed
732504b SDSS-1515: Update CAP importer to handle large org code arrays (#588)
2fa19af SDSS-1469: A11y | Add spacing between person node links (#583)
457b87e SDSS-1350: Adjusted News Sharing feed fields to raw output to properly import special characters in title and dek fields (#582)
74328c5 Import field configurations with allowed_formats before uninstalling allowed_formats module (#578)
0a644d5 Add filter_format dependencies (#577)

### Security
e72a86d SDSS-1507: Routine maintenance (#584)

### Maintenance
e72a86d SDSS-1507: Routine maintenance (#584)


## [4.10.0] - 2025-04-23

### Added
b8ab5f6a SDSS-000: Implemented http_cache_control module (#574)
0c96f5b3 SDSS-1496: Added more filters to Past Events list paragraph (#573)
a0dc1449 SDSS-586: Created Related Content list displays for People, News and Events (#460)

### Changed
ce245087 SDSS-460: Enabled the URL alias field on News, Events, and People node forms (#572)
75443354 SDSS-1445: Use the Authenticated role to render Search API results (#560)

### Removed
8ceb8590 SDSS-1445: Removed intranet search results test (#571)

### Security
45697cf4 SDSS-1472: Routine maintenance (#566)

### Maintenance
b8ab5f6a SDSS-000: Implement http_cache_control module (#574)
45697cf4 SDSS-1472: Routine maintenance (#566)


## [4.9.0] - 2025-03-25

### Added
46856be3 SDSS-1480: Add the ability to feature Events in the This Week newsletter (#561)
76265485 SDSS-1477: Manually point neza.stanford.edu to the nza site (#562)
ea872ea4 SDSS-1424: Added more social options to local footer; updated field_validation module (#556)

### Changed
8799cb89 SDSS-000: Change code deploy hook for enabling field_validation_legacy module (#565)
94a4304a SDSS-1477: Point nzea.stanford.edu to nza instead of neza.stanford.edu (#563)
4f7380ee SDSS-1442: Change memory limit increase to be during install tasks, not general drush commands (#557)

### Fixed
07e02c80 SDSS-1442: Set drush memory limit to 768M on Acquia (#555)

### Maintenance
5a4b3ce6 SDSS-1446: Added PHP Code Sniffer (PHPCS) to CI; cleaned-up code (#548)
54e9f17b SDSS-1478: Update functional tests to work with the latest Selenium version; update blt-sws; remove restore-keys from cache (#559)
ddd58f78 SDSS-1442: Remove inaccurate memory increase comment (#558)


## [4.8.1] - 2025-02-26

### Changed
105df37c SDSS-1332: Enhanced help text for News Sharing settings field (#540).
07a15dff SDSS-1333: Moved SDSS News Sharing Settings menu location to Configuration > Importers (#538).
a387a9a1 SDSS-1355: Changed default card style variant from Topic to Title (#537).
8177ef65 SDSS-1391: Added 'www' to Stanford University brand bar link (#534).

### Deprecated
7a5adcb3 SDSS-1370: Removed minimally_branded_subtheme from SDSS Profile (#536).

### Removed
7a5adcb3 SDSS-1370: Removed minimally_branded_subtheme from SDSS Profile (#536).

### Fixed
3082dd42 SDSS-1466: Restored Newsroom menu for sdss_main_site_subtheme (#552).

### Security
1ec46afa SDSS-1450: 4.7.4 Routine Maintenance (#547).

### Maintenance
1ec46afa SDSS-1450: 4.7.4 Routine Maintenance (#547).


## [4.8.0] - 2025-02-18

### Changed
933dc66b SDSS-1457: Changed local footer color (#544).
33d118f5 Made header logo clickable (#543).
20cd0926 SDSS-1411: Extended hamburger breakpoint to all breakpoints in sdss_main_site_subtheme (#539).

### Added
20cd0926 SDSS-1411: New sdss_main_site_subtheme (#539).
c0c1d799 SDSS-1452: New local video media type. Added it to image banner paragraph (#542).
33d118f5 SDSS-1413: Utility Navigation region to sdss_main_site_subtheme (#543).
eed0e34d SDSS-1412: New Utility Navigation menu (#518).

### Fixed
7bd0eb7e SDSS-1461: Quickstrike tweaks and fixes (#546).
67e3b531 SDSS-1461: Quickstrike tweaks and fixes (#545).
bcc0a7ff SDSS-1427: Fix SDSS News Sharing migration item_selector configuration (#529).


## [4.7.2] - 2025-01-13 / 2025-01-22

### Added
7bb8a797 SDSS-1439: Provisioned energy-transition-analysis.stanford.edu. (#530)

### Maintenance
f92fe9ea SDSS-1400: December 2024 maintenance and updates. (#527)
62f8c165 SDSS-1426: Update Gitpod setup documentation (#528)


## [4.7.1] - 2024-12-19

### Changed
56fd16c2 SDSS-1404: Quickstrike color updates (#520)

### Maintenance
17fefa07 Removed mysql56 dependency


## [4.7.0] - 2024-11-21

### Maintenance
ac4907ea SDSS-1314: Drupal 10.3 / PHP 8.3 (#510)
d680167c SDSS-1394: Update stanford_circle image style image mask file path (#508)


## [4.6.2] - 2024-10-16

### Removed
dfdebfd0 SDSS-1376: Remove "/r25/*/calendar" from block.stanford_basic.pagetitle (#496)
f62c0859 SDSS-1374: Drop featured filter from events this week RSS feed view (#495)
f278e47f SDSS-1369: Uninstall minimally_branded_subtheme (#493)
2c870628 SDSS-1281: Removed decanter from stanford_basic (#492)

### Fixed
74cd07a8 SDSS-1379: Set default value for Featured field (#504)
463cd60c Update search_api index processors to better handle URL's in text (#503)
0f5938b6 SDSS-1378: FIX | Set default value for explore more picker (#502)
a208304c SDSS-1377: Set default value for Imported via News Sharing field (#501)

### Maintenance
0aa9dc29 SDSS-1376: Update stanford_earth_r25 to v8.1.14 (#498)
459f406f SDSS-1376: Update stanford_earth_r25 module to 8.1.13 (#497)
28f8b3b9 SDSS-1362: Routine maintenance (#494)
856701d5 SDSS-000: Add patch to remove defunct placeimg URL from unit test (#500)
b7c111b2 SDSS-000: Changed drupal core constraint (#499)
ff978a45 SDSS-1367: Deprecated and updated inclusion of Drupal merge request patches (#491)
7fcaa8ad SDSS-1313: Consolidated stanford_profile_helper into the sdss_profile (#489)
e573ba13 SDSS-1366: Consolidated drupal-patches into SDSS (#490)


## [4.6.1] - 2024-09-06

### Fixed
0e9be86a SDSS-1365: Update colorbox module to v2.1.1 (#486)


## [4.6.0] - 2024-09-04

### Added
61139bfa SDSS-1359: Set Explore More Options default for existing content (#483)
1dce19d7 SDSS-1360: DEV | Set Featured Events default value for existing content (#482)
f8fbd72f SDSS-1299: Add Explore More options and display to News (#466)
c2303a11 SDSS-1337: Track imported News Sharing content (#478)
d4136ee8 SDSS-1300: Added featured event field and feeds (#477)

### Changed
f9ef95db SDSS-1358: Allow all editors to use Related Content field (#480)
70f3e232 SDSS-1185: Update caption text to be centered to the image and center aligned (#464)
2fb128a6 SDSS-1341: Allow News Lists to be filtered by focal areas, organization and shared tags (#469)


## [4.5.0] - 2024-07-31

### Added
b4624ad3 SDSS-1276: News Sharing MVP (#465)
09875389 SDSS-1135: Added publishing_date to source id for news sharing migration (#473)'
26c88097 SDSS-1302: Added larger left and larger right 2 column layouts (#453)
b5f6f538 SDSS-1307: Point esos.stanford.edu (#472)
ee3a472e SDSS-1250: Provisioned sustainablesocieties (#454)
17a43164 SDSS-1201 | @jdwjdwjdw | Add 4 column card grid option for lists, adjust one-column layout, fixup load more issue (#414)

### Changed
d1435eaa SDSS-1342: Allow Event Lists to be filtered by focal areas (#468)
0e68ff3b SDSS-1296: Update SDSS profile installation (#458)

### Removed
e14cfa29 SDSS-1270: Deprecate and fully remove earth_news_importer (#457)
98052f9c SDSS-1305: Removed width options from SDSS layout paragraphs (#448)
de48fc47 SDSS-1268: Removed white option from paragraph section background colors (#430)

### Fixed
eae0c70c FIXUP - turn off readonly for news sharing form (#467)
cc4e3751 SDSS-000 | @jdwjdwjdw | Full width specificity fixup (#456)

### Maintenance
87b8c7a8 SDSS-1308: Routine maintenance (#455)


## [4.4.2] - 2024-06-05

### Added
- 843b95cb SDSS-1303: Provisioned cepp (#449)
- bc2d5adf SDSS-1291: Label articles in RSS feed with external source (#435)

### Fixed
- 3a2cc46c SDSS-478: Fix to the wysiwyg spacing with section background colors (#421)
- 3af560e6 Fix changelog commit lists.
- 86633bf4 SDSS-834: Hide non-display paragraphs values in Layout Paragraphs preview (#428)

### Changed
- e723a971 SDSS-1253: Unpoint techtransferfordefense from hackingfordefense (#447)
- ff898444 SDSS-1283: Changed default media image mode to full responsive (#439)
- ce197716 SDSS-1288: Exclude Media Mentions from Explore More on News (#432)


## [4.4.1] - 2024-05-16

### Fixed
- dcb09224 SDSS-1294: Fixed logger syntax in SDSS Profile InstallTask (#443)


## [4.4.0] - 2024-05-16

### Added
- ff26b069 SDSS-1290: Updated Github action CI release workflow (#440)
- 193a1115 SDSS-1256: Added Sustainability Site Sync test and improved test automation (#431)
- 61cff2f8 SDSS-1269: Provisioned hph (#425)
- d3cd6d37 SDSS-1262: Provision for techtransferfordefense (#424)
- f4715d8e SDSS-1272: Enable search by title (#420)
- a0cdcc5c SDSS-1261: List of media mention spacing (#413)
- 9b0fa49e SDSS-1220: Add Media Mentions list display to News list paragraph (#409)

### Fixed
- df338cfe SDSS-1292: Change headline CSS specificity (#436)
- faf600ac SDSS-1286: Added admin css to stanford_profile_admin_theme and hid heading fields (#433)
- 89d92a5a SDSS-1249: Button color fix (#406)

### Changed
- bd45e591 SDSS-1266: Excluded Media Mention News from Newsroom Search (#427)
- 4792b275 SDSS-1266: Excluded Media Mention News from Site Search (#422)
- 9d5ebc1d SDSS-1257: Maintenance and dependency updates (#401)
- d999afe4 SDSS-1191: Changed headline and dek for xl-md breakpoint (#389)
- 0472d25a SDSS-1263: Change link colors for all inline textual links (#412)
- a6932607 SDSS-252: Allow Events List paragraph to be filtered by multiple taxonomies (#407)
- bad9fb7f SDSS-1265: Allow adding Basic Pages to Newsroom Menu from Edit Node form (#411)
- b054c483 SDSS-1235: Local footer config update (#405)
- 001759e3 SDSS-1252: Added additional terms to contextual filter for people list paragraph (#408)

### Removed
- fb883610 SDSS-1218: Disabled page cache module (#426)
- 3ed39a3b SDSS-1255: Uninstall earth_news_importer (#415)
- 6f9cc082 SDSS-1182: Remove Magazine Topics taxonomy (#410)


--------------------------------------------------------------------------------


4.3.2
--------------------------------------------------------------------------------
_Release Date: 2024-04-23_

- 109012a5 SDSS-1271: Updated stanford_samlauth module (#417)


4.3.1
--------------------------------------------------------------------------------
_Release Date: 2024-04-11_

- 725669a4 SDSS-869 | @jdwjdwjdw | Adjust news list card (#402)
- c1b2a0f1 SDSS-1161: fixing the spacer error (#400)
- f1028b26 SDSS-359: number of people on initial load of view page (#398)
- 01e53da9 DEVOPS-000 - PHP 8.2 and removinghooks (#397)
- d6613a5f SDSS-1245: decoupled menu labels (#396)
- 8a4a83fb SDSS-637: Publication shared tags list and Teasers (#393)
- a0a47bf8 SDSS-1244: fine tuning a11y fix (#395)
- b4c941e9 SDSS-869 | @jdwjdwjdw | Add News List Card paragraph type (#391)
- d517da97 SDSS-1072: Added role report blt command (#394)
- 17185222 SDSS-1230: added arialabeledby (#392)
- e868dd48 SDSS-1238: Add 'view r25 room calendars' user perm to sdssroom config (#390)
- 8458973e SDSS-1228: lazy load (#388)
- 6c83a172 SDSS-1036: Fix authoring font sizes  (#387)
- 51e88a93 SDSS-1237: fix to max-width (#386)
- ad932da8 SDSS-1142: Uninstalled views_rss module (#385)


4.3.0
--------------------------------------------------------------------------------
_Release Date: 2024-03-07_

- 20a6a944 SDSS-000: Updated blt-sws for parallel deploy fix (#382)
- e5638725 SDSS-1233: Add parallel deployment (#381)
- 684cae6d SDSS-1222: Added Newsroom menu label field and alter hook (#378)
- d5cab0ef SDSS-1080 | @jdwjdwjdw | Add threads social media icon to local footer, update social icon colors on hover (#375)
- 63826e10 SDSS-1226: no js available for newsroom menu (#374)
- b06ab72e Update list load more button margin-top (#380)
- 40f4f28d SDSS-1221: News list Views display without Media Mentions and with Load More (#377)
- 0cbbf7e7 SDSS-1225: Removed version from all libraries in sdss_profile themes (#379)
- bdd590bb SDSS-1125: fix to the red link in a dek (#376)
- c2ccdf78 SDSS-1223: Fix contextual menu button issue (#373)
- 42fdad4a SDSS-1216: Disabled page_cache_query_ignore (#371)
- 86efd19b SDSS-1202 | @jdwjdwjdw | Fixup news teasers (#367)
- 3ce13142 SDSS-1200: Removed the extra space on empty div (#365)
- f5f7b337 SDSS-1156: adding button state to newsroom school news menu (#368)
- 21c16e2c SDSS-1128: PHP 8.2 Upgrade and Dependency Updates (#339)


4.2.0
--------------------------------------------------------------------------------
_Release Date: 2024-02-21_

- 5d94fee3 (HEAD -> 4.x, origin/4.x) SDSS-812: SQUIZ | Add and enable Stanford Syndication module (#207)
- d4f60062 Sdss 1070  button margins (#362)
- f63bac6c (SDSS-835--fix-illegal-choice) SDSS-1166: Use service to load config sync storage (#366)
- 1c2d26ce SDSS-1176 and SDSS-241: fixup to the cards when added to a green background (#351)
- 6eaebd15 SDSS-1166: Added Media Mention field and displays to List paragraph (#346)
- 789a4a94 SDSS-1172: Added sync-staging and show-sites BLT Commands (#363)
- c486a94c SDSS-988: Fixed SDSS reviewer role (#361)
- d1b0af1c SDSS-372: Open up metatag defaults for editing (#360)
- 631cc0e5 SDSS-350: Added Room Reservation config_split and updated stanford_earth_r25 module (#340)
- 8efba57e Merge branch '4.x' of github.com:SU-SWS/ace-sdssgryphon into 4.x
- 1556baf8 Fixed example.local.sites.php.
- 7d3ec988 SDSS-1129: Rename four-column layout to one-four-one (#359)
- de0982df Added example.local.sites.php.
- c20c9174 SDSS-1129: 4 column layout with a header and footer, an ibeam (#338)
- 19e5e562 SDSS-1188: Linked Newsroom menu logo to /news page (#355)
- f5085d56 SDSS-997: Remove local footer lockup and replace with SDSS logo (#357)
- 865817f8 SDSS-000: Moved manage content menu link to bottom of content menu (#356)
- 0cf66e6a SDSS-000: Updated stanford_layout_paragraphs patch to remove test.
- 50ba8bf0 SDSS-000: Added patch to drop layout paragraph restrictions from stanford_layout_paragraphs (#358)
- 22d85c6a SDSS-1170: Add region based paragraph restrictions for layout_paragraphs (#353)
- 8597d70d SDSS-988: Added Reviewer role (#264)
- 63b07130 SDSS-469: Removed lock-up options SDSS does not support (#277)
- 93e9e465 SDSS-1183 | @jdwjdwjdw | Add padding to two-column editorial sidebar's with background colors (#354)
- 757381df SDSS-1157: Circle backgrounds on News Spotlight. (#344)
- c69bdbbd Update Card help text (#289)
- 2bb66fc1 SDSS-1081: removed the margin from further up the tree (#350)
- a8d31b6c SDSS-1134: Added Manage Content views and menu for key content types (#345)
- 062a97e6 SDSS-1060: Removed featured media label from view (#287)
- 9c04be27 SDSS-289: Enabled nesting on Shared Tags (#352)
- 6dbddd31 SDSS-1173: Display Stanford site alias on Status Report page (#349)
- b2588966 SDSS-985: Increased list paragraphs term depth (#313)
- e1b42970 SDSS-1163: Provision sustainablemobility (#348)
- f5df4b90 SDSS-1160: Provision environmentalsocialsci (#342)
- d812eaa6 SDSS-846: fix the misspelled tabindex (#341)
- f3ec990a SDSS-1076: Widening the max-width of site to 2000px (#315)
- b529a2b4 SDSS-1135: Point eep alias to gep site (#337)
- 7009187f Merge pull request #336 from SU-SWS/backtodev-4.1.1
- c36e248b (origin/backtodev-4.1.1, backtodev-4.1.1) backtodev-4.1.1


4.1.0
--------------------------------------------------------------------------------
_Release Date: 2023-12-13_

- ae854be SDSS-1145: Added Magazine Topics vocabulary to News list paragraph filters (#334)
- 2e8dd0b SDSS-1138: Added News search link to the News menu (#331)
- a72fb11 SDSS-868: NEWS | Added Spotlight paragraph type (#294)
- 3180a4b SDSS-1124: Newsroom search page (#328)
- 5c6bc1c SDSS-1133: Add sorting options to shared tags list paragraph (#329)
- 592f3f5  SDSS-595 | SDSS-596: Add Newsroom menu (#189)
- ab744c8 SDSS-808: Allow site managers to edit metatags (#286)
- f57d032 Merge pull request #327 from SU-SWS/backtodev-4.0.3
- 0dc9e03 backtodev-4.0.3


4.0.2
--------------------------------------------------------------------------------
_Release Date: 2023-11-30_

- 3e73be0 SDSS-1089: Added JS to redirect to current page when clicking saml_login links (#325)
- 409459e SDSS-1106: Ignore SDSS Subtheme settings (#324)
- 483e153 SDSS-1127: Use php8.1 docker images for automation (#323)
- fc77bcf SDSS-1084 | SDSS-1085: Increase bottom margin on Callout and Editorial Sidebar (#316)
- 7bd3a53 SDSS-1079: Updated stanford_earth_r25 module to 8.1.10 (#321)
- abbc14a SDSS-1103 | SDSS-1063: Provision waters-wood and sustainability-accelerator (#320)
- dc354c9 SDSS-987: Updated publications styles (#248)
- dda4335 SDSS-1086: Updated margins on images in wysiwyg (#314)
- b9eb51a Update .gitpod.yml (#319)
- e17defe Merge pull request #311 from SU-SWS/backtodev-4.0.2
- 387ea1c backtodev-4.0.2


4.0.1
--------------------------------------------------------------------------------
_Release Date: 2023-11-16_

- 92aeec3 SDSS-000: Removed memache packages from composer.json (#309)
- 264d452 SDSS-919: A11y | Added background-color behind banner images (#303)
- 63aee37 SDSS-998: Added space between masthead and article on News nodes (#304)
- a74c1bb SDSS-1082: Adjusted News node byline spacing (#307)
- 4da285f SDSS-1101: Moved SDSS theme JavaScript (#308)
- caea03d SDSS-1087: Added bottom margin to all layout paragraph sections (#305)
- a304ea9 SDSS-1092: Removed JS compiling in sdss_subtheme (#306)
- 43533f7 Merge pull request #302 from SU-SWS/backtodev-4.0.1
- 413b171 backtodev-4.0.1


4.0.0
--------------------------------------------------------------------------------
_Release Date: 2023-11-08_

- 6f23a79 SDSS-000: Point understand-energy dev and test domains (#300)
- 1242c3d SDSS-994: D10 | Gitpod support for 4.x (#299)
- 1e14218 Merge pull request #298 from SU-SWS/SDSS-994--d10-upgrade
- 582474b SDSS-995: D10 updates (#297)
- 454ddb6 SDSS-1073: D10 | Automated test updates (#296)
- 788049e CKEditor CSS fix.
- beffa65 SDSS-1069: UPSTREAM | Pull stanford_profile 11.0.8 (#295)
- 777fc28 Merge pull request #293 from SU-SWS/backtodev-3.0.1
- 630042e backtodev-3.0.1


3.0.0
--------------------------------------------------------------------------------
_Release Date: 2023-11-01_

- fe1f6e0 SDSS-000: Added robots.txt patch (#291)
- 65af33d SDSS-1065: Github automation updates for 3.x (#290)
- 298d3e9 SDSS-1046: D9.5 | Fixed navigation wrapping and search icon styles (#282)
- 15646b5 SDSS-1046: D9.5 | Adjusted layout paragraph spacing (#283)
- e1e0980 SDSS-1053: Updated stanford_earth_r25 to 8.1.7 (#284)
- 14743d6 Merge pull request #280 from SU-SWS/SDSS-551--D9.5--upgrade
- b871d70 SDSS-1017: D9.5 |  Layout background colors style adjustments (#281)
- a4f43f2 Fixes to update hooks.
- 3287f03 SDSS-559: Upgrade path for react to layout paragraphs. (#278)
- 4158e23 SDSS-1005: D9.5 | 3.x | Automated test updates (#275)
- e865781 SDSS-000: Increase rss_feeds view date range from 7 to 8 days (#276)
- 4e06d1a SDSS-1007: Support stanford_basic page.html.twig updates in sdss_subtheme (#270)
- 276378a SDSS-889: Added bg_color Layout Option for SDSS layout paragraphs (#263)
- fe69d5a SDSS-996: Switch to saml_auth from simplesamlphp (#261)
- b2b8346 Upstream | Updates from stanford_profile 10.1.2 (#259)
- f4a6650 Merge pull request #257 from SU-SWS/NOJIRA-20231004-merge-2.x
- 5612edd Updated subtheme version.
- d2f7c6f Merge branch '2.x' into NOJIRA-20231004-merge-2.x
- ae71e6b SDSS-849: A11Y | FE | Updated decanter and moved Skip to Main link into a landmark (#254)
- 8abaf45 SDSS-936: Updated line height for all image captions globally (#247)
- bf6cac5 SDSS-983: BUG | Fix role permissions for SDSS entities (#246)
- f9f3429 SDSS-968: Implement Media Contacts QA feedback (#241)
- 219db3a Merge pull request #245 from SU-SWS/backtodev-2.2.1
- aedf6ba backtodev-2.2.1
- bb968a1 Merge branch '2.x' into 3.x
- c5d83e5 SDSS-551: Drupal 9.5; Claro Admin theme; CKEditor 5; Layout paragraphs (#160)
- 5a0b8de Merge branch '2.x' into 3.x
- e6fd297 Merge branch 'feature/newsroom-layout-paragraphs' into 3.x


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
