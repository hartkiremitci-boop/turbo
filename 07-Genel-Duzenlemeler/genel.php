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

add_action('wp_footer', function() {
    echo '<!-- TotBagss Premium System v1.1.0 Active -->';
}, 100);

// Geliştirici Doğrulama Çubuğu (Admin Giriş Yapmışsa Görünür)
add_action('wp_footer', function() {
    if (current_user_can('manage_options')) {
        echo '<div style="position:fixed; bottom:0; left:0; width:100%; background:#4A083D; color:#fff; text-align:center; padding:5px; font-size:10px; z-index:10000; font-family:sans-serif;">TOTBAGSS PREMIUM V1.2.0 AKTİF - MODÜLER YAPI ÇALIŞIYOR</div>';
    }
}, 999);
