<?php
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
        <?php foreach ($categories as $cat) :
            $thumbnail_id = get_term_meta($cat->term_id, 'thumbnail_id', true);
            $image = wp_get_attachment_url($thumbnail_id);
            if (!$image) $image = wc_placeholder_img_src();
        ?>
            <a href="<?php echo get_term_link($cat); ?>" class="cat-item">
                <div class="cat-img-wrapper">
                    <img src="<?php echo $image; ?>" alt="<?php echo $cat->name; ?>">
                </div>
                <span><?php echo $cat->name; ?></span>
            </a>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
}
