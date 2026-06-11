<?php
class WPGD_Admin_Menu {
    
    public function __construct() {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
    }
    
    public function add_admin_menu() {
        add_menu_page(
            'Git Deploy',
            'Git Deploy',
            'manage_options',
            'wp-git-deploy',
            [$this, 'render_dashboard'],
            'dashicons-update',
            30
        );
        
        add_submenu_page(
            'wp-git-deploy',
            'Repositories',
            'Repositories',
            'manage_options',
            'wpgd-repos',
            [$this, 'render_repositories']
        );
        
        add_submenu_page(
            'wp-git-deploy',
            'Deployment Logs',
            'Logs',
            'manage_options',
            'wpgd-logs',
            [$this, 'render_logs']
        );
        
        add_submenu_page(
            'wp-git-deploy',
            'Settings',
            'Settings',
            'manage_options',
            'wpgd-settings',
            [$this, 'render_settings']
        );
    }
    
    public function enqueue_assets($hook) {
        if (strpos($hook, 'wp-git-deploy') === false && strpos($hook, 'wpgd-') === false) {
            return;
        }
        
        wp_enqueue_style('wpgd-admin', WPGD_PLUGIN_URL . 'admin/assets/css/admin.css', [], WPGD_VERSION);
        wp_enqueue_script('wpgd-admin', WPGD_PLUGIN_URL . 'admin/assets/js/admin.js', ['jquery'], WPGD_VERSION, true);
        
        wp_localize_script('wpgd-admin', 'wpgd_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wpgd_ajax_nonce')
        ]);
    }
    
    public function render_dashboard() {
        include_once WPGD_PLUGIN_DIR . 'admin/views/dashboard.php';
    }
    
    public function render_repositories() {
        include_once WPGD_PLUGIN_DIR . 'admin/views/repo-settings.php';
    }
    
    public function render_logs() {
        include_once WPGD_PLUGIN_DIR . 'admin/views/logs.php';
    }
    
    public function render_settings() {
        include_once WPGD_PLUGIN_DIR . 'admin/views/settings.php';
    }
}
