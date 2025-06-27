#!/bin/bash
# Run the Drupal Scheduler Cron on all sites on the multi-site using blt. This
# wiill trigger any scheduled content publishing/unpublishing tasks.

set -ev

cd /var/www/html/stanfordsos.prod
vendor/bin/blt drupal:cron:scheduler

set +v