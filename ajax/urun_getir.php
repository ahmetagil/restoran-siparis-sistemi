<?php // C:\xampp\htdocs\restoran\ajax/urun_getir.php
require_once __DIR__ . '/../includes/db.php';
header('Content-Type: application/json');

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $stmt = $pdo->prepare("SELECT * FROM Urunler WHERE urunID = ?");
    $stmt->execute([$id]);
    $urun = $stmt->fetch();
    
    $kategoriler = $pdo->query("SELECT * FROM Kategoriler")->fetchAll();
    $kategoriOptions = '';
    foreach($kategoriler as $kategori) {
        $selected = ($kategori['kategoriID'] == $urun['kategoriID']) ? 'selected' : '';
        $kategoriOptions .= "<option value='{$kategori['kategoriID']}' {$selected}>{$kategori['kategoriAdi']}</option>";
    }
    
    $modalBody = "
        <form method='POST' action='urunler.php'>
            <input type='hidden' name='action' value='guncelle'>
            <input type='hidden' name='urunID' value='{$urun['urunID']}'>
            <div class='mb-3'><label class='form-label'>Ürün Adı</label><input type='text' class='form-control' name='urunAdi' value='{$urun['urunAdi']}' required></div>
            <div class='mb-3'><label class='form-label'>Fiyat</label><input type='number' step='0.01' class='form-control' name='fiyat' value='{$urun['fiyat']}' required></div>
            <div class='mb-3'><label class='form-label'>Kategori</label><select class='form-select' name='kategoriID' required>{$kategoriOptions}</select></div>
            <button type='submit' class='btn btn-primary w-100'>Güncelle</button>
        </form>
    ";
    
    echo json_encode(['modalTitle' => 'Ürünü Düzenle', 'modalBody' => $modalBody]);
}
?>