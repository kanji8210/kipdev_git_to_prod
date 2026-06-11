<?php
class KIPDEV_Repo_Manager {
    private $table_name;
    
    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'kipdev_repos';
    }
    
    public function add_repository($data) {
        global $wpdb;
        
        // Encrypt sensitive data
        if (!empty($data['token'])) {
            $data['token_encrypted'] = $this->encrypt($data['token']);
            unset($data['token']);
        }
        
        if (!empty($data['ssh_key'])) {
            $data['ssh_key_encrypted'] = $this->encrypt($data['ssh_key']);
            unset($data['ssh_key']);
        }
        
        $result = $wpdb->insert($this->table_name, $data);
        return $result ? $wpdb->insert_id : false;
    }
    
    public function get_repositories() {
        global $wpdb;
        return $wpdb->get_results("SELECT * FROM {$this->table_name} ORDER BY created_at DESC");
    }
    
    public function get_repo($id) {
        global $wpdb;
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->table_name} WHERE id = %d", $id));
    }
    
    public function update_repository($id, $data) {
        global $wpdb;
        return $wpdb->update($this->table_name, $data, ['id' => $id]);
    }
    
    public function delete_repository($id) {
        global $wpdb;
        return $wpdb->delete($this->table_name, ['id' => $id]);
    }
    
    public function get_branches($repo_url, $auth) {
        // Implementation for fetching branches via Git API
        // Returns array of branch names
        return ['main', 'develop', 'staging'];
    }
    
    public function get_tags($repo_url, $auth) {
        // Implementation for fetching tags
        return ['v1.0.0', 'v1.1.0', 'v2.0.0'];
    }
    
    private function encrypt($data) {
        if (empty($data)) return '';
        $key = defined('AUTH_KEY') ? AUTH_KEY : 'default-key-change-me';
        return openssl_encrypt($data, 'aes-256-cbc', $key, 0, substr($key, 0, 16));
    }
    
    private function decrypt($data) {
        if (empty($data)) return '';
        $key = defined('AUTH_KEY') ? AUTH_KEY : 'default-key-change-me';
        return openssl_decrypt($data, 'aes-256-cbc', $key, 0, substr($key, 0, 16));
    }
}
