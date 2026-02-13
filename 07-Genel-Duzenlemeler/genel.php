<?php
/**
 * Modern Premium - Genel Fonksiyonlar
 */

if (!defined('ABSPATH')) exit;

add_filter('upload_mimes', function($mimes) { $mimes['webp'] = 'image/webp'; return $mimes; });
add_filter('wp_lazy_loading_enabled', '__return_true');

// Google Fonts için preconnect
add_action('wp_head', function() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
}, 1);

// Custom Verification Bar for Admin
add_action('wp_footer', function() {
    if (current_user_can('administrator')) {
        echo '<div style="position:fixed;bottom:0;left:0;width:100%;background:#c5a059;color:#fff;text-align:center;padding:5px;font-size:10px;z-index:99999;font-family:sans-serif;">MODERN PREMIUM V1.3 ACTIVE (No Cache)</div>';
    }
});
