<?php
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
    <div class="tot-static-timer" id="timer-<?php echo $id; ?>">
        <span>KARGO İÇİN:</span> <span class="tot-timer-val">00:00:00</span>
    </div>

    <div class="tot-rotating-panel" id="panel-<?php echo $id; ?>">
        <div class="tot-panel-item active"><span>AYNI GÜN KARGO</span></div>
        <div class="tot-panel-item" style="display:none;"><span>GÜVENLİ ÖDEME</span></div>
        <div class="tot-panel-item" style="display:none;"><span>%100 ORİJİNAL</span></div>
        <div class="tot-panel-item" style="display:none;"><span>KOLAY İADE</span></div>
    </div>

    <script>
    (function() {
        const id = '<?php echo $id; ?>';
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
    <?php
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
