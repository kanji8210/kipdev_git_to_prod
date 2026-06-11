<?php
/**
 * Plugin Name: WP Git Deploy
 * Plugin URI: https://yourwebsite.com/wp-git-deploy
 * Description: Deploy plugins and themes directly from GitHub, GitLab, or Bitbucket
 * Version: 1.0.0
 * Author: Your Name
 * License: GPL v2 or later
 * Text Domain: wp-git-deploy
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define constants
define('WPGD_VERSION', '1.0.0');
define('WPGD_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WPGD_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WPGD_LOG_DIR', WPGD_PLUGIN_DIR . 'logs/');
define('WPGD_BACKUP_DIR', WPGD_PLUGIN_DIR . 'backups/');

// Activation/Deactivation hooks
register_activation_hook(__FILE__, 'wpgd_activate');
register_deactivation_hook(__FILE__, 'wpgd_deactivate');

function wpgd_activate() {
    require_once WPGD_PLUGIN_DIR . 'includes/class-activator.php';
    WPGD_Activator::activate();
}

function wpgd_deactivate() {
    require_once WPGD_PLUGIN_DIR . 'includes/class-deactivator.php';
    WPGD_Deactivator::deactivate();
}

// Initialize plugin
add_action('plugins_loaded', 'wpgd_init');

function wpgd_init() {
    // Load required files
    require_once WPGD_PLUGIN_DIR . 'includes/class-repo-manager.php';
    require_once WPGD_PLUGIN_DIR . 'includes/class-deployer.php';
    require_once WPGD_PLUGIN_DIR . 'includes/class-rollback.php';
    require_once WPGD_PLUGIN_DIR . 'includes/class-validator.php';
    require_once WPGD_PLUGIN_DIR . 'includes/class-webhook.php';
    require_once WPGD_PLUGIN_DIR . 'includes/class-logger.php';
    
    // Initialize admin
    if (is_admin()) {
        require_once WPGD_PLUGIN_DIR . 'admin/admin-menu.php';
        new WPGD_Admin_Menu();
    }
    
    // Initialize REST API endpoints
    add_action('rest_api_init', 'wpgd_register_rest_routes');
}

function wpgd_register_rest_routes() {
    $webhook = new WPGD_Webhook();
    $webhook->register_routes();
}
