<?php
/**
 * Domain Valuator Shortcode
 * 
 * Handles the shortcode implementation for embedding the domain valuator
 * into posts and pages.
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Domain_Valuator_Shortcode {

    /**
     * Constructor
     */
    public function __construct() {
        add_shortcode('domain_valuator', array($this, 'render_shortcode'));
    }

    /**
     * Render the shortcode
     * 
     * @param array $atts Shortcode attributes
     * @return string The shortcode output
     */
    public static function render_shortcode($atts) {
        // Extract attributes
        $atts = shortcode_atts(array(
            'theme' => 'light',
            'results' => get_option('domain_valuator_max_results', '10'),
            'timeframe' => get_option('domain_valuator_default_timeframe', '3'),
            'button_text' => 'Subscribe Now'
        ), $atts, 'domain_valuator');
        
        // Check if user is subscribed
        $is_subscribed = Domain_Valuator_Helpers::is_user_subscribed();
        $subscription_status = $is_subscribed ? 'subscribed' : 'not-subscribed';
        
        // Get pricing details
        $price = get_option('domain_valuator_pricing_amount', '1.99');
        $period = get_option('domain_valuator_pricing_period', 'week');
        
        // Start output buffering
        ob_start();
        
        // Main container
        ?>
        <div class="domain-valuator-container theme-<?php echo esc_attr($atts['theme']); ?>" 
             data-max-results="<?php echo esc_attr($atts['results']); ?>" 
             data-timeframe="<?php echo esc_attr($atts['timeframe']); ?>"
             data-subscription-status="<?php echo esc_attr($subscription_status); ?>">
            
            <!-- Header -->
            <div class="domain-valuator-header">
                <h2 class="domain-valuator-title">Domain Valuator</h2>
                <p class="domain-valuator-subtitle">Find profitable domain investments with our AI-powered domain valuation tool</p>
            </div>
            
            <!-- Search form -->
            <div class="domain-valuator-search-form">
                <form id="domain-valuator-search">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="domain-budget">Your Budget ($)</label>
                            <input type="number" id="domain-budget" name="budget" placeholder="Enter your budget" min="1" max="10000" required>
                        </div>
                        <div class="form-group">
                            <label for="domain-timeframe">Investment Timeframe (months)</label>
                            <select id="domain-timeframe" name="timeframe">
                                <?php for ($i = 2; $i <= 12; $i++) : ?>
                                    <option value="<?php echo esc_attr($i); ?>" <?php selected($atts['timeframe'], $i); ?>><?php echo esc_html($i); ?> months</option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row search-submit">
                        <?php if ($is_subscribed) : ?>
                            <button type="submit" class="domain-valuator-search-button">Find Profitable Domains</button>
                        <?php else : ?>
                            <div class="subscription-notice">
                                <p class="subscription-text">
                                    To access our AI-powered domain valuation tool, subscribe for just $<?php echo esc_html($price); ?>/<?php echo esc_html($period); ?>
                                </p>
                                <button type="button" class="domain-valuator-subscribe-button" id="subscribe-button">
                                    <?php echo esc_html($atts['button_text']); ?>
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
            
            <!-- Results container (only visible for subscribed users) -->
            <?php if ($is_subscribed) : ?>
                <div class="domain-valuator-results" id="domain-valuator-results">
                    <div class="domain-valuator-loading" style="display: none;">
                        <div class="spinner"></div>
                        <p>Analyzing domain investments...</p>
                    </div>
                    <div class="results-container"></div>
                </div>
            <?php endif; ?>
            
            <!-- Subscription checkout container (for non-subscribed users) -->
            <?php if (!$is_subscribed) : ?>
                <div id="subscription-container" style="display: none;">
                    <div class="subscription-overlay"></div>
                    <div class="subscription-modal">
                        <div class="modal-header">
                            <h3>Subscribe to Domain Valuator</h3>
                            <button type="button" class="close-modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <p class="pricing-info">$<?php echo esc_html($price); ?>/<?php echo esc_html($period); ?></p>
                            
                            <?php if (is_user_logged_in()) : ?>
                                <!-- Payment form for logged-in users -->
                                <div id="payment-form-container">
                                    <form id="payment-form">
                                        <div id="card-element">
                                            <!-- Stripe Card Element will be inserted here -->
                                        </div>
                                        <div id="card-errors" role="alert"></div>
                                        <button type="submit" id="submit-payment">
                                            <span id="button-text">Subscribe Now</span>
                                            <span id="spinner" style="display: none;">
                                                <div class="spinner-border spinner-border-sm" role="status"></div>
                                            </span>
                                        </button>
                                    </form>
                                </div>
                            <?php else : ?>
                                <!-- Login/Register notice for guests -->
                                <div class="login-notice">
                                    <p>Please log in or create an account to subscribe</p>
                                    <a href="<?php echo esc_url(wp_login_url(get_permalink())); ?>" class="login-button">Log In</a>
                                    <a href="<?php echo esc_url(wp_registration_url()); ?>" class="register-button">Register</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Powered by notice -->
            <div class="domain-valuator-footer">
                <p>Powered by <a href="https://domainvaluator.com" target="_blank">Domain Valuator</a></p>
            </div>
        </div>
        <?php
        
        // Return the buffered output
        return ob_get_clean();
    }
} 