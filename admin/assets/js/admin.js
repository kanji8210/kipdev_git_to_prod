jQuery(document).ready(function($) {
    // Deploy now button handler
    $('.deploy-now').on('click', function(e) {
        e.preventDefault();
        var repoId = $(this).data('id');
        
        if (confirm('Are you sure you want to deploy this repository now?')) {
            showDeployingOverlay();
            
            $.ajax({
                url: kipdev_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'kipdev_deploy_now',
                    repo_id: repoId,
                    nonce: kipdev_ajax.nonce
                },
                success: function(response) {
                    hideDeployingOverlay();
                    if (response.success) {
                        alert('Deployment successful: ' + response.message);
                        location.reload();
                    } else {
                        alert('Deployment failed: ' + response.message);
                    }
                },
                error: function() {
                    hideDeployingOverlay();
                    alert('An error occurred during deployment');
                }
            });
        }
    });
    
    // Rollback button handler
    $('.rollback').on('click', function(e) {
        e.preventDefault();
        var repoId = $(this).data('id');
        var version = prompt('Enter the version tag or backup name to rollback to:');
        
        if (version) {
            if (confirm('Are you sure you want to rollback to ' + version + '?')) {
                showDeployingOverlay();
                
                $.ajax({
                    url: kipdev_ajax.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'kipdev_rollback',
                        repo_id: repoId,
                        version: version,
                        nonce: kipdev_ajax.nonce
                    },
                    success: function(response) {
                        hideDeployingOverlay();
                        if (response.success) {
                            alert('Rollback successful: ' + response.message);
                            location.reload();
                        } else {
                            alert('Rollback failed: ' + response.message);
                        }
                    },
                    error: function() {
                        hideDeployingOverlay();
                        alert('An error occurred during rollback');
                    }
                });
            }
        }
    });
    
    // Auth type toggle
    $('#auth_type').on('change', function() {
        if ($(this).val() === 'token') {
            $('#token_field').show();
            $('#ssh_field').hide();
        } else {
            $('#token_field').hide();
            $('#ssh_field').show();
        }
    });
    
    function showDeployingOverlay() {
        $('body').append('<div id="kipdev-deploying-overlay"><div class="kipdev-loader"><div class="kipdev-spinner"></div><p>Deploying... Please wait</p></div></div>');
    }
    
    function hideDeployingOverlay() {
        $('#kipdev-deploying-overlay').remove();
    }
});

// AJAX handlers for deployment
jQuery(document).ready(function($) {
    $.ajax({
        url: kipdev_ajax.ajax_url,
        type: 'POST',
        data: {
            action: 'kipdev_deploy_now',
            repo_id: repoId,
            nonce: kipdev_ajax.nonce
        }
    });
});
