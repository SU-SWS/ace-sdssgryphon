#!/bin/bash

set -ev

mv drush/deploy.gitignore .gitignore
rm -rf .ddev .github .gitpod .tugboat blt lando patches tests
