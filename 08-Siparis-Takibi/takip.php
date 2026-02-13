<?php
/**
 * Sipariş Takibi Sayfası ve Fonksiyonları
 */

if (!defined('ABSPATH')) exit;

// Takip Sayfasına Özel Header Ekle (Opsiyonel)
add_action('woocommerce_before_order_tracking_form', function() {
    ?>
    <div class="premium-order-header">
        <h2>SİPARİŞİNİ TAKİP ET</h2>
        <p>Siparişinizi takip etmek için <span>Sipariş Numarası</span> ve <span>E-posta</span> adresinizi giriniz.</p>
    </div>
    <?php
});
