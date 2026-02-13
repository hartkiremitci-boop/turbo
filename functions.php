<?php
/**
 * TotBagss Child Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Astra Child
 * @since 1.0.0
 */

/**
 * Enqueue styles
 */
function child_enqueue_styles() {
    wp_enqueue_style( 'astra-child-theme-css', get_stylesheet_directory_uri() . '/style.css', array('astra-theme-css'), '1.0.0', 'all' );
}
add_action( 'wp_enqueue_scripts', 'child_enqueue_styles', 15 );

/**
 * TotBag'sS Stil Günlükleri Carousel Shortcode
 * Kullanım: [totbagss_carousel]
 */
add_shortcode('totbagss_carousel', 'totbagss_product_carousel_handler');

function totbagss_product_carousel_handler() {
    // 20 Ürün Çek
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 20,
        'orderby' => 'date',
        'order' => 'DESC',
    );

    $loop = new WP_Query($args);

    if (!$loop->have_posts()) {
        return 'Ürün bulunamadı.';
    }

    ob_start();
    ?>

    <style>
    :root {
        --tot-primary: #5D0E49;
        --tot-hover: #3d0930;
    }

    .tot-carousel-wrapper {
        overflow: hidden;
        position: relative;
        padding: 60px 0;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        background: #fff;
    }

    .tot-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .tot-header span {
        color: #ccc;
        font-size: 10px;
        font-weight: 900;
        letter-spacing: 6px;
        display: block;
        margin-bottom: 8px;
    }

    .tot-header h2 {
        color: var(--tot-primary);
        font-size: 36px;
        font-weight: 900;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: -1.5px;
        position: relative;
        display: inline-block;
        background: linear-gradient(to right, #5D0E49 20%, #ff85d8 40%, #ff85d8 60%, #5D0E49 80%);
        background-size: 200% auto;
        color: #000;
        background-clip: text;
        text-fill-color: transparent;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        animation: shine 4s linear infinite;
    }

    @keyframes shine {
        to { background-position: 200% center; }
    }

    .tot-track {
        display: flex;
        gap: 24px;
        cursor: grab;
        padding-bottom: 30px;
        user-select: none;
        transition: transform 0.1s linear;
    }

    .tot-track:active {
        cursor: grabbing;
    }

    .tot-card-link {
        text-decoration: none;
        color: inherit;
        display: block;
        flex: 0 0 255px;
    }

    .tot-card {
        background: #fff;
        border-radius: 50px;
        border: 1px solid #f2f2f2;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        padding: 16px;
        height: 100%;
    }

    .tot-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 25px 50px rgba(93, 14, 73, 0.12);
    }

    .tot-img-box {
        position: relative;
        aspect-ratio: 4/5;
        border-radius: 40px;
        overflow: hidden;
        background: #f0f0f0;
        margin-bottom: 16px;
    }

    .tot-img-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 1s ease;
        display: block;
    }

    .tot-card:hover .tot-img-box img {
        transform: scale(1.1);
    }

    .tot-badge {
        position: absolute;
        top: 16px;
        left: 16px;
        background: var(--tot-primary);
        color: #fff;
        font-size: 9px;
        font-weight: 900;
        padding: 6px 14px;
        border-radius: 20px;
        z-index: 2;
    }

    .tot-timer {
        font-size: 13px;
        font-weight: 900;
        color: var(--tot-primary);
        margin-bottom: 10px;
        text-align: center;
        height: 20px;
        font-variant-numeric: tabular-nums;
    }

    .tot-timer.closed {
        color: #dc2626;
        font-size: 10px;
    }

    .tot-trust-banner {
        background: var(--tot-primary);
        color: #fff;
        font-size: 10px;
        font-weight: 900;
        height: 30px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
        overflow: hidden;
        position: relative;
    }

    .tot-trust-msg {
        position: absolute;
        width: 100%;
        text-align: center;
        transition: transform 0.6s ease;
    }

    .tot-name {
        font-size: 12px;
        font-weight: 800;
        color: #333;
        height: 40px;
        overflow: hidden;
        margin-bottom: 12px;
        line-height: 1.3;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        text-transform: uppercase;
        padding: 0 10px;
    }

    .tot-price {
        font-size: 18px;
        font-weight: 900;
        color: var(--tot-primary);
        margin-bottom: 16px;
        text-align: center;
    }

    .tot-add-btn {
        background: var(--tot-primary);
        color: #fff;
        border: none;
        width: 100%;
        padding: 15px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 900;
        cursor: pointer;
        transition: 0.2s;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        position: relative;
        z-index: 10;
    }

    .tot-add-btn:hover {
        background: var(--tot-hover);
        box-shadow: 0 8px 20px rgba(93, 14, 73, 0.3);
    }

    .tot-add-btn.added {
        background: #10b981;
        transform: scale(0.95);
    }

    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }

    .tot-carousel-container {
        overflow-x: auto;
        scroll-behavior: smooth;
        -ms-overflow-style: none;
        scrollbar-width: none;
        cursor: grab;
        padding: 10px 0;
    }
    </style>

    <div class="tot-carousel-wrapper">
        <div class="tot-header">
            <span>SON GELEN ÜRÜNLER</span>
            <h2>TotBag'sS Stil Günlükleri</h2>
        </div>

        <div class="tot-carousel-container no-scrollbar" id="totCarousel">
            <div class="tot-track" id="totTrack">
                <?php
                // Ürünleri 3 kez tekrarla (sonsuz scroll için)
                for($i = 0; $i < 3; $i++):
                    while ($loop->have_posts()) : $loop->the_post();
                        global $product;

                        $thumbnail_id = get_post_thumbnail_id();
                        $img_data = wp_get_attachment_image_src($thumbnail_id, 'woocommerce_thumbnail');
                        $img = $img_data ? esc_url($img_data[0]) : esc_url(wc_placeholder_img_src());
                        $price = $product->get_price();
                        $permalink = esc_url(get_permalink());
                        $product_id = get_the_ID();
                        $title = get_the_title();
                ?>

                <div class="tot-card-link">
                    <div class="tot-card">
                        <a href="<?php echo $permalink; ?>" style="text-decoration:none; color:inherit;">
                            <div class="tot-img-box">
                                <div class="tot-badge">YENİ</div>
                                <img src="<?php echo $img; ?>" alt="<?php echo esc_attr($title); ?>" loading="lazy">
                            </div>
                        </a>

                        <div class="tot-timer" data-type="kargo-timer">00:00:00</div>

                        <div class="tot-trust-banner">
                            <div class="tot-trust-msg">KARGO ALICI ÖDER</div>
                        </div>

                        <a href="<?php echo $permalink; ?>" style="text-decoration:none; color:inherit;">
                            <div class="tot-name"><?php echo esc_html($title); ?></div>
                            <div class="tot-price">₺<?php echo number_format((float)$price, 2, ',', '.'); ?></div>
                        </a>

                        <button class="tot-add-btn" data-product-id="<?php echo $product_id; ?>">
                            SEPETE EKLE
                        </button>
                    </div>
                </div>

                <?php
                    endwhile;
                    $loop->rewind_posts();
                endfor;
                wp_reset_postdata();
                ?>
            </div>
        </div>
    </div>

    <script>
    (function() {
        const trustMessages = ['KARGO ALICI ÖDER', 'HIZLI DESTEK', 'GÜVENLİ ÖDEME'];
        let msgIndex = 0;

        function updateTimers() {
            const now = new Date();
            const day = now.getDay();
            const hours = now.getHours();
            const timers = document.querySelectorAll('[data-type="kargo-timer"]');

            const isClosed = (day === 6 && hours >= 16) || day === 0;
            let display = "";

            if (isClosed) {
                display = "PAZAR KARGO KAPALI";
            } else {
                let target = new Date();
                target.setHours(16, 0, 0, 0);

                if (now > target) {
                    target.setDate(target.getDate() + 1);
                    if (target.getDay() === 0) target.setDate(target.getDate() + 1);
                }

                const diff = target - now;
                const h = Math.floor(diff / 3600000).toString().padStart(2, '0');
                const m = Math.floor((diff / 60000) % 60).toString().padStart(2, '0');
                const s = Math.floor((diff / 1000) % 60).toString().padStart(2, '0');
                display = `SON KARGO: ${h}:${m}:${s}`;
            }

            timers.forEach(t => {
                t.innerText = display;
                t.classList.toggle('closed', isClosed);
            });

            // Trust mesajlarını güncelle
            msgIndex = (msgIndex + 1) % trustMessages.length;
            document.querySelectorAll('.tot-trust-msg').forEach(m => {
                m.innerText = trustMessages[msgIndex];
            });
        }

        setInterval(updateTimers, 1000);
        updateTimers();

        // Carousel scroll mantığı
        const track = document.getElementById('totCarousel');
        if (!track) return;

        let isDown = false;
        let startX, scrollLeft;

        track.addEventListener('mousedown', (e) => {
            isDown = true;
            startX = e.pageX - track.offsetLeft;
            scrollLeft = track.scrollLeft;
            track.style.scrollBehavior = 'auto';
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

        // Otomatik scroll
        let autoScroll = setInterval(() => {
            if (!isDown) {
                track.scrollLeft += 0.5;
                if (track.scrollLeft >= (track.scrollWidth / 3) * 2) {
                    track.scrollLeft = track.scrollWidth / 3;
                }
            }
        }, 20);

        track.addEventListener('mouseenter', () => clearInterval(autoScroll));
        track.addEventListener('mouseleave', () => {
            autoScroll = setInterval(() => {
                if (!isDown) {
                    track.scrollLeft += 0.5;
                    if (track.scrollLeft >= (track.scrollWidth / 3) * 2) {
                        track.scrollLeft = track.scrollWidth / 3;
                    }
                }
            }, 20);
        });

        // Sepete ekleme
        document.querySelectorAll('.tot-add-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const productId = this.getAttribute('data-product-id');

                this.innerHTML = "EKLENİYOR...";
                this.disabled = true;

                const formData = new FormData();
                formData.append('add-to-cart', productId);

                fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                })
                .then(() => {
                    this.innerHTML = "SEPETE EKLENDİ ✓";
                    this.classList.add('added');

                    if (typeof jQuery !== 'undefined') {
                        jQuery(document.body).trigger('added_to_cart');
                    }

                    setTimeout(() => {
                        this.innerHTML = "SEPETE EKLE";
                        this.classList.remove('added');
                        this.disabled = false;
                    }, 1500);
                })
                .catch(() => {
                    this.innerHTML = "HATA OLUŞTU";
                    setTimeout(() => {
                        this.innerHTML = "SEPETE EKLE";
                        this.disabled = false;
                    }, 1500);
                });
            });
        });

        // Başlangıç scroll pozisyonu
        window.addEventListener('load', () => {
            if (track) track.scrollLeft = track.scrollWidth / 3;
        });
    })();
    </script>

    <?php
    return ob_get_clean();
}

// Fontları tek seferde yükle
add_action('wp_enqueue_scripts', 'totbagss_enqueue_fonts', 5);
function totbagss_enqueue_fonts() {
    wp_enqueue_style(
        'tot-google-fonts',
        'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&family=Bruno+Ace&family=Bruno+Ace+SC&display=swap',
        array(),
        null
    );
}

// Özel CSS - Astra Uyumlu (Moved most static CSS to style.css, but keeping logic here)
add_action('wp_head', 'totbagss_custom_logic_styles', 100);
function totbagss_custom_logic_styles() {
?>
<style>
:root {
    --tot-bordo: #4a083d;
    --tot-bordo-light: #6a0d58;
    --tot-hover: #ffa8c9;
    --tot-light-pink: #fffdfd;
    --tot-pink-border: #fce7ed;
    --tot-glow: rgba(255, 168, 201, 0.4);
    --tot-gold: #ffd700;
    --tot-silver: #c0c0c0;
    --tot-light-text: #666666;
}

/* Base font assignment */
body, h1, h2, h3, h4, h5, h6, p, a, span, button, .woocommerce * {
    font-family: 'Montserrat', sans-serif;
}

/* Menu font assignment */
.main-header-menu a, .ast-main-header-nav .menu-link {
    font-family: 'Bruno Ace', cursive;
}
</style>
<?php
}

// Rozet ekle
add_action('woocommerce_before_shop_loop_item_title', 'totbagss_add_badge', 5);
function totbagss_add_badge() {
    echo '<div class="tot-badge-new" style="position: absolute; top: 6px; left: 6px; background: #4a083d; color: #fff; padding: 2px 6px; border-radius: 4px; font-size: 9px; font-weight: 900; z-index: 10; text-transform: uppercase;">YENİ</div>';
}

// Sayaç ve panel ekle
add_action('woocommerce_before_shop_loop_item_title', 'totbagss_add_timer_panel', 20);
function totbagss_add_timer_panel() {
    global $product;
    $id = $product->get_id();
?>
<div class="tot-static-timer" id="timer-<?php echo esc_attr($id); ?>" style="font-size: 10px; font-weight: 900; color: #4a083d; text-transform: uppercase; margin: 2px 0; text-align: center;">
    <span>KARGO İÇİN:</span><span class="tot-timer-val">00:00:00</span>
</div>

<div class="tot-rotating-panel" id="panel-<?php echo esc_attr($id); ?>" style="background: linear-gradient(135deg, #4a083d 0%, #6a0d58 100%); border-radius: 5px; min-height: 24px; margin: 2px 0; display: flex; align-items: center; justify-content: center; color: #fff; position: relative; overflow: hidden;">
    <div class="tot-panel-item active"><span>KARGO ALICI ÖDER</span></div>
    <div class="tot-panel-item"><span>KOLAY DEĞİŞİM</span></div>
    <div class="tot-panel-item"><span>GÜVENLİ ÖDEME</span></div>
    <div class="tot-panel-item"><span>HIZLI DESTEK</span></div>
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
        if (items.length < 2) return;
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

// Tüm sayfalar üst boşluk
add_action('wp_head', 'totbagss_page_spacing', 100);
function totbagss_page_spacing() {
?>
<style>
.site-content { padding-top: 60px; padding-bottom: 60px; }
.woocommerce .entry-content { padding-top: 40px; padding-bottom: 40px; }
</style>
<?php
}

// Siparişler tablosu buton düzenleme
add_action('wp_head', 'totbagss_order_buttons', 100);
function totbagss_order_buttons() {
    if (!is_account_page()) return;
?>
<style>
.woocommerce-orders-table .button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    height: 36px;
    padding: 0 20px;
    font-size: 12px;
    font-weight: 600;
    border-radius: 6px;
    border: 2px solid;
    transition: all 0.3s;
}
.woocommerce-orders-table .button.view { border-color: #4A083D; color: #4A083D; }
.woocommerce-orders-table .button.pay { border-color: #FB5FAB; color: #FB5FAB; }
</style>
<?php
}

// Görsel Optimizasyonu
add_filter('upload_mimes', function($mimes) {
    $mimes['webp'] = 'image/webp';
    return $mimes;
});
add_filter('jpeg_quality', function() { return 85; });
add_filter('big_image_size_threshold', function() { return 1920; });
add_filter('wp_lazy_loading_enabled', '__return_true');

// Sidebars and account customizations
add_action('after_setup_theme', function() {
    register_sidebar(array(
        'name'          => __('Shop Filter Sidebar', 'astra'),
        'id'            => 'shop-filter-sidebar',
        'before_widget' => '<div id="%1$s" class="widget %2$s wpc-filters-section">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
});

add_filter('woocommerce_account_menu_items', function($items) {
    $items['dashboard'] = 'Profilim';
    return $items;
});

add_action('woocommerce_account_dashboard', function() {
    echo '<div class="premium-welcome" style="margin-bottom: 30px; padding: 20px; background: #fdf6f9; border-left: 4px solid #4A083D; border-radius: 8px;">';
    echo '<h3 style="color: #4A083D; margin-bottom: 10px;">Hoş Geldiniz!</h3>';
    echo '<p>TotBagss ailesinin bir parçası olduğunuz için mutluyuz.</p>';
    echo '</div>';
}, 5);

add_filter('woocommerce_login_redirect', function($redirect, $user) {
    return wc_get_page_permalink('shop');
}, 10, 2);
