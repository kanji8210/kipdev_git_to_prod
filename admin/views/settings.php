<div class="wrap">
    <h1>Plugin Settings</h1>
    
    <form method="post" action="options.php">
        <?php settings_fields('kipdev_settings'); ?>
        
        <table class="form-table">
            <tr>
                <th scope="row">Enable Webhook Auto-deploy</th>
                <td>
                    <input type="checkbox" name="kipdev_enable_webhook" value="1" <?php checked(get_option('kipdev_enable_webhook'), 1); ?>>
                    <p class="description">Automatically deploy when webhook is received</p>
                </td>
            </tr>
            <tr>
                <th scope="row">Webhook Secret</th>
                <td>
                    <input type="text" name="kipdev_webhook_secret" value="<?php echo esc_attr(get_option('kipdev_webhook_secret')); ?>" class="regular-text">
                    <p class="description">Secret key for webhook verification</p>
                </td>
            </tr>
            <tr>
                <th scope="row">Enable Cron Auto-updates</th>
                <td>
                    <input type="checkbox" name="kipdev_enable_cron" value="1" <?php checked(get_option('kipdev_enable_cron'), 1); ?>>
                    <p class="description">Check for updates every hour</p>
                </td>
            </tr>
            <tr>
                <th scope="row">Backup Retention Days</th>
                <td>
                    <input type="number" name="kipdev_backup_retention" value="<?php echo esc_attr(get_option('kipdev_backup_retention', 30)); ?>" class="small-text">
                    <p class="description">Number of days to keep old backups</p>
                </td>
            </tr>
            <tr>
                <th scope="row">Enable Pre-deploy Checks</th>
                <td>
                    <input type="checkbox" name="kipdev_enable_prechecks" value="1" <?php checked(get_option('kipdev_enable_prechecks'), 1); ?> checked>
                    <p class="description">Run PHP syntax and dependency checks before deployment</p>
                </td>
            </tr>
        </table>
        
        <?php submit_button(); ?>
    </form>
    
    <h2>System Information</h2>
    <table class="widefat striped">
        <tr><th>PHP Version</th><td><?php echo phpversion(); ?></td></tr>
        <tr><th>WordPress Version</th><td><?php echo get_bloginfo('version'); ?></td></tr>
        <tr><th>Git Available</th><td><?php echo (new KIPDEV_Validator())->check_git_available() ? 'Yes' : 'No'; ?></td></tr>
        <tr><th>Disk Free Space</th><td><?php echo size_format(disk_free_space(ABSPATH)); ?></td></tr>
        <tr><th>Upload Max Size</th><td><?php echo ini_get('upload_max_filesize'); ?></td></tr>
        <tr><th>Memory Limit</th><td><?php echo ini_get('memory_limit'); ?></td></tr>
    </table>
</div>
