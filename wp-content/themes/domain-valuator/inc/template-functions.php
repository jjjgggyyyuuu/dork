<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Domain_Valuator_Theme
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function domain_valuator_body_classes($classes) {
    // Adds a class of hfeed to non-singular pages.
    if (!is_singular()) {
        $classes[] = 'hfeed';
    }

    // Adds a class if no sidebar is present.
    if (!is_active_sidebar('sidebar-1')) {
        $classes[] = 'no-sidebar';
    }
    
    // Add a class for the page template
    if (is_page_template()) {
        $template = get_page_template_slug();
        if ($template) {
            $classes[] = 'template-' . sanitize_html_class(str_replace('.php', '', $template));
        }
    }

    return $classes;
}
add_filter('body_class', 'domain_valuator_body_classes');

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function domain_valuator_pingback_header() {
    if (is_singular() && pings_open()) {
        printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
    }
}
add_action('wp_head', 'domain_valuator_pingback_header');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function domain_valuator_content_width() {
    $GLOBALS['content_width'] = apply_filters('domain_valuator_content_width', 1200);
}
add_action('after_setup_theme', 'domain_valuator_content_width', 0);

/**
 * Wrap embedded media as suggested by Readability
 *
 * @link https://gist.github.com/965956
 *
 * @param string $cache Cached embedded HTML.
 * @param string $url URL for embedded media.
 * @param array  $attr Attributes of embedded media.
 *
 * @return string Modified Cached embedded HTML.
 */
function domain_valuator_embed_wrap($cache, $url, $attr = array()) {
    return '<div class="entry-content-asset">' . $cache . '</div>';
}
add_filter('embed_oembed_html', 'domain_valuator_embed_wrap', 10, 3); 