<?php
/**
 * Plugin Name: Launch AI Works SEO Meta
 * Description: Outputs meta description, keywords, Open Graph and Twitter/X card tags, and an optional SEO title, from the custom fields seo_title, seo_description, seo_keywords and seo_image. Falls back to the post title, excerpt, featured image, a default share image and the site icon. Adds output to <head> only; changes no layout, content or styles. Steps aside if a full SEO plugin is active.
 * Version: 1.1.0
 * Author: Launch AI Works
 */
if (!defined('ABSPATH')) { exit; }
function laiw_seo_has_seo_plugin() {
    return defined('WPSEO_VERSION') || class_exists('RankMath') || defined('SEOPRESS_VERSION') || defined('AIOSEO_VERSION');
}
add_filter('pre_get_document_title', function ($title) {
    if (laiw_seo_has_seo_plugin() || !is_singular()) { return $title; }
    $custom = get_post_meta(get_queried_object_id(), 'seo_title', true);
    return $custom ? $custom : $title;
}, 20);
add_action('wp_head', function () {
    if (is_admin() || is_feed() || !is_singular() || laiw_seo_has_seo_plugin()) { return; }
    $id = get_queried_object_id();
    if (!$id) { return; }
    $title = get_post_meta($id, 'seo_title', true);
    if (!$title) { $title = get_the_title($id); }
    $title = wp_strip_all_tags(html_entity_decode($title, ENT_QUOTES, 'UTF-8'));
    $desc = get_post_meta($id, 'seo_description', true);
    if (!$desc && has_excerpt($id)) { $desc = get_the_excerpt($id); }
    $desc = wp_strip_all_tags(html_entity_decode((string) $desc, ENT_QUOTES, 'UTF-8'));
    $kw = trim((string) get_post_meta($id, 'seo_keywords', true));
    $img = get_post_meta($id, 'seo_image', true);
    if (!$img) { $img = get_the_post_thumbnail_url($id, 'large'); }
    if (!$img) { $img = content_url('/uploads/laiw-share-default.png'); }
    $type = is_single() ? 'article' : 'website';
    $url = get_permalink($id);
    echo "\n<!-- Launch AI Works SEO Meta -->\n";
    if ($desc) { echo '<meta name="description" content="' . esc_attr($desc) . '" />' . "\n"; }
    if ($kw) { echo '<meta name="keywords" content="' . esc_attr($kw) . '" />' . "\n"; }
    echo '<meta property="og:type" content="' . esc_attr($type) . '" />' . "\n";
    echo '<meta property="og:locale" content="en_US" />' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '" />' . "\n";
    echo '<meta property="og:title" content="' . esc_attr($title) . '" />' . "\n";
    echo '<meta property="og:url" content="' . esc_url($url) . '" />' . "\n";
    if ($desc) { echo '<meta property="og:description" content="' . esc_attr($desc) . '" />' . "\n"; }
    if ($img) {
        echo '<meta property="og:image" content="' . esc_url($img) . '" />' . "\n";
        echo '<meta name="twitter:image" content="' . esc_url($img) . '" />' . "\n";
    }
    if ($type === 'article') {
        echo '<meta property="article:published_time" content="' . esc_attr(get_post_time('c', true, $id)) . '" />' . "\n";
        if ($kw) {
            foreach (array_slice(array_map('trim', explode(',', $kw)), 0, 6) as $t) {
                if ($t !== '') { echo '<meta property="article:tag" content="' . esc_attr($t) . '" />' . "\n"; }
            }
        }
    }
    echo '<meta name="twitter:card" content="' . ($img ? 'summary_large_image' : 'summary') . '" />' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr($title) . '" />' . "\n";
    if ($desc) { echo '<meta name="twitter:description" content="' . esc_attr($desc) . '" />' . "\n"; }
    echo "<!-- /Launch AI Works SEO Meta -->\n";
}, 5);
