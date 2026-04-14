# ACE SDSSGryphon
The ACE SDSSGryphon stack serves the Doerr School of Sustainability. This stack is based on the [SU-SWS/ace-gryphon](https://github.com/SU-SWS/ace-gryphon) stack.


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


# Setup Local Environment - Native LAMP Stack
(See below for Lando config)

BLT provides an automation layer for testing, building, and launching Drupal 8 applications. For ease when updating codebase it is recommended to use  Drupal VM. If you prefer, you can use another tool such as Docker, [DDEV](https://docs.acquia.com/blt/install/alt-env/ddev/), [Docksal](https://docs.acquia.com/blt/install/alt-env/docksal/), [Lando](https://docs.acquia.com/blt/install/alt-env/lando/), (other) Vagrant, or your own custom LAMP stack, however support is very limited for these solutions.
1. Install Composer dependencies.
After you have forked, cloned the project and setup your blt.yml file install Composer Dependencies. (Warning: this can take some time based on internet speeds.)
    ```
    $ composer install
    ```
2. Setup a local blt alias.
If the blt alias is not available use this command outside and inside vagrant (one time only).
    ```
    $ composer run-script blt-alias
    ```
3. Set up local BLT
Copy the file `blt/example.local.blt.yml` and name it `local.blt.yml`. Populate all available information with your local configuration values.

4. Setup Local settings
After you have the `local.blt.yml` file configured, set up the settings.php for you setup.
    ```
    $ blt blt:init:settings
    ```
5. Get secret keys and settings
SAML and other certificate files will be download for local use.
     ```
    $ blt sws:keys
    ```

# Setup Local Environment - Lando

You can set up this stack as a complete development environment using nothing more than Docker and Lando.

### Prerequisites

1. [Install Lando](https://lando.dev/download/) (includes Docker Desktop)
2. Acquia Cloud credentials in `~/.acquia/` (for syncing databases)
3. SSH keys in `~/.ssh/` (for Acquia access)

### Installation

1. Clone this repo and `cd` into it.
2. Copy the Lando config: `cp lando/default.lando.yml .lando.yml`
3. Start Lando: `lando start`
4. Create a database and sync: `lando create-db sustainability && lando sync sustainability`
5. Visit `https://sustainability.sdss.lndo.site`

See [lando/README.md](lando/README.md) for full documentation including domain routing, available tooling commands, theme builds, and troubleshooting.


# Important Configuration Files

BLT uses a number of configuration (`.yml` or `.json`) files to define and customize behaviors. Some examples of these are:

* `blt/blt.yml` (formerly blt/project.yml prior to BLT 9.x)
* `blt/local.blt.yml` (local only specific blt configuration)
* `drush/sites` (contains Drush aliases for this project)
* `composer.json` (includes required components, including Drupal Modules, for this project)
