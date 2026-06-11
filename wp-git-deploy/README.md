# WP Git Deploy Plugin

Deploy WordPress plugins and themes directly from Git repositories.

## Features
- Connect to GitHub, GitLab, Bitbucket
- One-click deployment
- Automatic rollback
- Webhook support for auto-deployment
- Pre-deployment validation
- Deployment logging

## Installation
1. Copy to `/wp-content/plugins/wp-git-deploy`
2. Activate the plugin
3. Go to Git Deploy menu in WordPress admin
4. Add your repositories and start deploying

## Requirements
- PHP 7.2+
- Git installed on server
- WordPress 5.0+
- SSH or Personal Access Token for repository access

## Usage
1. Add repository connection
2. Configure target plugin/theme
3. Click "Deploy Now" or setup webhook for automatic deployment
4. Monitor logs in the Logs section

## Security
- Tokens and SSH keys are encrypted in database
- Nonce verification on all actions
- Capability checks for admin access
- .htaccess protection for sensitive directories

## Support
For issues and feature requests, please create an issue on GitHub.
