<?php
class WPGD_Validator {
    
    public function run_all_checks($repo) {
        $errors = [];
        
        // Check PHP syntax
        $php_errors = $this->check_php_syntax($repo);
        if (!empty($php_errors)) {
            $errors = array_merge($errors, $php_errors);
        }
        
        // Check required files
        $missing_files = $this->check_required_files($repo);
        if (!empty($missing_files)) {
            $errors = array_merge($errors, $missing_files);
        }
        
        // Check Composer dependencies
        $composer_errors = $this->check_composer($repo);
        if (!empty($composer_errors)) {
            $errors = array_merge($errors, $composer_errors);
        }
        
        return [
            'success' => empty($errors),
            'errors' => $errors
        ];
    }
    
    private function check_php_syntax($repo) {
        $errors = [];
        
        // This would check PHP files in the repo
        // Implementation depends on how you access the files
        
        return $errors;
    }
    
    private function check_required_files($repo) {
        $errors = [];
        
        if ($repo->target_type === 'plugin') {
            // Check for main plugin file
            $main_file = WP_PLUGIN_DIR . '/' . $repo->target_slug . '/' . $repo->target_slug . '.php';
            // Add more checks as needed
        }
        
        return $errors;
    }
    
    private function check_composer($repo) {
        $errors = [];
        
        // Check if composer.json exists and run composer validate
        $composer_path = WP_PLUGIN_DIR . '/' . $repo->target_slug . '/composer.json';
        if (file_exists($composer_path)) {
            exec('composer validate ' . escapeshellarg($composer_path) . ' 2>&1', $output, $code);
            if ($code !== 0) {
                $errors[] = 'Composer validation failed: ' . implode("\n", $output);
            }
        }
        
        return $errors;
    }
    
    public function check_disk_space() {
        $free_space = disk_free_space(ABSPATH);
        $min_space = 100 * 1024 * 1024; // 100MB minimum
        
        return $free_space > $min_space;
    }
    
    public function check_git_available() {
        exec('git --version 2>&1', $output, $code);
        return $code === 0;
    }
}
