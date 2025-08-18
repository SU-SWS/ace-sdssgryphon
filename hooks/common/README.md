# Custom Scripts in this Directory

This directory is intended for Acquia Cloud Hook scripts and is the only location in the codebase where executable scripts are reliably maintained on Acquia Cloud. It contains utility shell scripts for managing and automating Drupal multisite operations.

## Scripts

### run-drupal-cron.sh
Runs Drupal cron on all multisites using BLT. This should be scheduled regularly to ensure all sites perform routine maintenance tasks.

**Usage:**
```bash
./run-drupal-cron.sh [dev|test|prod]
```
Defaults to `test` if no environment is provided.

### run-drupal-cron-scheduler.sh
Runs the Drupal Scheduler cron on all multisites using BLT. This triggers any scheduled content publishing or unpublishing tasks and should be run more frequently than the standard cron.

**Usage:**
```bash
./run-drupal-cron-scheduler.sh [dev|test|prod]
```
Defaults to `test` if no environment is provided.