<?php
/**
 * Domain Valuator Theme Functions
 *
 * @package Domain_Valuator_Theme
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Define constants
 */
define('DOMAIN_VALUATOR_THEME_VERSION', '1.0.0');
define('DOMAIN_VALUATOR_THEME_DIR', get_template_directory());
define('DOMAIN_VALUATOR_THEME_URI', get_template_directory_uri());

/**
 * Theme setup
 */
function domain_valuator_theme_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('automatic-feed-links');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));
    add_theme_support('custom-logo', array(
        'height'      => 250,
        'width'       => 250,
        'flex-width'  => true,
        'flex-height' => true,
    ));
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'domain-valuator'),
        'footer'  => __('Footer Menu', 'domain-valuator'),
    ));
}
add_action('after_setup_theme', 'domain_valuator_theme_setup');

/**
 * Enqueue scripts and styles
 */
function domain_valuator_theme_scripts() {
    // Main stylesheet
    wp_enqueue_style(
        'domain-valuator-style',
        get_stylesheet_uri(),
        array(),
        DOMAIN_VALUATOR_THEME_VERSION
    );
    
    // Theme JavaScript
    wp_enqueue_script(
        'domain-valuator-theme-js',
        DOMAIN_VALUATOR_THEME_URI . '/assets/js/theme.js',
        array('jquery'),
        DOMAIN_VALUATOR_THEME_VERSION,
        true
    );
    
    // Conditionally load comments script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'domain_valuator_theme_scripts');

/**
 * Register widget areas
 */
function domain_valuator_theme_widgets_init() {
    register_sidebar(array(
        'name'          => __('Sidebar', 'domain-valuator'),
        'id'            => 'sidebar-1',
        'description'   => __('Add widgets here to appear in your sidebar.', 'domain-valuator'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
    
    register_sidebar(array(
        'name'          => __('Footer Widget Area', 'domain-valuator'),
        'id'            => 'footer-1',
        'description'   => __('Add widgets here to appear in your footer.', 'domain-valuator'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'domain_valuator_theme_widgets_init');

/**
 * Custom template tags for this theme.
 */
require DOMAIN_VALUATOR_THEME_DIR . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require DOMAIN_VALUATOR_THEME_DIR . '/inc/template-functions.php';

/**
 * Load compatibility file for Domain Valuator plugin
 */
if (defined('DOMAIN_VALUATOR_VERSION')) {
    require DOMAIN_VALUATOR_THEME_DIR . '/inc/plugin-compatibility.php';
}

/**
 * Displays the post thumbnail if it exists
 */
if (!function_exists('domain_valuator_post_thumbnail')) {
    function domain_valuator_post_thumbnail() {
        if (post_password_required() || is_attachment() || !has_post_thumbnail()) {
            return;
        }

        if (is_singular()) :
            ?>
            <div class="post-thumbnail">
                <?php the_post_thumbnail(); ?>
            </div><!-- .post-thumbnail -->
        <?php else : ?>
            <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php
                the_post_thumbnail(
                    'post-thumbnail',
                    array(
                        'alt' => the_title_attribute(
                            array(
                                'echo' => false,
                            )
                        ),
                    )
                );
                ?>
            </a>
        <?php
        endif; // End is_singular().
    }
} 