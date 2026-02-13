<?php
/**
 * TotBagss Premium Child Theme - Ana Yükleyici
 * Bu tema modüler bir yapıya sahiptir. Tüm özellikler klasörlere ayrılmıştır.
 */

if (!defined('ABSPATH')) exit;

// 1. Child Theme Stilini ve Temel Yapıyı Yükle
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style('astra-child-theme-css', get_stylesheet_uri(), array('astra-theme-css'), '1.1.0');
});

// 2. Modül Klasörlerini ve Dosyalarını Tanımla
$tot_modules = array(
    '00-Performans-ve-Kalite'           => 'performans.php',
    '01-Hero-Alani'                     => 'hero.php',
    '03-Urun-Karuseli'                  => 'karusel.php',
    '04-Magaza-Basligi'                 => 'magaza-basligi.php',
    '05-Urun-Kartlari'                  => 'urunler.php',
    '06-Hesap-ve-Sepet'                 => 'hesap.php',
    '07-Genel-Duzenlemeler'             => 'genel.php',
    '08-Siparis-Takibi'                 => 'takip.php',
    '09-Mobil-Alt-Menu'                 => 'menu.php',
    '10-Urun-Sayfasi-Iyilestirmeleri'   => 'urun.php',
    '11-Destek-Hatti'                   => 'destek.php',
    '12-Iletisim-Sayfasi'               => 'iletisim.php',
    '13-Mobil-Hizli-Satin-Al'           => 'hizli-satin-al.php',
    '14-Brevo-Entegrasyonu'             => 'brevo.php',
    '15-Hizli-Kategoriler'              => 'kategoriler.php',
    '16-Mobil-Arama'                    => 'arama.php'
);

// 3. Modülleri Yükle (PHP ve CSS)
foreach ($tot_modules as $folder => $file) {
    $php_path = get_stylesheet_directory() . '/' . $folder . '/' . $file;
    if (file_exists($php_path)) {
        require_once $php_path;
    }

    // CSS dosyasını otomatik kuyruğa ekle (Dosya adı modül klasör adıyla aynı veya .css uzantılı ise)
    $css_name = str_replace('.php', '.css', $file);
    $css_path = get_stylesheet_directory() . '/' . $folder . '/' . $css_name;

    if (file_exists($css_path)) {
        add_action('wp_enqueue_scripts', function() use ($folder, $css_name) {
            wp_enqueue_style('tot-' . sanitize_title($folder), get_stylesheet_directory_uri() . '/' . $folder . '/' . $css_name, array(), '1.1.0');
        });
    }
}

// 4. Genel Varlıklar (Geriye Dönük Uyumluluk ve Global Ayarlar)
add_action('wp_enqueue_scripts', function() {
    // Genel JS
    if (file_exists(get_stylesheet_directory() . '/07-Genel-Duzenlemeler/genel.js')) {
        wp_enqueue_script('tot-global-js', get_stylesheet_directory_uri() . '/07-Genel-Duzenlemeler/genel.js', array('jquery'), '1.1.0', true);
    }
});
