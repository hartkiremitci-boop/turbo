/**
 * TotBagss Genel JavaScript Fonksiyonları
 */
document.addEventListener('DOMContentLoaded', function() {
    // Sayfa yüklendiğinde görseller için yumuşak geçiş
    const images = document.querySelectorAll('img');
    images.forEach(img => {
        if (img.complete) {
            img.style.opacity = '1';
        } else {
            img.addEventListener('load', () => {
                img.style.opacity = '1';
            });
        }
    });

    // Harici linkleri yeni sekmede aç
    const links = document.querySelectorAll('a[href^="http"]');
    links.forEach(link => {
        if (!link.href.includes(window.location.host)) {
            link.setAttribute('target', '_blank');
            link.setAttribute('rel', 'noopener noreferrer');
        }
    });

    // Mobil menü dokunmatik iyileştirmesi
    if (window.innerWidth < 768) {
        document.body.classList.add('is-mobile');
    }
});
