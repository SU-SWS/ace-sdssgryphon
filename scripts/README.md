# Scripts Directory

This directory contains utility shell scripts for managing and automating Drupal multisite operations on Acquia.

## Scripts

### run-drupal-cron.sh
Runs the Drupal cron on all multisites using BLT. This should be scheduled regularly to ensure all sites perform routine maintenance tasks.

### run-drupal-cron-scheduler.sh
Runs the Drupal Scheduler cron on all multisites using BLT. This triggers any scheduled content publishing or unpublishing tasks and should be run more frequently than the standard cron.
