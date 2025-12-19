# SDSS Site Provision

## Requirements
*See [SDSS Development Requirements](development-requirements.md)*

## Introduction
This document will walk through provisioning a new site on the SDSS Acquia application. A few quick notes:

- Acquia use "test" as the name for the "stage" environment in code.
- Placeholders in example code are in `ALL_CAPS` and meant to be replaced with the relevant data. 
  - **`URL_ALIAS`** refers to the name of the site in the URL and is used in URLs and domain names (e.g., `my-site`).
  - **`MACHINE_NAME`** refers to the name of the site in the code and is used in code and database naming (e.g., `my_site`).
  - This is expanded on in the next section.

## Provisioning Part 1

### Determine Site URL Aliases and machine name
Determine the machine name for directories and databases based on the URL alias.

Format: 
- One underscore becomes a dash (`_ = -`).
- Two underscores become a dot (`__ = .`).

For example: `my-site.stanford.edu`
- The `URL_ALIAS` is `my-site`
- The `MACHINE_NAME` is `my_site`

> :bulb: **Note:** If the full URL has an additional subdomain with a dot (E.g., `my-site.sws.stanford`) the configuration becomes a bit more complicated. The `MACHINE_NAME` would be `my_site__sws` and the `URL_ALIAS` would vary depend on where it's being entered.

### Establish Domains with Stanford
Create the NetDB URL aliases for all 3 environments [in NetDB](https://netdb.stanford.edu/). 

- The `-dev` and `-test` URL aliases go on the `se3-dev.stanford.edu` NetDB node.
- The `-prod` URL alias goes on the `Acquia-52-36-131-229.NoDomain` "Super Node" under the `sustainability-prod` Sub-Node.
  
URL Aliases:
```
URL_ALIAS-dev
URL_ALIAS-test
URL_ALIAS-prod
```

Example:
```
sustainability-dev
sustainability-test
sustainability-prod
```

> :bulb: **Note:** The live domain will be added at launch – stick to dev, test and prod.

#### Creating the URL Aliases
1. Click the Modify link on the node.
2. Enter the number of URL aliases to add in the "Count" Field (if in a "Super Node" make sure this is added under the correct "Sub-node").
3. Click "Add Alias".
4. Enter the URL aliases in the new empty fields.
5. Click "Save".

### Testing the Domains (Optional)
- DNS changes push out every 30 minutes on the :05 and :35 of the hour.
- Ping the site domain to check if it’s resolved (e.g., `ping -c 4 MACHINE_NAME.stanford.edu`).

### Ensure stack is setup locally with latest repo and access to ACQUIA

#### Ensure you are working off the most recent release branch
Get the latest default branch code. This is important because drush uses the local repo multi-site definition to run its commands. If you don’t have the latest code with all of the current multi-site definitions, the drush steps may fail.

- E.g., `git checkout 4.x && git fetch && git pull`

#### Get all updated dependencies
`composer install`

#### Create a new branch for provisioning
`git checkout -b TICKET(S)--provision-SITE(S)`

- E.g., `git checkout -b SDSS-123--provision-mysite`.
- E.g., `git checkout -b SDSS-123-124--provision-mysite-yoursite`
- E.g., `git checkout -b SDSS-123-124-125--provision-3-sites`

### Add the new domains to each of the ACQUIA environments
If you have [ACLI](https://docs.acquia.com/acquia-cloud-platform/add-ons/acquia-cli/acquia-cli-installation) installed you can use [ACLI Domain Create](https://docs.acquia.com/acquia-cloud-platform/add-ons/acquia-cli/commands/api:environments:domain-create). Otherwise, follow the Acquia UI steps below.

```
acli api:environments:domain-create <dev-environmentId> URL_ALIAS-dev.stanford.edu
acli api:environments:domain-create <test-environmentId> URL_ALIAS-test.stanford.edu
acli api:environments:domain-create <prod-environmentId> URL_ALIAS-prod.stanford.edu
```

#### Acquia UI Steps
- Open the the [Acquia dashboard](https://cloud.acquia.com/a/develop/all)
- Select the SDSS Gryphon application
- Click the relevant environment (dev, test or prod) to open it.
- Click the "Domain Management" navigation item.
- Click the "Add Domain" button.
- Add the full applicable domain for the relevant environment and save.
    - E.g., `URL_ALIAS-dev.stanford.edu` on the dev environment.
- Repeat for each environment and domain.

### Create a new database
> :bulb: **Note:** A database only needs to be created once at the application level. Acquia will handle creating the database for all 3 environments automatically.

If you have [ACLI](https://docs.acquia.com/acquia-cloud-platform/add-ons/acquia-cli/acquia-cli-installation) installed you can use [ACLI Database Create](https://docs.acquia.com/acquia-cloud-platform/add-ons/acquia-cli/commands/api:applications:database-create). Otherwise, follow the Acquia UI steps below.

```
acli api:applications:database-create <applicationUuid> <datbase_name>
```

#### Acquia UI Steps
- Open the the [Acquia dashboard](https://cloud.acquia.com/a/develop/all)
- Select the SDSS Gryphon application
- Select any environment (dev/test/prod).
- Click the "Databases" navigation item.
- Click the Add Database button.
- Use the machine name as the database name and save.

### Create Site Directory and settings

#### Run multi-site initialization command to add a new site

Run `sws:multisite:new-site <machine_name>`

#### Verify the new site was set up correctly
Check the multisites array in the [`drush/drush.yml`](../drush/drush.yml) file for the new site(s).
- The new site should be there under `multisites:`.
Check the drush aliases files for the new site(s) in `drush/sites`.

### Create PR
- Commit changes, push the code up to the origin and make a Pull Request.
- Set the base branch (branch to be merged into) to the current development branch (should be the default).
- Assign the PR for review.

**You are done for now.** The last step cannot be completed until the site provision code has been merged and deployed to production.

## Provisioning Part 2

### Install and Configure Site
These steps cannot be completed until the provision code has been merged and deployed to production.

#### Install profile on the prod site
> :warning: **Warning: Be careful with this command!** If it is executed on the wrong site it will completely wipe out the site with a fresh install.

To install a new site with default profile:
`drush @MACHINE_NAME.prod si sdss_profile -y`

### Configure SAML login settings
Make sure the correct workgroups have the appropriate access to the site.

Go to: `/admin/config/people/stanford-saml`
- For SWS this workgroup is: `uit:sws` (mapped to Administrator role)
- For SDSS this workgroup is: `sdss:webdev` (mapped to Site Manager or Administrator role)
