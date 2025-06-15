<?php
include __DIR__ . '/includes/header.php';

// Güvenlik: Geçerli bir masa ID'si var mı kontrol et. Yoksa ana sayfaya at.
$masa_id = (int)($_GET['masa_id'] ?? 0);
if ($masa_id === 0) {
    header('Location: index.php');
    exit();
}

// FORM GÖNDERİLDİĞİNDE ÇALIŞACAK BLOK
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? ''; // Hangi butona basıldığını anla.
    try {
        if ($action === 'yeni_siparis') {
            $stmt = $pdo->prepare("CALL sp_YeniSiparisOlustur(?, ?)");
            $stmt->execute([$masa_id, $_POST['garson_id']]);
            $_SESSION['message'] = 'Yeni sipariş başarıyla oluşturuldu.';
            $_SESSION['message_type'] = 'success';
        } elseif ($action === 'urun_ekle') {
            $stmt = $pdo->prepare("CALL sp_SipariseUrunEkle(?, ?, ?)");
            $stmt->execute([$_POST['siparis_id'], $_POST['urun_id'], $_POST['adet']]);
            // Sayfa yenileceği için burada özel mesaja gerek yok.
        } elseif ($action === 'detay_sil') {
            $stmt = $pdo->prepare("CALL sp_SiparisDetaySil(?)");
            $stmt->execute([$_POST['siparis_detay_id']]);
        } elseif ($action === 'hesap_kapat') {
            $stmt = $pdo->prepare("CALL sp_HesabiKapat(?)");
            $stmt->execute([$_POST['siparis_id']]);
            $_SESSION['message'] = 'Hesap kapatıldı ve masa boşaltıldı.';
            $_SESSION['message_type'] = 'success';
            header("Location: index.php"); // İş bitince ana sayfaya dön.
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['message'] = 'Veritabanı Hatası: ' . $e->getMessage();
        $_SESSION['message_type'] = 'danger';
    }
    // Her işlemden sonra (hesap kapatma hariç), sayfanın güncel halini göstermek için kendini yenile.
    header("Location: siparis.php?masa_id=" . $masa_id);
    exit();
}

// SAYFA İÇERİĞİNİ OLUŞTURMA (Her zaman çalışır)
// Veritabanından masa ve varsa aktif sipariş bilgilerini çek.
$stmt = $pdo->prepare("SELECT m.masaNo, s.siparisID FROM Masalar m LEFT JOIN Siparisler s ON m.masaID = s.masaID AND s.durum NOT IN ('Ödendi', 'İptal') WHERE m.masaID = ?");
$stmt->execute([$masa_id]);
$siparisBilgi = $stmt->fetch();
$stmt->closeCursor();

// Aktif bir sipariş ID'si varsa bir değişkene atayalım. Yoksa null olacak.
$siparis_id = $siparisBilgi['siparisID'];
?>

<!-- SAYFA BAŞLIĞI VE HESAP KAPATMA BUTONU -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Masa: <span class="text-primary fw-bold"><?php echo htmlspecialchars($siparisBilgi['masaNo']); ?></span></h2>
    <?php if ($siparis_id): ?>
    <form method="POST" onsubmit="return confirm('Hesap kapatılacak. Emin misiniz?');">
        <input type="hidden" name="action" value="hesap_kapat">
        <input type="hidden" name="siparis_id" value="<?php echo $siparis_id; ?>">
        <button type="submit" class="btn btn-success btn-lg"><i class="fas fa-cash-register me-2"></i>Hesabı Kapat</button>
    </form>
    <?php endif; ?>
</div>

<div class="row g-4">
    <!-- SOL TARAF: Adisyon Kısmı -->
    <div class="col-lg-7"><div class="card shadow-sm border-secondary"><h5 class="card-header"><i class="fas fa-receipt me-2"></i>Adisyon</h5><div class="card-body">
        <?php if($siparis_id): // EĞER AKTİF SİPARİŞ VARSA BURAYI GÖSTER ?>
            <?php
            // Sipariş detaylarını ve genel bilgileri çek
            $detayStmt = $pdo->prepare("CALL sp_SiparisDetayGetir(?)"); $detayStmt->execute([$siparis_id]); $detaylar = $detayStmt->fetchAll(); $detayStmt->closeCursor();
            $tutarStmt = $pdo->prepare("SELECT g.ad, s.toplamTutar FROM Siparisler s JOIN Garsonlar g ON s.garsonID = g.garsonID WHERE s.siparisID = ?"); $tutarStmt->execute([$siparis_id]); $genelBilgi = $tutarStmt->fetch(); $tutarStmt->closeCursor();
            ?>
            <p><strong>Garson:</strong> <?php echo htmlspecialchars($genelBilgi['ad']); ?></p><hr>
            <table class="table table-hover">
                <thead><tr><th>Ürün</th><th>Adet</th><th class="text-end">Fiyat</th><th class="text-end">Toplam</th><th class="text-end">Sil</th></tr></thead>
                <tbody>
                <?php foreach($detaylar as $detay): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($detay['urunAdi']);?></td><td><?php echo $detay['adet'];?></td>
                        <td class='text-end'><?php echo number_format($detay['birimFiyat'],2,',','.');?>₺</td>
                        <td class='text-end fw-bold'><?php echo number_format($detay['araToplam'],2,',','.');?>₺</td>
                        <td class='text-end'>
                            <form method="POST" onsubmit="return confirm('Ürünü siliyorsunuz, emin misiniz?');">
                                <input type="hidden" name="action" value="detay_sil">
                                <input type="hidden" name="siparis_detay_id" value="<?php echo $detay['siparisDetayID']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-times"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table><hr>
            <div class="d-flex justify-content-end align-items-center"><h4 class="me-3">TOPLAM:</h4><h4 class="text-success fw-bold"><?php echo number_format($genelBilgi['toplamTutar'],2,',','.');?>₺</h4></div>
        <?php else: // EĞER AKTİF SİPARİŞ YOKSA BURAYI GÖSTER ?>
            <div class='alert alert-info text-center'>Bu masada aktif bir sipariş bulunmuyor.</div>
        <?php endif; ?>
    </div></div></div>

    <!-- SAĞ TARAF: Formlar Kısmı -->
    <div class="col-lg-5"><div class="card shadow-sm border-secondary">
        <?php if($siparis_id): // EĞER AKTİF SİPARİŞ VARSA ÜRÜN EKLEME FORMUNU GÖSTER ?>
            <h5 class="card-header"><i class="fas fa-plus-circle me-2"></i>Ürün Ekle</h5><div class="card-body">
            <form method="POST">
                <input type="hidden" name="action" value="urun_ekle">
                <input type="hidden" name="siparis_id" value="<?php echo $siparis_id; ?>">
                <div class="mb-3"><label class="form-label">Ürün</label><select class="form-select" name="urun_id" required><option value="" selected disabled>Seçiniz...</option><?php $stmt = $pdo->query("SELECT urunID, urunAdi FROM Urunler WHERE stokDurumu = 1"); foreach ($stmt->fetchAll() as $urun) echo "<option value='{$urun['urunID']}'>".htmlspecialchars($urun['urunAdi'])."</option>"; $stmt->closeCursor();?></select></div>
                <div class="mb-3"><label class="form-label">Adet</label><input type="number" class="form-control" name="adet" value="1" min="1"></div>
                <button type="submit" class="btn btn-primary w-100">Ekle</button>
            </form></div>
        <?php else: // EĞER AKTİF SİPARİŞ YOKSA YENİ SİPARİŞ FORMUNU GÖSTER ?>
            <h5 class="card-header"><i class="fas fa-file-alt me-2"></i>Yeni Sipariş Oluştur</h5><div class="card-body">
            <form method="POST">
                <input type="hidden" name="action" value="yeni_siparis">
                <div class="mb-3"><label class="form-label">Garson</label><select class="form-select" name="garson_id" required><option value="" selected disabled>Seçiniz...</option><?php $stmt = $pdo->query("CALL sp_GarsonlariListele()"); foreach ($stmt->fetchAll() as $garson) echo "<option value='{$garson['garsonID']}'>{$garson['ad']} {$garson['soyad']}</option>"; $stmt->closeCursor();?></select></div>
                <button type="submit" class="btn btn-success w-100">Siparişi Başlat</button>
            </form></div>
        <?php endif; ?>
    </div></div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>