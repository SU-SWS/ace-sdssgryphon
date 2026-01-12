#!/bin/bash
# Run the Drupal Scheduler Cron on all sites on the multi-site.
# Usage: ./run-drupal-cron-scheduler.sh [dev|test|prod]
# Defaults to 'test' if no environment is provided.

set -e

ENVIRONMENT=${1:-test}
if [[ ! "$ENVIRONMENT" =~ ^(dev|test|prod)$ ]]; then
  echo "Invalid environment: $ENVIRONMENT"
  exit 1
fi
cd /var/www/html/stanfordsos.$ENVIRONMENT
drush sdss:drupal:cron:scheduler
