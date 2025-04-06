<?php
/**
 * Domain Valuator template file
 *
 * @package Domain_Valuator
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Extract shortcode attributes
$title = $atts['title'];
$show_analytics = filter_var($atts['show_analytics'], FILTER_VALIDATE_BOOLEAN);
?>

<div class="domain-valuator-container">
    <h2 class="domain-valuator-title"><?php echo esc_html($title); ?></h2>
    
    <div class="domain-valuator-form-container">
        <form id="domain-valuator-form" class="domain-valuator-form">
            <div class="form-group">
                <label for="domain-name"><?php esc_html_e('Domain Name', 'domain-valuator'); ?></label>
                <input type="text" id="domain-name" name="domain" class="form-control" placeholder="example.com" required>
            </div>
            
            <div class="form-group">
                <label for="budget"><?php esc_html_e('Budget', 'domain-valuator'); ?></label>
                <div class="range-control">
                    <input type="range" id="budget" name="budget" min="100" max="10000" step="100" value="1000" class="form-range">
                    <span id="budget-display" class="range-display">$1000</span>
                </div>
            </div>
            
            <div class="form-group">
                <label for="timeframe"><?php esc_html_e('Investment Timeframe (months)', 'domain-valuator'); ?></label>
                <div class="range-control">
                    <input type="range" id="timeframe" name="timeframe" min="1" max="24" step="1" value="6" class="form-range">
                    <span id="timeframe-display" class="range-display">6 months</span>
                </div>
            </div>
            
            <button type="submit" class="submit-button"><?php esc_html_e('Analyze Domain', 'domain-valuator'); ?></button>
        </form>
    </div>
    
    <div class="domain-valuator-results">
        <?php if (!is_user_logged_in()): ?>
        <div class="domain-valuator-subscription">
            <p><?php esc_html_e('Sign up to get full access to domain investment insights for just $1.99/week', 'domain-valuator'); ?></p>
            <button class="subscribe-button"><?php esc_html_e('Subscribe Now', 'domain-valuator'); ?></button>
        </div>
        <?php endif; ?>
    </div>
</div> 