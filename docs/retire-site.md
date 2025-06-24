# SDSS Site Retirement

## Requirements
*See [SDSS Development Requirements](development-requirements.md)*

## Introduction
This document outlines the steps required to retire an existing site from the SDSS Acquia application. Please follow each step carefully to ensure a clean and safe removal.  

> ⚠️ **Important:** Do not delete the database or site files until after the next deployment and confirmation, to preserve the ability to restore the site if needed.

## Site Retirement Steps

### Remove Domains from NetDB
- Log in to [NetDB](https://netdb.stanford.edu/).
- Locate the relevant NetDB node(s) for the site.
- Remove the associated URL aliases: `SITE_ALIAS-dev`, `SITE_ALIAS-test`, `SITE_ALIAS-prod` and the live alias.
- For SDSS there are 3 separate NetDB nodes to modify: one for dev/test, one for prod, and another for the WAF.

### Remove Domains from WAF
> ⚠️ **Important:** Notify other developers in the WAF Slack channel before making these changes.
- Create a new version of the WAF property.
- Remove the relevant hostnames from SDSS hostname forwarding.
- Activate the new version on both staging and production.
- Confirm activation was successful.

### Remove Domains from Acquia Cloud
- Log in to the [Acquia Cloud dashboard](https://cloud.acquia.com/).
- For each environment (dev, test, prod), navigate to **Domain Management**.
- Remove the site's domains from each environment.

### Remove Database from Acquia Cloud
> ⚠️ **Warning:** Do not remove the database until after the next deployment and confirmation that the site is no longer needed.

- In the Acquia Cloud dashboard, go to **Databases**.
- Locate the database associated with the site (e.g., `SITE_MACHINE_NAME`).
- After confirmation, delete the database.

### Remove Cron/Scheduled Job from Acquia Cloud
- In the Acquia Cloud dashboard, go to **Cron** or **Scheduled Jobs** on the production environment.
- Remove any cron jobs related to the site.

### Remove Site from the Codebase
- Ensure your local repo is up to date:
  ```bash
  git checkout 4.x && git fetch && git pull
  composer install
  ```
- Create a new branch for the retirements:
  ```bash
  git checkout -b SDSS-XXXX--retire-SITE_MACHINE_NAME
  ```
- Remove site from the BLT multisite array in [`blt/blt.yml`](../blt/blt.yml).
- Remove site directories:
  ```bash
  git rm -r docroot/sites/SITE_MACHINE_NAME
  ```
- Remove Drush alias files:
  ```bash
  git rm -r drush/sites/SITE_MACHINE_NAME.site.yml
  ```
- Push the code, create a PR, and assign it for review.

### Remove Status Alerts
- Remove any status alerts or monitoring for the site (e.g., StatusCake, Pingdom).

---

> 💡 **Note:** Always leave the database and site files intact until after the next deployment and confirmation. This ensures the site can be restored if needed.

---

## Final Confirmation
- Double-check that all references to the site have been removed from code, infrastructure, and monitoring.
- Communicate with stakeholders to confirm the site has been successfully retired.
- After the next deployment and confirmation, you may delete the database and any remaining files.
