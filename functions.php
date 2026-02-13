<?php
/**
 * TotBagss Premium Child Theme Functions
 * Modular Structure - Consolidated & Optimized
 */

if (!defined('ABSPATH')) exit;

// 1. Enqueue Child Theme Styles
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style('astra-child-theme-css', get_stylesheet_uri(), array('astra-theme-css'), '1.0.6');
});

// 2. Modular Components Definition
$components = array(
    '00-Performans-ve-Kalite/performans.php',
    '01-Hero-Alani/hero.php',
    '03-Urun-Karuseli/karusel.php',
    '04-Magaza-Basligi/magaza-basligi.php',
    '05-Urun-Kartlari/urunler.php',
    '06-Hesap-ve-Sepet/hesap.php',
    '07-Genel-Duzenlemeler/genel.php',
    '08-Siparis-Takibi/takip.php',
    '09-Mobil-Alt-Menu/menu.php',
    '10-Urun-Sayfasi-Iyilestirmeleri/urun.php',
    '11-Destek-Hatti/destek.php',
    '12-Iletisim-Sayfasi/iletisim.php',
    '13-Mobil-Hizli-Satin-Al/hizli-satin-al.php',
    '14-Brevo-Entegrasyonu/brevo.php',
    '15-Hizli-Kategoriler/kategoriler.php',
    '16-Mobil-Arama/arama.php'
);

// 3. Load PHP Logic
foreach ($components as $file) {
    $path = get_stylesheet_directory() . '/' . $file;
    if (file_exists($path)) {
        require_once $path;
    }
}

// 4. Load Component CSS
add_action('wp_enqueue_scripts', function() {
    $css_files = array(
        'tot-kalite'     => '00-Performans-ve-Kalite/kalite.css',
        'tot-hero'       => '01-Hero-Alani/hero.css',
        'tot-karusel'    => '03-Urun-Karuseli/karusel.css',
        'tot-magaza'     => '04-Magaza-Basligi/magaza-basligi.css',
        'tot-urunler'    => '05-Urun-Kartlari/urunler.css',
        'tot-hesap'      => '06-Hesap-ve-Sepet/hesap.css',
        'tot-genel'      => '07-Genel-Duzenlemeler/genel.css',
        'tot-takip'      => '08-Siparis-Takibi/siparis-takibi.css',
        'tot-mob-nav'    => '09-Mobil-Alt-Menu/menu.css',
        'tot-sticky'     => '10-Urun-Sayfasi-Iyilestirmeleri/urun.css',
        'tot-destek'     => '11-Destek-Hatti/destek.css',
        'tot-iletisim'   => '12-Iletisim-Sayfasi/iletisim.css',
        'tot-hizli-sat'  => '13-Mobil-Hizli-Satin-Al/hizli-satin-al.css',
        'tot-cats'       => '15-Hizli-Kategoriler/kategoriler.css',
        'tot-search'     => '16-Mobil-Arama/arama.css'
    );

    foreach ($css_files as $handle => $rel_path) {
        wp_enqueue_style($handle, get_stylesheet_directory_uri() . '/' . $rel_path, array(), '1.0.6');
    }
});
