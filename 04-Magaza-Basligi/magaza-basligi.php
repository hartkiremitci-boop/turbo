<?php
/**
 * Premium Mağaza Başlığı (Kategoriler & Sıralama)
 */
add_action('woocommerce_before_shop_loop', 'totbagss_premium_shop_header', 15);
function totbagss_premium_shop_header() {
    if (!is_shop() && !is_product_category()) return;

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
