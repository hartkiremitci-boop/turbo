# TotBagss Premium Child Theme & Setup Guide

Bu depo, TotBagss e-ticaret sitesini tamamen "Pro" bir görünüme kavuşturmak için hazırlanan özel kodları ve kurulum rehberini içerir.

## Dosyalar

- `style.css`: Tüm premium tasarım (Hero, Shop Header, Ürün Kartları, Sepet ve Hesap sayfaları).
- `functions.php`: Gelişmiş ürün karuseli, kargo sayaçları, dinamik paneller ve WooCommerce özelleştirmeleri.
- `simple-custom.css`: Sipariş takip sayfası gibi belirli alanlar için ek özelleştirmeler.

## Önemli Özellikler

- **Premium Shop Header:** Shop sayfasında kategori chip'leri ve modern sıralama menüsü.
- **Enhanced Product Cards:** Parlama efekti, dönen bilgi paneli ve kargo sayacı entegre edildi.
- **Improved Carousel:** Mobil uyumlu, akıcı kaydırma ve "Zamansız Şıklık" geçiş kartı.
- **Conflict Resolution:** Sepet butonu ve Brevo butonu çakışmaları giderildi.

---

## Brevo & WooCommerce Bağlantı Rehberi (Step-by-Step)

Brevo'yu (eski adıyla Sendinblue) WooCommerce ödeme sistemine bağlamak ve profesyonel bir e-posta pazarlama altyapısı kurmak için şu adımları izleyin:

### 1. Brevo Eklentisini Kurun
- WordPress panelinizde **Eklentiler > Yeni Ekle** kısmına gidin.
- "Brevo for WooCommerce" aramasını yapın ve eklentiyi kurup etkinleştirin.

### 2. API Anahtarını Alın
- [Brevo](https://www.brevo.com/) hesabınıza giriş yapın.
- Sağ üstteki profil menüsünden **SMTP & API** seçeneğine tıklayın.
- **Create a new API key** butonuna basın, isimlendirin ve anahtarı kopyalayın.

### 3. WordPress'e Bağlayın
- WordPress menüsünde çıkan **Brevo** sekmesine gidin.
- Kopyaladığınız API anahtarını buraya yapıştırın ve **Bağlan** deyin.

### 4. WooCommerce Entegrasyonunu Aktif Edin
- Brevo ayarları içinde **WooCommerce** sekmesini bulun.
- "Enable WooCommerce tracking" ve "Sync customers" seçeneklerini aktif edin.
- Bu sayede sepetini terk eden müşterilere otomatik e-postalar gönderebilirsiniz.

### 5. Ödeme (Checkout) ile Eşleştirme
- "Opt-in field" seçeneğini aktif ederek, ödeme sayfasında müşterilerin bültene kayıt olmasını sağlayın.
- Bu işlem, `functions.php` içine eklediğimiz premium stil kodlarıyla otomatik olarak güzelleşecektir.

---

## Kurulum Talimatları

1. `style.css` içeriğini WordPress temanızın ana CSS dosyasına veya Customizer'daki "Ek CSS" kısmına ekleyin.
2. `functions.php` içeriğini temanızın `functions.php` dosyasına veya "Code Snippets" eklentisiyle yeni bir snippet olarak ekleyin.
3. `simple-custom.css` içeriğini "Simple Custom CSS" gibi bir eklentiye veya temanızın özel CSS alanına ekleyin.
