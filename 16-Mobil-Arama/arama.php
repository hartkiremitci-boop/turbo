<?php
/**
 * Mobil Hızlı Arama Barı
 */
add_action('wp_body_open', 'totbagss_mobile_search_bar');
function totbagss_mobile_search_bar() {
    if (!wp_is_mobile() || !is_front_page()) return;
    ?>
    <div class="tot-mobile-search-wrapper">
        <form role="search" method="get" class="woocommerce-product-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
            <input type="search" class="search-field" placeholder="Ürün ara..." value="<?php echo get_search_query(); ?>" name="s" />
            <button type="submit" value="Ara"><i class="ast-icon-search"></i></button>
            <input type="hidden" name="post_type" value="product" />
        </form>
    </div>
    <?php
}
