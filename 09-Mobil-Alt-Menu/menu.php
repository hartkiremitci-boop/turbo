<?php
add_action('wp_footer', function() {
    if (!wp_is_mobile()) return;
    ?>
    <div class="tot-mobile-nav">
        <a href="<?php echo home_url(); ?>" class="nav-item">
            <i class="ast-icon-home"></i>
            <span>Ana Sayfa</span>
        </a>
        <a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>" class="nav-item">
            <i class="ast-icon-shopping-bag"></i>
            <span>Mağaza</span>
        </a>
        <a href="<?php echo wc_get_cart_url(); ?>" class="nav-item">
            <i class="ast-icon-shopping-cart"></i>
            <span>Sepet</span>
        </a>
        <a href="<?php echo get_permalink(get_option('woocommerce_myaccount_page_id')); ?>" class="nav-item">
            <i class="ast-icon-account_circle"></i>
            <span>Hesabım</span>
        </a>
    </div>
    <?php
});
