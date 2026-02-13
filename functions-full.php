<?php
/**
 * TotBagss Premium - FULL PHP LOGIC
 */



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

});

add_action('wp_footer', function() {
    if (!is_product()) return;
    global $product;
    ?>
    <div class="tot-sticky-add-to-cart">
        <div class="sticky-container">
            <div class="sticky-info">
                <span class="sticky-title"> the_title(); ?></span>
                <span class="sticky-price"> echo $product->get_price_html(); ?></span>
            </div>
            <button class="sticky-btn" onclick="document.querySelector('.single_add_to_cart_button').click();">SEPETE EKLE</button>
        </div>
    </div>
    <script>
    window.addEventListener('scroll', function() {
        const sticky = document.querySelector('.tot-sticky-add-to-cart');
        if (window.scrollY > 500) {
            sticky.classList.add('visible');
        } else {
            sticky.classList.remove('visible');
        }
    });
    </script>

});

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
                    'id': ' echo $pid; ?>',
                    'name': ' echo esc_js($product->get_name()); ?>',
                    'price':  echo $product->get_price(); ?>
                });
            }
        </script>

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
                'order_id': ' echo $order_id; ?>',
                'amount':  echo $order->get_total(); ?>,
                'currency': 'TRY'
            });
        }
    </script>

}

/**
 * Mobil Hızlı Kategori Izgarası (Daire ikonlar)
 */
add_shortcode('tot_mobile_cats', 'totbagss_mobile_category_grid');
function totbagss_mobile_category_grid() {
    if (!wp_is_mobile()) return '';

    $categories = get_terms('product_cat', array('hide_empty' => true, 'parent' => 0));
    ob_start();
    ?>
    <div class="tot-mobile-cat-grid">
         foreach ($categories as $cat) :
            $thumbnail_id = get_term_meta($cat->term_id, 'thumbnail_id', true);
            $image = wp_get_attachment_url($thumbnail_id);
            if (!$image) $image = wc_placeholder_img_src();
        ?>
            <a href=" echo get_term_link($cat); ?>" class="cat-item">
                <div class="cat-img-wrapper">
                    <img src=" echo $image; ?>" alt=" echo $cat->name; ?>">
                </div>
                <span> echo $cat->name; ?></span>
            </a>
         endforeach; ?>
    </div>

    return ob_get_clean();
}

/**
 * Ürün Kartları ve Liste Görünümü Özelleştirmeleri
 */

if (!defined('ABSPATH')) exit;

// 1. Ürün Kartına "Yeni" Rozeti Ekle
add_action('woocommerce_before_shop_loop_item_title', function() {
    echo '<div class="tot-badge-new">YENİ</div>';
}, 5);

// 2. Kargo Sayacı ve Bilgi Paneli
add_action('woocommerce_after_shop_loop_item_title', 'totbagss_card_info_panels', 15);
function totbagss_card_info_panels() {
    global $product;
    $id = $product->get_id();
    ?>
    <div class="tot-static-timer" id="timer- echo $id; ?>">
        <span>KARGO İÇİN:</span> <span class="tot-timer-val">00:00:00</span>
    </div>

    <div class="tot-rotating-panel" id="panel- echo $id; ?>">
        <div class="tot-panel-item active"><span>AYNI GÜN KARGO</span></div>
        <div class="tot-panel-item" style="display:none;"><span>GÜVENLİ ÖDEME</span></div>
        <div class="tot-panel-item" style="display:none;"><span>%100 ORİJİNAL</span></div>
        <div class="tot-panel-item" style="display:none;"><span>KOLAY İADE</span></div>
    </div>

    <script>
    (function() {
        const id = ' echo $id; ?>';
        const panel = document.getElementById('panel-' + id);
        if (!panel) return;
        const items = panel.querySelectorAll('.tot-panel-item');
        let current = 0;
        setInterval(() => {
            items[current].style.display = 'none';
            current = (current + 1) % items.length;
            items[current].style.display = 'block';
        }, 3000);

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

}

// 3. Ajaxlı Sepete Ekleme Butonu (Liste Görünümü)
add_action('woocommerce_after_shop_loop_item', 'totbagss_ajax_add_to_cart_btn', 10);
function totbagss_ajax_add_to_cart_btn() {
    global $product;
    if ($product->is_type('simple')) {
        // Standart butonu kaldırıp kendi Ajax butonumuzu ekleyebiliriz
        // veya mevcut olanı stilize edebiliriz. Modül 05 CSS'i bunu halleder.
    }
}

add_action('wp_footer', function() {
    ?>
    <a href="https://wa.me/905XXXXXXX" class="tot-whatsapp-float" target="_blank">
        <i class="ast-icon-whatsapp"></i>
    </a>

});

/**
 * Ajax Destekli Ürün Karuseli
 */

if (!defined('ABSPATH')) exit;

add_shortcode('tot_carousel', 'totbagss_main_carousel');
function totbagss_main_carousel($atts) {
    $a = shortcode_atts(array('cat' => 'yeni-gelenler', 'limit' => 8), $atts);
    $q = new WP_Query(array(
        'post_type' => 'product',
        'posts_per_page' => $a['limit'],
        'product_cat' => $a['cat'],
        'stock_status' => 'instock'
    ));

    ob_start();
    if ($q->have_posts()) : ?>
    <div class="tot-carousel-wrapper">
        <div class="tot-carousel-container no-scrollbar" id="totMainCarousel">
            <div class="tot-carousel-track">
                 while ($q->have_posts()) : $q->the_post(); global $product;
                    $img = wp_get_attachment_image_src($product->get_image_id(), 'woocommerce_thumbnail');
                    $img_url = $img ? $img[0] : wc_placeholder_img_src();
                ?>
                <div class="tot-card-link">
                    <div class="tot-card">
                        <a href=" the_permalink(); ?>" class="tot-product-inner">
                            <div class="tot-img-box">
                                <img src=" echo $img_url; ?>" alt=" the_title(); ?>" loading="lazy">
                            </div>
                            <div class="tot-name"> the_title(); ?></div>
                            <div class="tot-price">₺ echo number_format($product->get_price(), 2, ',', '.'); ?></div>
                        </a>
                        <button class="tot-add-btn" data-product-id=" the_ID(); ?>">SEPETE EKLE</button>
                    </div>
                </div>
                 endwhile; wp_reset_postdata(); ?>

                <div class="tot-card-link">
                    <div class="tot-carousel-transition">
                        <div class="transition-title">ZAMANSIZ ŞIKLIĞI<br>KEŞFEDİN</div>
                        <a href="/magaza" class="transition-btn">TÜMÜNÜ GÖR</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    (function() {
        const track = document.getElementById('totMainCarousel');
        if (!track) return;
        let isDown = false; let startX, scrollLeft;
        track.addEventListener('mousedown', (e) => { isDown = true; startX = e.pageX - track.offsetLeft; scrollLeft = track.scrollLeft; });
        track.addEventListener('mouseleave', () => isDown = false);
        track.addEventListener('mouseup', () => isDown = false);
        track.addEventListener('mousemove', (e) => { if (!isDown) return; e.preventDefault(); const x = e.pageX - track.offsetLeft; const walk = (x - startX) * 2; track.scrollLeft = scrollLeft - walk; });

        document.querySelectorAll('.tot-add-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.dataset.productId;
                this.innerText = '...';
                const fd = new FormData();
                fd.append('add-to-cart', id);
                fetch(window.location.href, { method: 'POST', body: fd })
                .then(() => {
                    this.innerText = 'EKLENDİ ✓';
                    this.style.background = '#10b981';
                    if (typeof jQuery !== 'undefined') jQuery(document.body).trigger('added_to_cart');
                    setTimeout(() => { this.innerText = 'SEPETE EKLE'; this.style.background = '#4A083D'; }, 2000);
                });
            });
        });
    })();
    </script>
     endif;
    return ob_get_clean();
}

/**
 * Mobil Hızlı Arama Barı
 */
add_action('wp_body_open', 'totbagss_mobile_search_bar');
function totbagss_mobile_search_bar() {
    if (!wp_is_mobile() || !is_front_page()) return;
    ?>
    <div class="tot-mobile-search-wrapper">
        <form role="search" method="get" class="woocommerce-product-search" action=" echo esc_url( home_url( '/' ) ); ?>">
            <input type="search" class="search-field" placeholder="Ürün ara..." value=" echo get_search_query(); ?>" name="s" />
            <button type="submit" value="Ara"><i class="ast-icon-search"></i></button>
            <input type="hidden" name="post_type" value="product" />
        </form>
    </div>

}

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
        <a href=" echo wc_get_checkout_url(); ?>" class="tot-cart-pay-btn">ÖDEMEYE GEÇ</a>
    </div>
    <style>
        .tot-sticky-cart-action { position: fixed; bottom: 70px; left: 0; right: 0; padding: 15px; background: #fff; box-shadow: 0 -5px 15px rgba(0,0,0,0.1); z-index: 9998; }
        .tot-cart-pay-btn { display: block; background: #4A083D; color: #fff; text-align: center; padding: 18px; border-radius: 12px; font-weight: 900; text-decoration: none; }
    </style>

});

/**
 * İletişim Sayfası ve Form Düzenlemeleri
 */
// Gerekirse buraya Contact Form 7 hookları eklenebilir.

/**
 * Mağaza Başlığı, Kategoriler ve Filtreler
 */

if (!defined('ABSPATH')) exit;

add_action('woocommerce_before_shop_loop', 'totbagss_premium_shop_header', 15);
function totbagss_premium_shop_header() {
    if (!is_shop() && !is_product_category()) return;

    // Varsayılanları gizle
    remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
    remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);

    $categories = get_terms('product_cat', array('hide_empty' => true, 'parent' => 0));
    ?>
    <div class="tot-premium-shop-header">
        <div class="shop-header-top">
            <h1 class="shop-title"> woocommerce_page_title(); ?></h1>
            <div class="shop-actions">
                <div class="tot-sort-wrapper">
                     woocommerce_catalog_ordering(); ?>
                </div>
            </div>
        </div>

        <div class="shop-categories-bar no-scrollbar">
            <a href=" echo get_permalink(wc_get_page_id('shop')); ?>" class="cat-chip  echo is_shop() ? 'active' : ''; ?>">TÜMÜ</a>
             foreach ($categories as $cat) :
                $active = (is_product_category($cat->slug)) ? 'active' : '';
            ?>
                <a href=" echo get_term_link($cat); ?>" class="cat-chip  echo $active; ?>">
                     echo esc_html($cat->name); ?>
                </a>
             endforeach; ?>
        </div>
    </div>

}

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
        <h3>Hoş Geldiniz,  echo wp_get_current_user()->display_name; ?>!</h3>
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

}, 5);

// Giriş Sonrası Yönlendirme
add_filter('woocommerce_login_redirect', function($redirect, $user) {
    return wc_get_page_permalink('shop');
}, 10, 2);

/**
 * TotBagss Performans ve Kalite Optimizasyon Sistemi
 * Kasmayı engellemek ve site hızını maksimize etmek için tasarlandı.
 */

if (!defined('ABSPATH')) exit;

// 1. Emoji ve Gereksiz WP Scriptlerini Kaldır
add_action('init', function() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

    // RSD, WLW, Shortlink gibi gereksiz meta tagleri temizle
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wp_shortlink_wp_head');
});

// 2. Query Strings (Sürüm Bilgileri) Kaldır - Önbellek Dostu
add_filter('script_loader_src', 'totbagss_cleanup_query_string', 15, 1);
add_filter('style_loader_src', 'totbagss_cleanup_query_string', 15, 1);
function totbagss_cleanup_query_string($src) {
    if (strpos($src, 'ver=')) $src = remove_query_arg('ver', $src);
    return $src;
}

// 3. WooCommerce Bloat Temizliği
add_action('wp_enqueue_scripts', function() {
    if (function_exists('is_woocommerce')) {
        // Sadece sepet, ödeme ve hesap sayfalarında gerekli olanları tut, diğerlerinde temizle
        if (!is_woocommerce() && !is_cart() && !is_checkout() && !is_account_page()) {
            wp_dequeue_style('woocommerce-layout');
            wp_dequeue_style('woocommerce-general');
            wp_dequeue_style('woocommerce-smallscreen');
            wp_dequeue_script('wc-add-to-cart');
            wp_dequeue_script('wc-cart-fragments');
            wp_dequeue_script('woocommerce');
            wp_dequeue_script('wc-cart');
            wp_dequeue_script('wc-chosen');
        }
    }
}, 999);

// 4. Kalp Atışı (Heartbeat) Kontrolü - CPU Tasarrufu
add_action('init', function() {
    wp_deregister_script('heartbeat');
}, 1);

// 5. Veritabanı Revizyon Kontrolü
if (!defined('WP_POST_REVISIONS')) define('WP_POST_REVISIONS', 2);

// 6. Scriptleri Ertele (Defer) - LCP ve TTI İyileştirmesi
add_filter('script_loader_tag', function($tag, $handle) {
    if (is_admin()) return $tag;
    if (strpos($handle, 'jquery') !== false && !strpos($handle, 'migrate')) return $tag;
    return str_replace(' src', ' defer src', $tag);
}, 10, 2);

// 7. XML-RPC Kapat (Güvenlik ve Hız)
add_filter('xmlrpc_enabled', '__return_false');

// 8. WP Embed Scriptini Kaldır
add_action('wp_footer', function() {
    wp_deregister_script('wp-embed');
});

// 9. Gutenberg Blok CSS'lerini Sadece Gerekli Sayfalarda Yükle
add_action('wp_enqueue_scripts', function() {
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('wc-block-style');
}, 100);

// 10. JQuery Migrate Kaldır
add_action('wp_default_scripts', function($scripts) {
    if (!is_admin() && isset($scripts->registered['jquery'])) {
        $script = $scripts->registered['jquery'];
        if ($script->deps) {
            $script->deps = array_diff($script->deps, array('jquery-migrate'));
        }
    }
});

/**
 * Görsel Optimizasyonu & Genel Filtreler
 */
add_filter('upload_mimes', function($mimes) { $mimes['webp'] = 'image/webp'; return $mimes; });
add_filter('jpeg_quality', function() { return 85; });
add_filter('big_image_size_threshold', function() { return 1920; });
add_filter('wp_lazy_loading_enabled', '__return_true');

// Genel JS Enqueue
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_script('tot-genel-js', get_stylesheet_directory_uri() . '/07-Genel-Duzenlemeler/genel.js', array(), '1.0.0', true);
});

add_action('wp_footer', function() {
    echo '<!-- TotBagss Premium System v1.1.0 Active -->';
}, 100);

add_action('wp_footer', function() {
    if (!wp_is_mobile()) return;
    ?>
    <div class="tot-mobile-nav">
        <a href=" echo home_url(); ?>" class="nav-item">
            <i class="ast-icon-home"></i>
            <span>Ana Sayfa</span>
        </a>
        <a href=" echo get_permalink(wc_get_page_id('shop')); ?>" class="nav-item">
            <i class="ast-icon-shopping-bag"></i>
            <span>Mağaza</span>
        </a>
        <a href=" echo wc_get_cart_url(); ?>" class="nav-item">
            <i class="ast-icon-shopping-cart"></i>
            <span>Sepet</span>
        </a>
        <a href=" echo get_permalink(get_option('woocommerce_myaccount_page_id')); ?>" class="nav-item">
            <i class="ast-icon-account_circle"></i>
            <span>Hesabım</span>
        </a>
    </div>

});

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

}, 100);

// Hero Shortcode (Eğer manuel eklenmek istenirse)
add_shortcode('tot_hero_section', function($atts) {
    ob_start();
    ?>
    <div class="tot-premium-hero">
        <!-- Hero HTML buraya gelebilir -->
    </div>

    return ob_get_clean();
});
