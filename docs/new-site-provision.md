# SDSS Site Provision

## Requirements
*See [SDSS Development Requirements](development-requirements.md)*

## Introduction
This document will walk through provisioning a new site on the SDSS Acquia application. A few quick notes:

- BLT/Acquia use "test" as the name for the "stage" environment in code.
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
Get the latest default branch code. This is important because BLT uses the local repo multi-site definition to run its commands. If you don’t have the latest code with all of the current multi-site definitions, the BLT steps may fail.

- E.g., `git checkout 4.x && git fetch && git pull`

#### Get all updated dependencies
`composer install`

#### Create a new branch for provisioning
`git checkout -b TICKET(S)--provision-SITE(S)`

- E.g., `git checkout -b SDSS-123--provision-mysite`.
- E.g., `git checkout -b SDSS-123-124--provision-mysite-yoursite`
- E.g., `git checkout -b SDSS-123-124-125--provision-3-sites`

### Add the new domains to each of the ACQUIA environments
> :bulb: **Note:** The following blt commands are issued locally but are running remote actions on ACQUIA to setup the hosting/server.

```
blt gryphon:add-domain dev URL_ALIAS-dev.stanford.edu
blt gryphon:add-domain test URL_ALIAS-test.stanford.edu
blt gryphon:add-domain prod URL_ALIAS-prod.stanford.edu
```

> :bulb: **Note:** If provisioning multiple sites at the same time you can add multiple domains to an environment in one command: E.g., `blt gryphon:add-domain dev URL_ALIAS-dev.stanford.edu, URL_ALIAS2-dev.stanford.edu`

#### (Alternative) Add the new domains to each of the ACQUIA environments 
If the blt command does not work you can add the domains to each environment using the [Acquia dashboard](https://cloud.acquia.com/a/develop/all) and the steps below. 

- Click the relevant environment (dev, test or prod) to open it.
- Click the "Domain Management" navigation item.
- Click the "Add Domain" button.
- Add the full applicable domain for the relevant environment and save.
    - E.g., `URL_ALIAS-dev.stanford.edu` on the dev environment.
- Repeat for each environment and domain.

### Create a new database in the ACQUIA dashboard
Create a new database in the [Acquia dashboard](https://cloud.acquia.com/a/develop/all) using the machine name. 

- Select any environment (dev/test/prod).
- Click the "Databases" navigation item.
- Click the Add Database button.
- Use the machine name as the database name and save.
- The database will be created for all 3 environments automatically -- only need to do this once on any environment.

### Create Site Directory and settings

#### Run multi-site initialization command to add a new site

Run `blt recipes:multisite:init`

Enter the following when prompted:
- **Site machine name (e.g. 'example'):** The machine name (with underscores)
    - E.g., `my_site`
- **Local domain name:** Use default (leave blank / hit enter)
- **Would you like to configure the local database credentials? (y/n):** n
- **Default remote drush alias:** The machine name (with underscores) followed by .prod (not .remote!)
    - E.g., `my_site.prod`
- **Default local drush alias:** Use default (leave blank / hit enter)
- **Would you like to create the database on Acquia now? (y/n):** n

#### Verify the new site was set up correctly
Check the multisites array in the [`blt/blt.yml`](../blt/blt.yml) file for the new site(s).
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
