<div class="wrap">
    <h1>Repository Connections</h1>
    
    <form method="post" action="">
        <?php wp_nonce_field('kipdev_add_repo'); ?>
        <table class="form-table">
            <tr>
                <th scope="row">Repository Name</th>
                <td><input type="text" name="repo_name" class="regular-text" required></td>
            </tr>
            <tr>
                <th scope="row">Repository Type</th>
                <td>
                    <select name="repo_type">
                        <option value="github">GitHub</option>
                        <option value="gitlab">GitLab</option>
                        <option value="bitbucket">Bitbucket</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row">Repository URL</th>
                <td><input type="url" name="repo_url" class="regular-text" required></td>
            </tr>
            <tr>
                <th scope="row">Target Type</th>
                <td>
                    <select name="target_type">
                        <option value="plugin">Plugin</option>
                        <option value="theme">Theme</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row">Target Slug</th>
                <td><input type="text" name="target_slug" class="regular-text" required></td>
            </tr>
            <tr>
                <th scope="row">Authentication</th>
                <td>
                    <select name="auth_type" id="auth_type">
                        <option value="token">Access Token</option>
                        <option value="ssh">SSH Key</option>
                    </select>
                </td>
            </tr>
            <tr id="token_field">
                <th scope="row">Access Token</th>
                <td><input type="password" name="token" class="regular-text"></td>
            </tr>
            <tr id="ssh_field" style="display:none;">
                <th scope="row">SSH Private Key</th>
                <td><textarea name="ssh_key" rows="5" class="large-text"></textarea></td>
            </tr>
            <tr>
                <th scope="row">Active Branch</th>
                <td><input type="text" name="active_branch" value="main" class="regular-text"></td>
            </tr>
        </table>
        <?php submit_button('Add Repository'); ?>
    </form>
    
    <h2>Connected Repositories</h2>
    <?php
    $repos = (new KIPDEV_Repo_Manager())->get_repositories();
    if ($repos) {
        echo '<table class="wp-list-table widefat fixed striped">';
        echo '<thead><tr><th>Name</th><th>Type</th><th>Target</th><th>Branch</th><th>Actions</th></tr></thead><tbody>';
        foreach ($repos as $repo) {
            echo "<tr>
                <td>{$repo->name}</td>
                <td>{$repo->type}</td>
                <td>{$repo->target_type}/{$repo->target_slug}</td>
                <td>{$repo->active_branch}</td>
                <td>
                    <button class='button deploy-now' data-id='{$repo->id}'>Deploy Now</button>
                    <button class='button rollback' data-id='{$repo->id}'>Rollback</button>
                </td>
            </tr>";
        }
        echo '</tbody></table>';
    } else {
        echo '<p>No repositories connected yet.</p>';
    }
    ?>
</div>
