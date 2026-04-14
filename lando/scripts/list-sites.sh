#!/bin/bash
# Lists all available multisites with their local URLs.

sites=()
for settings in /app/docroot/sites/*/settings.php; do
  site_dir=$(basename "$(dirname "$settings")")
  if [ "$site_dir" != "default" ]; then
    sites+=("$site_dir")
  fi
done

IFS=$'\n' sorted=($(sort <<<"${sites[*]}")); unset IFS

echo "Available multisites (${#sorted[@]}):"
echo ""
for site in "${sorted[@]}"; do
  # Convert underscores to dashes (matching sites.php convention)
  url=$(echo "$site" | sed 's/__/./g; s/_/-/g')
  echo "  $site -> https://$url.sdss.lndo.site"
done
