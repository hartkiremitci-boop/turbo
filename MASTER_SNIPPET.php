<?php
/**
 * TotBagss Premium Master Snippet (v1.3.0)
 * Tüm fonksiyonlar tek bir dosyada birleştirilmiştir.
 * Code Snippets eklentisine yapıştırılabilir.
 */

if (!defined('ABSPATH')) exit;

/* -------------------------------------------------------------------------
   1. PERFORMANS VE KALITE OPTIMIZASYONU
------------------------------------------------------------------------- */
add_action('init', function() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wp_shortlink_wp_head');
});

add_filter('script_loader_src', 'tot_cleanup_query_string', 15, 1);
add_filter('style_loader_src', 'tot_cleanup_query_string', 15, 1);
function tot_cleanup_query_string($src) {
    if (strpos($src, 'ver=')) $src = remove_query_arg('ver', $src);
    return $src;
}

add_action('wp_enqueue_scripts', function() {
    if (function_exists('is_woocommerce')) {
        if (!is_woocommerce() && !is_cart() && !is_checkout() && !is_account_page()) {
            wp_dequeue_style('woocommerce-layout');
            wp_dequeue_style('woocommerce-general');
            wp_dequeue_style('woocommerce-smallscreen');
            wp_dequeue_script('wc-add-to-cart');
            wp_dequeue_script('wc-cart-fragments');
        }
    }
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
}, 999);

add_action('init', function() { wp_deregister_script('heartbeat'); }, 1);
add_filter('xmlrpc_enabled', '__return_false');

/* -------------------------------------------------------------------------
   2. GENEL DUZENLEMELER & WEBP DESTEGI
------------------------------------------------------------------------- */
add_filter('upload_mimes', function($mimes) { $mimes['webp'] = 'image/webp'; return $mimes; });
add_filter('wp_lazy_loading_enabled', '__return_true');

add_action('wp_head', function() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
}, 1);

/* -------------------------------------------------------------------------
   3. URUN KARTLARI & KARGO SAYACI
------------------------------------------------------------------------- */
add_action('woocommerce_before_shop_loop_item_title', function() {
    echo '<div class="tot-badge-new">YENİ</div>';
}, 5);

add_action('woocommerce_after_shop_loop_item_title', 'tot_card_info_panels', 15);
function tot_card_info_panels() {
    global $product;
    $id = $product->get_id();
    ?>
    <div class="tot-static-timer" id="timer-<?php echo $id; ?>">
        <span>KARGO İÇİN:</span> <span class="tot-timer-val">00:00:00</span>
    </div>
    <div class="tot-rotating-panel" id="panel-<?php echo $id; ?>">
        <div class="tot-panel-item active"><span>AYNI GÜN KARGO</span></div>
        <div class="tot-panel-item" style="display:none;"><span>GÜVENLİ ÖDEME</span></div>
        <div class="tot-panel-item" style="display:none;"><span>%100 ORİJİNAL</span></div>
    </div>
    <script>
    (function() {
        const id = '<?php echo $id; ?>';
        const panel = document.getElementById('panel-' + id);
        if (panel) {
            const items = panel.querySelectorAll('.tot-panel-item');
            let current = 0;
            setInterval(() => {
                items[current].style.display = 'none';
                current = (current + 1) % items.length;
                items[current].style.display = 'block';
            }, 3000);
        }
        function up() {
            const now = new Date();
            let t = new Date();
            t.setHours(16,0,0,0);
            if(now > t) t.setDate(t.getDate()+1);
            const d = t - now;
            const h = Math.floor(d/3600000).toString().padStart(2,'0');
            const m = Math.floor((d%3600000)/60000).toString().padStart(2,'0');
            const s = Math.floor((d%60000)/1000).toString().padStart(2,'0');
            const el = document.querySelector('#timer-'+id+' .tot-timer-val');
            if(el) el.innerText = h+':'+m+':'+s;
        }
        setInterval(up, 1000); up();
    })();
    </script>
    <?php
}

/* -------------------------------------------------------------------------
   4. MOBIL ALT MENU
------------------------------------------------------------------------- */
add_action('wp_footer', function() {
    if (!wp_is_mobile()) return;
    ?>
    <div class="tot-mobile-nav">
        <a href="<?php echo home_url(); ?>" class="tot-nav-item">
            <i class="ast-icon-home"></i>
            <span>Ana Sayfa</span>
        </a>
        <a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>" class="tot-nav-item">
            <i class="ast-icon-shopping-bag"></i>
            <span>Mağaza</span>
        </a>
        <a href="<?php echo wc_get_cart_url(); ?>" class="tot-nav-item">
            <i class="ast-icon-shopping-cart"></i>
            <span>Sepet</span>
        </a>
        <a href="<?php echo get_permalink(get_option('woocommerce_myaccount_page_id')); ?>" class="tot-nav-item">
            <i class="ast-icon-account_circle"></i>
            <span>Profil</span>
        </a>
    </div>
    <?php
});

/* -------------------------------------------------------------------------
   5. WHATSAPP DESTEK HATTI
------------------------------------------------------------------------- */
add_action('wp_footer', function() {
    ?>
    <a href="https://wa.me/905000000000" class="tot-whatsapp-float" target="_blank">
        <i class="fab fa-whatsapp"></i>
    </a>
    <?php
});

/* -------------------------------------------------------------------------
   6. HESABIM VE SEPET DUZENLEMELERI
------------------------------------------------------------------------- */
add_filter('woocommerce_account_menu_items', function($items) {
    $items['dashboard'] = 'Profilim';
    $items['orders'] = 'Siparişlerim';
    return $items;
});

add_filter('woocommerce_login_redirect', function($redirect, $user) {
    return wc_get_page_permalink('shop');
}, 10, 2);

/* -------------------------------------------------------------------------
   7. MAGAZA BASLIGI VE KATEGORILER
------------------------------------------------------------------------- */
add_action('woocommerce_before_shop_loop', 'tot_premium_shop_header', 15);
function tot_premium_shop_header() {
    if (!is_shop() && !is_product_category()) return;
    remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
    remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
    $categories = get_terms('product_cat', array('hide_empty' => true, 'parent' => 0));
    ?>
    <div class="tot-premium-shop-header">
        <div class="shop-header-top">
            <h1 class="shop-title"><?php woocommerce_page_title(); ?></h1>
            <div class="tot-sort-wrapper"><?php woocommerce_catalog_ordering(); ?></div>
        </div>
        <div class="shop-categories-bar no-scrollbar">
            <a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>" class="cat-chip <?php echo is_shop() ? 'active' : ''; ?>">TÜMÜ</a>
            <?php foreach ($categories as $cat) : ?>
                <a href="<?php echo get_term_link($cat); ?>" class="cat-chip <?php echo (is_product_category($cat->slug)) ? 'active' : ''; ?>">
                    <?php echo esc_html($cat->name); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}

/* -------------------------------------------------------------------------
   8. MOBIL HIZLI SATIN AL (STICKY BUTTON)
------------------------------------------------------------------------- */
add_action('wp_footer', function() {
    if (!is_cart() || !wp_is_mobile()) return;
    ?>
    <div class="tot-sticky-cart-action">
        <a href="<?php echo wc_get_checkout_url(); ?>" class="tot-cart-pay-btn">ÖDEMEYE GEÇ</a>
    </div>
    <?php
});

/* -------------------------------------------------------------------------
   9. ADMIN DOGRULAMA BARI (OPSIYONEL)
------------------------------------------------------------------------- */
add_action('wp_footer', function() {
    if (current_user_can('administrator')) {
        echo '<div style="position:fixed;bottom:0;left:0;width:100%;background:#c5a059;color:#fff;text-align:center;padding:5px;font-size:10px;z-index:99999;font-family:sans-serif;">MODERN PREMIUM V1.3 ACTIVE</div>';
    }
});
