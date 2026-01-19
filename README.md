# ACE SDSSGryphon
The ACE SDSSGryphon stack serves the Doerr School of Sustainability. This stack is based on the [SU-SWS/ace-gryphon](https://github.com/SU-SWS/ace-gryphon) stack. The project was initially created with [Acquia's BLT tool](https://github.com/acquia/blt/). After BLT's deprecation in December 2024, the project moved to the SWS maintained [su-sws/sws-drush-commands](https://github.com/SU-SWS/sws-drush-commands). SWS Drush Commands leverages Drush to have feature parity to important BLT commands, but is not exactly the same.

## Local Environment Setup
Please see the [Local Environment Setup](docs/local-environment-setup.md) Documentation.

# Docs
- [Development Requirements](docs/development-requirements.md)
- [Release and Deployment](docs/release-deploy.md)
- [New Site Provision](docs/new-site-provision.md)
- [Retire Site](docs/retire-site.md)
- [Routine Maintenance](docs/routine-maintenance.md)
- [Upgrading Drupal Core](docs/drupal-core-upgrades.md)
- [Testing](docs/testing.md)
- [Patching and patch management instructions](patches/README.md)


# SDSS Theme
The primary theme for SDSS is the `sdss_subtheme` located in the `sdss_profile`. Theme assets can be compiled via composer in the root directory.

1. Ensure you have the required Node version (see `.nvmrc` in the project root).
2. Run `nvm use` in your terminal (one time only per session).
3. Run `composer build-theme` to compile theme assets.


# ADR's
This site uses Architecture Decision Records to record important technical decisions and the context surrounding them. ADR's can be found in [docs/architecture/decisions](docs/architecture/decisions/). For more information see [0. Record architecture decisions](docs/architecture/decisions/0000-record-architecture-decisions.md).
