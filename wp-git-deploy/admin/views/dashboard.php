<div class="wrap">
    <h1>WordPress Git Deploy Dashboard</h1>
    
    <div class="wpgd-stats-grid">
        <div class="wpgd-stat-card">
            <h3>Connected Repositories</h3>
            <div class="stat-number"><?php echo count((new WPGD_Repo_Manager())->get_repositories()); ?></div>
        </div>
        
        <div class="wpgd-stat-card">
            <h3>Last Deployment</h3>
            <div class="stat-text"><?php 
                $logger = new WPGD_Logger();
                $last = $logger->get_last_deployment(0);
                echo $last ? $last->created_at : 'No deployments yet';
            ?></div>
        </div>
        
        <div class="wpgd-stat-card">
            <h3>System Status</h3>
            <div class="stat-text">
                Git: <?php echo (new WPGD_Validator())->check_git_available() ? '✓ Available' : '✗ Not found'; ?>
            </div>
        </div>
    </div>
    
    <div class="wpgd-recent-deployments">
        <h2>Recent Deployments</h2>
        <?php
        $logs = (new WPGD_Logger())->get_logs(null, 10);
        if ($logs) {
            echo '<table class="wp-list-table widefat fixed striped">';
            echo '<thead><tr><th>Time</th><th>Status</th><th>Message</th></tr></thead><tbody>';
            foreach ($logs as $log) {
                $status_class = $log->status == 'success' ? 'success' : ($log->status == 'failed' ? 'error' : 'warning');
                echo "<tr>
                    <td>{$log->created_at}</td>
                    <td><span class='status-{$status_class}'>{$log->status}</span></td>
                    <td>{$log->log_message}</td>
                </tr>";
            }
            echo '</tbody></table>';
        } else {
            echo '<p>No deployments yet.</p>';
        }
        ?>
    </div>
</div>
