<?php
/**
 * Compatibility functions for the Domain Valuator plugin
 *
 * @package Domain_Valuator_Theme
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Add a specific template for domain valuator pages
 *
 * @param string $template The current template path
 * @return string The modified template path
 */
function domain_valuator_template_include($template) {
    // Check if we're on a page that has the domain valuator shortcode
    global $post;
    
    if (is_page() && has_shortcode($post->post_content, 'domain_valuator')) {
        $new_template = locate_template(array('page-domain-valuator.php'));
        if (!empty($new_template)) {
            return $new_template;
        }
    }
    
    return $template;
}
add_filter('template_include', 'domain_valuator_template_include');

/**
 * Add body class for pages with the domain valuator shortcode
 *
 * @param array $classes Current body classes
 * @return array Modified body classes
 */
function domain_valuator_body_class($classes) {
    global $post;
    
    if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'domain_valuator')) {
        $classes[] = 'has-domain-valuator';
    }
    
    return $classes;
}
add_filter('body_class', 'domain_valuator_body_class');

/**
 * Add custom styles for the Domain Valuator plugin
 */
function domain_valuator_plugin_styles() {
    // Only load if the plugin is active
    if (defined('DOMAIN_VALUATOR_VERSION')) {
        // Add additional styles to customize the plugin appearance
        wp_add_inline_style('domain-valuator-css', '
            /* Custom theme styles for Domain Valuator */
            .domain-valuator-container {
                background-color: #f9f9f9;
                border-radius: 8px;
                padding: 20px;
                margin-bottom: 30px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            }
            
            .domain-valuator-form {
                margin-bottom: 20px;
            }
            
            .domain-valuator-results {
                border-top: 1px solid #eee;
                padding-top: 20px;
            }
            
            .domain-valuator-subscription {
                background-color: #f5f5f5;
                border: 1px solid #e5e5e5;
                padding: 15px;
                margin-top: 20px;
                text-align: center;
                border-radius: 4px;
            }
            
            .domain-valuator-chart {
                margin: 30px 0;
            }
        ');
    }
}
add_action('wp_enqueue_scripts', 'domain_valuator_plugin_styles', 20);

/**
 * Create a custom page template for the Domain Valuator
 */
function domain_valuator_create_page_template() {
    // Only create if the template doesn't exist and the plugin is active
    $template_path = get_template_directory() . '/page-domain-valuator.php';
    
    if (!file_exists($template_path) && defined('DOMAIN_VALUATOR_VERSION')) {
        $template_content = <<<EOT
<?php
/**
 * Template Name: Domain Valuator
 * 
 * Custom template for pages using the Domain Valuator shortcode
 *
 * @package Domain_Valuator_Theme
 */

get_header();
?>

<main id="primary" class="site-main domain-valuator-page">
    <div class="container">
        <header class="entry-header">
            <h1 class="entry-title"><?php the_title(); ?></h1>
        </header><!-- .entry-header -->

        <div class="entry-content">
            <?php the_content(); ?>
        </div><!-- .entry-content -->
    </div><!-- .container -->
</main><!-- #primary -->

<?php
get_footer();
EOT;
        
        // Write the template file
        file_put_contents($template_path, $template_content);
    }
}
add_action('after_setup_theme', 'domain_valuator_create_page_template'); 