#!/bin/bash
#
# Cloud Hook: post-code-deploy (prod)
#
# This hook runs BLT post-code-deploy for prod only.
#
# Usage: post-code-deploy site target-env source-branch deployed-tag repo-url repo-type

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

blt sws:post-code-deploy $target_env $deployed_tag

set +v
