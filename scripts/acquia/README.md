# Acquia Cloud Server-Side Scripts

This directory contains utility shell scripts for managing and automating Drupal multisite operations on Acquia Cloud. **Only scripts intended for use on Acquia should be placed here.**

All scripts in this directory must be tracked as executable in the repository to run on Acquia Cloud. If you are unable to commit permission changes you may need to toggle fileMode tracking on before altering permissions:
```bash
git config core.fileMode true
chmod +x scripts/acquia/*.sh
git add scripts/acquia/*.sh
git commit -m "Updated Acquia Cloud script permissions."
# Toggle back to off after making changes.
git config core.fileMode false
```

## Scripts

### run-drupal-cron.sh
Runs Drupal cron on all multisites using BLT. This should be scheduled regularly to ensure all sites perform routine maintenance tasks. 

**Usage:**
```bash
./run-drupal-cron.sh [dev|test|prod]
```
If no environment is provided, defaults to `test`.

### run-drupal-cron-scheduler.sh
Runs the Drupal Scheduler cron on all multisites using BLT. This triggers any scheduled content publishing or unpublishing tasks and should be run more frequently than the standard cron.

**Usage:**
```bash
./run-drupal-cron-scheduler.sh [dev|test|prod]
```
If no environment is provided, defaults to `test`.
