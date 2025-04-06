<?php
/**
 * Domain Valuator Uninstall
 *
 * @package Domain_Valuator
 */

// If uninstall not called from WordPress, exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete options
delete_option('domain_valuator_stripe_publishable_key');
delete_option('domain_valuator_stripe_secret_key');
delete_option('domain_valuator_stripe_price_id');
delete_option('domain_valuator_stripe_webhook_secret');
delete_option('domain_valuator_pricing_amount');
delete_option('domain_valuator_pricing_period');
delete_option('domain_valuator_max_results');
delete_option('domain_valuator_default_timeframe');
delete_option('domain_valuator_use_plugin_css');

// Remove user meta for all users
$users = get_users(array(
    'meta_query' => array(
        'relation' => 'OR',
        array(
            'key' => 'domain_valuator_subscription_id',
            'compare' => 'EXISTS'
        ),
        array(
            'key' => 'domain_valuator_subscription_status',
            'compare' => 'EXISTS'
        ),
        array(
            'key' => 'domain_valuator_customer_id',
            'compare' => 'EXISTS'
        ),
        array(
            'key' => 'domain_valuator_subscription_period_end',
            'compare' => 'EXISTS'
        ),
        array(
            'key' => 'domain_valuator_subscription_cancelled',
            'compare' => 'EXISTS'
        )
    )
));

foreach ($users as $user) {
    delete_user_meta($user->ID, 'domain_valuator_subscription_id');
    delete_user_meta($user->ID, 'domain_valuator_subscription_status');
    delete_user_meta($user->ID, 'domain_valuator_customer_id');
    delete_user_meta($user->ID, 'domain_valuator_subscription_period_end');
    delete_user_meta($user->ID, 'domain_valuator_subscription_cancelled');
} 