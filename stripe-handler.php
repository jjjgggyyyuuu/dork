<?php
// Enable error reporting for debugging during development (disable in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Load configuration file with API keys
// You should place this file OUTSIDE your web root for security!
$config = include_once '../config.php';

// If no config file is found, attempt to use constants
if (!isset($config) || !is_array($config)) {
    define('STRIPE_SECRET_KEY', getenv('STRIPE_SECRET_KEY')); 
    define('STRIPE_PUBLISHABLE_KEY', getenv('NEXT_PUBLIC_STRIPE_PUBLISHABLE_KEY'));
    define('STRIPE_PRICE_ID', getenv('NEXT_PUBLIC_STRIPE_PRICE_ID'));
    define('STRIPE_WEBHOOK_SECRET', getenv('STRIPE_WEBHOOK_SECRET'));
    define('APP_URL', getenv('NEXT_PUBLIC_APP_URL') ?: 'https://dorkysearch.org');
} else {
    define('STRIPE_SECRET_KEY', $config['STRIPE_SECRET_KEY']);
    define('STRIPE_PUBLISHABLE_KEY', $config['NEXT_PUBLIC_STRIPE_PUBLISHABLE_KEY']);
    define('STRIPE_PRICE_ID', $config['NEXT_PUBLIC_STRIPE_PRICE_ID']);
    define('STRIPE_WEBHOOK_SECRET', $config['STRIPE_WEBHOOK_SECRET']);
    define('APP_URL', $config['NEXT_PUBLIC_APP_URL'] ?: 'https://dorkysearch.org');
}

// Check if Stripe library is available
if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
    http_response_code(500);
    die(json_encode([
        'error' => 'Stripe library not found. Please run: composer require stripe/stripe-php'
    ]));
}

// Load Stripe library
require_once __DIR__ . '/vendor/autoload.php';

// Set up Stripe with your secret key
\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);
$stripe = new \Stripe\StripeClient(STRIPE_SECRET_KEY);

// Get the request method and path
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Handle different API endpoints
header('Content-Type: application/json');

// Creating a checkout session
if ($action === 'create_session') {
    if ($requestMethod !== 'POST') {
        http_response_code(405);
        die(json_encode(['error' => 'Method not allowed']));
    }

    try {
        // Get POST data
        $input = json_decode(file_get_contents('php://input'), true);
        $priceId = isset($input['priceId']) ? $input['priceId'] : STRIPE_PRICE_ID;
        
        if (empty($priceId)) {
            throw new Exception('Price ID is required');
        }
        
        // Create checkout session
        $session = $stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price' => $priceId,
                    'quantity' => 1,
                ],
            ],
            'mode' => 'subscription',
            'success_url' => APP_URL . '/success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => APP_URL . '/',
            'metadata' => [
                'appName' => 'Domain Valuator',
            ],
        ]);
        
        echo json_encode(['sessionId' => $session->id]);
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'error' => $e->getMessage()
        ]);
    }
    exit;
}

// Creating a customer portal link
if ($action === 'create_portal') {
    if ($requestMethod !== 'POST') {
        http_response_code(405);
        die(json_encode(['error' => 'Method not allowed']));
    }

    try {
        // Get POST data
        $input = json_decode(file_get_contents('php://input'), true);
        $customerId = isset($input['customerId']) ? $input['customerId'] : null;
        
        if (empty($customerId)) {
            throw new Exception('Customer ID is required');
        }
        
        // Create portal session
        $session = $stripe->billingPortal->sessions->create([
            'customer' => $customerId,
            'return_url' => APP_URL,
        ]);
        
        echo json_encode(['url' => $session->url]);
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'error' => $e->getMessage()
        ]);
    }
    exit;
}

// Webhook handling
if ($action === 'webhook') {
    try {
        $payload = @file_get_contents('php://input');
        $sigHeader = isset($_SERVER['HTTP_STRIPE_SIGNATURE']) ? $_SERVER['HTTP_STRIPE_SIGNATURE'] : null;
        
        if (empty($sigHeader) || empty(STRIPE_WEBHOOK_SECRET)) {
            throw new Exception('Webhook signature or secret is missing');
        }
        
        // Verify the event
        $event = \Stripe\Webhook::constructEvent(
            $payload, $sigHeader, STRIPE_WEBHOOK_SECRET
        );
        
        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                // Payment was successful
                $session = $event->data->object;
                // Here you could update a database, send an email, etc.
                break;
            case 'customer.subscription.updated':
            case 'customer.subscription.deleted':
                // Handle subscription changes
                $subscription = $event->data->object;
                break;
            default:
                // Unexpected event type
                break;
        }
        
        http_response_code(200);
        echo json_encode(['received' => true]);
        
    } catch (\UnexpectedValueException $e) {
        // Invalid payload
        http_response_code(400);
        echo json_encode(['error' => 'Invalid payload']);
    } catch (\Stripe\Exception\SignatureVerificationException $e) {
        // Invalid signature
        http_response_code(400);
        echo json_encode(['error' => 'Invalid signature']);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}

// If none of the actions matched, return a 404
http_response_code(404);
echo json_encode(['error' => 'Endpoint not found']);
?> 