# ACE SDSSGryphon
The ACE SDSSGryphon stack serves the Doerr School of Sustainability. This stack is based on the [SU-SWS/ace-gryphon](https://github.com/SU-SWS/ace-gryphon) stack.


# Docs
- [Development Requirements](docs/development-requirements.md)
- [Release and Deployment](docs/release-deploy.md)
- [New Site Provision](docs/new-site-provision.md)
- [Retire Site](docs/retire-site.md)


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
You can set up this stack as a complete development environment using nothing more than Docker and Lando installed in your linux-like environment.

### Prerequisites

#### Linux/MacOS
1. Set up Docker on your distro of choice.  Instructions for installing Docker in linux [can be found here](https://docs.docker.com/desktop/linux/install/), and these are [the MacOS instructions.](https://docs.docker.com/desktop/mac/install/)
2. Set up Lando on your distro of choice.  Instructions for installing Lando in linux [can be found here](https://docs.lando.dev/getting-started/installation.html#linux), and these are [the MacOS instructions.](https://docs.lando.dev/getting-started/installation.html#macos)

#### Windows/WSL
Because Docker works best with Windows Subsystem for Linux V.2, we suggest you proceed that way.
1. [Install Windows Subsystem for Linux V.2](https://docs.microsoft.com/en-us/windows/wsl/install)
2. [Install Docker Desktop for Windows, and enable the WSL2 extensions.](https://docs.docker.com/desktop/windows/wsl/)
3. [Install Docker for linux in your WSL2 environment.](https://docs.docker.com/desktop/linux/install/ubuntu/)
4. Install Lando for linux in your WSL2 environment.  [Instructions can be found here.](https://docs.lando.dev/getting-started/installation.html#linux)

No other prerequisites are necessary, though you may find it helpful to have PHP 8.1+, Composer 2, and Git installed locally on your system.

### Installation
1. Clone this repo to a convenient place in your environment, and change directories to the location to which you cloned it.
2. In the repo root, run `./lando/setup_lando.sh`.
3. After the basic installation is complete, it will update the drush aliases, and it will sync down the `sustainability` database and files, and your app should be available at `http://sustainability.lndo.site`. SSO will be configured automatically.


# Important Configuration Files

BLT uses a number of configuration (`.yml` or `.json`) files to define and customize behaviors. Some examples of these are:

* `blt/blt.yml` (formerly blt/project.yml prior to BLT 9.x)
* `blt/local.blt.yml` (local only specific blt configuration)
* `drush/sites` (contains Drush aliases for this project)
* `composer.json` (includes required components, including Drupal Modules, for this project)
