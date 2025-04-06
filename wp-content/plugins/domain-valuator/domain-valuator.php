<?php
/**
 * Plugin Name: Domain Valuator
 * Plugin URI: https://example.com/domain-valuator
 * Description: AI-powered domain investment tool that helps domain resellers find profitable domain investments
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://example.com
 * Text Domain: domain-valuator
 * Domain Path: /languages
 *
 * @package Domain_Valuator
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('DOMAIN_VALUATOR_VERSION', '1.0.0');
define('DOMAIN_VALUATOR_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('DOMAIN_VALUATOR_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Main Domain Valuator class
 */
class Domain_Valuator {
    /**
     * Constructor
     */
    public function __construct() {
        // Register scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'register_scripts'));
        
        // Register shortcode
        add_shortcode('domain_valuator', array($this, 'domain_valuator_shortcode'));
    }
    
    /**
     * Register scripts and styles
     */
    public function register_scripts() {
        // Register Chart.js
        wp_register_script(
            'chartjs',
            'https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js',
            array(),
            '3.7.1',
            true
        );
        
        // Register main plugin script
        wp_register_script(
            'domain-valuator-js',
            DOMAIN_VALUATOR_PLUGIN_URL . 'assets/js/domain-valuator.js',
            array('jquery', 'chartjs'),
            DOMAIN_VALUATOR_VERSION,
            true
        );
        
        // Register plugin styles
        wp_register_style(
            'domain-valuator-css',
            DOMAIN_VALUATOR_PLUGIN_URL . 'assets/css/domain-valuator.css',
            array(),
            DOMAIN_VALUATOR_VERSION
        );
    }
    
    /**
     * Domain Valuator shortcode callback
     */
    public function domain_valuator_shortcode($atts) {
        // Enqueue required scripts and styles
        wp_enqueue_script('chartjs');
        wp_enqueue_script('domain-valuator-js');
        wp_enqueue_style('domain-valuator-css');
        
        // Default attributes
        $atts = shortcode_atts(
            array(
                'title' => 'Domain Valuator',
                'show_analytics' => 'true',
            ),
            $atts,
            'domain_valuator'
        );
        
        // Start output buffering
        ob_start();
        
        // Include template
        include DOMAIN_VALUATOR_PLUGIN_DIR . 'templates/domain-valuator.php';
        
        // Return the buffered content
        return ob_get_clean();
    }
}

// Initialize the plugin
$domain_valuator = new Domain_Valuator(); 