<?php
/**
 * Domain Valuator Widget
 * 
 * Creates a WordPress widget for the Domain Valuator
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Domain_Valuator_Widget extends WP_Widget {

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'domain_valuator_widget',
            __('Domain Valuator', 'domain-valuator'),
            array(
                'description' => __('Displays the Domain Valuator tool', 'domain-valuator'),
                'classname' => 'domain-valuator-widget',
            )
        );
    }

    /**
     * Widget frontend display
     * 
     * @param array $args Widget arguments
     * @param array $instance Widget instance
     */
    public function widget($args, $instance) {
        // Get widget options
        $title = apply_filters('widget_title', isset($instance['title']) ? $instance['title'] : '');
        $theme = isset($instance['theme']) ? $instance['theme'] : 'light';
        $results = isset($instance['results']) ? $instance['results'] : get_option('domain_valuator_max_results', '10');
        $timeframe = isset($instance['timeframe']) ? $instance['timeframe'] : get_option('domain_valuator_default_timeframe', '3');
        $button_text = isset($instance['button_text']) ? $instance['button_text'] : 'Subscribe Now';
        
        // Before widget
        echo $args['before_widget'];
        
        // Title
        if (!empty($title)) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        
        // Widget content
        echo Domain_Valuator_Shortcode::render_shortcode(array(
            'theme' => $theme,
            'results' => $results,
            'timeframe' => $timeframe,
            'button_text' => $button_text
        ));
        
        // After widget
        echo $args['after_widget'];
    }

    /**
     * Widget backend form
     * 
     * @param array $instance Widget instance
     * @return string|void
     */
    public function form($instance) {
        // Get saved values
        $title = isset($instance['title']) ? $instance['title'] : __('Domain Valuator', 'domain-valuator');
        $theme = isset($instance['theme']) ? $instance['theme'] : 'light';
        $results = isset($instance['results']) ? $instance['results'] : get_option('domain_valuator_max_results', '10');
        $timeframe = isset($instance['timeframe']) ? $instance['timeframe'] : get_option('domain_valuator_default_timeframe', '3');
        $button_text = isset($instance['button_text']) ? $instance['button_text'] : 'Subscribe Now';
        
        // Widget title
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'domain-valuator'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        
        <!-- Theme option -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('theme')); ?>"><?php esc_html_e('Theme:', 'domain-valuator'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('theme')); ?>" name="<?php echo esc_attr($this->get_field_name('theme')); ?>">
                <option value="light" <?php selected($theme, 'light'); ?>><?php esc_html_e('Light', 'domain-valuator'); ?></option>
                <option value="dark" <?php selected($theme, 'dark'); ?>><?php esc_html_e('Dark', 'domain-valuator'); ?></option>
            </select>
        </p>
        
        <!-- Results option -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('results')); ?>"><?php esc_html_e('Number of Results:', 'domain-valuator'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('results')); ?>" name="<?php echo esc_attr($this->get_field_name('results')); ?>" type="number" min="1" max="50" value="<?php echo esc_attr($results); ?>" />
        </p>
        
        <!-- Timeframe option -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('timeframe')); ?>"><?php esc_html_e('Default Timeframe (months):', 'domain-valuator'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('timeframe')); ?>" name="<?php echo esc_attr($this->get_field_name('timeframe')); ?>">
                <?php for ($i = 2; $i <= 12; $i++) : ?>
                    <option value="<?php echo esc_attr($i); ?>" <?php selected($timeframe, $i); ?>><?php echo esc_html($i); ?></option>
                <?php endfor; ?>
            </select>
        </p>
        
        <!-- Button text option -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('button_text')); ?>"><?php esc_html_e('Subscribe Button Text:', 'domain-valuator'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('button_text')); ?>" name="<?php echo esc_attr($this->get_field_name('button_text')); ?>" type="text" value="<?php echo esc_attr($button_text); ?>" />
        </p>
        <?php
    }

    /**
     * Update widget instance
     * 
     * @param array $new_instance New settings
     * @param array $old_instance Old settings
     * @return array Settings to save
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        
        // Save widget options
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['theme'] = sanitize_text_field($new_instance['theme']);
        $instance['results'] = absint($new_instance['results']);
        $instance['timeframe'] = absint($new_instance['timeframe']);
        $instance['button_text'] = sanitize_text_field($new_instance['button_text']);
        
        return $instance;
    }
} 