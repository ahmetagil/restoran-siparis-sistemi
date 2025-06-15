<?php // C:\xampp\htdocs\restoran\yonetim\kategoriler.php
include __DIR__ . '/../includes/header.php';

// FORM İŞLEMLERİ: Ekleme, Silme ve Güncelleme
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    try {
        if ($action === 'ekle') {
            $stmt = $pdo->prepare("CALL sp_KategoriEkle(?)");
            $stmt->execute([$_POST['kategoriAdi']]);
            $_SESSION['success_message'] = "Kategori başarıyla eklendi.";
        } elseif ($action === 'sil') {
            $stmt = $pdo->prepare("CALL sp_KategoriSil(?)");
            $stmt->execute([$_POST['kategoriID']]);
            $_SESSION['success_message'] = "Kategori başarıyla silindi.";
        } elseif ($action === 'guncelle') {
            $stmt = $pdo->prepare("CALL sp_KategoriGuncelle(?, ?)");
            $stmt->execute([$_POST['kategoriID'], $_POST['kategoriAdi']]);
            $_SESSION['success_message'] = "Kategori başarıyla güncellendi.";
        }
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "İşlem sırasında bir hata oluştu: " . $e->getMessage();
    }
    header("Location: kategoriler.php");
    exit();
}

// Veritabanından mevcut kategorileri çek
$kategoriler = $pdo->query("CALL sp_KategorileriListele()")->fetchAll();
?>

<h1 class="mb-4">Kategori Yönetimi</h1>
<div class="row">
    <!-- Yeni Kategori Ekleme Formu -->
    <div class="col-md-4">
        <div class="card bg-dark border-secondary">
            <h5 class="card-header">Yeni Kategori Ekle</h5>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="action" value="ekle">
                    <div class="mb-3">
                        <label for="kategoriAdi" class="form-label">Kategori Adı</label>
                        <input type="text" class="form-control" name="kategoriAdi" placeholder="Örn: İçecekler" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Ekle</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Mevcut Kategoriler Listesi -->
    <div class="col-md-8">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Kategori Adı</th>
                    <th class="text-end">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($kategoriler as $kategori): ?>
                    <tr>
                        <td><?php echo $kategori['kategoriID']; ?></td>
                        <td><?php echo htmlspecialchars($kategori['kategoriAdi']); ?></td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-info btn-edit" data-id="<?php echo $kategori['kategoriID']; ?>" data-type="kategori"><i class="fas fa-edit"></i></button>
                            <form method="POST" class="d-inline" onsubmit="return confirm('Bu kategoriyi silmek istediğinizden emin misiniz?');">
                                <input type="hidden" name="action" value="sil">
                                <input type="hidden" name="kategoriID" value="<?php echo $kategori['kategoriID']; ?>">
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- GÜNCELLEME MODAL'I (Tüm sayfalarda aynı ID ile olabilir) -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="editModalBody">
                <!-- Bu kısım AJAX ile doldurulacak -->
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>