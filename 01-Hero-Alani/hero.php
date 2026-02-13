<?php
/**
 * Hero Alanı Carousel Shortcode
 */
add_shortcode('tot_carousel', 'totbagss_hero_carousel');
function totbagss_hero_carousel($atts) {
    $a = shortcode_atts(array('cat' => 'yeni-gelenler', 'limit' => 6), $atts);
    $q = new WP_Query(array('post_type' => 'product', 'posts_per_page' => $a['limit'], 'product_cat' => $a['cat']));

    ob_start();
    if ($q->have_posts()) : ?>
    <div class="tot-carousel-wrapper">
        <div class="tot-carousel-container no-scrollbar" id="totCarousel">
            <div class="tot-carousel-track">
                <?php while ($q->have_posts()) : $q->the_post(); global $product;
                    $img = wp_get_attachment_image_src($product->get_image_id(), 'woocommerce_thumbnail');
                    $img_url = $img ? $img[0] : wc_placeholder_img_src();
                ?>
                <div class="tot-card-link">
                    <div class="tot-card">
                        <a href="<?php the_permalink(); ?>" style="text-decoration:none; color:inherit;">
                            <div class="tot-img-box">
                                <img src="<?php echo $img_url; ?>" alt="<?php the_title(); ?>" loading="lazy">
                            </div>
                            <div class="tot-name"><?php the_title(); ?></div>
                            <div class="tot-price">₺<?php echo number_format($product->get_price(), 2, ',', '.'); ?></div>
                        </a>
                        <button class="tot-add-btn" data-product-id="<?php the_ID(); ?>">SEPETE EKLE</button>
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
        let isDown = false; let startX, scrollLeft;
        track.addEventListener('mousedown', (e) => { isDown = true; startX = e.pageX - track.offsetLeft; scrollLeft = track.scrollLeft; });
        track.addEventListener('mouseleave', () => isDown = false);
        track.addEventListener('mouseup', () => isDown = false);
        track.addEventListener('mousemove', (e) => { if (!isDown) return; e.preventDefault(); const x = e.pageX - track.offsetLeft; const walk = (x - startX) * 2; track.scrollLeft = scrollLeft - walk; });

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
                    setTimeout(() => { this.innerText = 'SEPETE EKLE'; this.style.background = '#4A083D'; }, 2000);
                });
            });
        });
    })();
    </script>
    <?php endif;
    return ob_get_clean();
}
