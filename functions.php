<?php
/**
 * TotBagss Premium Child Theme Functions
 * Modular Structure
 */

if (!defined('ABSPATH')) exit;

// 1. Enqueue Child Theme Styles
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style('astra-child-theme-css', get_stylesheet_uri(), array('astra-theme-css'), '1.0.5');
});

// 2. Load Modular Components
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

foreach ($components as $file) {
    $path = get_stylesheet_directory() . '/' . $file;
    if (file_exists($path)) {
        require_once $path;
    }
}

// 3. Enqueue Component Styles
add_action('wp_enqueue_scripts', function() {
    $css_files = array(
        'kalite-css'   => '00-Performans-ve-Kalite/kalite.css',
        'hero-css'     => '01-Hero-Alani/hero.css',
        'karusel-css'  => '03-Urun-Karuseli/karusel.css',
        'magaza-css'   => '04-Magaza-Basligi/magaza-basligi.css',
        'urunler-css'  => '05-Urun-Kartlari/urunler.css',
        'hesap-css'    => '06-Hesap-ve-Sepet/hesap.css',
        'genel-css'    => '07-Genel-Duzenlemeler/genel.css',
        'takip-css'    => '08-Siparis-Takibi/siparis-takibi.css',
        'mob-nav-css'  => '09-Mobil-Alt-Menu/menu.css',
        'sticky-css'   => '10-Urun-Sayfasi-Iyilestirmeleri/urun.css',
        'destek-css'   => '11-Destek-Hatti/destek.css',
        'iletisim-css' => '12-Iletisim-Sayfasi/iletisim.css',
        'hizli-satin-al-css' => '13-Mobil-Hizli-Satin-Al/hizli-satin-al.css',
        'hizli-kategoriler-css' => '15-Hizli-Kategoriler/kategoriler.css',
        'mobil-arama-css' => '16-Mobil-Arama/arama.css'
    );

    foreach ($css_files as $handle => $rel_path) {
        wp_enqueue_style($handle, get_stylesheet_directory_uri() . '/' . $rel_path, array(), '1.0.5');
    }
});
