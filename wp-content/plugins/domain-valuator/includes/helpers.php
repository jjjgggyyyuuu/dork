<?php
/**
 * Domain Valuator Helpers
 * 
 * Utility functions for the Domain Valuator plugin
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Domain_Valuator_Helpers {

    /**
     * Check if the current user has an active subscription
     * 
     * @return bool Whether the user is subscribed
     */
    public static function is_user_subscribed() {
        // If not logged in, definitely not subscribed
        if (!is_user_logged_in()) {
            return false;
        }
        
        // Get current user
        $user_id = get_current_user_id();
        
        // Check subscription status
        $subscription_status = get_user_meta($user_id, 'domain_valuator_subscription_status', true);
        
        // Check if subscription is active
        if ($subscription_status === 'active') {
            return true;
        }
        
        // Check if subscription is trialing
        if ($subscription_status === 'trialing') {
            return true;
        }
        
        // Check if there's a valid subscription period end
        $period_end = get_user_meta($user_id, 'domain_valuator_subscription_period_end', true);
        if (!empty($period_end) && $period_end > time()) {
            return true;
        }
        
        return false;
    }

    /**
     * Get subscription status details
     * 
     * @return array Subscription details
     */
    public static function get_subscription_details() {
        // If not logged in, return empty details
        if (!is_user_logged_in()) {
            return array(
                'has_subscription' => false,
                'status' => 'none',
                'customer_id' => '',
                'subscription_id' => '',
                'period_end' => 0,
                'period_end_formatted' => '',
            );
        }
        
        // Get current user
        $user_id = get_current_user_id();
        
        // Get subscription details
        $status = get_user_meta($user_id, 'domain_valuator_subscription_status', true);
        $customer_id = get_user_meta($user_id, 'domain_valuator_customer_id', true);
        $subscription_id = get_user_meta($user_id, 'domain_valuator_subscription_id', true);
        $period_end = get_user_meta($user_id, 'domain_valuator_subscription_period_end', true);
        
        // Format period end
        $period_end_formatted = '';
        if (!empty($period_end)) {
            $period_end_formatted = date_i18n(get_option('date_format'), $period_end);
        }
        
        return array(
            'has_subscription' => self::is_user_subscribed(),
            'status' => $status,
            'customer_id' => $customer_id,
            'subscription_id' => $subscription_id,
            'period_end' => $period_end,
            'period_end_formatted' => $period_end_formatted,
        );
    }

    /**
     * Format currency amount
     * 
     * @param float $amount Amount to format
     * @return string Formatted amount
     */
    public static function format_currency($amount) {
        return '$' . number_format((float)$amount, 2, '.', ',');
    }

    /**
     * Calculate ROI percentage
     * 
     * @param float $initial_investment Initial investment amount
     * @param float $final_value Final value
     * @return string Formatted ROI percentage
     */
    public static function calculate_roi($initial_investment, $final_value) {
        if (empty($initial_investment) || $initial_investment == 0) {
            return '0%';
        }
        
        $roi = (($final_value - $initial_investment) / $initial_investment) * 100;
        return round($roi, 1) . '%';
    }

    /**
     * Generate a sample domain data set for testing
     * 
     * @param float $budget User's budget
     * @param int $timeframe Investment timeframe in months
     * @param int $limit Maximum number of results
     * @return array Array of domain data
     */
    public static function generate_sample_domains($budget, $timeframe, $limit = 10) {
        $domains = array();
        
        // Domain TLDs for samples
        $tlds = array('.com', '.net', '.org', '.io', '.co', '.app', '.dev');
        
        // Domain prefixes
        $prefixes = array(
            'smart', 'quick', 'fast', 'easy', 'bright', 'clever', 'swift',
            'rapid', 'instant', 'dynamic', 'modern', 'tech', 'digital', 'cyber',
            'auto', 'meta', 'crypto', 'web', 'app', 'cloud', 'mobile', 'data'
        );
        
        // Domain suffixes
        $suffixes = array(
            'hub', 'space', 'spot', 'zone', 'central', 'center', 'portal',
            'place', 'point', 'square', 'market', 'mart', 'store', 'shop',
            'solutions', 'systems', 'services', 'tools', 'works', 'tech'
        );
        
        // Generate random domains
        for ($i = 0; $i < $limit; $i++) {
            // Random domain components
            $prefix = $prefixes[array_rand($prefixes)];
            $suffix = $suffixes[array_rand($suffixes)];
            $tld = $tlds[array_rand($tlds)];
            
            // Create domain name
            $domain = $prefix . $suffix . $tld;
            
            // Generate current price under budget
            $current_price = round(mt_rand(10, $budget * 0.9), 2);
            
            // Calculate future value based on timeframe
            // Higher timeframe = potentially higher ROI
            $growth_multiplier = 1 + (mt_rand(10, 30) / 100) * ($timeframe / 6);
            $future_value = round($current_price * $growth_multiplier, 2);
            
            // Calculate ROI
            $roi = self::calculate_roi($current_price, $future_value);
            
            // Add to results
            $domains[] = array(
                'domain' => $domain,
                'current_price' => $current_price,
                'future_value' => $future_value,
                'roi' => $roi,
                'timeframe' => $timeframe
            );
        }
        
        // Sort by ROI (highest first)
        usort($domains, function($a, $b) {
            $a_roi = floatval(str_replace('%', '', $a['roi']));
            $b_roi = floatval(str_replace('%', '', $b['roi']));
            return $b_roi <=> $a_roi;
        });
        
        return $domains;
    }
} 