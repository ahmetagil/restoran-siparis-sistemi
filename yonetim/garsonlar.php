<?php // C:\xampp\htdocs\restoran\yonetim\garsonlar.php
include __DIR__ . '/../includes/header.php';

// FORM İŞLEMLERİ: Ekleme ve Silme
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    try {
        if ($action === 'ekle') {
            $tarih = !empty($_POST['iseGirisTarihi']) ? $_POST['iseGirisTarihi'] : null;
            $stmt = $pdo->prepare("CALL sp_GarsonEkle(?, ?, ?)");
            $stmt->execute([$_POST['ad'], $_POST['soyad'], $tarih]);
            $_SESSION['success_message'] = "Garson başarıyla eklendi.";
        } elseif ($action === 'sil') {
            $stmt = $pdo->prepare("CALL sp_GarsonSil(?)");
            $stmt->execute([$_POST['garsonID']]);
            $_SESSION['success_message'] = "Garson başarıyla silindi.";
        }
        // Not: Garson güncelleme saklı yordamı eklenirse, buraya 'guncelle' case'i de eklenebilir.
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "İşlem sırasında bir hata oluştu: " . $e->getMessage();
    }
    header("Location: garsonlar.php");
    exit();
}

// Veritabanından mevcut garsonları çek
$garsonlar = $pdo->query("CALL sp_GarsonlariListele()")->fetchAll();
?>

<h1 class="mb-4">Garson Yönetimi</h1>
<div class="row">
    <!-- Yeni Garson Ekleme Formu -->
    <div class="col-md-4">
        <div class="card bg-dark border-secondary">
            <h5 class="card-header">Yeni Garson Ekle</h5>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="action" value="ekle">
                    <div class="mb-3">
                        <label class="form-label">Adı</label>
                        <input type="text" class="form-control" name="ad" required>
                    </div>
                     <div class="mb-3">
                        <label class="form-label">Soyadı</label>
                        <input type="text" class="form-control" name="soyad" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">İşe Giriş Tarihi</label>
                        <input type="date" class="form-control" name="iseGirisTarihi">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Ekle</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Mevcut Garsonlar Listesi -->
    <div class="col-md-8">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Adı Soyadı</th>
                    <th>İşe Giriş Tarihi</th>
                    <th class="text-end">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($garsonlar as $garson): ?>
                    <tr>
                        <td><?php echo $garson['garsonID']; ?></td>
                        <td><?php echo htmlspecialchars($garson['ad'] . ' ' . $garson['soyad']); ?></td>
                        <td><?php echo !empty($garson['iseGirisTarihi']) ? date('d.m.Y', strtotime($garson['iseGirisTarihi'])) : '-'; ?></td>
                        <td class="text-end">
                            <!-- Garson güncelleme için de benzer bir modal yapısı kurulabilir -->
                            <a href="#" class="btn btn-sm btn-info disabled" title="Düzenle"><i class="fas fa-edit"></i></a>
                            <form method="POST" class="d-inline" onsubmit="return confirm('Emin misiniz?');">
                                <input type="hidden" name="action" value="sil">
                                <input type="hidden" name="garsonID" value="<?php echo $garson['garsonID']; ?>">
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>