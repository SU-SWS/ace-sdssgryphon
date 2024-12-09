# ACE SDSSGryphon

The ACE SDSSGryphon stack serves the Doerr School of Sustainability. This stack is based on the [SU-SWS/ace-gryphon](https://github.com/SU-SWS/ace-gryphon) stack.

The primary theme for SDSS is the `sdss_subtheme` located in the `sdss_profile`.

----
# GitPod Setup
1. Add your ssh key to [GitPod](https://gitpod.io/variables)
   1. It is recommended to have a password-less ssh key for simplicity.
      1. `ssh-keygen -b 4096`, press enter when asked for the password
      2. Add this ssh public key to the necessary services: Acquia, Github, etc.
   2. Get the base64 string of your ssh key files
      1. `cat id_rsa | base64` for the private key
      2. `cat id_rsa.pub | base64` for the public key.
      - NOTE: Use `-w0` with base64 to remote any white space/line breaks.
   1. Get the base64 string of your gitconfig
      1. `cat ~/.gitconfig | base64`
   3. In GitPod, add a variable named `SSH_PRIVATE_KEY` with the base64 encoded private key
   4. In GitPod, add a variable named `SSH_PUBLIC_KEY` with the base64 encoded public key
   5. In Gitpod, add a variable named `GITCONFIG` with your base64 encoded git config
2. Recommended, but not required:
   1. Install the GitPod browser plugin
   2. Configure your browser settings for an easier experience: https://www.gitpod.io/docs/configure/browser-settings
3. Open a gitpod workspace with [these instructions](https://www.gitpod.io/docs/getting-started#start-your-first-workspace)


----
# Config Management
Each site has the ability to determine its own configuration management strategy.
The default site in this repo will be using a configuration management that uses
the configuration from the `sdss_profile`. By default this is the behavior
of all other sites unless defined within their own settings.php.

There are three options a site can choose from:
1. Do nothing and the configuration sync directory will use what is in `sdss_profile`.
2. Modify the configuration sync directory to a desired directory such as another profile.
3. Modify the configuration sync directory to point to an empty directory. This
will bypass any configuration management strategy and the site's configuration will be updated via update hooks.

----
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

Optional:
If you wish to not provide statistics and user information back to Acquia run
     ```
    $ blt blt:telemetry:disable --no-interaction
    ```
# Setup Local Environment - Lando
---

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
3. After the basic installation is complete you may be prompted for an Acquia API token, generated [here](https://cloud.acquia.com/app/profile/tokens). Then it will update the drush aliases, and it will sync down the `sustainability` database and files, and your app should be available at `http://sustainability.lndo.site`. SSO will be configured automatically.


---
## Other Local Setup Steps

1. Set up frontend build and theme.
By default BLT sets up a site with the lightning profile and a cog base theme. You can choose your own profile before setup in the blt.yml file. If you do choose to use cog, see [Cog's documentation](https://github.com/acquia-pso/cog/blob/8.x-1.x/STARTERKIT/README.md#create-cog-sub-theme) for installation.
See [BLT's Frontend docs](https://docs.acquia.com/blt/developer/frontend/) to see how to automate the theme requirements and frontend tests.
After the initial theme setup you can configure `blt/blt.yml` to install and configure your frontend dependencies with `blt setup`.

2. Pull Files locally.
Use BLT to pull all files down from your Cloud environment.

   ```
   $ blt drupal:sync:files
   ```

3. Sync the Cloud Database.
If you have an existing database you can use BLT to pull down the database from your Cloud environment.
   ```
   $ blt sync
   ```


---

# Resources

Additional [BLT documentation](https://docs.acquia.com/blt/) may be useful. You may also access a list of BLT commands by running this:
```
$ blt
```

Note the following properties of this project:
* Primary development branch: 1.x
* Local environment: @default.local
* Local site URL: http://local.example.loc/

## Working With a BLT Project

BLT projects are designed to instill software development best practices (including git workflows).

Our BLT Developer documentation includes an [example workflow](https://docs.acquia.com/blt/developer/dev-workflow/).

### Important Configuration Files

BLT uses a number of configuration (`.yml` or `.json`) files to define and customize behaviors. Some examples of these are:

* `blt/blt.yml` (formerly blt/project.yml prior to BLT 9.x)
* `blt/local.blt.yml` (local only specific blt configuration)
* `box/config.yml` (if using Drupal VM)
* `drush/sites` (contains Drush aliases for this project)
* `composer.json` (includes required components, including Drupal Modules, for this project)

## Resources

* GitHub - https://github.com/SU-SWS/ace-gryphon
