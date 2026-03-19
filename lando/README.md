# Lando Setup

Local development environment using [Lando](https://lando.dev/).

## Prerequisites

1. [Install Lando](https://lando.dev/download/) (includes Docker Desktop)
2. Ensure you have Acquia Cloud credentials configured in `~/.acquia/` (needed for syncing databases)
3. Ensure your SSH keys are in `~/.ssh/` (needed for Acquia access)

## Quick Start

1. Copy the Lando config:

    ```bash
    cp lando/default.lando.yml .lando.yml
    ```

2. Build your containers:

    ```bash
    lando start
    ```

    This will:
    - Install PHP 8.3, MySQL 8.0, Node.js 20
    - Run `composer install` and initialize BLT settings
    - Patch local settings files for Lando compatibility
    - Set up the multisite domain mapping

3. Create a database and sync a site:

    ```bash
    lando create-db sustainability
    lando sync sustainability
    ```

4. Log in as admin:

    ```bash
    lando drush @sustainability.local uli
    ```

5. Visit the site at `https://sustainability.sdss.lndo.site`

## Domain Routing

This setup uses a **wildcard proxy** (`*.sdss.lndo.site`), so all multisites are automatically routable without `/etc/hosts` entries. The domain pattern is:

```
https://SITENAME.sdss.lndo.site
```

Where `SITENAME` is the site directory name with underscores replaced by dashes and double underscores replaced by periods. Examples:

| Site directory | Local URL |
|---|---|
| `sustainability` | `sustainability.sdss.lndo.site` |
| `energy_transition_analysis` | `energy-transition-analysis.sdss.lndo.site` |
| `woods_water` | `woods-water.sdss.lndo.site` |

## Tooling Commands

| Command | Description |
|---|---|
| `lando sites` | List all available multisites with URLs |
| `lando sync SITE_NAME` | Sync a site's database from Acquia |
| `lando create-db SITE_NAME` | Create a local database for a multisite |
| `lando drush @SITE_NAME.local COMMAND` | Run a drush command against a specific site |
| `lando blt COMMAND` | Run a BLT command |
| `lando node` / `lando npm` / `lando yarn` | Run Node.js tools |

### Workflow: Working on a Site

```bash
# 1. See what sites are available
lando sites

# 2. Create the database and sync
lando create-db energy
lando sync energy

# 3. Log in
lando drush @energy.local uli

# 4. Visit the site
# https://energy.sdss.lndo.site

# 5. Clear cache after changes
lando drush @energy.local cr

# 6. Export config changes
lando drush @energy.local config-export
```

## Common Commands

* `lando drush @SITE_NAME.local uli` - Get an admin login link
* `lando drush @SITE_NAME.local cr` - Clear cache
* `lando drush @SITE_NAME.local config-export` - Export config
* `lando drush @SITE_NAME.local config-import` - Import config
* `lando info` - Check your Lando config, URLs, ports, etc.
* `docker ps` - Check that your Docker containers are running

## Building the Theme

The SDSS subtheme requires Node.js for building:

```bash
lando yarn --cwd docroot/profiles/sdss/sdss_profile/themes/sdss_subtheme install --frozen-lockfile
lando yarn --cwd docroot/profiles/sdss/sdss_profile/themes/sdss_subtheme build
```

Or use the composer script:

```bash
lando composer build-theme
```

## Local Overrides

To customize Lando settings locally (e.g., enable Xdebug), create a `.lando.local.yml` file (not committed to git):

```yaml
config:
  xdebug: true
```

Then run `lando rebuild -y` to apply changes.

## Adding a New Site

Because the wildcard proxy and dynamic `sites.php` mapping handle routing automatically, adding a new site is simple:

1. Create the site directory under `docroot/sites/` with the appropriate settings files (copy from an existing site).
2. Add the site to `blt/blt.yml` under `multisites`.
3. Run `lando rebuild -y` to regenerate settings files.
4. Create the database and sync: `lando create-db NEW_SITE && lando sync NEW_SITE`

No changes to `.lando.yml` or `/etc/hosts` are needed.

## SimpleSAML Authentication

To configure SimpleSAML for local SSO login:

1. Run `lando blt sws:keys`
2. Run `lando blt sbsc`
3. Copy the SimpleSAML config:
   ```bash
   cp lando/default.local.config.php vendor/simplesamlphp/simplesamlphp/config/local.config.php
   ```
4. Clear cache: `lando drush @SITE_NAME.local cr`

## Troubleshooting

### Proxy Issues

If the wildcard proxy isn't routing correctly:

1. Run `lando poweroff`
2. Remove the proxy container: `docker rm -f landoproxyhyperion5000gandalfedition_proxy_1`
3. Restart: `lando start`

### SSL Certificate

Trust the Lando development CA certificate for HTTPS to work without browser warnings:
[Trusting the CA](https://docs.lando.dev/core/v3/security.html#trusting-the-ca)

### Composer Timeout

If `composer install` times out: `lando composer --global config process-timeout 2000`

### Starting Fresh

If something is fundamentally broken: `lando destroy` will completely clear your Lando instance. Then start from step 1 of the Quick Start.
