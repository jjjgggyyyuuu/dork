<?php
/**
 * Domain Valuator Admin Settings
 * 
 * Handles the WordPress admin settings page for the Domain Valuator plugin.
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Domain_Valuator_Admin_Settings {

    /**
     * Constructor
     */
    public function __construct() {
        // Add admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
        // Register settings
        add_action('admin_init', array($this, 'register_settings'));
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_options_page(
            __('Domain Valuator Settings', 'domain-valuator'),
            __('Domain Valuator', 'domain-valuator'),
            'manage_options',
            'domain-valuator-settings',
            array($this, 'settings_page')
        );
    }

    /**
     * Register settings
     */
    public function register_settings() {
        // Register settings
        register_setting('domain_valuator_settings', 'domain_valuator_stripe_publishable_key');
        register_setting('domain_valuator_settings', 'domain_valuator_stripe_secret_key');
        register_setting('domain_valuator_settings', 'domain_valuator_stripe_price_id');
        register_setting('domain_valuator_settings', 'domain_valuator_stripe_webhook_secret');
        register_setting('domain_valuator_settings', 'domain_valuator_pricing_amount');
        register_setting('domain_valuator_settings', 'domain_valuator_pricing_period');
        register_setting('domain_valuator_settings', 'domain_valuator_max_results');
        register_setting('domain_valuator_settings', 'domain_valuator_default_timeframe');
        register_setting('domain_valuator_settings', 'domain_valuator_use_plugin_css');
        
        // Add settings sections
        add_settings_section(
            'domain_valuator_stripe_settings',
            __('Stripe Integration Settings', 'domain-valuator'),
            array($this, 'stripe_settings_section_callback'),
            'domain_valuator_settings'
        );
        
        add_settings_section(
            'domain_valuator_subscription_settings',
            __('Subscription Settings', 'domain-valuator'),
            array($this, 'subscription_settings_section_callback'),
            'domain_valuator_settings'
        );
        
        add_settings_section(
            'domain_valuator_display_settings',
            __('Display Settings', 'domain-valuator'),
            array($this, 'display_settings_section_callback'),
            'domain_valuator_settings'
        );
        
        // Add settings fields - Stripe
        add_settings_field(
            'domain_valuator_stripe_publishable_key',
            __('Stripe Publishable Key', 'domain-valuator'),
            array($this, 'stripe_publishable_key_callback'),
            'domain_valuator_settings',
            'domain_valuator_stripe_settings'
        );
        
        add_settings_field(
            'domain_valuator_stripe_secret_key',
            __('Stripe Secret Key', 'domain-valuator'),
            array($this, 'stripe_secret_key_callback'),
            'domain_valuator_settings',
            'domain_valuator_stripe_settings'
        );
        
        add_settings_field(
            'domain_valuator_stripe_price_id',
            __('Stripe Price ID', 'domain-valuator'),
            array($this, 'stripe_price_id_callback'),
            'domain_valuator_settings',
            'domain_valuator_stripe_settings'
        );
        
        add_settings_field(
            'domain_valuator_stripe_webhook_secret',
            __('Stripe Webhook Secret', 'domain-valuator'),
            array($this, 'stripe_webhook_secret_callback'),
            'domain_valuator_settings',
            'domain_valuator_stripe_settings'
        );
        
        // Add settings fields - Subscription
        add_settings_field(
            'domain_valuator_pricing_amount',
            __('Subscription Price', 'domain-valuator'),
            array($this, 'pricing_amount_callback'),
            'domain_valuator_settings',
            'domain_valuator_subscription_settings'
        );
        
        add_settings_field(
            'domain_valuator_pricing_period',
            __('Billing Period', 'domain-valuator'),
            array($this, 'pricing_period_callback'),
            'domain_valuator_settings',
            'domain_valuator_subscription_settings'
        );
        
        // Add settings fields - Display
        add_settings_field(
            'domain_valuator_max_results',
            __('Maximum Results', 'domain-valuator'),
            array($this, 'max_results_callback'),
            'domain_valuator_settings',
            'domain_valuator_display_settings'
        );
        
        add_settings_field(
            'domain_valuator_default_timeframe',
            __('Default Timeframe (months)', 'domain-valuator'),
            array($this, 'default_timeframe_callback'),
            'domain_valuator_settings',
            'domain_valuator_display_settings'
        );
        
        add_settings_field(
            'domain_valuator_use_plugin_css',
            __('Use Plugin CSS', 'domain-valuator'),
            array($this, 'use_plugin_css_callback'),
            'domain_valuator_settings',
            'domain_valuator_display_settings'
        );
    }

    /**
     * Settings page
     */
    public function settings_page() {
        ?>
        <div class="wrap">
            <h2><?php echo esc_html__('Domain Valuator Settings', 'domain-valuator'); ?></h2>
            
            <form method="post" action="options.php">
                <?php
                settings_fields('domain_valuator_settings');
                do_settings_sections('domain_valuator_settings');
                submit_button();
                ?>
            </form>
            
            <div class="domain-valuator-admin-info">
                <h3><?php echo esc_html__('How to Use Domain Valuator', 'domain-valuator'); ?></h3>
                <p><?php echo esc_html__('Use the shortcode [domain_valuator] to embed the domain valuator tool in any page or post.', 'domain-valuator'); ?></p>
                <p><?php echo esc_html__('You can customize the shortcode with these parameters:', 'domain-valuator'); ?></p>
                <ul>
                    <li><code>theme="light"</code> - <?php echo esc_html__('Set the theme (light or dark)', 'domain-valuator'); ?></li>
                    <li><code>results="10"</code> - <?php echo esc_html__('Number of results to show', 'domain-valuator'); ?></li>
                    <li><code>timeframe="3"</code> - <?php echo esc_html__('Default investment timeframe in months', 'domain-valuator'); ?></li>
                    <li><code>button_text="Subscribe Now"</code> - <?php echo esc_html__('Customize the subscription button text', 'domain-valuator'); ?></li>
                </ul>
                
                <h3><?php echo esc_html__('Stripe Integration', 'domain-valuator'); ?></h3>
                <p><?php echo esc_html__('To set up Stripe integration:', 'domain-valuator'); ?></p>
                <ol>
                    <li><?php echo esc_html__('Create a Stripe account at stripe.com', 'domain-valuator'); ?></li>
                    <li><?php echo esc_html__('Create a subscription product with a price point', 'domain-valuator'); ?></li>
                    <li><?php echo esc_html__('Get your API keys from the Stripe Dashboard', 'domain-valuator'); ?></li>
                    <li><?php echo esc_html__('Set up a webhook pointing to:', 'domain-valuator'); ?> <code><?php echo esc_url(rest_url('domain-valuator/v1/webhook')); ?></code></li>
                </ol>
                
                <h3><?php echo esc_html__('Need Help?', 'domain-valuator'); ?></h3>
                <p><?php echo esc_html__('For support, visit:', 'domain-valuator'); ?> <a href="https://domainvaluator.com/support" target="_blank">domainvaluator.com/support</a></p>
            </div>
        </div>
        <?php
    }

    /**
     * Stripe settings section callback
     */
    public function stripe_settings_section_callback() {
        echo '<p>' . esc_html__('Configure your Stripe integration for processing subscription payments.', 'domain-valuator') . '</p>';
    }

    /**
     * Subscription settings section callback
     */
    public function subscription_settings_section_callback() {
        echo '<p>' . esc_html__('Configure subscription pricing and billing settings.', 'domain-valuator') . '</p>';
    }

    /**
     * Display settings section callback
     */
    public function display_settings_section_callback() {
        echo '<p>' . esc_html__('Configure how the domain valuator tool appears on your site.', 'domain-valuator') . '</p>';
    }

    /**
     * Stripe publishable key callback
     */
    public function stripe_publishable_key_callback() {
        $value = get_option('domain_valuator_stripe_publishable_key');
        echo '<input type="text" class="regular-text" name="domain_valuator_stripe_publishable_key" value="' . esc_attr($value) . '" />';
        echo '<p class="description">' . esc_html__('Your Stripe publishable key (starts with pk_).', 'domain-valuator') . '</p>';
    }

    /**
     * Stripe secret key callback
     */
    public function stripe_secret_key_callback() {
        $value = get_option('domain_valuator_stripe_secret_key');
        echo '<input type="password" class="regular-text" name="domain_valuator_stripe_secret_key" value="' . esc_attr($value) . '" />';
        echo '<p class="description">' . esc_html__('Your Stripe secret key (starts with sk_).', 'domain-valuator') . '</p>';
    }

    /**
     * Stripe price ID callback
     */
    public function stripe_price_id_callback() {
        $value = get_option('domain_valuator_stripe_price_id');
        echo '<input type="text" class="regular-text" name="domain_valuator_stripe_price_id" value="' . esc_attr($value) . '" />';
        echo '<p class="description">' . esc_html__('Your Stripe subscription price ID (starts with price_).', 'domain-valuator') . '</p>';
    }

    /**
     * Stripe webhook secret callback
     */
    public function stripe_webhook_secret_callback() {
        $value = get_option('domain_valuator_stripe_webhook_secret');
        echo '<input type="password" class="regular-text" name="domain_valuator_stripe_webhook_secret" value="' . esc_attr($value) . '" />';
        echo '<p class="description">' . esc_html__('Your Stripe webhook signing secret (starts with whsec_).', 'domain-valuator') . '</p>';
        echo '<p class="description">' . esc_html__('Webhook endpoint:', 'domain-valuator') . ' <code>' . esc_url(rest_url('domain-valuator/v1/webhook')) . '</code></p>';
    }

    /**
     * Pricing amount callback
     */
    public function pricing_amount_callback() {
        $value = get_option('domain_valuator_pricing_amount', '1.99');
        echo '<input type="text" name="domain_valuator_pricing_amount" value="' . esc_attr($value) . '" />';
        echo '<p class="description">' . esc_html__('Subscription price amount (e.g. 1.99).', 'domain-valuator') . '</p>';
    }

    /**
     * Pricing period callback
     */
    public function pricing_period_callback() {
        $value = get_option('domain_valuator_pricing_period', 'week');
        ?>
        <select name="domain_valuator_pricing_period">
            <option value="day" <?php selected($value, 'day'); ?>><?php esc_html_e('Daily', 'domain-valuator'); ?></option>
            <option value="week" <?php selected($value, 'week'); ?>><?php esc_html_e('Weekly', 'domain-valuator'); ?></option>
            <option value="month" <?php selected($value, 'month'); ?>><?php esc_html_e('Monthly', 'domain-valuator'); ?></option>
            <option value="year" <?php selected($value, 'year'); ?>><?php esc_html_e('Yearly', 'domain-valuator'); ?></option>
        </select>
        <p class="description"><?php esc_html_e('Billing period for subscriptions.', 'domain-valuator'); ?></p>
        <?php
    }

    /**
     * Max results callback
     */
    public function max_results_callback() {
        $value = get_option('domain_valuator_max_results', '10');
        echo '<input type="number" name="domain_valuator_max_results" min="1" max="50" value="' . esc_attr($value) . '" />';
        echo '<p class="description">' . esc_html__('Maximum number of domain results to display (1-50).', 'domain-valuator') . '</p>';
    }

    /**
     * Default timeframe callback
     */
    public function default_timeframe_callback() {
        $value = get_option('domain_valuator_default_timeframe', '3');
        ?>
        <select name="domain_valuator_default_timeframe">
            <?php for ($i = 2; $i <= 12; $i++) : ?>
                <option value="<?php echo esc_attr($i); ?>" <?php selected($value, (string)$i); ?>><?php echo esc_html($i); ?></option>
            <?php endfor; ?>
        </select>
        <p class="description"><?php esc_html_e('Default investment timeframe in months.', 'domain-valuator'); ?></p>
        <?php
    }

    /**
     * Use plugin CSS callback
     */
    public function use_plugin_css_callback() {
        $value = get_option('domain_valuator_use_plugin_css', 'yes');
        ?>
        <label>
            <input type="checkbox" name="domain_valuator_use_plugin_css" value="yes" <?php checked($value, 'yes'); ?> />
            <?php esc_html_e('Use the plugin\'s built-in CSS styles', 'domain-valuator'); ?>
        </label>
        <p class="description"><?php esc_html_e('Uncheck if you want to style the tool yourself with your theme\'s CSS.', 'domain-valuator'); ?></p>
        <?php
    }
} 