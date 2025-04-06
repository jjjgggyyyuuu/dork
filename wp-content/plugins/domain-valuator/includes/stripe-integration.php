<?php
/**
 * Domain Valuator Stripe Integration
 * 
 * Handles the Stripe payment processing for subscriptions.
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Domain_Valuator_Stripe {

    /**
     * Constructor
     */
    public function __construct() {
        // Register Ajax endpoints
        add_action('wp_ajax_domain_valuator_create_checkout', array($this, 'create_checkout_session'));
        add_action('wp_ajax_nopriv_domain_valuator_create_checkout', array($this, 'create_checkout_session'));
        
        // Register success page endpoint
        add_action('wp_ajax_domain_valuator_subscription_success', array($this, 'handle_subscription_success'));
        add_action('wp_ajax_nopriv_domain_valuator_subscription_success', array($this, 'handle_subscription_success'));
        
        // Register webhook handler
        add_action('rest_api_init', array($this, 'register_webhook_endpoint'));
    }

    /**
     * Create a Stripe Checkout Session
     */
    public function create_checkout_session() {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'domain-valuator-nonce')) {
            wp_send_json_error('Invalid security token');
            return;
        }
        
        // Check if user is logged in
        if (!is_user_logged_in()) {
            wp_send_json_error('User must be logged in');
            return;
        }
        
        // Get current user
        $user = wp_get_current_user();
        
        // Load Stripe API
        $this->load_stripe();
        
        try {
            // Get Stripe configuration
            $price_id = get_option('domain_valuator_stripe_price_id');
            
            // Create checkout session
            $checkout_session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price' => $price_id,
                    'quantity' => 1,
                ]],
                'mode' => 'subscription',
                'success_url' => add_query_arg(
                    [
                        'action' => 'domain_valuator_subscription_success',
                        'session_id' => '{CHECKOUT_SESSION_ID}',
                        'nonce' => wp_create_nonce('domain-valuator-success-nonce')
                    ],
                    admin_url('admin-ajax.php')
                ),
                'cancel_url' => wp_get_referer() ? wp_get_referer() : home_url(),
                'customer_email' => $user->user_email,
                'client_reference_id' => $user->ID,
                'metadata' => [
                    'user_id' => $user->ID,
                    'username' => $user->user_login
                ]
            ]);
            
            // Return the ID to the client
            wp_send_json_success([
                'id' => $checkout_session->id
            ]);
        } catch (Exception $e) {
            wp_send_json_error($e->getMessage());
        }
    }

    /**
     * Handle successful subscription
     */
    public function handle_subscription_success() {
        // Verify nonce
        if (!isset($_GET['nonce']) || !wp_verify_nonce($_GET['nonce'], 'domain-valuator-success-nonce')) {
            wp_die('Invalid security token');
            return;
        }
        
        // Get session ID
        $session_id = isset($_GET['session_id']) ? sanitize_text_field($_GET['session_id']) : '';
        if (empty($session_id)) {
            wp_die('Missing session ID');
            return;
        }
        
        // Load Stripe API
        $this->load_stripe();
        
        try {
            // Retrieve the session
            $session = \Stripe\Checkout\Session::retrieve($session_id);
            
            // Check if this is a valid subscription
            if ($session->mode != 'subscription') {
                wp_die('Invalid session type');
                return;
            }
            
            // Get the subscription
            $subscription = \Stripe\Subscription::retrieve($session->subscription);
            
            // Get user ID from metadata or client_reference_id
            $user_id = isset($session->metadata->user_id) ? $session->metadata->user_id : $session->client_reference_id;
            
            // Update user metadata
            update_user_meta($user_id, 'domain_valuator_subscription_id', $subscription->id);
            update_user_meta($user_id, 'domain_valuator_subscription_status', $subscription->status);
            update_user_meta($user_id, 'domain_valuator_customer_id', $subscription->customer);
            update_user_meta($user_id, 'domain_valuator_subscription_period_end', $subscription->current_period_end);
            
            // Redirect to the referring page or home
            $redirect_url = wp_get_referer() ? wp_get_referer() : home_url();
            wp_redirect($redirect_url);
            exit;
        } catch (Exception $e) {
            wp_die('Error processing subscription: ' . $e->getMessage());
        }
    }

    /**
     * Register webhook endpoint
     */
    public function register_webhook_endpoint() {
        register_rest_route('domain-valuator/v1', '/webhook', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_webhook'),
            'permission_callback' => '__return_true'
        ));
    }

    /**
     * Handle Stripe webhook
     */
    public function handle_webhook($request) {
        // Get request body
        $payload = $request->get_body();
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        
        // Load Stripe API
        $this->load_stripe();
        
        $webhook_secret = get_option('domain_valuator_stripe_webhook_secret');
        
        try {
            // Verify webhook signature
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $webhook_secret
            );
            
            // Handle specific events
            switch ($event->type) {
                case 'customer.subscription.updated':
                case 'customer.subscription.deleted':
                    $subscription = $event->data->object;
                    $this->update_user_subscription_status($subscription);
                    break;
            }
            
            return new WP_REST_Response(array('status' => 'success'), 200);
        } catch (Exception $e) {
            return new WP_REST_Response(array('status' => 'error', 'message' => $e->getMessage()), 400);
        }
    }

    /**
     * Update user subscription status
     */
    private function update_user_subscription_status($subscription) {
        // Find the user by subscription ID
        $users = get_users(array(
            'meta_key' => 'domain_valuator_subscription_id',
            'meta_value' => $subscription->id
        ));
        
        if (empty($users)) {
            return;
        }
        
        $user = $users[0];
        
        // Update subscription status
        update_user_meta($user->ID, 'domain_valuator_subscription_status', $subscription->status);
        update_user_meta($user->ID, 'domain_valuator_subscription_period_end', $subscription->current_period_end);
        
        // If subscription is cancelled or unpaid, update accordingly
        if (in_array($subscription->status, array('canceled', 'unpaid'))) {
            update_user_meta($user->ID, 'domain_valuator_subscription_cancelled', 'yes');
        }
    }

    /**
     * Load Stripe library
     */
    private function load_stripe() {
        // Check if Stripe is already loaded
        if (!class_exists('\Stripe\Stripe')) {
            // Include Composer autoloader if available
            if (file_exists(DOMAIN_VALUATOR_PLUGIN_DIR . 'vendor/autoload.php')) {
                require_once DOMAIN_VALUATOR_PLUGIN_DIR . 'vendor/autoload.php';
            } else {
                // Include Stripe PHP SDK directly
                require_once DOMAIN_VALUATOR_PLUGIN_DIR . 'includes/stripe-php/init.php';
            }
        }
        
        // Set API key
        \Stripe\Stripe::setApiKey(get_option('domain_valuator_stripe_secret_key'));
        
        // Set API version
        \Stripe\Stripe::setApiVersion('2023-10-16');
    }
} 