<?php include __DIR__ . '/includes/header.php'; ?>
<div class="px-4 py-3 text-center"><h1 class="display-4 logo">MUTFAK EKRANI</h1><p class="lead mb-4 text-white-50">Aktif siparişler burada listelenir. Bu ekran her 5 saniyede bir otomatik olarak güncellenir.</p></div>
<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="mutfak-ekrani"></div>
<script>
$(document).ready(function() {
    function mutfakGuncelle() {
        $.ajax({
            url: 'ajax/mutfak_guncelle.php',
            success: function(data) { $('#mutfak-ekrani').html(data); }
        });
    }
    mutfakGuncelle();
    setInterval(mutfakGuncelle, 5000);
});
</script>
<?php include __DIR__ . '/includes/footer.php'; ?>