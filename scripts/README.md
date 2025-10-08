# Custom Scripts in this Directory

## Scripts

### run-drupal-cron.sh
Runs Drupal cron on all multisites using BLT. This should be scheduled regularly to ensure all sites perform routine maintenance tasks. 
* This script is written to be run on the Acquia application.

**Usage:**
```bash
./run-drupal-cron.sh [dev|test|prod]
```
Defaults to `test` if no environment is provided.

### run-drupal-cron-scheduler.sh
Runs the Drupal Scheduler cron on all multisites using BLT. This triggers any scheduled content publishing or unpublishing tasks and should be run more frequently than the standard cron.
* This script is written to be run on the Acquia application.

**Usage:**
```bash
./run-drupal-cron-scheduler.sh [dev|test|prod]
```
Defaults to `test` if no environment is provided.

### build-theme.sh
Builds the front-end assets for the SDSS subtheme using Yarn. This script ensures the correct Node version is used (via nvm), installs dependencies if needed, and runs the build process. 
* This script is written to be run on local environments and possibly as part of a CI workflow.

**Usage:**
```bash
./build-theme.sh
```
**Details:**
- Changes directory to `docroot/profiles/sdss/sdss_profile/themes/sdss_subtheme`.
- If `node_modules` does not exist, runs `yarn install --frozen-lockfile` to install dependencies.
- Runs `yarn build` to build theme assets.
- Make sure to run `nvm use` first to select the correct Node version.