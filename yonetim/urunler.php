<?php // C:\xampp\htdocs\restoran\yonetim\urunler.php (HATASIZ HALİ)
// __DIR__ . '/../' -> Bu dosyanın bulunduğu klasörden (yonetim) bir üste çık (restoran) ve oradan devam et.
include __DIR__ . '/../includes/header.php';

// Form işlemleri... (Bu kısım önceki mesajdakiyle aynı ve doğru)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    try {
        if ($action === 'ekle') {
            $stmt = $pdo->prepare("CALL sp_UrunEkle(?, ?, ?)");
            $stmt->execute([$_POST['urunAdi'], $_POST['fiyat'], $_POST['kategoriID']]);
            $_SESSION['success_message'] = "Ürün başarıyla eklendi.";
        } elseif ($action === 'sil') {
            $stmt = $pdo->prepare("CALL sp_UrunSil(?)");
            $stmt->execute([$_POST['urunID']]);
             $_SESSION['success_message'] = "Ürün başarıyla silindi.";
        } elseif ($action === 'guncelle') { // Güncelleme işlemi
            $stok = isset($_POST['stokDurumu']) ? 1 : 0;
            $stmt = $pdo->prepare("CALL sp_UrunGuncelle(?, ?, ?, ?, ?)");
            $stmt->execute([$_POST['urunID'], $_POST['urunAdi'], $_POST['fiyat'], $stok, $_POST['kategoriID']]);
            $_SESSION['success_message'] = "Ürün başarıyla güncellendi.";
        }
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "İşlem sırasında hata: " . $e->getMessage();
    }
    header("Location: urunler.php"); exit();
}

$urunler = $pdo->query("CALL sp_UrunleriListele()")->fetchAll();
$kategoriler = $pdo->query("CALL sp_KategorileriListele()")->fetchAll();
?>
<!-- HTML Kısmı (Bu kısım da önceki mesajdakiyle aynı ve doğru) -->
<h1 class="mb-4">Ürün Yönetimi</h1>
<div class="row">
    <div class="col-md-4">
        <div class="card bg-dark border-secondary">
            <h5 class="card-header">Yeni Ürün Ekle</h5>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="action" value="ekle">
                    <div class="mb-3"><label class="form-label">Ürün Adı</label><input type="text" class="form-control" name="urunAdi" required></div>
                    <div class="mb-3"><label class="form-label">Fiyat (₺)</label><input type="number" step="0.01" class="form-control" name="fiyat" required></div>
                    <div class="mb-3"><label class="form-label">Kategori</label>
                        <select class="form-select" name="kategoriID" required>
                            <option value="">Seçiniz...</option>
                            <?php foreach ($kategoriler as $kategori) echo "<option value='{$kategori['kategoriID']}'>{$kategori['kategoriAdi']}</option>"; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Ekle</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <table class="table table-hover">
            <thead class="table-light"><tr><th>ID</th><th>Ürün Adı</th><th>Fiyat</th><th>Kategori</th><th>İşlemler</th></tr></thead>
            <tbody>
                <?php foreach ($urunler as $urun): ?>
                    <tr>
                        <td><?php echo $urun['urunID']; ?></td>
                        <td><?php echo htmlspecialchars($urun['urunAdi']); ?></td>
                        <td><?php echo number_format($urun['fiyat'], 2, ',', '.'); ?> ₺</td>
                        <td><?php echo htmlspecialchars($urun['kategoriAdi']); ?></td>
                        <td>
                            <button class="btn btn-sm btn-info btn-edit" data-id="<?php echo $urun['urunID']; ?>" data-type="urun"><i class="fas fa-edit"></i></button>
                            <form method="POST" class="d-inline" onsubmit="return confirm('Emin misiniz?');">
                                <input type="hidden" name="action" value="sil"><input type="hidden" name="urunID" value="<?php echo $urun['urunID']; ?>">
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<!-- GÜNCELLEME MODAL'I -->
<div class="modal fade" id="editModal" tabindex="-1"><div class="modal-dialog modal-dialog-centered"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title" id="editModalLabel"></h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body" id="editModalBody"></div>
</div></div></div>
<?php include __DIR__ . '/../includes/footer.php'; ?>