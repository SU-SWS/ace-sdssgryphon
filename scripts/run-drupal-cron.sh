#!/bin/bash
# Run the Drupal Cron on all sites on the multi-site using blt.

set -ev

cd /var/www/html/stanfordsos.prod
vendor/bin/blt drupal:cron

set +v
