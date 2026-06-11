<?php
class KIPDEV_Admin_Menu {
    
    public function __construct() {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
    }
    
    public function add_admin_menu() {
        add_menu_page(
            'Git To Prod',
            'Git To Prod',
            'manage_options',
            'kipdev-git-to-prod',
            [$this, 'render_dashboard'],
            'dashicons-update',
            30
        );
        
        add_submenu_page(
            'kipdev-git-to-prod',
            'Repositories',
            'Repositories',
            'manage_options',
            'kipdev-repos',
            [$this, 'render_repositories']
        );
        
        add_submenu_page(
            'kipdev-git-to-prod',
            'Deployment Logs',
            'Logs',
            'manage_options',
            'kipdev-logs',
            [$this, 'render_logs']
        );
        
        add_submenu_page(
            'kipdev-git-to-prod',
            'Settings',
            'Settings',
            'manage_options',
            'kipdev-settings',
            [$this, 'render_settings']
        );
    }
    
    public function enqueue_assets($hook) {
        if (strpos($hook, 'kipdev-git-to-prod') === false && strpos($hook, 'kipdev-') === false) {
            return;
        }
        
        wp_enqueue_style('kipdev-admin', KIPDEV_PLUGIN_URL . 'admin/assets/css/admin.css', [], KIPDEV_VERSION);
        wp_enqueue_script('kipdev-admin', KIPDEV_PLUGIN_URL . 'admin/assets/js/admin.js', ['jquery'], KIPDEV_VERSION, true);
        
        wp_localize_script('kipdev-admin', 'kipdev_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('kipdev_ajax_nonce')
        ]);
    }
    
    public function render_dashboard() {
        include_once KIPDEV_PLUGIN_DIR . 'admin/views/dashboard.php';
    }
    
    public function render_repositories() {
        include_once KIPDEV_PLUGIN_DIR . 'admin/views/repo-settings.php';
    }
    
    public function render_logs() {
        include_once KIPDEV_PLUGIN_DIR . 'admin/views/logs.php';
    }
    
    public function render_settings() {
        include_once KIPDEV_PLUGIN_DIR . 'admin/views/settings.php';
    }
}
