<?php
/**
 * Görsel Optimizasyonu & Genel Filtreler
 */
add_filter('upload_mimes', function($mimes) { $mimes['webp'] = 'image/webp'; return $mimes; });
add_filter('jpeg_quality', function() { return 85; });
add_filter('big_image_size_threshold', function() { return 1920; });
add_filter('wp_lazy_loading_enabled', '__return_true');

// Genel JS Enqueue
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_script('tot-genel-js', get_stylesheet_directory_uri() . '/07-Genel-Duzenlemeler/genel.js', array(), '1.0.0', true);
});
