<?php
session_start();
include("db_baglanti.php");

if (!isset($_SESSION["uye_id"])) {
    header("Location: giris.php");
    exit();
}

if (!isset($_GET["siparis_id"])) {
    echo "<div class='container mt-5 alert alert-danger'>Sipariş ID bulunamadı.</div>";
    exit();
}

$uye_id = $_SESSION["uye_id"];
$siparis_id = intval($_GET["siparis_id"]);

$sorgu = $conn->prepare("SELECT * FROM siparis WHERE siparis_id = ? AND uye_id = ?");
$sorgu->bind_param("ii", $siparis_id, $uye_id);
$sorgu->execute();
$siparis = $sorgu->get_result()->fetch_assoc();

if (!$siparis) {
    echo "<div class='container mt-5 alert alert-danger'>Böyle bir sipariş bulunamadı.</div>";
    exit();
}

$urun_sorgu = $conn->prepare("
    SELECT sd.*, u.urun_adi, u.resim 
    FROM siparis_detay sd 
    JOIN urun u ON sd.urun_id = u.urun_id 
    WHERE sd.siparis_id = ?
");
$urun_sorgu->bind_param("i", $siparis_id);
$urun_sorgu->execute();
$urunler = $urun_sorgu->get_result()->fetch_all(MYSQLI_ASSOC);

$bilgi_sorgu = $conn->prepare("SELECT * FROM siparis_bilgi WHERE siparis_id = ?");
$bilgi_sorgu->bind_param("i", $siparis_id);
$bilgi_sorgu->execute();
$bilgi = $bilgi_sorgu->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="keywords" content="Aksesuar,Takı,Gözlük,Şapka,Çanta,Toka,Saç Aksesuarı">
  <meta name="description" content="Yakamoz Aksesuar – Fatura Bilgisi">
  <link rel="icon" type="image/png" href="YA-Dükkan Resimleri/icon.png">
  <title>Fatura Bilgisi - Yakamoz Aksesuar</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">

<?php include 'navbar.php'; ?>

<div class="container mt-5">
  <div class="card shadow p-4">
    <h2 class="mb-4 text-center" style="font-family: 'Playfair Display', serif;">Fatura Bilgisi</h2>

    <div class="mb-4">
      <p class="mb-2"><strong>Sipariş Tarihi:</strong> <?= $siparis["siparis_tarihi"] ?></p>
      <p class="mb-2"><strong>Sipariş Durumu:</strong> <?= $siparis["durum"] ?></p>
      <p class="mb-2"><strong>Kargo Firması:</strong> <?= $siparis["kargo_firma"] ?? "Bilgi yok" ?></p>
      <p class="mb-2"><strong>Kargo Takip No:</strong> <?= $siparis["kargo_takip_no"] ?? "Bilgi yok" ?></p>
      <p class="mb-0"><strong>Toplam Tutar:</strong> <?= $siparis["toplam_tutar"] ?> TL</p>
    </div>

    <hr>

    <h5 class="mt-4 mb-3">Ürünler:</h5>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-2 mb-4">
      <?php foreach ($urunler as $urun): ?>
        <div class="col" style="flex: 0 0 auto; width: 250px;">
          <div class="card h-100 border-0 shadow-sm">
            <img src="Resimler/<?= htmlspecialchars($urun['resim']) ?>" 
                 class="card-img-top img-fluid" 
                 alt="<?= htmlspecialchars($urun['urun_adi']) ?>" 
                 style="height: 200px; object-fit: cover;">

            <div class="card-body p-2 d-flex flex-column justify-content-between">
              <div>
                <h6 class="card-title fw-semibold mb-2" style="font-size: 1rem;">
                  <?= htmlspecialchars($urun["urun_adi"]) ?>
                </h6>
                <p class="card-text mb-1" style="font-size: 0.9rem;">
                  <strong>Adet:</strong> <span class="fw-normal"><?= $urun["adet"] ?></span>
                </p>
                <p class="card-text mb-1" style="font-size: 0.9rem;">
                  <strong>Birim Fiyat:</strong> <span class="fw-normal"><?= $urun["birim_fiyat"] ?> TL</span>
                </p>
                <p class="card-text mb-0" style="font-size: 0.9rem;">
                  <strong>Ara Toplam:</strong> <span class="fw-normal"><?= $urun["birim_fiyat"] * $urun["adet"] ?> TL</span>
                </p>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <hr>

    <h5 class="mt-4 mb-3">Teslimat Bilgisi:</h5>
    <div class="mb-3">
      <p class="mb-2"><strong>Ad Soyad:</strong> <?= $bilgi["ad_soyad"] ?></p>
      <p class="mb-2"><strong>Telefon:</strong> <?= $bilgi["telefon"] ?></p>
      <p class="mb-2"><strong>Adres:</strong> <?= $bilgi["adres"] ?> - <?= $bilgi["ilce"] ?>/<?= $bilgi["sehir"] ?></p>
      <p class="mb-0"><strong>Ödeme Yöntemi:</strong> <?= $bilgi["odeme_yontemi"] ?></p>
    </div>

    <div class="text-center mt-4">
      <a href="siparislerim.php" class="btn btn-dark">← Siparişlerime Dön</a>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
