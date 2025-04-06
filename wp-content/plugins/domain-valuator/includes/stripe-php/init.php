<?php
/**
 * Stripe PHP SDK Initialization
 * 
 * This is a placeholder file for the Stripe PHP SDK.
 * In a production environment, you should install the Stripe PHP SDK via Composer.
 */

// Display a notice if this file is accessed directly
if (!defined('ABSPATH')) {
    exit('Direct access denied.');
}

// Check if Composer's autoloader exists and the Stripe library is installed
if (file_exists(dirname(dirname(dirname(__FILE__))) . '/vendor/autoload.php')) {
    // Load Stripe via Composer autoload
    require_once dirname(dirname(dirname(__FILE__))) . '/vendor/autoload.php';
} else {
    // If Stripe is not installed via Composer, display an admin notice
    function domain_valuator_stripe_missing_notice() {
        ?>
        <div class="notice notice-error">
            <p>
                <strong><?php _e('Domain Valuator: Stripe PHP SDK Missing', 'domain-valuator'); ?></strong>
            </p>
            <p>
                <?php _e('The Stripe PHP SDK is not installed. Please install it using Composer by running the following command in your plugin directory:', 'domain-valuator'); ?>
            </p>
            <pre>composer install</pre>
            <p>
                <?php _e('Alternatively, you can download the Stripe PHP SDK manually from:', 'domain-valuator'); ?>
                <a href="https://github.com/stripe/stripe-php" target="_blank">https://github.com/stripe/stripe-php</a>
            </p>
        </div>
        <?php
    }
    
    // Hook the notice to admin_notices
    add_action('admin_notices', 'domain_valuator_stripe_missing_notice');
    
    // Define a minimal set of classes to prevent fatal errors
    // This is just a placeholder for development/testing
    if (!class_exists('\Stripe\Stripe')) {
        class StripeBase {
            public static function setApiKey($apiKey) {
                return true;
            }
            
            public static function setApiVersion($apiVersion) {
                return true;
            }
        }
        
        class StripeObject {
            public $id;
            public $object;
            public $created;
            public $livemode;
            public $metadata;
            
            public function __construct($params = null) {
                $this->id = isset($params['id']) ? $params['id'] : 'test_' . time();
                $this->object = get_class($this);
                $this->created = time();
                $this->livemode = false;
                $this->metadata = new \stdClass();
            }
            
            public static function retrieve($id, $opts = null) {
                $class = get_called_class();
                return new $class(['id' => $id]);
            }
        }
        
        class StripePlaceholder extends StripeObject {
            public static function create($params = null, $opts = null) {
                $class = get_called_class();
                return new $class($params);
            }
        }
        
        // Minimal implementation of the Stripe namespace
        class Stripe extends \StripeBase {}
        class Customer extends \StripePlaceholder {}
        class Subscription extends \StripePlaceholder {}
        class Checkout {
            public static function Session($params = null) {
                $session = new \StripePlaceholder($params);
                $session->url = '#';
                $session->mode = 'subscription';
                $session->subscription = 'sub_test';
                $session->customer = 'cus_test';
                $session->client_reference_id = '';
                return $session;
            }
        }
        
        class Webhook {
            public static function constructEvent($payload, $sigHeader, $secret) {
                return (object)[
                    'type' => 'test.event',
                    'data' => (object)[
                        'object' => new Subscription(['id' => 'sub_test'])
                    ]
                ];
            }
        }
    }
}