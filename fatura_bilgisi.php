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

$uye_id     = (int)$_SESSION["uye_id"];
$siparis_id = (int)$_GET["siparis_id"];

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

$bilgi_sorgu = $conn->prepare("
    SELECT 
        a.ad_soyad,
        a.telefon,
        a.acik_adres AS adres,
        a.ilce,
        a.il,
        o.ad AS odeme_yontemi
    FROM siparis s
    LEFT JOIN adres a ON s.adres_id = a.adres_id
    LEFT JOIN odeme_yontemi o ON s.odeme_yontemi_id = o.odeme_yontemi_id
    WHERE s.siparis_id = ? AND s.uye_id = ?
");
$bilgi_sorgu->bind_param("ii", $siparis_id, $uye_id);
$bilgi_sorgu->execute();
$bilgi = $bilgi_sorgu->get_result()->fetch_assoc();

$durum_raw  = $siparis["durum"] ?? "";
$durum      = trim($durum_raw);
$is_kargoda = (mb_strtolower($durum,'UTF-8') === mb_strtolower('Kargoya Verildi','UTF-8'));

$siparis_no = trim($siparis["siparis_no"] ?? "");
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

    <div class="mb-3">
      <p class="mb-2"><strong>Sipariş No:</strong> <?= htmlspecialchars($siparis_no ?: '#'.$siparis_id) ?></p>
      <p class="mb-2"><strong>Sipariş Tarihi:</strong> <?= htmlspecialchars($siparis["siparis_tarihi"]) ?></p>
      <p class="mb-2"><strong>Sipariş Durumu:</strong> <?= htmlspecialchars($durum) ?></p>
      <p class="mb-2"><strong>Kargo Firması:</strong> <?= htmlspecialchars($siparis["kargo_firma"] ?? "Bilgi yok") ?></p>
      <p class="mb-0"><strong>Toplam Tutar:</strong> <?= htmlspecialchars($siparis["toplam_tutar"]) ?> TL</p>

      <?php if ($is_kargoda): ?>
        <a class="btn btn-dark btn-sm mt-3" target="_blank" rel="noopener" href="https://www.yurticikargo.com/">
          Kargo Takibi
        </a>
      <?php endif; ?>
    </div>

    <hr>

    <h5 class="mt-4 mb-3">Ürünler:</h5>

    <div class="row g-3 mb-4">
      <?php foreach ($urunler as $urun): ?>
        <div class="col-6 col-md-3">
          <div class="card p-2 h-100">
            <img src="Resimler/<?= htmlspecialchars($urun['resim']) ?>"
                 class="card-img-top img-fluid"
                 style="height: 250px; object-fit: cover;"
                 alt="<?= htmlspecialchars($urun['urun_adi']) ?>">
            <div class="card-body text-center">
              <h6 class="card-title"><?= htmlspecialchars($urun["urun_adi"]) ?></h6>
              <p class="card-text mb-1"><strong>Adet:</strong> <?= (int)$urun["adet"] ?></p>
              <p class="card-text mb-1"><strong>Fiyat:</strong> <?= htmlspecialchars($urun["birim_fiyat"]) ?> TL</p>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <hr>

    <h5 class="mt-4 mb-3">Teslimat Bilgisi:</h5>
    <div class="mb-3">
      <p class="mb-2"><strong>Ad Soyad:</strong> <?= htmlspecialchars($bilgi["ad_soyad"] ?? "Bilgi yok") ?></p>
      <p class="mb-2"><strong>Telefon:</strong> <?= htmlspecialchars($bilgi["telefon"] ?? "Bilgi yok") ?></p>
      <p class="mb-2">
        <strong>Adres:</strong>
        <?= htmlspecialchars($bilgi["adres"] ?? "Bilgi yok") ?> -
        <?= htmlspecialchars($bilgi["ilce"] ?? "") ?>/<?= htmlspecialchars($bilgi["il"] ?? "") ?>
      </p>
      <p class="mb-0"><strong>Ödeme Yöntemi:</strong> <?= htmlspecialchars($bilgi["odeme_yontemi"] ?? "Bilgi yok") ?></p>
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
