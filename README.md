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
* **🔐 Güvenlik**: Token tabanlı kimlik doğrulama ve yetkilendirme
* **🔄 WebSocket**: Sunucularınızı gerçek zamanlı olarak izlemek için WebSocket kullanıyoruz

## 🛠️ Teknolojiler

* Laravel Framework
* MySQL
* AdminLTE
* Chart.js
* DataTables
* WebSocket
* Node.js
* WebSSH2



## ⚙️ API Endpointleri

**POST** `/api/system-info`
Sistem bilgilerini kaydetmek için kullanılır. Authorization header'ında API key gereklidir.

**GET** `/api/validate-token`
Token doğrulama için kullanılır. Authorization header'ında token gereklidir.



## 🚀 Kurulum Laravel

1. `git clone [proje-url]` - Projeyi yerel makinenize klonlayın
2. `composer install` - Gerekli bağımlılıkları yükleyin
3. `cp .env.example .env` - Çevre değişkenlerini yapılandırın
4. `php artisan migrate` - Veritabanı tablolarını oluşturun
5. `php artisan db:seed` - Örnek verileri ekleyin
6. `/admin/login` - Admin paneline giriş yapın
   * Kullanıcı Adı: **admin@admin.com**
   * Şifre: **password**

## 🚀 Kurulum Node.js
2. `npm install` - Gerekli bağımlılıkları yükleyin
3. `node websocket-server.js` - WebSocket sunucusunu başlatın

## 📝 Lisans

Bu proje MIT lisansı altında lisanslanmıştır. Detaylar için LICENSE dosyasına bakın.
