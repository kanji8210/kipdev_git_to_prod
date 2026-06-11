# KipDev Git Deployer

> Ship plugin and theme updates from Git with confidence: automated checks, staged rollouts, and instant rollback.

## Overview

KipDev Git Deployer automates the deployment of WordPress plugins and themes directly from Git repositories. It eliminates manual uploads and fragile workflows by turning Git commits, tags, and branches into safe, auditable deployments—enabling teams to ship faster while keeping sites stable.

## Key Features

- **Continuous Delivery**: Push to Git, updates deploy automatically with reduced human error
- **Safe Updates**: Pre-deployment checks, staged rollouts, and automatic rollback protect production
- **Full Traceability**: Every deployment links to commits, tags, and changelogs for complete audit trails
- **Developer-Friendly**: SSH and token auth, branch/tag selection, familiar Git workflows
- **Flexible Triggers**: Webhooks for instant deploys or scheduled polling for restricted environments
- **Enterprise Security**: Encrypted credentials, integrity verification, role-based access controls

## How It Works

1. **Connect Repository** – Admin connects GitHub/GitLab/Bitbucket repo with SSH key or access token
2. **Register Package** – Plugin reads manifest (composer.json/package.json) and extracts metadata
3. **Trigger Deployment** – Webhook on push/tag or scheduled polling checks for updates
4. **Pre-Deployment Checks** – Sandbox environment runs: syntax linting, dependency resolution, compatibility tests
5. **Staged Deployment** – Optional preview mode for QA before production release
6. **Production Release** – Atomic file operations, database migrations, auto-rollback on failure
7. **Monitoring** – Deployment logs, email alerts, audit trail, one-click version revert

## Architecture

| Component | Purpose |
|-----------|---------|
| **Admin Dashboard** | Repository management, deployment controls, logs, rollback UI |
| **Webhook Receiver & Scheduler** | Instant webhooks and periodic polling for updates |
| **Sandbox Runner** | Isolated environment for pre-deployment checks and testing |
| **Deployer Engine** | Atomic file operations, migrations, safe updates |
| **Security Layer** | Encrypted secrets, permission roles, integrity verification |

## Use Cases

- **Agencies** – Manage consistent plugin updates across multiple client sites
- **Plugin Authors** – Publish releases directly from CI/CD pipelines
- **Enterprise Teams** – Require auditable deployments with instant rollback
- **Staging Workflows** – Validate updates in preview environments before production

## Quick Start

1. Install plugin and activate from WordPress admin
2. Navigate to KipDev Git Deployer settings
3. Click "Connect Repository" and enter Git repo URL
4. Configure authentication (SSH key or personal access token)
5. Select branch or tag to track
6. Configure webhook in Git provider (or enable polling)
7. Click "Deploy Now" to initialize

## Webhook Configuration

```
Webhook URL: https://your-site.example.com/wp-admin/admin.php?page=kipdev-git-deployer&webhook=receive
Content Type: application/json
Events: push, tag_push, release
```

SAMPLE TOKEN CONFIG (PSEUDO)

GIT_PROVIDER=github
REPO_URL=git@github.com:org/repo.git
DEPLOY_KEY_PRIVATE=-----BEGIN RSA PRIVATE KEY-----
...your private key...
-----END RSA PRIVATE KEY-----
ACCESS_TOKEN=ghp_xxxxyyyyzzzz

CONFIGURATION EXAMPLES

Minimal composer.json style manifest (example)

{
  "name": "vendor/plugin-name",
  "version": "1.0.0",
  "type": "wordpress-plugin",
  "require": {
    "php": ">=7.4"
  }
}

Minimal package.json style manifest (example)

{
  "name": "vendor/theme-name",
  "version": "1.0.0",
  "dependencies": {
    "some-lib": "^2.0"
  }
}

Sample webhook payload outline (push event)

{
  "ref": "refs/heads/main",
  "before": "commit-sha-old",
  "after": "commit-sha-new",
  "repository": {
    "full_name": "org/repo",
    "url": "git@github.com:org/repo.git"
  },
  "commits": [
    {
      "id": "commit-sha-new",
      "message": "Short commit message",
      "author": { "name": "Dev", "email": "dev@example.com" }
    }
  ]
}

USAGE AND ADMIN ACTIONS

- Deploy Now
  - Triggers an immediate fetch, runs pre‑deployment checks, and applies the update.
  - Expected outcome: new version deployed or a clear failure log with diagnostics.

- View Logs
  - Shows deployment history, commit links, and test results.
  - Use logs to trace failures and identify the offending commit.

- Rollback
  - Reverts files and database changes to the last stable commit.
  - Expected outcome: site restored to previous working state; failure details logged.

- Preview Deploy
  - Deploys to a staging or preview area for QA before production release.
  - Use for stakeholder validation and manual testing.

Troubleshooting tips
- If deployment fails on dependency install, check composer/npm logs in the deployment log.
- If webhook not received, verify webhook URL and provider delivery logs.
- If permissions error occurs, confirm token scopes or SSH key access for the repo.

SECURITY AND PERMISSIONS

- Credentials
  - Store SSH keys and tokens encrypted at rest.
  - Limit token scopes to only what is required for read and deploy operations.

- Permission roles
  - Restrict who can connect repos, trigger deploys, and perform rollbacks.
  - Use role‑based access control in the admin dashboard.

- Integrity checks
  - Verify file checksums after download.
  - Validate manifests and block deployments with missing or unexpected files.

CONTRIBUTING AND ROADMAP

- Contributing
  - Open issues and pull requests on the project repository.
  - Include reproduction steps and logs for bug reports.

- Roadmap (planned features)
  - Multi‑repo management and bulk deployments.
  - Deeper CI integration and artifact support.
  - Staged rollouts with percentage‑based traffic splitting.
  - Enhanced preview environments and automated visual regression tests.

LICENSE AND CONTACT

- License: [INSERT LICENSE TYPE HERE]
- Maintainer: [MAINTAINER NAME OR TEAM]
- Contact: [MAINTAINER EMAIL OR ISSUE TRACKER URL]

END OF README.txt

