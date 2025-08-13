<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include("../db_baglanti.php");

if (!isset($_SESSION['uye_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: ../girisyap.php");
    exit();
}

if (!isset($_GET["id"])) {
    echo "Sipariş ID bulunamadı.";
    exit();
}

$siparis_id = intval($_GET["id"]);

$sorgu = $conn->prepare("SELECT s.*, CONCAT(u.ad, ' ', u.soyad) AS musteri_ad, u.email 
                         FROM siparis s 
                         JOIN uye u ON s.uye_id = u.uye_id 
                         WHERE s.siparis_id = ?");
$sorgu->bind_param("i", $siparis_id);
$sorgu->execute();
$siparis = $sorgu->get_result()->fetch_assoc();

$urun_sorgu = $conn->prepare("SELECT sd.*, u.urun_adi, u.resim 
                              FROM siparis_detay sd 
                              JOIN urun u ON sd.urun_id = u.urun_id 
                              WHERE sd.siparis_id = ?");
$urun_sorgu->bind_param("i", $siparis_id);
$urun_sorgu->execute();
$urunler = $urun_sorgu->get_result();

$bilgi_sorgu = $conn->prepare("SELECT * FROM siparis_bilgi WHERE siparis_id = ?");
$bilgi_sorgu->bind_param("i", $siparis_id);
$bilgi_sorgu->execute();
$bilgi = $bilgi_sorgu->get_result()->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $yeni_durum = $_POST["durum"];
    $kargo_firma = $_POST["kargo_firma"];
    $kargo_takip = $_POST["kargo_takip_no"];

    $guncelle = $conn->prepare("UPDATE siparis SET durum = ?, kargo_firma = ?, kargo_takip_no = ? WHERE siparis_id = ?");
    $guncelle->bind_param("sssi", $yeni_durum, $kargo_firma, $kargo_takip, $siparis_id);
    $guncelle->execute();

    header("Location: admin_siparisler.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="keywords" content="Aksesuar,Takı,Gözlük,Şapka,Çanta,Toka,Saç Aksesuarı">
  <meta name="description" content="Yakamoz Aksesuar – Admin Girişi">
  <link rel="icon" type="image/png" href="../YA-Dükkan Resimleri/icon.png">
  <title>Admin Sipariş Detayı - Yakamoz Aksesuar</title>
  <link rel="stylesheet" href="../style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
    body { background-color: #f8f9fa; }
    .panel-card {
        max-width: 900px;
        width: 100%;
        border-radius: 1rem;
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }
    .product-card img {
        height: 150px;
        object-fit: cover;
        border-radius: .5rem;
    }
</style>
</head>
<body>

<div class="container d-flex justify-content-center align-items-center py-5">
    <div class="card panel-card p-4">
        <h2 class="mb-4 text-center">📦 Sipariş #<?= $siparis_id ?> Detayları</h2>

        <div class="mb-4">
            <p><strong>Müşteri:</strong> <?= htmlspecialchars($siparis["musteri_ad"]) ?> (<?= htmlspecialchars($siparis["email"]) ?>)</p>
            <p><strong>Tarih:</strong> <?= $siparis["siparis_tarihi"] ?></p>
            <p><strong>Toplam Tutar:</strong> <?= $siparis["toplam_tutar"] ?> TL</p>
        </div>

        <hr>

        <h5 class="mb-3">Ürünler</h5>
        <div class="row g-3 mb-4">
            <?php while ($u = $urunler->fetch_assoc()): ?>
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="card product-card h-100 p-2 shadow-sm">
                        <img src="Resimler/<?= htmlspecialchars($u["resim"]) ?>" class="img-fluid" alt="<?= htmlspecialchars($u["urun_adi"]) ?>">
                        <div class="mt-2">
                            <h6><?= htmlspecialchars($u["urun_adi"]) ?></h6>
                            <small>Adet: <?= $u["adet"] ?> | <?= $u["birim_fiyat"] ?> TL</small>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <hr>

        <h5 class="mb-3">Teslimat & Ödeme Bilgisi</h5>
        <div class="bg-light p-3 rounded mb-4">
            <p><strong>Ad Soyad:</strong> <?= htmlspecialchars($bilgi["ad_soyad"]) ?></p>
            <p><strong>Telefon:</strong> <?= htmlspecialchars($bilgi["telefon"]) ?></p>
            <p><strong>Adres:</strong> <?= htmlspecialchars($bilgi["adres"]) ?> - <?= htmlspecialchars($bilgi["ilce"]) ?>/<?= htmlspecialchars($bilgi["sehir"]) ?></p>
            <p><strong>Ödeme:</strong> <?= htmlspecialchars($bilgi["odeme_yontemi"]) ?></p>
        </div>

        <h5 class="mb-3">⚙️ Sipariş Durumu ve Kargo</h5>
        <form method="POST" class="mb-3">
            <div class="mb-3">
                <label class="form-label">Sipariş Durumu</label>
                <select name="durum" class="form-select">
                    <option value="Hazırlanıyor" <?= $siparis["durum"] == "Hazırlanıyor" ? "selected" : "" ?>>Hazırlanıyor</option>
                    <option value="Kargoya Verildi" <?= $siparis["durum"] == "Kargoya Verildi" ? "selected" : "" ?>>Kargoya Verildi</option>
                    <option value="Teslim Edildi" <?= $siparis["durum"] == "Teslim Edildi" ? "selected" : "" ?>>Teslim Edildi</option>
                    <option value="İptal Edildi" <?= $siparis["durum"] == "İptal Edildi" ? "selected" : "" ?>>İptal Edildi</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Kargo Firması</label>
                <input type="text" name="kargo_firma" class="form-control" value="<?= htmlspecialchars($siparis["kargo_firma"]) ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Kargo Takip No</label>
                <input type="text" name="kargo_takip_no" class="form-control" value="<?= htmlspecialchars($siparis["kargo_takip_no"]) ?>">
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-success px-4">Kaydet</button>
                <a href="admin_siparisler.php" class="btn btn-secondary px-4">Geri Dön</a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
