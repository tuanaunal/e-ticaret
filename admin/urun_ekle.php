<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include '../db_baglanti.php';

if (!isset($_SESSION['uye_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: ../girisyap.php");
    exit();
}

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(16));
}

$kategori_sql = "SELECT * FROM kategori";
$kategori_result = $conn->query($kategori_sql);

$mesaj = "";
$mesaj_tur = "info";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!isset($_POST['csrf']) || !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
        $mesaj = "Oturum doğrulaması başarısız. Lütfen tekrar deneyin.";
        $mesaj_tur = "danger";
    } else {
        $kategori_id = intval($_POST['kategori_id']);
        $urun_adi = trim($_POST['urun_adi']);
        $aciklama = trim($_POST['aciklama']);
        $fiyat = floatval($_POST['fiyat']);
        $stok = intval($_POST['stok']);

        if ($kategori_id <= 0 || $urun_adi === "" || $fiyat <= 0 || $stok < 0) {
            $mesaj = "Lütfen tüm alanları doğru doldurun.";
            $mesaj_tur = "warning";
        } elseif (!isset($_FILES['resim']) || $_FILES['resim']['error'] !== 0) {
            $mesaj = "Lütfen geçerli bir resim seçin.";
            $mesaj_tur = "warning";
        } else {
            $izinli_uzantilar = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $dosya_adi = basename($_FILES['resim']['name']);
            $dosya_uzantisi = strtolower(pathinfo($dosya_adi, PATHINFO_EXTENSION));

            if (!in_array($dosya_uzantisi, $izinli_uzantilar) || $_FILES['resim']['size'] > 5 * 1024 * 1024) {
                $mesaj = "Geçersiz dosya türü veya boyutu çok büyük (max 5MB).";
                $mesaj_tur = "warning";
            } else {
                $yeni_dosya_adi = time() . "_" . preg_replace("/[^a-zA-Z0-9-_\.]/", "_", $dosya_adi);
                $hedef_dizin = "../resimler/" . $yeni_dosya_adi;

                if (move_uploaded_file($_FILES['resim']['tmp_name'], $hedef_dizin)) {
                    $stmt = $conn->prepare("INSERT INTO urun (kategori_id, urun_adi, aciklama, fiyat, stok, resim) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("issdis", $kategori_id, $urun_adi, $aciklama, $fiyat, $stok, $yeni_dosya_adi);

                    if ($stmt->execute()) {
                        $mesaj = "Ürün başarıyla eklendi 🎉";
                        $mesaj_tur = "success";
                    } else {
                        $mesaj = "Veritabanı hatası: " . htmlspecialchars($conn->error);
                        $mesaj_tur = "danger";
                    }
                } else {
                    $mesaj = "Resim yüklenirken bir hata oluştu.";
                    $mesaj_tur = "danger";
                }
            }
        }
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="keywords" content="Aksesuar,Takı,Gözlük,Şapka,Çanta,Toka,Saç Aksesuarı">
<meta name="description" content="Yakamoz Aksesuar – Admin Ürün Ekle">
<link rel="icon" type="image/png" href="../YA-Dükkan Resimleri/icon.png">
<title>Admin Ürün Ekle - Yakamoz Aksesuar</title>
<link rel="stylesheet" href="../style.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
    body { background-color:#f8f9fa; }
    .panel-card {
        width:100%;
        max-width:650px;
        border-radius:1rem;
        box-shadow:0 10px 25px rgba(0,0,0,.08);
    }
    .panel-title { font-family:'Playfair Display', serif; }
</style>
</head>
<body>

<div class="container-fluid min-vh-100 d-flex justify-content-center align-items-center py-4">
    <div class="card panel-card p-4">
        <h2 class="panel-title text-center mb-3">Ürün Ekle</h2>

        <?php if(!empty($mesaj)): ?>
            <div class="alert alert-<?= $mesaj_tur ?>"><?= $mesaj ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" novalidate>
            <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">

            <div class="mb-3">
                <label class="form-label">Kategori</label>
                <select name="kategori_id" class="form-control" required>
                    <?php while ($row = $kategori_result->fetch_assoc()): ?>
                        <option value="<?= $row['kategori_id'] ?>"><?= htmlspecialchars($row['kategori_adi']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Ürün Adı</label>
                <input type="text" name="urun_adi" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Açıklama</label>
                <textarea name="aciklama" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Fiyat (₺)</label>
                <input type="number" name="fiyat" step="0.01" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Stok</label>
                <input type="number" name="stok" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Resim</label>
                <input type="file" name="resim" class="form-control" accept=".jpg,.jpeg,.png,.gif,.webp" required>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-dark">Ekle</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
