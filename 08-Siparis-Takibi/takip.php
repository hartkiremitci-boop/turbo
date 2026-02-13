<?php
/**
 * Sipariş Takibi Özelleştirmeleri
 */
add_filter('woocommerce_account_menu_items', function($items) {
    // Sipariş takibi linkini hesaba ekle veya düzenle
    return $items;
}, 20);
