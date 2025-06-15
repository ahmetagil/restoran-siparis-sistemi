<?php // C:\xampp\htdocs\restoran\ajax\siparis_islemleri.php (NİHAİ VE TAM HALİ)
require_once __DIR__ . '/../includes/db.php';

// Bu dosyanın hem siparis.php tarafından 'include' edilebildiğini, hem de AJAX ile çağrılabildiğini anla.
$is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']);
if ($is_ajax) {
    header('Content-Type: application/json');
}

$response = ['success' => false, 'message' => 'Geçersiz istek.'];

// Eğer bu bir AJAX POST isteğiyse, veritabanı işlemini yap.
if ($is_ajax && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $siparis_id = $_POST['siparis_id'] ?? 0;

    if ($action === 'urun_ekle') {
        try {
            // Ürün ekleme saklı yordamını çağır.
            $stmt = $pdo->prepare("CALL sp_SipariseUrunEkle(?, ?, ?)");
            $stmt->execute([$siparis_id, $_POST['urun_id'], $_POST['adet']]);
            $stmt->closeCursor();
            $response['success'] = true; // İşlemin başarılı olduğunu işaretle.
        } catch (PDOException $e) {
            $response['message'] = $e->getMessage();
        }
    }
}

// --- HTML OLUŞTURMA BÖLÜMÜ ---
// Bu bölüm, hem AJAX isteği başarılı olduğunda, hem de siparis.php sayfası ilk yüklendiğinde çalışır.

// Hangi siparişin detayını göstereceğimizi belirle.
if ($is_ajax) {
    // AJAX isteğiyse, POST ile gelen sipariş ID'sini kullan.
    $siparis_id_to_load = $_POST['siparis_id'] ?? 0;
} else {
    // siparis.php'den 'include' edildiyse, orada önceden tanımlanmış olan $siparis_id değişkenini kullan.
    $siparis_id_to_load = $siparis_id ?? null;
}

// Çıktıyı bir değişkende toplamak için tamponlamayı başlat.
ob_start();

if ($siparis_id_to_load) {
    // Gerekli tüm verileri veritabanından çek.
    $detayStmt = $pdo->prepare("CALL sp_SiparisDetayGetir(?)");
    $detayStmt->execute([$siparis_id_to_load]);
    $detaylar = $detayStmt->fetchAll();
    $detayStmt->closeCursor();

    $tutarStmt = $pdo->prepare("SELECT g.ad, g.soyad, s.toplamTutar FROM Siparisler s JOIN Garsonlar g ON s.garsonID = g.garsonID WHERE s.siparisID = ?");
    $tutarStmt->execute([$siparis_id_to_load]);
    $siparisGenelBilgi = $tutarStmt->fetch();
    $tutarStmt->closeCursor();
    ?>

    <p><strong>Garson:</strong> <?php echo htmlspecialchars($siparisGenelBilgi['ad'] . ' ' . $siparisGenelBilgi['soyad']); ?></p>
    <hr>
    <div id="adisyon-icerik">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Ürün</th>
                    <th>Adet</th>
                    <th class="text-end">Birim Fiyat</th>
                    <th class="text-end">Toplam</th>
                    <th class="text-end">Sil</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($detaylar as $detay): ?>
                <tr>
                    <td><?php echo htmlspecialchars($detay['urunAdi']);?></td>
                    <td><?php echo $detay['adet'];?></td>
                    <td class='text-end'><?php echo number_format($detay['birimFiyat'],2,',','.');?> ₺</td>
                    <td class='text-end fw-bold'><?php echo number_format($detay['araToplam'],2,',','.');?> ₺</td>
                    <td class='text-end'>
                        <!-- HER SATIR İÇİN TAM VE EKSİKSİZ SİLME FORMU -->
                        <form method="POST" action="<?php echo BASE_URL; ?>siparis.php?masa_id=<?php echo $_GET['masa_id'] ?? ''; ?>" onsubmit="return confirm('Ürünü siliyorsunuz, emin misiniz?');">
                            <input type="hidden" name="action" value="detay_sil">
                            <input type="hidden" name="siparis_detay_id" value="<?php echo $detay['siparisDetayID']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-times"></i></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <hr>
        <div class="d-flex justify-content-end align-items-center">
            <h4 class="me-3">GENEL TOPLAM:</h4>
            <h4 class="text-success fw-bold"><?php echo number_format($siparisGenelBilgi['toplamTutar'],2,',','.');?> ₺</h4>
        </div>
    </div>

    <?php
} else {
    // Eğer görüntülenecek bir sipariş yoksa...
    if (!$is_ajax) { // Sadece sayfa ilk yüklendiğinde bu mesajı göster.
        echo "<div class='alert alert-info text-center'>Bu masada aktif bir sipariş bulunmamaktadır.</div>";
    }
}

// Oluşturulan tüm HTML'i $html değişkenine al.
$html = ob_get_clean();

// Eğer bu bir AJAX isteği ise, sonucu JSON olarak paketle ve gönder.
if ($is_ajax) {
    $response['html'] = $html;
    echo json_encode($response);
} else {
    // Eğer normal bir 'include' ise, HTML'i doğrudan ekrana bas.
    echo $html;
}
?>