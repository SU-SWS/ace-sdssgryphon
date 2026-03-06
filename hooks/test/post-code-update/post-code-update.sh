#!/bin/bash
#
# Cloud Hook: post-code-update (stage)
#
# This hook runs BLT post-code-update for stage only.
#
# Usage: post-code-update site target-env source-branch deployed-tag repo-url repo-type

set -ev

site="$1"
target_env="$2"
source_branch="$3"
deployed_tag="$4"
repo_url="$5"
repo_type="$6"

repo_root="/var/www/html/$site.$target_env"
export PATH=$repo_root/vendor/bin:$PATH
cd $repo_root

blt sws:post-code-update $target_env $deployed_tag

set +v
