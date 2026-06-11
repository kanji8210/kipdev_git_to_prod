<?php
/**
 * Plugin Name: KipDev Git To Prod
 * Plugin URI: https://github.com/kanji8210/kipdev_git_to_prod
 * Description: Deploy plugins and themes directly from GitHub, GitLab, or Bitbucket
 * Version: 1.0.0
 * Author: Dennis Kip
 * License: GPL v2 or later
 * Text Domain: kipdev-git-to-prod
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define constants
define('KIPDEV_VERSION', '1.0.0');
define('KIPDEV_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('KIPDEV_PLUGIN_URL', plugin_dir_url(__FILE__));
define('KIPDEV_LOG_DIR', KIPDEV_PLUGIN_DIR . 'logs/');
define('KIPDEV_BACKUP_DIR', KIPDEV_PLUGIN_DIR . 'backups/');

// Activation/Deactivation hooks
register_activation_hook(__FILE__, 'kipdev_activate');
register_deactivation_hook(__FILE__, 'kipdev_deactivate');

function kipdev_activate() {
    require_once KIPDEV_PLUGIN_DIR . 'includes/class-activator.php';
    KIPDEV_Activator::activate();
}

function kipdev_deactivate() {
    require_once KIPDEV_PLUGIN_DIR . 'includes/class-deactivator.php';
    KIPDEV_Deactivator::deactivate();
}

// Initialize plugin
add_action('plugins_loaded', 'kipdev_init');

function kipdev_init() {
    // Load required files
    require_once KIPDEV_PLUGIN_DIR . 'includes/class-repo-manager.php';
    require_once KIPDEV_PLUGIN_DIR . 'includes/class-deployer.php';
    require_once KIPDEV_PLUGIN_DIR . 'includes/class-rollback.php';
    require_once KIPDEV_PLUGIN_DIR . 'includes/class-validator.php';
    require_once KIPDEV_PLUGIN_DIR . 'includes/class-webhook.php';
    require_once KIPDEV_PLUGIN_DIR . 'includes/class-logger.php';
    
    // Initialize admin
    if (is_admin()) {
        require_once KIPDEV_PLUGIN_DIR . 'admin/admin-menu.php';
        new KIPDEV_Admin_Menu();
    }
    
    // Initialize REST API endpoints
    add_action('rest_api_init', 'kipdev_register_rest_routes');
}

function kipdev_register_rest_routes() {
    $webhook = new KIPDEV_Webhook();
    $webhook->register_routes();
}
