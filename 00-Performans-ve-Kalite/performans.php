<?php
/**
 * Performans ve Kalite Ayarları (Kasmayı Engelleme)
 */

// 1. Gereksiz Emoji Yüklemelerini Kaldır
add_action('init', function() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
});

// 2. Query Strings Kaldır (Cache Dostu)
add_filter('script_loader_src', 'totbagss_remove_query_strings', 15, 1);
add_filter('style_loader_src', 'totbagss_remove_query_strings', 15, 1);
function totbagss_remove_query_strings($src) {
    if (strpos($src, 'ver=')) $src = remove_query_arg('ver', $src);
    return $src;
}

// 3. XML-RPC Kapat (Güvenlik ve Hız)
add_filter('xmlrpc_enabled', '__return_false');

// 4. WooCommerce Bloat Kaldır (Sadece gerekli sayfalarda yükle)
add_action('wp_enqueue_scripts', function() {
    if (function_exists('is_woocommerce')) {
        if (!is_woocommerce() && !is_cart() && !is_checkout()) {
            wp_dequeue_style('woocommerce-layout');
            wp_dequeue_style('woocommerce-general');
            wp_dequeue_style('woocommerce-smallscreen');
            wp_dequeue_script('wc-add-to-cart');
            wp_dequeue_script('wc-cart-fragments');
            wp_dequeue_script('woocommerce');
        }
    }
}, 99);

// 5. Heartbeat Kontrolü (CPU Tasarrufu)
add_action('init', function() {
    wp_deregister_script('heartbeat');
});

// 6. Script Defer (jQuery hariç tüm scriptleri ertele)
add_filter('script_loader_tag', function($tag, $handle) {
    if (is_admin()) return $tag;
    if ('jquery' === $handle) return $tag;
    return str_replace(' src', ' defer src', $tag);
}, 10, 2);

// 7. Google Fonts Preconnect
add_action('wp_head', function() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . PHP_EOL;
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . PHP_EOL;
}, 5);

// 8. WP Embeds Kaldır
add_action('wp_footer', function() {
    wp_deregister_script('wp-embed');
});

// 9. Gutenberg CSS Sadece Gerekli Sayfalarda (Basit yaklaşım)
// Not: Bazı temalarda sorun çıkarabilir, Astra genellikle iyi yönetir.

// 10. Post Revisions Limit (Sadece 3 revizyon sakla)
if (!defined('WP_POST_REVISIONS')) define('WP_POST_REVISIONS', 3);

// 11. Self Pingbacks Kapat
add_action('pre_ping', function(&$links) {
    $home = get_option('home');
    foreach ($links as $l => $link) {
        if (0 === strpos($link, $home)) unset($links[$l]);
    }
});

// 12. Block Library CSS Temizliği (Gutenberg kullanmıyorsak veya minimal kullanıyorsak)
add_action('wp_enqueue_scripts', function() {
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('wc-block-style'); // WooCommerce block styles
}, 100);

// 13. JQuery Migrate Kaldır (Modern temalarda genellikle gerekmez)
add_action('wp_default_scripts', function($scripts) {
    if (!is_admin() && isset($scripts->registered['jquery'])) {
        $script = $scripts->registered['jquery'];
        if ($script->deps) {
            $script->deps = array_diff($script->deps, array('jquery-migrate'));
        }
    }
});

// 14. WooCommerce Cart Fragments Optimizasyonu
// Sadece sepet veya ürün sayfalarında çalıştırılması hızı artırır.
add_action('wp_enqueue_scripts', function() {
    if (function_exists('is_woocommerce')) {
        if (!is_cart() && !is_checkout() && !is_product() && !is_shop()) {
            wp_dequeue_script('wc-cart-fragments');
        }
    }
}, 1000);
