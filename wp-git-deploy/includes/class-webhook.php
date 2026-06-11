<?php
class WPGD_Webhook {
    
    public function register_routes() {
        register_rest_route('wpgd/v1', '/webhook/(?P<id>\d+)', [
            'methods' => 'POST',
            'callback' => [$this, 'handle_webhook'],
            'permission_callback' => '__return_true'
        ]);
    }
    
    public function handle_webhook($request) {
        $repo_id = $request->get_param('id');
        $secret = $request->get_header('X-Hub-Signature');
        
        // Verify webhook secret
        if (!$this->verify_webhook($request->get_body(), $secret)) {
            return new WP_REST_Response(['error' => 'Invalid signature'], 401);
        }
        
        $payload = json_decode($request->get_body(), true);
        
        // Check if push to relevant branch
        $branch = $this->extract_branch_from_payload($payload);
        
        $deployer = new WPGD_Deployer();
        $result = $deployer->deploy($repo_id, null, $branch);
        
        return new WP_REST_Response($result, $result['success'] ? 200 : 500);
    }
    
    private function verify_webhook($payload, $signature) {
        // Implement webhook signature verification
        return true;
    }
    
    private function extract_branch_from_payload($payload) {
        // Extract branch from GitHub/GitLab/Bitbucket payload
        if (isset($payload['ref'])) {
            $ref_parts = explode('/', $payload['ref']);
            return end($ref_parts);
        }
        
        return 'main';
    }
}
