#!/bin/bash
# Run the Drupal Cron on all sites on the multi-site using blt.
# Usage: ./run-drupal-cron.sh [dev|test|prod]
# Defaults to 'test' if no environment is provided.

set -e

ENVIRONMENT=${1:-test}
if [[ ! "$ENVIRONMENT" =~ ^(dev|test|prod)$ ]]; then
  echo "Invalid environment: $ENVIRONMENT"
  exit 1
fi
cd /var/www/html/stanfordsos.$ENVIRONMENT
vendor/bin/blt sdss:drupal:cron
