# 1. Automate database copy updates

## Status

Accepted

## Context

Copying databases down from production to staging and then running updates across all staging sites was a manual and time consuming process for a developer. To improve workflows we want to run updates automatically when a database is copied and offload the database copying process.

## Decision

- Add code in the `post-db-copy` cloud hook to run updates on a site after a database has been copied on any environment. 
- Add a Github actions workflow to copy production databases to staging. This workflow will be triggered manually from the Actions tab on Github.

## Consequences

- Database updates and configuration imports will be performed automatically when a database is copied or restored on any environment. 
- Developers can perform the database copying from production to staging by clicking a button.
