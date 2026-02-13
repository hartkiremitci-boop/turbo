<?php
/**
 * Mobil Hızlı Satın Alma Optimizasyonları
 */

// 1. Ürün Sayfasına "Hemen Al" Butonu Ekle (Sadece Mobil)
add_action('woocommerce_after_add_to_cart_button', 'totbagss_add_buy_now_button', 10);
function totbagss_add_buy_now_button() {
    if (!wp_is_mobile()) return;
    ?>
    <button type="submit" name="tot_buy_now" value="1" class="tot-buy-now-btn button alt">
        HEMEN AL
    </button>
    <?php
}

// 2. "Hemen Al" tıklandığında direkt ödeme sayfasına yönlendir
add_filter('woocommerce_add_to_cart_redirect', function($url) {
    if (isset($_REQUEST['tot_buy_now'])) {
        return wc_get_checkout_url();
    }
    return $url;
});

// 3. Ödeme Sayfasını Mobilde Sadeleştir
add_filter('woocommerce_checkout_fields', 'totbagss_simplify_mobile_checkout', 999);
function totbagss_simplify_mobile_checkout($fields) {
    if (!wp_is_mobile()) return $fields;

    // Not alanını küçült veya gizle (Gerekirse)
    // unset($fields['order']['order_comments']);

    return $fields;
}

// 4. Mobilde Sepet Sayfası İçin Yüzer "Ödemeye Geç" Butonu
add_action('wp_footer', function() {
    if (!is_cart() || !wp_is_mobile()) return;
    ?>
    <div class="tot-mobile-cart-sticky">
        <a href="<?php echo wc_get_checkout_url(); ?>" class="button alt">ÖDEMEYE GEÇ</a>
    </div>
    <?php
});

// 5. Numeric Keyboard for Mobile Inputs
add_filter('woocommerce_checkout_fields', 'totbagss_numeric_keyboard_fields', 1000);
function totbagss_numeric_keyboard_fields($fields) {
    if (!wp_is_mobile()) return $fields;

    if (isset($fields['billing']['billing_phone'])) {
        $fields['billing']['billing_phone']['input_class'][] = 'tot-numeric-input';
        $fields['billing']['billing_phone']['custom_attributes'] = array('inputmode' => 'tel');
    }

    if (isset($fields['billing']['billing_postcode'])) {
        $fields['billing']['billing_postcode']['custom_attributes'] = array('inputmode' => 'numeric');
    }

    return $fields;
}
