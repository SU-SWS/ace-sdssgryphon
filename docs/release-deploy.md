# SDSS Release and Deployment Documentation

:bulb: Whether this is your first release or your fiftieth, thoughtful preparation and double-checking your steps is always valuable. Asking questions and sharing knowledge helps everyone succeed.

## Deployment Process Overview
- The next version for the release is determined using [SEMVER](https://semver.org/) patch/minor/major conventions.
- A new release branch is created off the latest development branch, named `VERSION-release`.
- When the release branch is merged into the main branch, it triggers GitHub Actions workflows which:
  - Create a new tag and release on GitHub based on the PR version label.
  - Create a code artifact for deployment and push it to Acquia.
- Once the code artifact is pushed to Acquia, it is deployed to production through the Acquia Cloud UI.

## Requirements
*See [SDSS Development Requirements](development-requirements.md)*

## Create database backups

Running the backup step now ensures the backups are completed by the time the release is created and artifact is ready to deploy.

- Open the application in the Acquia Cloud UI.
- Log in and select the SDSS Gryphon application.
- Backup all databases on Production.
- To backup all databases:
  1. Click the icon next to the "Databases" tab for Prod.
  2. Check "Select All".
  3. Click "Continue" and then "Back Up".
- The modal may take some time to disappear as it processes after clicking the “Back Up” button. Don’t click the button more than once.

## Create a New Release
### Prep the Code Locally
```bash
git checkout main && git fetch && git pull
git checkout DEV_BRANCH && git fetch && git pull
# Example:
git checkout 1.x && git fetch && git pull
```

### Determine the Release Version

- Review code changes between the main and dev branch using SEMVER conventions:
  - **Major**: Incompatible API changes (e.g., `2.0.0`)
  - **Minor**: New functionality, backward compatible (e.g., `1.1.0`)
  - **Patch**: Backward compatible bug fixes (e.g., `1.0.1`)
- Use `git log` to review commits:
```bash
git log ^MAIN_BRANCH DEV_BRANCH --oneline
# Example:
git log ^main 1.x --oneline
```

### Create a Release Branch

```bash
git checkout DEV_BRANCH
git checkout -b release-VERSION
# Example:
git checkout 1.x
git checkout -b release-1.0.0
```

### Increment Version Numbers
Update version numbers in relevant files:
- `docroot/profiles/sdss/sdss_profile/sdss_profile.info.yml`
- `docroot/profiles/sdss/sdss_profile/themes/sdss_subtheme/sdss_subtheme.info.yml`

### Fill Out the CHANGELOG.md

- Get a list of commits:
```bash
git log ^MAIN_BRANCH DEV_BRANCH --oneline
# Example:
git log ^main 1.x --oneline
```
- Update `CHANGELOG.md` using [Keep a Changelog](https://keepachangelog.com/en/1.1.0/) format.
- Sort commits into appropriate categories. If there is a category with no commits, put "None.".
- Keep commit hash, ticket #, commit message and PR #. Remove anything else.

:bulb: **Tip:** Clean up commit messages to make them less technical and more human-readable. This helps future you (and others) quickly understand what changed in the release.

### Commit and Push Changes
```bash
git commit -am "VERSION"
git push
# Example:
git commit -am "1.0.0"
git push
```

## Create Release Branch PR
- Create a PR on GitHub for the release branch.
- Set the PR title to the version number (e.g., `1.0.0`).
- Set the base branch to `main`.
  - Double check to make sure the base branch is set to the main branch correctly. It’s easy to miss this step because the base branch is set to the development branch by default.
- Apply the proper major, minor, or patch label to the PR.
  - The PR label is what the Github actions automation uses to create the release. Make sure to get the labels right here.
  - This is also good opportunity to briefly review the changes in the release and verify the SEMVER aligns well. It’s still easy at this point to start a new release branch and PR if anything needs to be changed.

:warning: **Double check the base branch and PR label!** Make sure the base branch is set to the main branch (`main`) and the PR label is correct based on the version (major, minor, or patch).

## Merge the Release PR
- Once all tests pass, merge the PR using a **merge commit** (not squash merge).
- Set the merge commit title/message to the version (e.g., `1.0.0`).

## Confirm GitHub Actions Automation
- After merging, GitHub Actions will:
  - Create a new tag and release with the version number.
  - Create a deployment artifact and push it to Acquia.
- The deployment artifact is named using the current date and release version (e.g., `2025-10-08_1.0.0`).

## Create a Back-to-Dev PR and Merge
- After merging the release branch into main, merge main back into the development branch.
```bash
git checkout main && git fetch && git pull
git checkout DEV_BRANCH && git fetch && git pull
# Example:
git checkout 1.x && git fetch && git pull
git checkout -b backtodev-version
# Example:
git checkout -b backtodev-1.0.1
git merge main
```
- Update version numbers to the next patch version (e.g., `1.0.1-dev`).
- Commit, push, and create PR:
```bash
git commit -am "backtodev-VERSION"
git push
```

**Important:** Backtodev PRs into *.x branches should be merged (not squashed). If branch protection rules enforce squash merges, a repo administrator may need to force a regular merge. This is necessary to maintain proper history and versioning. Assign a repo admin if you cannot merge directly.

## Update GitHub Release Notes with Package Updates
- Use the `composer-lock-diff` package to generate a table of updated dependencies for release notes and PR summary.
- Composer Lock Diff is included in this repo. You can either run the command through `/vendor/bin/composer-lock-diff` or add it to your `$PATH` in `~/.bashrc`.

**Recommended usage:**
```bash
composer-lock-diff --md --from=PREVIOUS_VERSION --to=NEW_VERSION
# Example:
composer-lock-diff --md --from=4.13.1 --to=4.14.0
```

- Update the release notes on GitHub:
  - Replace commit list with `CHANGELOG.md` notes.
  - Keep the "Full Changelog" link.
  - Paste `composer-lock-diff` output at the bottom.

:information_source: The release has been created and a deployment artifact has been pushed, but no deployment has been made at this point. The next step is to deploy to production, which is the true “point of no return”.

## Deploy the Release Code Artifact to Production
- Open the application in the Acquia Cloud UI.
- Log in and select the SDSS Gryphon application.

## Deploy the Release to Production

:memo: **Remember:** Before deploying to production, take a moment to verify all steps and details above. If anything feels off, pause and ask for a second opinion.

The deployment artifact is a tag, not a branch.
To deploy:
  1. Click the icon next to the "Code" tab for Prod.
  2. Select the deployment artifact tag (e.g., `tags/2025-10-08_1.0.0`).
  3. Click "Continue" and then "Switch" to start deployment.
Notify the client Slack channel that deployment has started.

## Monitor the Production Deployment
- Monitor progress and alert relevant teams in Slack.
- Once complete, an automated Slack message will indicate success/failure in the alerts channel.

If all sites deployed successfully, you are done with the deployment! Follow steps below to update Jira and communicate to the associated teams.
If any sites failed, follow troubleshooting steps in this document (below).

:confetti_ball: Congratulations on a successful deployment! Remember to share the good news in Slack and celebrate your accomplishment.

## Administrative Follow-Ups

:bulb: **Tip:** If you’re unsure about ticket grooming, announcements, or scheduling, check in with the team and project manager.

### Ticket Grooming and Release Preparation
- Groom tickets for the release: mark completed tickets with the correct fix version.
- Update all relevant JIRA tickets in the version to "Ready to Deploy" or "Done" as appropriate.
- Set the release date for the release and mark the release as completed in JIRA.
- Create the next release version in JIRA and schedule the next release date based on the regular cadence. Use the current release name scheme to name the release. The version will be determined during release.
- Create routine maintenance tickets for the next release (clone or repurpose previous tickets as needed) and update as needed for the next release (fix version, title, and ticket links).

### Announcements
- Announce in client Slack channel:
  - Example template:
    > SDSS version VERSION has been successfully deployed to production 🎉
    > Highlights 🎉
    > ANYTHING NOTABLE ABOUT THE RELEASE
    > Full release notes in thread.
- Announce in internal Slack channel:
  - Notify of successful deployment, next release version, and tentative release date.

## Troubleshooting a Deployment

:information_source: If a site failed to update, investigate using the deployment task log in Acquia Cloud UI on the main application page. This will indicate which sites failed to update at the bottom of the log.

:handshake: For major failures or critical troubleshooting, always reach out to another developer or senior engineer. Collaboration is key, and no one is expected to solve production issues alone. Use internal communication channels to coordinate. For major failures, consider a code change or rollback (see below).

**Manual Site Updates:**

If a site needs special handling (e.g., updates ahead of time or for troubleshooting a failed update after deployment), run manual updates using drush.
- Because of our parallel deployment process, sometimes there’s a database lock that prevents an upgrade. Running updates manually will resolve this issue.

```bash
drush @DRUSHALIAS.prod updatedb -y
drush @DRUSHALIAS.prod ci -y
drush @DRUSHALIAS.prod cr
```

## Rolling Back a Deployment

:bulb: **Tip:** If you think a rollback might be needed, consult with another developer or senior engineer before proceeding, and use internal communication channels to coordinate. Rollbacks are rare and should be a shared decision. A hotfix or fixing issues in place is preferred when possible.

- Deploy the previous code artifact to production.
- Cancel the deploy hook for the deployment.
- Restore site backups over the previous code.
