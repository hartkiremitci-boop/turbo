<?php
add_action('wp_footer', function() {
    if (!is_product()) return;
    global $product;
    ?>
    <div class="tot-sticky-add-to-cart">
        <div class="sticky-container">
            <div class="sticky-info">
                <span class="sticky-title"><?php the_title(); ?></span>
                <span class="sticky-price"><?php echo $product->get_price_html(); ?></span>
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
    <?php
});
