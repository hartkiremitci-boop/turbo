<?php
/**
 * WooCommerce Rozet, Sayaç ve Panel Logic
 */
add_action('woocommerce_before_shop_loop_item_title', 'totbagss_add_badge', 5);
function totbagss_add_badge() {
    echo '<div class="tot-badge-new">YENİ</div>';
}

add_action('woocommerce_before_shop_loop_item_title', 'totbagss_add_timer_panel', 20);
function totbagss_add_timer_panel() {
    global $product;
    $id = $product->get_id();
?>
<div class="tot-static-timer" id="timer-<?php echo esc_attr($id); ?>">
    <span>KARGO İÇİN:</span> <span class="tot-timer-val">00:00:00</span>
</div>

<div class="tot-rotating-panel" id="panel-<?php echo esc_attr($id); ?>">
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
