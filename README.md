
### GEREKSİNİMLER
---
Projenin çalışması için bilgisayarınızda XAMPP Kontrol Paneli'nin kurulu olması yeterlidir.
(https://www.apachefriends.org/tr/index.html)


---
### KURULUM ADIMLARI
---

Lütfen aşağıdaki adımları sırayla takip ediniz.

**ADIM 1: Proje Dosyalarının Kopyalanması**

1.  Bu kılavuzun bulunduğu dizindeki `restoran` klasörünün tamamını kopyalayın.
2.  Bilgisayarınızdaki XAMPP kurulum dizinine giderek `htdocs` klasörünü açın. (Genellikle `C:\xampp\htdocs\`)
3.  Kopyaladığınız `restoran` klasörünü bu `htdocs` klasörünün içine yapıştırın.
    *   Sonuçta dosya yolunuz `C:\xampp\htdocs\restoran\` şeklinde olmalıdır.


**ADIM 2: XAMPP'nin Başlatılması**

1.  XAMPP Kontrol Paneli'ni çalıştırın.
2.  **Apache** ve **MySQL** modüllerinin yanındaki "Start" butonlarına basarak servisleri başlatın. Her ikisinin de yeşil renkte olduğundan emin olun.


**ADIM 3: Veritabanının Kurulması (İçe Aktarma Yöntemi)**

1.  Web tarayıcınızı açın ve adres çubuğuna `http://localhost/phpmyadmin/` yazarak phpMyAdmin arayüzüne gidin.
2.  Sol üst köşedeki **"Yeni" (New)** butonuna tıklayın.
3.  Veritabanı adı olarak `restoran_db` yazın.
4.  Karşılaştırma (Collation) menüsünden `utf8mb4_turkish_ci` seçeneğini seçin ve "Oluştur" (Create) butonuna basın.
5.  Veritabanı oluşturulduktan sonra, sol menüden yeni oluşturduğunuz `restoran_db` veritabanının üzerine tıklayarak onu seçin.
6.  Üst menüden **"İçe Aktar" (Import)** sekmesine tıklayın.
7.  "Dosya Seç" (Choose File) butonuna tıklayın ve proje dosyaları içinde bulunan `restoran_db.sql` yedek dosyasını seçin.
8.  Başka hiçbir ayarı değiştirmeden sayfanın en altındaki **"İçe Aktar" (Import/Go)** butonuna basın.


Bu işlemin sonunda yeşil bir onay mesajı görmelisiniz. Bu, veritabanınızın tabloları, test verileri, fonksiyonları, tetikleyicileri ve saklı yordamlarıyla birlikte başarıyla kurulduğu anlamına gelir.


**ADIM 4: Uygulamanın Çalıştırılması**

1.  Web tarayıcınızı açın.
2.  Adres çubuğuna `http://localhost/restoran/` yazın ve Enter'a basın.

Uygulamanın ana sayfası (Masalar ekranı) karşınıza gelecektir. Proje artık kullanıma hazırdır.

Saygılarımla,
[Ahmet Ağıl]
