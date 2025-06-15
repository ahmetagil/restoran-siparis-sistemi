<?php // C:\xampp\htdocs\restoran\index.php
include __DIR__ . '/includes/header.php';
$masalar = $pdo->query("CALL sp_MasalariListele()")->fetchAll();
?>
<div class="px-4 py-5 text-center"><h1 class="display-4 text-primary">RESTORAN YÖNETİM SİSTEMİ</h1><div class="col-lg-8 mx-auto"><p class="lead mb-4 text-white-50">Aşağıdan bir masa seçerek yeni sipariş oluşturabilir veya mevcut siparişleri yönetebilirsiniz.</p></div></div>
<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
    <?php foreach ($masalar as $masa): ?>
        <div class="col">
            <a href="siparis.php?masa_id=<?php echo $masa['masaID']; ?>" class="text-decoration-none">
                <div class="card text-white h-100 masa-karti">
                    <div class="card-body text-center d-flex flex-column justify-content-center p-4">
                        <i class="fas <?php echo $masa['durum'] == 'Dolu' ? 'fa-users' : 'fa-check-circle'; ?> fa-3x mb-3 text-<?php echo $masa['durum'] == 'Dolu' ? 'danger' : 'success'; ?>"></i>
                        <h4 class="card-title"><?php echo htmlspecialchars($masa['masaNo']); ?></h4>
                        <span class="badge bg-secondary align-self-center"><?php echo htmlspecialchars($masa['kapasite']); ?> Kişilik</span>
                    </div>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>