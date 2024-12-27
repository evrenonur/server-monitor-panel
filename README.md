# 🚀 Sunucu İzleme Sistemi

Modern ve güçlü sunucu yönetim platformu

## 💡 Proje Hakkında

Bu platform, sunucularınızı gerçek zamanlı olarak izlemenizi ve yönetmenizi sağlayan güçlü bir web uygulamasıdır. Gelişmiş metrikler ve kullanıcı dostu arayüzü ile sunucu yönetimini basitleştirir.

## ✨ Özellikler

* **📊 Kaynak İzleme**: CPU, RAM ve Disk kullanımını gerçek zamanlı takip edin
* **⚡ Süreç Yönetimi**: Çalışan süreçleri görüntüleyin ve yönetin
* **⚙️ Servisler**: Servisleri görüntüleyin ve yönetin
* **🔄 Güncellemeler**: Sistem güncellemelerini takip edin
* **📈 Metrikler**: Detaylı performans grafikleri ve raporlar

## 🛠️ Teknolojiler

* Laravel Framework
* MySQL
* AdminLTE
* Chart.js
* DataTables

## ⚙️ API Endpointleri

**POST** `/api/system-info`
Sistem bilgilerini kaydetmek için kullanılır. Authorization header'ında API key gereklidir.

## 🚀 Kurulum

1. `git clone [proje-url]` - Projeyi yerel makinenize klonlayın
2. `composer install` - Gerekli bağımlılıkları yükleyin
3. `cp .env.example .env` - Çevre değişkenlerini yapılandırın
4. `php artisan migrate` - Veritabanı tablolarını oluşturun
5. `php artisan db:seed` - Örnek verileri ekleyin
6. `/admin/login` - Admin paneline giriş yapın
   * Kullanıcı Adı: **admin@admin.com**
   * Şifre: **password**

## 📝 Lisans

Bu proje MIT lisansı altında lisanslanmıştır. Detaylar için LICENSE dosyasına bakın.
