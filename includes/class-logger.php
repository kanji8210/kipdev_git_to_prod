<?php
class KIPDEV_Logger {
    private $table_name;
    
    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'kipdev_logs';
    }
    
    public function log($repo_id, $status, $from_commit, $to_commit, $message) {
        global $wpdb;
        
        return $wpdb->insert($this->table_name, [
            'repo_id' => $repo_id,
            'status' => $status,
            'from_commit' => $from_commit,
            'to_commit' => $to_commit,
            'log_message' => $message,
            'created_at' => current_time('mysql')
        ]);
    }
    
    public function get_logs($repo_id = null, $limit = 50) {
        global $wpdb;
        
        if ($repo_id) {
            return $wpdb->get_results($wpdb->prepare(
                "SELECT * FROM {$this->table_name} WHERE repo_id = %d ORDER BY created_at DESC LIMIT %d",
                $repo_id,
                $limit
            ));
        } else {
            return $wpdb->get_results($wpdb->prepare(
                "SELECT * FROM {$this->table_name} ORDER BY created_at DESC LIMIT %d",
                $limit
            ));
        }
    }
    
    public function get_last_deployment($repo_id) {
        global $wpdb;
        
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$this->table_name} WHERE repo_id = %d AND status = 'success' ORDER BY created_at DESC LIMIT 1",
            $repo_id
        ));
    }
}
