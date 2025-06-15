<?php
// C:\xampp\htdocs\restoran\ajax\mutfak_guncelle.php
require_once __DIR__ . '/../includes/db.php';

try {
    $aktifSiparislerStmt = $pdo->query("CALL sp_AktifSiparisleriListele()");
    $aktifSiparisler = $aktifSiparislerStmt->fetchAll();
    $aktifSiparislerStmt->closeCursor();

    if (count($aktifSiparisler) > 0) {
        foreach ($aktifSiparisler as $siparis) {
            ?>
            <div class="col"><div class="card shadow-sm mutfak-karti mb-3 bg-dark border-secondary">
                <div class="card-header fw-bold d-flex justify-content-between bg-black text-primary">
                    <span>Masa: <?php echo htmlspecialchars($siparis['masaNo']); ?></span>
                    <span class="badge bg-warning text-dark align-self-center"><?php echo htmlspecialchars($siparis['durum']); ?></span>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <?php
                        $detayStmt = $pdo->prepare("CALL sp_SiparisDetayGetir(?)");
                        $detayStmt->execute([$siparis['siparisID']]);
                        $detaylar = $detayStmt->fetchAll();
                        $detayStmt->closeCursor();

                        foreach($detaylar as $detay):
                        ?>
                        <li class="list-group-item bg-dark d-flex justify-content-between align-items-center px-0">
                            <span class="fw-bold"><?php echo htmlspecialchars($detay['urunAdi']); ?></span>
                            <span class="badge bg-primary rounded-pill fs-6"><?php echo $detay['adet']; ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="card-footer text-white-50 text-end">
                    Garson: <?php echo htmlspecialchars($siparis['garsonAdi']); ?>
                </div>
            </div></div>
            <?php
        }
    } else {
        echo '<div class="col-12 text-center mt-5"><div class="display-1 text-success"><i class="fas fa-mug-hot"></i></div><h4 class="mt-3">Mutfak Temiz!</h4><p class="text-white-50">Bekleyen yeni sipariş bulunmamaktadır.</p></div>';
    }
} catch (PDOException $e) {
    echo '<div class="col-12"><div class="alert alert-danger"><strong>Veritabanı Hatası!</strong> Mutfak verileri alınamadı. Hata: ' . $e->getMessage() . '</div></div>';
}
?>