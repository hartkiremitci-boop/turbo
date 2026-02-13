<?php
/**
 * Hesabım ve Sepet Sayfası Özelleştirmeleri
 */
add_filter('woocommerce_account_menu_items', function($items) {
    $items['dashboard'] = 'Profilim';
    return $items;
});

add_action('woocommerce_account_dashboard', function() {
    echo '<div class="premium-welcome">';
    echo '<h3>Hoş Geldiniz!</h3>';
    echo '<p>TotBagss ailesinin bir parçası olduğunuz için mutluyuz. Siparişlerinizi ve profilinizi buradan yönetebilirsiniz.</p>';
    echo '</div>';
}, 5);

add_filter('woocommerce_login_redirect', function($redirect, $user) { return wc_get_page_permalink('shop'); }, 10, 2);

add_action('woocommerce_account_dashboard', 'totbagss_premium_dashboard_stats', 10);
function totbagss_premium_dashboard_stats() {
    ?>
    <div class="tot-account-stats">
        <div class="stat-card">
            <div class="stat-label">Üyelik</div>
            <div class="stat-value">PREMIUM ÜYE</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Destek</div>
            <div class="stat-value">7/24 ÖNCELİKLİ</div>
        </div>
    </div>
    <?php
}
