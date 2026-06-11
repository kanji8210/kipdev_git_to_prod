<?php
class WPGD_Rollback {
    private $logger;
    
    public function __construct() {
        $this->logger = new WPGD_Logger();
    }
    
    public function rollback($repo_id, $version) {
        try {
            $repo_manager = new WPGD_Repo_Manager();
            $repo = $repo_manager->get_repo($repo_id);
            
            // Find backup for this version
            $backup_path = WPGD_BACKUP_DIR . 'backup_' . $repo->target_slug . '_' . $version;
            
            if (!file_exists($backup_path)) {
                throw new Exception('Backup not found for version: ' . $version);
            }
            
            $target_path = $this->get_target_path($repo);
            
            // Create current backup before rollback
            if (file_exists($target_path)) {
                $current_backup = WPGD_BACKUP_DIR . 'pre_rollback_' . $repo->target_slug . '_' . date('Ymd_His');
                rename($target_path, $current_backup);
            }
            
            // Restore from backup
            rename($backup_path, $target_path);
            
            $this->logger->log($repo_id, 'rolled_back', $version, null, 'Rolled back to version: ' . $version);
            
            return ['success' => true, 'message' => 'Rollback successful'];
            
        } catch (Exception $e) {
            $this->logger->log($repo_id, 'failed', null, null, 'Rollback failed: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    public function get_available_backups($repo_id) {
        $backups = [];
        $files = glob(WPGD_BACKUP_DIR . 'backup_*');
        
        foreach ($files as $file) {
            if (is_dir($file)) {
                $backups[] = basename($file);
            }
        }
        
        return $backups;
    }
    
    private function get_target_path($repo) {
        if ($repo->target_type === 'plugin') {
            return WP_PLUGIN_DIR . '/' . $repo->target_slug;
        } else {
            return get_theme_root() . '/' . $repo->target_slug;
        }
    }
}
