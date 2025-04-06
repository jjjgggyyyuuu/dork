<?php
/**
 * WordPress Theme Tag Functions
 *
 * This is a placeholder file to stub common WordPress functions
 * used in templates to prevent PHP errors.
 *
 * @package WordPress
 */

if (!function_exists('get_header')) {
    function get_header($name = null) {
        $template = ABSPATH . 'wp-content/themes/domain-valuator/header.php';
        if (file_exists($template)) {
            include $template;
        }
    }
}

if (!function_exists('get_footer')) {
    function get_footer($name = null) {
        $template = ABSPATH . 'wp-content/themes/domain-valuator/footer.php';
        if (file_exists($template)) {
            include $template;
        }
    }
}

if (!function_exists('get_sidebar')) {
    function get_sidebar($name = null) {
        $template = ABSPATH . 'wp-content/themes/domain-valuator/sidebar.php';
        if (file_exists($template)) {
            include $template;
        }
    }
}

if (!function_exists('the_content')) {
    function the_content($more_link_text = null, $strip_teaser = false) {
        echo '<p>This is placeholder content for development purposes.</p>';
    }
}

if (!function_exists('the_title')) {
    function the_title($before = '', $after = '', $echo = true) {
        $title = 'Sample Page Title';
        if ($echo) {
            echo $before . $title . $after;
        } else {
            return $before . $title . $after;
        }
    }
}

if (!function_exists('the_permalink')) {
    function the_permalink() {
        echo '/sample-page/';
    }
}

if (!function_exists('the_ID')) {
    function the_ID() {
        echo '1';
    }
}

if (!function_exists('get_the_ID')) {
    function get_the_ID() {
        return 1;
    }
}

if (!function_exists('post_class')) {
    function post_class($class = '') {
        echo 'class="post hentry ' . esc_attr($class) . '"';
    }
}

if (!function_exists('comments_template')) {
    function comments_template($file = '/comments.php', $separate_comments = false) {
        echo '<!-- Comments template placeholder -->';
    }
}

if (!function_exists('comments_open')) {
    function comments_open($post_id = null) {
        return false;
    }
}

if (!function_exists('get_comments_number')) {
    function get_comments_number($post_id = 0) {
        return 0;
    }
}

if (!function_exists('get_post_type')) {
    function get_post_type($post = null) {
        return 'page';
    }
}

if (!function_exists('single_post_title')) {
    function single_post_title($prefix = '', $display = true) {
        $title = 'Sample Post Title';
        if ($display) {
            echo $prefix . $title;
        } else {
            return $prefix . $title;
        }
    }
}

if (!function_exists('the_posts_navigation')) {
    function the_posts_navigation($args = array()) {
        echo '<!-- Posts navigation placeholder -->';
    }
}

if (!function_exists('get_the_title')) {
    function get_the_title($post = 0) {
        return 'Sample Page Title';
    }
}

if (!function_exists('the_title_attribute')) {
    function the_title_attribute($args = array()) {
        return 'Sample Page Title';
    }
}

if (!function_exists('is_page_template')) {
    function is_page_template($template = '') {
        return false;
    }
}

if (!function_exists('get_page_template_slug')) {
    function get_page_template_slug($post = null) {
        return '';
    }
}

if (!function_exists('is_active_sidebar')) {
    function is_active_sidebar($index) {
        return false;
    }
}

if (!function_exists('dynamic_sidebar')) {
    function dynamic_sidebar($index) {
        echo '<!-- Sidebar widget area placeholder -->';
        return true;
    }
}

if (!function_exists('the_excerpt')) {
    function the_excerpt() {
        echo '<p>This is a sample excerpt for development purposes...</p>';
    }
}

// Domain Valuator theme specific functions
if (!function_exists('domain_valuator_posted_on')) {
    function domain_valuator_posted_on() {
        echo '<span class="posted-on">Posted on <a href="#">April 6, 2023</a></span>';
    }
}

if (!function_exists('domain_valuator_posted_by')) {
    function domain_valuator_posted_by() {
        echo '<span class="byline"> by <span class="author vcard"><a class="url fn n" href="#">Admin</a></span></span>';
    }
}

if (!function_exists('domain_valuator_entry_footer')) {
    function domain_valuator_entry_footer() {
        echo '<!-- Entry footer placeholder -->';
    }
}

if (!function_exists('domain_valuator_post_thumbnail')) {
    function domain_valuator_post_thumbnail() {
        echo '<!-- Post thumbnail placeholder -->';
    }
}

if (!function_exists('wp_link_pages')) {
    function wp_link_pages($args = '') {
        // Do nothing
    }
}

if (!function_exists('edit_post_link')) {
    function edit_post_link($text = null, $before = '', $after = '', $id = 0, $class = 'post-edit-link') {
        // Do nothing
    }
}

if (!function_exists('get_search_form')) {
    function get_search_form($args = array()) {
        echo '<form role="search" method="get" class="search-form" action="/">';
        echo '<label>';
        echo '<span class="screen-reader-text">Search for:</span>';
        echo '<input type="search" class="search-field" placeholder="Search …" value="" name="s">';
        echo '</label>';
        echo '<input type="submit" class="search-submit" value="Search">';
        echo '</form>';
    }
}

if (!function_exists('pings_open')) {
    function pings_open($post_id = null) {
        return false;
    }
} 