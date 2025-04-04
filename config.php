<?php
/**
 * Configuration file for Domain Valuator application
 * NOTE: Place this file ONE DIRECTORY ABOVE your public_html folder for security!
 */

// IMPORTANT: Replace these values with your actual API keys
return [
    // Stripe Configuration
    'STRIPE_SECRET_KEY' => 'sk_test_your_secret_key',
    'NEXT_PUBLIC_STRIPE_PUBLISHABLE_KEY' => 'pk_test_your_publishable_key',
    'NEXT_PUBLIC_STRIPE_PRICE_ID' => 'price_your_subscription_price_id',
    'STRIPE_WEBHOOK_SECRET' => 'whsec_your_webhook_secret',
    
    // Application Settings
    'NEXT_PUBLIC_APP_NAME' => 'Domain Valuator',
    'NEXT_PUBLIC_APP_URL' => 'https://dorkysearch.org', // Replace with your actual URL
    
    // Service Limits
    'NEXT_PUBLIC_MAX_RESULTS' => 9,
    'NEXT_PUBLIC_DEFAULT_TIMEFRAME' => 3,
    'NEXT_PUBLIC_SUBSCRIPTION_PRICE' => 1.99,
    'NEXT_PUBLIC_SUBSCRIPTION_PERIOD' => 'week',
];
?> 