<?php
/**
 * Brevo (Sendinblue) Entegrasyon Destek Modülü
 * WooCommerce verilerini Brevo Automation için optimize eder.
 */

// 1. Brevo Takip Kodunu Header'a Ekle (Gerekiyorsa buraya ID eklenmeli)
add_action('wp_head', 'totbagss_brevo_tracking_script', 20);
function totbagss_brevo_tracking_script() {
    // Buradaki ID'yi kullanıcı Brevo panelinden alıp değiştirmelidir.
    // Varsayılan olarak boş bırakıyoruz veya bir placeholder koyuyoruz.
    ?>
    <!-- Brevo Tracking Code Placeholder -->
    <script type="text/javascript">
        (function() {
            window.sib = { e_loop: [] };
            window.StayInTouch = { e_loop: [] };
        })();
    </script>
    <?php
}

// 2. Sepete Ekleme Olayını Brevo'ya Bildir (JS ile)
add_action('woocommerce_add_to_cart', 'totbagss_brevo_track_add_to_cart', 10, 6);
function totbagss_brevo_track_add_to_cart($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data) {
    // Bu olay sunucu taraflı çalışır. Brevo JS API'sine push yapmak için
    // bir flag set edip wp_footer'da script basabiliriz.
    set_transient('tot_brevo_track_atc', $product_id, 30);
}

add_action('wp_footer', function() {
    $pid = get_transient('tot_brevo_track_atc');
    if ($pid) {
        delete_transient('tot_brevo_track_atc');
        $product = wc_get_product($pid);
        ?>
        <script type="text/javascript">
            if (typeof sendinblue !== 'undefined') {
                sendinblue.track('cart_updated', {
                    'id': '<?php echo $pid; ?>',
                    'name': '<?php echo esc_js($product->get_name()); ?>',
                    'price': <?php echo $product->get_price(); ?>
                });
            }
        </script>
        <?php
    }
});

// 3. Ödeme Başarılı Olduğunda Brevo'yu Bilgilendir
add_action('woocommerce_thankyou', 'totbagss_brevo_order_completed', 10, 1);
function totbagss_brevo_order_completed($order_id) {
    if (!$order_id) return;
    $order = wc_get_order($order_id);

    // JS tarafında 'order_completed' eventini tetikle
    ?>
    <script type="text/javascript">
        if (typeof sendinblue !== 'undefined') {
            sendinblue.track('order_completed', {
                'order_id': '<?php echo $order_id; ?>',
                'amount': <?php echo $order->get_total(); ?>,
                'currency': 'TRY'
            });
        }
    </script>
    <?php
}
