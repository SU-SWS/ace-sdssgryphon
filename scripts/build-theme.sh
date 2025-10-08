#!/bin/bash
# Run nvm use to ensure correct Node version before running this script.

set -e
cd "docroot/profiles/sdss/sdss_profile/themes/sdss_subtheme"
if [ ! -d node_modules ]; then
  yarn install --frozen-lockfile
fi
yarn build
