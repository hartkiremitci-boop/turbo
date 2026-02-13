<?php
/**
 * Mobil Hızlı Satın Alma ve Kullanıcı Deneyimi (UX)
 */

if (!defined('ABSPATH')) exit;

// 1. Ürün Sayfasında "Hemen Al" Butonu
add_action('woocommerce_after_add_to_cart_button', function() {
    if (!wp_is_mobile()) return;
    ?>
    <button type="submit" name="tot_direct_buy" value="1" class="tot-buy-now-btn">
        HEMEN AL
    </button>
    <?php
}, 20);

// "Hemen Al" tıklandığında direkt ödemeye yönlendir
add_filter('woocommerce_add_to_cart_redirect', function($url) {
    if (isset($_REQUEST['tot_direct_buy'])) {
        return wc_get_checkout_url();
    }
    return $url;
});

// 2. Ödeme Sayfasında Gereksiz Alanları Temizle (Hızlı Ödeme)
add_filter('woocommerce_checkout_fields', function($fields) {
    if (!wp_is_mobile()) return $fields;

    // Opsiyonel: Şirket adı gibi alanları gizleyerek formu kısaltın
    unset($fields['billing']['billing_company']);

    // Telefon ve Posta Kodu için numerik klavye
    if (isset($fields['billing']['billing_phone'])) {
        $fields['billing']['billing_phone']['custom_attributes'] = array('inputmode' => 'tel');
    }
    return $fields;
}, 999);

// 3. Mobilde Sepet Sayfasında Sabit "Öde" Butonu
add_action('wp_footer', function() {
    if (!is_cart() || !wp_is_mobile()) return;
    ?>
    <div class="tot-sticky-cart-action">
        <a href="<?php echo wc_get_checkout_url(); ?>" class="tot-cart-pay-btn">ÖDEMEYE GEÇ</a>
    </div>
    <style>
        .tot-sticky-cart-action { position: fixed; bottom: 70px; left: 0; right: 0; padding: 15px; background: #fff; box-shadow: 0 -5px 15px rgba(0,0,0,0.1); z-index: 9998; }
        .tot-cart-pay-btn { display: block; background: #4A083D; color: #fff; text-align: center; padding: 18px; border-radius: 12px; font-weight: 900; text-decoration: none; }
    </style>
    <?php
});
