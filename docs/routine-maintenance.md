# SDSS Routine Maintenance

This document outlines the recommended process for performing routine maintenance for this application. Routine maintenance ensures the codebase remains healthy, secure, and up-to-date. Some steps are automated, while others require manual review and action.

> ⚡ **Note:** Automated dependency updates handle most regular package upgrades, but manual maintenance is still required for reviewing constraints, troubleshooting, and other tasks not covered by automation.

## Automated Dependency Update Coverage
This codebase runs automated dependency updates weekly via a Github Action. The automated workflow:
- Updates all modules and packages based on the composer configuration constraints
- Updates Drupal Core patch versions only
- Runs database updates and exports configuration changes
- Creates a pull request to be reviewed by a maintainer

> 💡 **Tip:** Automated updates only upgrade packages allowed by the current composer constraints. Major version upgrades, version-locked packages, and some conflicts will not be updated automatically.

## Routine Maintenance Coverage
Routine maintenance is intended to cover tasks that go beyond automated updates:
- Review outdated dependencies constrained by automated dependency updates (major versions, version-locked packages, composer conflicts, etc.)
- Identify, deprecate, and remove unnecessary packages and modules
- Perform other maintenance tasks or create tickets to inform and address them separately

> 🔍 **Why manual review?** Automated updates cannot resolve all dependency conflicts or update packages with strict constraints. Manual review is needed to keep dependencies current and remove unused code.

## Requirements
*See [SDSS Development Requirements](development-requirements.md)*

## Routine Maintenance Process

### Maintenance Workflow Overview

```text
Routine Maintenance Workflow:

  [Start]
     |
     v
  Set up local environment
     |
     v
  Update all packages (automated/manual)
     |
     v
  Assess package constraints
     |
     v
  Remove unused packages/modules
     |
     v
  Review updates & run code scans/tests
     |
     v
  Troubleshoot issues (composer/db/tests)
     |
     v
  [Done]
```

### Set up local environment

Before making any changes, ensure your local environment is up to date and ready for maintenance work.

**Checklist:**
- [ ] Check out the most recent version of the develop branch (e.g., `git checkout 4.x`)
- [ ] Install current packages (`composer install`)
- [ ] Install the `sdss_profile` on the default site (`drush @default.local si sdss_profile -y`)
- [ ] Make sure all configuration is up to date after installing and syncing (`drush @default.local ci -y`, repeat until nothing left to import)
- [ ] Create a new branch for any updates (e.g., `git checkout -b TICKET-123--routine-maintenance`)


### Update all packages based on existing constraints

> 🤖 **Automated:** This step is performed weekly by the automated workflow, but you may run it manually if needed. Manual updates may be necessary if you have just changed composer constraints or resolved a conflict.

**Checklist:**
- [ ] Run a composer update with dependencies (`composer update -W`)
- [ ] Run database updates on the local site (`drush @default.local updatedb -y`)
- [ ] Export configuration post database update (`drush @default.local config-export -y`)
- [ ] Commit and push your changes

**Why run manually?** Running updates manually after changing constraints or resolving conflicts ensures your local environment reflects the latest possible package versions.

#### Run a composer update with dependencies

`composer update -W`

#### Run database updates on the local site

`drush @default.local updatedb -y`

#### Export configuration post database update

`drush @default.local config-export -y`

> ⚠️ **Important:** The config-export step is critical because database updates can and will change configuration. If those changes are not exported, it could create problems down the line or during deployment.

> 🚨 **Warning:** If you run a config-import after running database updates, it will overwrite any configuration changes made by the updates. If this happens, you will need to re-install/re-sync the local site and start over.

Commit and push your changes.


### Assess Package Constraints to Update

**Checklist:**
- [ ] Review outdated packages using `composer outdated` or `composer show -lo`
   - Example: `composer outdated --direct`
- [ ] Investigate conflicts with `composer why-not PACKAGE VERSION`
   - Example: `composer why-not drupal/core 10.1.6`
- [ ] Test updates with `composer require PACKAGE/VERSION --dry-run`
   - Example: `composer require "drupal/core:10.1.16" --dry-run`
- [ ] Update constraints if safe, then repeat update steps above
- [ ] Commit and push your changes

To avoid conflicts/issues, [**packages are constrained**](https://getcomposer.org/doc/articles/versions.md#versions-and-constraints) to specific minor or patch versions (see [SEMVER](https://semver.org/) for more info on major, minor and patch versions). Occasionally you will want to re-assess if it is safe to change those constraints and upgrade to a latest version. This may require previous knowledge of the issue or a manual assessment of those packages and what is in the latest versions.

You can do this step *before* updating all packages with existing constraints, but it’s often more helpful to update packages based on the existing constraints and resolving any issues with those updates before jumping into this.

A few composer commands to help see outdated packages:

`composer outdated` or `composer show -lo`
   - [Composer outdated documentation](https://getcomposer.org/doc/03-cli.md#outdated)
   - [Composer show documentation](https://getcomposer.org/doc/03-cli.md#show-info)
   - The `--direct` flag is also useful to only see direct dependencies.
`composer why-not PACKAGE VERSION`
   - Example: `composer why-not su-sws/drupal-patches 10.1.6`
   - If the `composer show` command shows an outdated package but the constraint for the package *should allow it* to upgrade, there is probably a conflict. The `composer why-not` command can help determine why the package is not being updated.
`composer require PACKAGE/VERSION --dry-run`
   - Example: `composer require "su-sws/drupal-patches:10.1.16" --dry-run`
   - Similar to `composer why-not`, this can help determine why a package is not updating. Running a `composer require` for a specific package version with the `--dry-run` parameter will run the command as a test, but won’t actually execute the command or make any changes.

Regularly review these packages to determine if constraints can be safely changed and upgrades performed. After updating a constraint, repeat the update steps above.
Commit and push your changes.


### Assess Packages Safe to Remove

**Checklist:**
- [ ] Check disabled and uninstalled modules on your local site (`drush @default.local pml --type=module --status="disabled,not installed" --no-core`)
   - Example: `drush @default.local pml --type=module --status="disabled,not installed" --no-core`
- [ ] Review the `core.extension.yml` in the profiles config/sync directory
- [ ] Confirm the package is not needed for other sites (check `config_ignore` settings)
- [ ] Remove the package (`composer remove PACKAGE`)
   - Example: `composer remove drupal/google_analytics`
- [ ] Commit and push your changes

Modules will get uninstalled, deprecated, or no longer be useful. Because modules need to be uninstalled in a separate deploy while the package still exists before the package can be removed, sometimes packages will be present for modules no longer in use.

A few ways to determine modules no longer in use:

- Check disabled and uninstalled modules on your local site  
   - `drush @default.local pml --type=module --status="disabled,not installed" --no-core`
- Review the core.extension.yml in the profiles config/sync directory. Modules no longer in use will not be listed here.

> 🛑 **Be cautious before removing a package!**

Only remove a package if you are certain it is not in use. Some packages are dependencies of others and may not appear in `core.extension.yml` but could still be enabled.

Some packages are used only on specific sites. Even if not enabled locally, they may be needed elsewhere. Check `config_ignore` settings to see if a module or its configuration is ignored—this usually means it is still in use somewhere.

To remove a package:

Run `composer remove PACKAGE`
   - Example: `composer remove drupal/google_analytics`

Commit and push your changes.


### Review Updates

**Checklist:**
- [ ] Do a basic click through of the site
- [ ] Login to the site
- [ ] Check the status page: `/admin/reports/status`

On the local site, review updates.
- Do a basic click through of the site.
- Login to the site.
- Check the status page: `/admin/reports/status`


### Additional Maintenance

**Checklist:**
- [ ] Review any additional maintenance tasks or tickets
- [ ] Address them here if appropriate, or create a separate task or PR if needed


#### Code Scans
**Checklist:**
- [ ] Run PHPStan for deprecation and static analysis (`vendor/bin/phpstan |& tee ~/logs/sdss/drupal-compatibility.log`)
- [ ] Review scan results for deprecations and actionable errors
- [ ] Run PHPCS for coding standards and PHP compatibility (`vendor/bin/phpcs`)
- [ ] For PHP version compatibility, run: `vendor/bin/phpcs -p docroot/modules --standard=PHPCompatibility --runtime-set testVersion 8.3 --extensions=php,module,install,inc`
- [ ] Create tickets for any issues that cannot be addressed immediately

See [Code Scans](code-scans.md) documentation for how to run these scans.

- Run PHPStan for deprecation and static analysis:
   - `vendor/bin/phpstan |& tee ~/logs/sdss/drupal-compatibility.log`
   - Review scan results for deprecations and actionable errors.
   - Focus on deprecation issues and breaking changes.
- Run PHPCS for coding standards and PHP compatibility:
   - `vendor/bin/phpcs`
   - For PHP version compatibility:
      - `vendor/bin/phpcs -p docroot/modules --standard=PHPCompatibility --runtime-set testVersion 8.3 --extensions=php,module,install,inc`


#### Updating Tests
**Checklist:**
- [ ] Investigate any test failures after updates
- [ ] Update tests as needed to work with changes
- [ ] If a test fails due to a real bug, follow database update troubleshooting steps
- [ ] See the [Testing documentation](testing.md) for more info



## Update Troubleshooting


### Composer Update Troubleshooting
**Checklist:**
- [ ] If package updates fail during `composer update`, review conflicts
   - Example: `composer update -W` (shows error/conflict output)
- [ ] Decide if another package needs to be updated or constrained to a specific version
   - Example: Add a constraint in `composer.json` or use `composer require drupal/google_analytics:2.1.6`


### Database Update Troubleshooting
**Checklist:**
- [ ] If database updates fail during `drush updatedb`, review the error and try a quick resolution
   - Example: `drush @default.local updatedb` (shows error output)
- [ ] Check the [Drupal.org issue queue](http://drupal.org) for similar problems
- [ ] Retry the update steps if needed
- [ ] If unresolved, upgrade to the most recent safe version and either [modify the constraint](https://getcomposer.org/doc/articles/versions.md#writing-version-constraints) or [add a conflict](https://getcomposer.org/doc/04-schema.md#conflict) for the package
   - Example: Add a constraint in `composer.json` or use `composer require drupal/google_analytics:2.1.6`
- [ ] Note the issue and review it during the next maintenance round
- [ ] Report unresolved issues in the [Drupal.org issue queue](http://Drupal.org) or comment on an existing thread
- [ ] If you find a solution, contribute a patch or share the resolution
