<?php
class WPGD_Deactivator {
    public static function deactivate() {
        // Clear scheduled cron jobs
        wp_clear_scheduled_hook('wpgd_cron_check');
        
        // Optionally clear transients
        delete_transient('wpgd_deployment_lock');
    }
}
