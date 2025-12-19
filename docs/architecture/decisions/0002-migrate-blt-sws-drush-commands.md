# 2. Implement SWS Drush Commands and Deprecate BLT

## Date

2025-12-19

## Status

Accepted


## Context

The [acquia/blt](https://github.com/acquia/blt) tool  used in this project was officially deprecated in December 2024. While BLT still functions, an adequate SWS maintained replacement ([su-sws/sws-drush-commands](https://github.com/SU-SWS/sws-drush-commands
)) is available and already used in other projects. SWS Drush Commands supports the majority of the commands previously provided by BLT. Additionally, BLT does not support Drupal 11 and is a blocker for the required D11 upgrade, which must be completed by Summer 2026.

Migrating to sws-drush-commands positions the project for the Drupal 11 upgrade and removes reliance on deprecated and unsupported packages.


## Decision

Adopt SWS Drush Commands and deprecate the use of BLT in this project.


## Consequences

- Workflows, scripts, and documentation must be updated to use SWS Drush Commands.
- There is not 100% feature parity between BLT and SWS Drush Commands; additional commands or modifications may be required to fully support the codebase.
- There is no period we can have BLT and SWS Drush Commands overlap, it requires a complete adoption of SWS Drush Commands.
