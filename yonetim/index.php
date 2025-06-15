<?php // C:\xampp\htdocs\restoran\yonetim\index.php
include __DIR__ . '/../includes/header.php';
// İstatistikleri çek
$toplamUrun = $pdo->query("SELECT COUNT(*) FROM Urunler")->fetchColumn();
$toplamKategori = $pdo->query("SELECT COUNT(*) FROM Kategoriler")->fetchColumn();
$toplamGarson = $pdo->query("SELECT COUNT(*) FROM Garsonlar")->fetchColumn();
$aktifSiparis = $pdo->query("SELECT COUNT(*) FROM Siparisler WHERE durum != 'Ödendi' AND durum != 'İptal'")->fetchColumn();
?>
<h1 class="text-center mb-5 text-primary">Yönetim Paneli</h1>
<div class="row text-center">
    <div class="col-md-3 mb-4"><div class="card bg-dark p-3"><h3 class="text-primary"><?php echo $toplamUrun; ?></h3><p>Toplam Ürün</p></div></div>
    <div class="col-md-3 mb-4"><div class="card bg-dark p-3"><h3 class="text-primary"><?php echo $toplamKategori; ?></h3><p>Toplam Kategori</p></div></div>
    <div class="col-md-3 mb-4"><div class="card bg-dark p-3"><h3 class="text-primary"><?php echo $toplamGarson; ?></h3><p>Toplam Garson</p></div></div>
    <div class="col-md-3 mb-4"><div class="card bg-dark p-3"><h3 class="text-primary"><?php echo $aktifSiparis; ?></h3><p>Aktif Sipariş</p></div></div>
</div>

<div class="list-group mt-4">
    <a href="urunler.php" class="list-group-item list-group-item-action bg-dark text-light"><i class="fas fa-hamburger fa-fw me-2"></i>Ürün Yönetimi</a>
    <a href="kategoriler.php" class="list-group-item list-group-item-action bg-dark text-light"><i class="fas fa-tags fa-fw me-2"></i>Kategori Yönetimi</a>
    <a href="garsonlar.php" class="list-group-item list-group-item-action bg-dark text-light"><i class="fas fa-user-tie fa-fw me-2"></i>Garson Yönetimi</a>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>