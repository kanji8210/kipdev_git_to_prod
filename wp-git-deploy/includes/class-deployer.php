<?php
class WPGD_Deployer {
    private $logger;
    private $validator;
    
    public function __construct() {
        $this->logger = new WPGD_Logger();
        $this->validator = new WPGD_Validator();
    }
    
    public function deploy($repo_id, $commit_hash = null, $branch = null) {
        // Check if already deploying
        if (get_transient('wpgd_deploying_' . $repo_id)) {
            return ['success' => false, 'message' => 'Deployment already in progress'];
        }
        
        set_transient('wpgd_deploying_' . $repo_id, true, 300);
        
        try {
            $repo_manager = new WPGD_Repo_Manager();
            $repo = $repo_manager->get_repo($repo_id);
            
            if (!$repo) {
                throw new Exception('Repository not found');
            }
            
            // Log start
            $this->logger->log($repo_id, 'started', null, $commit_hash, 'Deployment started');
            
            // Pre-deployment validation
            $validation = $this->validator->run_all_checks($repo);
            if (!$validation['success']) {
                throw new Exception('Pre-deployment checks failed: ' . implode(', ', $validation['errors']));
            }
            
            // Clone or update repository
            $temp_dir = $this->clone_repository($repo, $branch);
            
            // Run pre-deploy scripts if exists
            $this->run_pre_deploy_scripts($temp_dir);
            
            // Deploy to target location
            $target_path = $this->get_target_path($repo);
            $this->deploy_files($temp_dir, $target_path);
            
            // Run post-deploy scripts
            $this->run_post_deploy_scripts($target_path);
            
            // Cleanup
            $this->cleanup_temp_dir($temp_dir);
            
            // Log success
            $this->logger->log($repo_id, 'success', null, $commit_hash, 'Deployment completed successfully');
            
            delete_transient('wpgd_deploying_' . $repo_id);
            
            return ['success' => true, 'message' => 'Deployment successful'];
            
        } catch (Exception $e) {
            $this->logger->log($repo_id, 'failed', null, $commit_hash, $e->getMessage());
            delete_transient('wpgd_deploying_' . $repo_id);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    private function clone_repository($repo, $branch) {
        $temp_dir = WPGD_BACKUP_DIR . 'temp_' . uniqid();
        
        // Build git clone command
        $repo_url = $this->get_authenticated_url($repo);
        $cmd = sprintf('git clone --depth 1 --branch %s %s %s 2>&1',
            escapeshellarg($branch ?: $repo->active_branch),
            escapeshellarg($repo_url),
            escapeshellarg($temp_dir)
        );
        
        exec($cmd, $output, $return_code);
        
        if ($return_code !== 0) {
            throw new Exception('Git clone failed: ' . implode("\n", $output));
        }
        
        return $temp_dir;
    }
    
    private function get_authenticated_url($repo) {
        // Add authentication to URL
        return $repo->url;
    }
    
    private function get_target_path($repo) {
        if ($repo->target_type === 'plugin') {
            return WP_PLUGIN_DIR . '/' . $repo->target_slug;
        } else {
            return get_theme_root() . '/' . $repo->target_slug;
        }
    }
    
    private function deploy_files($source, $destination) {
        // Create backup before deploying
        if (file_exists($destination)) {
            $backup_dir = WPGD_BACKUP_DIR . 'backup_' . basename($destination) . '_' . date('Ymd_His');
            rename($destination, $backup_dir);
        }
        
        // Copy new files
        $this->copy_directory($source, $destination);
    }
    
    private function copy_directory($source, $destination) {
        if (!is_dir($source)) {
            return false;
        }
        
        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }
        
        foreach (scandir($source) as $file) {
            if ($file == '.' || $file == '..' || $file == '.git') continue;
            
            $src_file = $source . '/' . $file;
            $dst_file = $destination . '/' . $file;
            
            if (is_dir($src_file)) {
                $this->copy_directory($src_file, $dst_file);
            } else {
                copy($src_file, $dst_file);
            }
        }
        
        return true;
    }
    
    private function run_pre_deploy_scripts($temp_dir) {
        if (file_exists($temp_dir . '/pre-deploy.sh')) {
            exec('chmod +x ' . escapeshellarg($temp_dir . '/pre-deploy.sh') . ' && ' . escapeshellarg($temp_dir . '/pre-deploy.sh'));
        }
    }
    
    private function run_post_deploy_scripts($target_path) {
        if (file_exists($target_path . '/post-deploy.sh')) {
            exec('chmod +x ' . escapeshellarg($target_path . '/post-deploy.sh') . ' && ' . escapeshellarg($target_path . '/post-deploy.sh'));
        }
    }
    
    private function cleanup_temp_dir($temp_dir) {
        if (file_exists($temp_dir)) {
            exec('rm -rf ' . escapeshellarg($temp_dir));
        }
    }
}
