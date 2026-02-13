<?php
/**
 * TotBagss Child Theme functions and definitions
 */

/**
 * Enqueue styles
 */
function child_enqueue_styles() {
    wp_enqueue_style( 'astra-child-theme-css', get_stylesheet_directory_uri() . '/style.css', array('astra-theme-css'), '1.0.1', 'all' );
}
add_action( 'wp_enqueue_scripts', 'child_enqueue_styles', 15 );

/**
 * Fontları tek seferde yükle
 */
add_action('wp_enqueue_scripts', 'totbagss_enqueue_fonts', 5);
function totbagss_enqueue_fonts() {
    wp_enqueue_style(
        'tot-google-fonts',
        'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&family=Bruno+Ace&family=Bruno+Ace+SC&display=swap',
        array(),
        null
    );
}

/**
 * TotBag'sS Stil Günlükleri Carousel Shortcode
 */
add_shortcode('totbagss_carousel', 'totbagss_product_carousel_handler');

function totbagss_product_carousel_handler() {
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 20,
        'orderby' => 'date',
        'order' => 'DESC',
    );

    $loop = new WP_Query($args);
    if (!$loop->have_posts()) return 'Ürün bulunamadı.';

    ob_start();
    ?>

    <style>
    .tot-carousel-wrapper {
        overflow: hidden;
        position: relative;
        padding: 40px 0 60px;
        background: #fff;
    }

    .tot-header {
        text-align: center;
        margin-bottom: 35px;
    }

    .tot-header span.premium-eyebrow {
        font-family: 'Bruno Ace SC', sans-serif;
        color: #4A083D;
        font-size: 11px;
        font-weight: 400;
        letter-spacing: 4px;
        display: block;
        margin-bottom: 12px;
        opacity: 0.8;
        position: relative;
    }

    .tot-header span.premium-eyebrow::before,
    .tot-header span.premium-eyebrow::after {
        content: "";
        display: inline-block;
        width: 30px;
        height: 1px;
        background: #4A083D;
        vertical-align: middle;
        margin: 0 15px;
        opacity: 0.3;
    }

    .tot-header h2 {
        font-family: 'Bruno Ace SC', sans-serif;
        font-size: clamp(22px, 4vw, 32px);
        font-weight: 400;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 2px;
        background: linear-gradient(135deg, #4A083D 0%, #ff5fa2 50%, #4A083D 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-size: 200% auto;
        animation: shineText 5s linear infinite;
    }

    @keyframes shineText {
        to { background-position: 200% center; }
    }

    .tot-carousel-container {
        overflow-x: auto;
        scroll-behavior: smooth;
        -ms-overflow-style: none;
        scrollbar-width: none;
        cursor: grab;
        padding: 10px 20px 30px;
        scroll-snap-type: x mandatory;
    }

    .tot-carousel-container::-webkit-scrollbar { display: none; }

    .tot-track {
        display: flex;
        gap: 20px;
    }

    .tot-card-link {
        flex: 0 0 260px;
        scroll-snap-align: start;
        text-decoration: none;
        color: inherit;
    }

    .tot-card {
        background: #fff;
        border-radius: 24px;
        border: 1px solid #f2f2f2;
        padding: 12px;
        transition: all 0.4s ease;
        position: relative;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .tot-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(74, 8, 61, 0.08);
        border-color: #ff5fa2;
    }

    .tot-img-box {
        aspect-ratio: 1/1.2;
        border-radius: 18px;
        overflow: hidden;
        margin-bottom: 15px;
        background: #f9f9f9;
    }

    .tot-img-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 1.5s ease;
    }

    .tot-card:hover .tot-img-box img { transform: scale(1.1); }

    .tot-carousel-transition {
        min-width: 260px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        padding: 40px;
        background: #fdf6f9;
        border-radius: 24px;
        scroll-snap-align: center;
        border: 1px dashed #4A083D;
    }

    .transition-title {
        font-family: 'Bruno Ace SC', sans-serif;
        font-size: 16px;
        color: #4A083D;
        margin-bottom: 15px;
        line-height: 1.4;
    }

    .transition-btn {
        font-family: 'Montserrat', sans-serif;
        font-size: 11px;
        font-weight: 800;
        color: #fff;
        background: #4A083D;
        padding: 10px 20px;
        border-radius: 30px;
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    @media (max-width: 768px) {
        .tot-card-link { flex: 0 0 220px; }
        .tot-header h2 { font-size: 18px; }
    }
    </style>

    <div class="tot-carousel-wrapper">
        <div class="tot-header">
            <span class="premium-eyebrow">SON GELEN ÜRÜNLER</span>
            <h2>TotBag'sS Stil Günlükleri</h2>
        </div>

        <div class="tot-carousel-container" id="totCarousel">
            <div class="tot-track">
                <?php
                while ($loop->have_posts()) : $loop->the_post();
                    global $product;
                    $img = wp_get_attachment_image_src(get_post_thumbnail_id(), 'woocommerce_thumbnail');
                    $img_url = $img ? $img[0] : wc_placeholder_img_src();
                ?>
                <div class="tot-card-link">
                    <div class="tot-card">
                        <a href="<?php the_permalink(); ?>" style="text-decoration:none; color:inherit;">
                            <div class="tot-img-box">
                                <img src="<?php echo $img_url; ?>" alt="<?php the_title(); ?>" loading="lazy">
                            </div>
                            <div class="tot-name" style="font-weight:700; font-size:12px; color:#333; margin-bottom:8px; height:34px; overflow:hidden; text-transform:uppercase;"><?php the_title(); ?></div>
                            <div class="tot-price" style="font-weight:900; color:#4A083D; font-size:16px;">₺<?php echo number_format($product->get_price(), 2, ',', '.'); ?></div>
                        </a>
                        <button class="tot-add-btn" data-product-id="<?php the_ID(); ?>" style="width:100%; background:#4A083D; color:#fff; border:none; padding:12px; border-radius:14px; margin-top:15px; font-weight:800; font-size:10px; cursor:pointer; text-transform:uppercase; letter-spacing:1px;">SEPETE EKLE</button>
                    </div>
                </div>
                <?php endwhile; wp_reset_postdata(); ?>

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
        const track = document.getElementById('totCarousel');
        if (!track) return;

        let isDown = false;
        let startX, scrollLeft;

        track.addEventListener('mousedown', (e) => {
            isDown = true;
            startX = e.pageX - track.offsetLeft;
            scrollLeft = track.scrollLeft;
        });
        track.addEventListener('mouseleave', () => isDown = false);
        track.addEventListener('mouseup', () => isDown = false);
        track.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - track.offsetLeft;
            const walk = (x - startX) * 2;
            track.scrollLeft = scrollLeft - walk;
        });

        document.querySelectorAll('.tot-add-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                const id = this.dataset.productId;
                this.innerText = '...';
                const fd = new FormData();
                fd.append('add-to-cart', id);
                fetch(window.location.href, { method: 'POST', body: fd })
                .then(() => {
                    this.innerText = 'EKLENDİ ✓';
                    this.style.background = '#10b981';
                    if (typeof jQuery !== 'undefined') jQuery(document.body).trigger('added_to_cart');
                    setTimeout(() => {
                        this.innerText = 'SEPETE EKLE';
                        this.style.background = '#4A083D';
                    }, 2000);
                });
            });
        });
    })();
    </script>
    <?php
    return ob_get_clean();
}

/**
 * WooCommerce Rozet, Sayaç ve Panel Logic
 */
add_action('woocommerce_before_shop_loop_item_title', 'totbagss_add_badge', 5);
function totbagss_add_badge() {
    echo '<div class="tot-badge-new" style="position: absolute; top: 10px; left: 10px; background: #4a083d; color: #fff; padding: 4px 8px; border-radius: 6px; font-size: 8px; font-weight: 900; z-index: 10; text-transform: uppercase;">YENİ</div>';
}

add_action('woocommerce_before_shop_loop_item_title', 'totbagss_add_timer_panel', 20);
function totbagss_add_timer_panel() {
    global $product;
    $id = $product->get_id();
?>
<div class="tot-static-timer" id="timer-<?php echo esc_attr($id); ?>" style="font-size: 9px; font-weight: 900; color: #4a083d; text-transform: uppercase; margin: 4px 0; text-align: center; letter-spacing:0.5px;">
    <span>KARGO İÇİN:</span> <span class="tot-timer-val" style="color:#ff5fa2;">00:00:00</span>
</div>

<div class="tot-rotating-panel" id="panel-<?php echo esc_attr($id); ?>" style="background: linear-gradient(135deg, #4a083d 0%, #6a0d58 100%); border-radius: 8px; min-height: 28px; margin: 4px 0; display: flex; align-items: center; justify-content: center; color: #fff; position: relative; overflow: hidden; font-size: 9px; font-weight: 800;">
    <div class="tot-panel-item active"><span>KARGO ALICI ÖDER</span></div>
    <div class="tot-panel-item" style="display:none;"><span>KOLAY DEĞİŞİM</span></div>
    <div class="tot-panel-item" style="display:none;"><span>GÜVENLİ ÖDEME</span></div>
    <div class="tot-panel-item" style="display:none;"><span>HIZLI DESTEK</span></div>
</div>

<script>
(function() {
    const id = '<?php echo esc_js($id); ?>';
    const panel = document.getElementById('panel-' + id);
    const timer = document.getElementById('timer-' + id);
    if (!panel || !timer) return;
    const items = panel.querySelectorAll('.tot-panel-item');
    let current = 0;
    setInterval(() => {
        items[current].style.display = 'none';
        current = (current + 1) % items.length;
        items[current].style.display = 'block';
    }, 4000);
    function updateTimer() {
        const now = new Date();
        let target = new Date();
        target.setHours(16, 0, 0, 0);
        if (now > target) target.setDate(target.getDate() + 1);
        const diff = target - now;
        const h = Math.floor(diff / 3600000).toString().padStart(2, '0');
        const m = Math.floor((diff % 3600000) / 60000).toString().padStart(2, '0');
        const s = Math.floor((diff % 60000) / 1000).toString().padStart(2, '0');
        const el = timer.querySelector('.tot-timer-val');
        if (el) el.innerText = h + ':' + m + ':' + s;
    }
    setInterval(updateTimer, 1000);
    updateTimer();
})();
</script>
<?php
}

/**
 * Görsel Optimizasyonu & Diğer Ayarlar
 */
add_filter('upload_mimes', function($mimes) { $mimes['webp'] = 'image/webp'; return $mimes; });
add_filter('jpeg_quality', function() { return 85; });
add_filter('big_image_size_threshold', function() { return 1920; });
add_filter('wp_lazy_loading_enabled', '__return_true');

add_filter('woocommerce_account_menu_items', function($items) {
    $items['dashboard'] = 'Profilim';
    return $items;
});

add_action('woocommerce_account_dashboard', function() {
    echo '<div class="premium-welcome" style="margin-bottom: 30px; padding: 25px; background: #fdf6f9; border-left: 5px solid #4A083D; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.02);">';
    echo '<h3 style="color: #4A083D; margin-bottom: 12px; font-family:\'Bruno Ace SC\', sans-serif; font-weight:400;">Hoş Geldiniz!</h3>';
    echo '<p style="font-size:14px; color:#555;">TotBagss ailesinin bir parçası olduğunuz için mutluyuz. Siparişlerinizi ve profilinizi buradan yönetebilirsiniz.</p>';
    echo '</div>';
}, 5);

add_filter('woocommerce_login_redirect', function($redirect, $user) { return wc_get_page_permalink('shop'); }, 10, 2);

/**
 * Premium Shop Header (Categories & Sorting)
 */
add_action('woocommerce_before_shop_loop', 'totbagss_premium_shop_header', 15);
function totbagss_premium_shop_header() {
    if (!is_shop() && !is_product_category()) return;

    // Hide default sorting/result count (we will restyle them or replace)
    remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
    remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);

    $categories = get_terms('product_cat', array('hide_empty' => true, 'parent' => 0));
    ?>
    <div class="tot-premium-shop-header">
        <div class="shop-header-top">
            <h1 class="shop-title"><?php woocommerce_page_title(); ?></h1>
            <div class="shop-actions">
                <div class="tot-sort-wrapper">
                    <?php woocommerce_catalog_ordering(); ?>
                </div>
            </div>
        </div>

        <div class="shop-categories-bar no-scrollbar">
            <a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>" class="cat-chip <?php echo is_shop() ? 'active' : ''; ?>">TÜMÜ</a>
            <?php foreach ($categories as $cat) :
                $active = (is_product_category($cat->slug)) ? 'active' : '';
            ?>
                <a href="<?php echo get_term_link($cat); ?>" class="cat-chip <?php echo $active; ?>">
                    <?php echo esc_html($cat->name); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}

/**
 * Premium My Account Details Customization
 */
add_action('woocommerce_account_dashboard', 'totbagss_premium_dashboard_stats', 10);
function totbagss_premium_dashboard_stats() {
    $current_user = wp_get_current_user();
    ?>
    <div class="tot-account-stats" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px; margin-top: 30px;">
        <div class="stat-card" style="background:#fff; padding:20px; border-radius:15px; border:1px solid #eee; text-align:center;">
            <div style="font-size:11px; color:#999; text-transform:uppercase; margin-bottom:5px;">Üyelik</div>
            <div style="font-weight:900; color:#4A083D;">PREMIUM ÜYE</div>
        </div>
        <div class="stat-card" style="background:#fff; padding:20px; border-radius:15px; border:1px solid #eee; text-align:center;">
            <div style="font-size:11px; color:#999; text-transform:uppercase; margin-bottom:5px;">Destek</div>
            <div style="font-weight:900; color:#4A083D;">7/24 ÖNCELİKLİ</div>
        </div>
    </div>
    <?php
}
