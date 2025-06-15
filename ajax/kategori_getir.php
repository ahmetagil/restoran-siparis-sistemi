<?php // C:\xampp\htdocs\restoran\ajax/kategori_getir.php
require_once __DIR__ . '/../includes/db.php';
header('Content-Type: application/json');

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $stmt = $pdo->prepare("SELECT * FROM Kategoriler WHERE kategoriID = ?");
    $stmt->execute([$id]);
    $kategori = $stmt->fetch();
    
    // Modal'ın gövdesini (body) HTML olarak oluşturuyoruz.
    $modalBody = "
        <form method='POST' action='kategoriler.php'>
            <input type='hidden' name='action' value='guncelle'>
            <input type='hidden' name='kategoriID' value='{$kategori['kategoriID']}'>
            <div class='mb-3'>
                <label class='form-label'>Kategori Adı</label>
                <input type='text' class='form-control' name='kategoriAdi' value='{$kategori['kategoriAdi']}' required>
            </div>
            <button type='submit' class='btn btn-primary w-100'>Güncelle</button>
        </form>
    ";
    
    // JavaScript'e JSON formatında veri gönderiyoruz.
    echo json_encode(['modalTitle' => 'Kategoriyi Düzenle', 'modalBody' => $modalBody]);
}
?>