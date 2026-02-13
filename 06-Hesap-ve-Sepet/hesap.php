<?php
/**
 * Hesabım, Sepet ve Ödeme Sayfası Mantığı
 */

if (!defined('ABSPATH')) exit;

// Menü Elemanlarını Türkçeleştir/Düzenle
add_filter('woocommerce_account_menu_items', function($items) {
    $items['dashboard'] = 'Profilim';
    $items['orders'] = 'Siparişlerim';
    return $items;
});

// Hoşgeldin Paneli
add_action('woocommerce_account_dashboard', function() {
    ?>
    <div class="premium-welcome">
        <h3>Hoş Geldiniz, <?php echo wp_get_current_user()->display_name; ?>!</h3>
        <p>Buradan siparişlerinizi görüntüleyebilir ve hesap ayarlarınızı yönetebilirsiniz.</p>
    </div>
    <div class="tot-account-stats">
        <div class="stat-card">
            <div class="stat-label">Üyelik Durumu</div>
            <div class="stat-value">PREMIUM ÜYE</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Destek Hattı</div>
            <div class="stat-value">7/24 ÖNCELİKLİ</div>
        </div>
    </div>
    <?php
}, 5);

// Giriş Sonrası Yönlendirme
add_filter('woocommerce_login_redirect', function($redirect, $user) {
    return wc_get_page_permalink('shop');
}, 10, 2);
