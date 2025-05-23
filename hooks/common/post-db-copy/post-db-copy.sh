#!/bin/sh
#
# Cloud Hook: post-db-copy
#
# The post-db-copy hook is run whenever you use the Workflow page to copy a
# database from one environment to another.
#
# Usage: post-db-copy site target-env db-name source-env

set -ev

site="$1"
target_env="$2"
db_name="$3"
source_env="$4"

# Prep for BLT commands.
repo_root="/var/www/html/$site.$target_env"
export PATH=$repo_root/vendor/bin:$PATH
cd $repo_root

# This command is the default and only runs updates if the site is on an ODE
# environment.
#blt artifact:ac-hooks:post-db-copy $site $target_env $db_name $source_env --environment=$target_env -v --no-interaction -D drush.ansi=false

# Run the BLT command to run deploy hooks, including updating the database and
# importing configuration, on the target environment and site.
blt artifact:update:drupal --site=$db_name --environment=$target_env

set +v
