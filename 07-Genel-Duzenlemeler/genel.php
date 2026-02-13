<?php
/**
 * Zara Style - Genel Fonksiyonlar
 */

if (!defined('ABSPATH')) exit;

add_filter('upload_mimes', function($mimes) { $mimes['webp'] = 'image/webp'; return $mimes; });
add_filter('wp_lazy_loading_enabled', '__return_true');

// Google Fonts için preconnect
add_action('wp_head', function() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
}, 1);
