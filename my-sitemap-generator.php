<?php
/*
Plugin Name: My Sitemap Generator
Plugin URI: https://github.com/1599500358/My-Sitemap-Generator
Description: A simple WordPress plugin example with automatic sitemap.xml generation.
Version: 1.0.0
Author: Your Name
Author URI: https://ezmockup.ai
License: GPL2
*/

// Register rewrite rules and schedule event on plugin activation
function my_sitemap_generator_activate() {
    // Add rewrite rule for sitemap.xml
    add_rewrite_rule('^sitemap\\.xml$', 'index.php?my_sitemap_generator_sitemap=1', 'top');
    flush_rewrite_rules();
    // Schedule sitemap generation event
    if (!wp_next_scheduled('my_sitemap_generator_generate_sitemap_event')) {
        wp_schedule_event(time(), 'hourly', 'my_sitemap_generator_generate_sitemap_event');
    }
}
register_activation_hook(__FILE__, 'my_sitemap_generator_activate');

// Cleanup rewrite rules and scheduled event on plugin deactivation
function my_sitemap_generator_deactivate() {
    flush_rewrite_rules();
    wp_clear_scheduled_hook('my_sitemap_generator_generate_sitemap_event');
}
register_deactivation_hook(__FILE__, 'my_sitemap_generator_deactivate');

// Add custom query var for sitemap
add_filter('query_vars', function($vars) {
    $vars[] = 'my_sitemap_generator_sitemap';
    return $vars;
});
// Output sitemap.xml when requested
add_action('template_redirect', function() {
    if (get_query_var('my_sitemap_generator_sitemap')) {
        my_sitemap_generator_output_sitemap();
        exit;
    }
});
// Generate sitemap.xml content
function my_sitemap_generator_generate_sitemap() {
    $urls = [];
    // Homepage
    $urls[] = home_url('/');
    // Posts
    $posts = get_posts(['post_type'=>'post','post_status'=>'publish','numberposts'=>-1]);
    foreach ($posts as $post) {
        $urls[] = get_permalink($post);
    }
    // Pages
    $pages = get_posts(['post_type'=>'page','post_status'=>'publish','numberposts'=>-1]);
    foreach ($pages as $page) {
        $urls[] = get_permalink($page);
    }
    // Categories
    $categories = get_categories(['hide_empty'=>true]);
    foreach ($categories as $cat) {
        $urls[] = get_category_link($cat);
    }
    // Tags
    $tags = get_tags(['hide_empty'=>true]);
    foreach ($tags as $tag) {
        $urls[] = get_tag_link($tag);
    }
    // Build XML
    $xml = '<?xml version="1.0" encoding="UTF-8"?>\n';
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">\n';
    foreach ($urls as $url) {
        $xml .= "  <url><loc>" . esc_url($url) . "</loc></url>\n";
    }
    $xml .= '</urlset>';
    // Save to uploads directory
    $upload_dir = wp_upload_dir();
    file_put_contents($upload_dir['basedir'] . '/sitemap.xml', $xml);
    return $xml;
}
// Output sitemap.xml
function my_sitemap_generator_output_sitemap() {
    header('Content-Type: application/xml; charset=UTF-8');
    $upload_dir = wp_upload_dir();
    $file = $upload_dir['basedir'] . '/sitemap.xml';
    if (file_exists($file)) {
        readfile($file);
    } else {
        echo my_sitemap_generator_generate_sitemap();
    }
}
// Scheduled event: auto-generate sitemap
add_action('my_sitemap_generator_generate_sitemap_event', 'my_sitemap_generator_generate_sitemap');
// Generate sitemap once on plugin activation
register_activation_hook(__FILE__, 'my_sitemap_generator_generate_sitemap');

// Admin notice example
add_action('admin_notices', function() {
    echo '<div class="notice notice-success is-dismissible"><p>My Plugin is activated!</p></div>';
});
