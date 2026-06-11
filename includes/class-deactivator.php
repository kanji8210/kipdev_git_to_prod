<?php
class KIPDEV_Deactivator {
    public static function deactivate() {
        // Clear scheduled cron jobs
        wp_clear_scheduled_hook('kipdev_cron_check');
        
        // Optionally clear transients
        delete_transient('kipdev_deployment_lock');
    }
}
