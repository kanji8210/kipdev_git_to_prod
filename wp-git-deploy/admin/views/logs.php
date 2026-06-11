<div class="wrap">
    <h1>Deployment Logs</h1>
    
    <?php
    $logs = (new WPGD_Logger())->get_logs(null, 100);
    if ($logs) {
        echo '<table class="wp-list-table widefat fixed striped">';
        echo '<thead><tr>
            <th>Date/Time</th>
            <th>Repository ID</th>
            <th>Status</th>
            <th>From Commit</th>
            <th>To Commit</th>
            <th>Message</th>
        </tr></thead><tbody>';
        
        foreach ($logs as $log) {
            $status_class = $log->status == 'success' ? 'success' : ($log->status == 'failed' ? 'error' : 'warning');
            echo "<tr>
                <td>{$log->created_at}</td>
                <td>{$log->repo_id}</td>
                <td><span class='status-{$status_class}'>{$log->status}</span></td>
                <td><code>" . substr($log->from_commit, 0, 7) . "</code></td>
                <td><code>" . substr($log->to_commit, 0, 7) . "</code></td>
                <td>{$log->log_message}</td>
            </tr>";
        }
        
        echo '</tbody></table>';
    } else {
        echo '<p>No deployment logs found.</p>';
    }
    ?>
</div>
