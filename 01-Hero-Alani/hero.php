<?php
/**
 * Hero ve Header Alanı Düzenlemeleri
 */

if (!defined('ABSPATH')) exit;

// Sayfa üstü boşlukları temizle
add_action('wp_head', function() {
    ?>
    <style>
        .site-header { padding-bottom: 0 !important; margin-bottom: 0 !important; }
        .main-header-bar { padding-bottom: 0 !important; margin-bottom: 0 !important; }
        #content { padding-top: 0 !important; margin-top: 0 !important; }
        .ast-container { padding-top: 0 !important; }
    </style>
    <?php
}, 100);

// Hero Shortcode (Eğer manuel eklenmek istenirse)
add_shortcode('tot_hero_section', function($atts) {
    ob_start();
    ?>
    <div class="tot-premium-hero">
        <!-- Hero HTML buraya gelebilir -->
    </div>
    <?php
    return ob_get_clean();
});
