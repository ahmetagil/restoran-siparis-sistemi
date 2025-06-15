// C:\xampp\htdocs\restoran\assets\js\custom.js (Mutfak için güncellenmiş hali)

$(document).ready(function() {
    
    // Sadece "mutfak-ekrani" ID'sine sahip bir div'in olduğu sayfada çalış.
    // Bu, kodun başka sayfalarda gereksiz yere çalışmasını engeller.
    var mutfakEkrani = $('#mutfak-ekrani');
    if (mutfakEkrani.length) {

        // mutfakGuncelle adında bir fonksiyon tanımla.
        function mutfakGuncelle() {
            // Arka planda ajax/mutfak_guncelle.php dosyasına bir istek gönder.
            $.ajax({
                url: '/restoran/ajax/mutfak_guncelle.php', // İstek yapılacak adres
                success: function(data) {
                    // İstek başarılı olursa, gelen HTML verisini al ve 
                    // #mutfak-ekrani div'inin içeriğiyle tamamen değiştir.
                    mutfakEkrani.html(data);
                },
                error: function() {
                    // Bir hata olursa, kullanıcıyı bilgilendir.
                    mutfakEkrani.html('<div class="col"><div class="alert alert-danger">Mutfak ekranı güncellenemedi. Sunucuyla bağlantı kesilmiş olabilir.</div></div>');
                }
            });
        }

        // Sayfa ilk yüklendiğinde fonksiyonu bir kez çalıştır.
        mutfakGuncelle();
        
        // Ardından her 5000 milisaniyede (5 saniyede) bir tekrar çalıştır.
        setInterval(mutfakGuncelle, 5000);
    }

});