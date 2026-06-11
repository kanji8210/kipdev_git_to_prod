<?php
class KIPDEV_Activator {
    public static function activate() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'kipdev_repos';
        
        // Create repos table
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            name varchar(100) NOT NULL,
            type enum('github','gitlab','bitbucket') NOT NULL,
            url varchar(255) NOT NULL,
            auth_type enum('ssh','token') NOT NULL,
            token_encrypted text,
            ssh_key_encrypted text,
            target_type enum('plugin','theme') NOT NULL,
            target_slug varchar(100) NOT NULL,
            active_branch varchar(100) DEFAULT 'main',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";
        
        // Create logs table
        $logs_table = $wpdb->prefix . 'kipdev_logs';
        $sql .= "CREATE TABLE IF NOT EXISTS $logs_table (
            id int(11) NOT NULL AUTO_INCREMENT,
            repo_id int(11) NOT NULL,
            status enum('started','success','failed','rolled_back') NOT NULL,
            from_commit varchar(40),
            to_commit varchar(40),
            log_message text,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY repo_id (repo_id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        
        // Create log and backup directories
        if (!file_exists(KIPDEV_LOG_DIR)) {
            wp_mkdir_p(KIPDEV_LOG_DIR);
        }
        if (!file_exists(KIPDEV_BACKUP_DIR)) {
            wp_mkdir_p(KIPDEV_BACKUP_DIR);
        }
        
        // Add .htaccess to protect logs
        file_put_contents(KIPDEV_LOG_DIR . '.htaccess', 'Deny from all');
        file_put_contents(KIPDEV_BACKUP_DIR . '.htaccess', 'Deny from all');
        
        // Schedule cron job
        if (!wp_next_scheduled('kipdev_cron_check')) {
            wp_schedule_event(time(), 'hourly', 'kipdev_cron_check');
        }
    }
}
