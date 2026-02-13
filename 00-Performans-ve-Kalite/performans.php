<?php
/**
 * TotBagss Performans ve Kalite Optimizasyon Sistemi
 * Kasmayı engellemek ve site hızını maksimize etmek için tasarlandı.
 */

if (!defined('ABSPATH')) exit;

// 1. Emoji ve Gereksiz WP Scriptlerini Kaldır
add_action('init', function() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

    // RSD, WLW, Shortlink gibi gereksiz meta tagleri temizle
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wp_shortlink_wp_head');
});

// 2. Query Strings (Sürüm Bilgileri) Kaldır - Önbellek Dostu
add_filter('script_loader_src', 'totbagss_cleanup_query_string', 15, 1);
add_filter('style_loader_src', 'totbagss_cleanup_query_string', 15, 1);
function totbagss_cleanup_query_string($src) {
    if (strpos($src, 'ver=')) $src = remove_query_arg('ver', $src);
    return $src;
}

// 3. WooCommerce Bloat Temizliği
add_action('wp_enqueue_scripts', function() {
    if (function_exists('is_woocommerce')) {
        // Sadece sepet, ödeme ve hesap sayfalarında gerekli olanları tut, diğerlerinde temizle
        if (!is_woocommerce() && !is_cart() && !is_checkout() && !is_account_page()) {
            wp_dequeue_style('woocommerce-layout');
            wp_dequeue_style('woocommerce-general');
            wp_dequeue_style('woocommerce-smallscreen');
            wp_dequeue_script('wc-add-to-cart');
            wp_dequeue_script('wc-cart-fragments');
            wp_dequeue_script('woocommerce');
            wp_dequeue_script('wc-cart');
            wp_dequeue_script('wc-chosen');
        }
    }
}, 999);

// 4. Kalp Atışı (Heartbeat) Kontrolü - CPU Tasarrufu
add_action('init', function() {
    wp_deregister_script('heartbeat');
}, 1);

// 5. Veritabanı Revizyon Kontrolü
if (!defined('WP_POST_REVISIONS')) define('WP_POST_REVISIONS', 2);

// 6. Scriptleri Ertele (Defer) - LCP ve TTI İyileştirmesi
add_filter('script_loader_tag', function($tag, $handle) {
    if (is_admin()) return $tag;
    if (strpos($handle, 'jquery') !== false && !strpos($handle, 'migrate')) return $tag;
    return str_replace(' src', ' defer src', $tag);
}, 10, 2);

// 7. XML-RPC Kapat (Güvenlik ve Hız)
add_filter('xmlrpc_enabled', '__return_false');

// 8. WP Embed Scriptini Kaldır
add_action('wp_footer', function() {
    wp_deregister_script('wp-embed');
});

// 9. Gutenberg Blok CSS'lerini Sadece Gerekli Sayfalarda Yükle
add_action('wp_enqueue_scripts', function() {
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('wc-block-style');
}, 100);

// 10. JQuery Migrate Kaldır
add_action('wp_default_scripts', function($scripts) {
    if (!is_admin() && isset($scripts->registered['jquery'])) {
        $script = $scripts->registered['jquery'];
        if ($script->deps) {
            $script->deps = array_diff($script->deps, array('jquery-migrate'));
        }
    }
});
