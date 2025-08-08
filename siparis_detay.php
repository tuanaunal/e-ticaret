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

$uye_id    = $_SESSION["uye_id"];
$siparis_id = (int)$_GET["siparis_id"];

$sorgu = $conn->prepare("
  SELECT s.*, 
         o.ad AS odeme_adi,
         a.ad_soyad, a.telefon, a.il, a.ilce, a.mahalle, a.acik_adres, a.posta_kodu
  FROM siparis s
  LEFT JOIN odeme_yontemi o ON o.odeme_yontemi_id = s.odeme_yontemi_id
  LEFT JOIN adres a ON a.adres_id = s.adres_id
  WHERE s.siparis_id = ? AND s.uye_id = ?
");
$sorgu->bind_param("ii", $siparis_id, $uye_id);
$sorgu->execute();
$siparis = $sorgu->get_result()->fetch_assoc();

if (!$siparis) {
    echo "<div class='container mt-5 alert alert-danger'>Bu sipariş bulunamadı.</div>";
    exit();
}

$tarih = date("d.m.Y H:i", strtotime($siparis["siparis_tarihi"]));

$urun_sorgu = $conn->prepare("
  SELECT sd.*, u.urun_adi, u.resim 
  FROM siparis_detay sd 
  JOIN urun u ON sd.urun_id = u.urun_id 
  WHERE sd.siparis_id = ?
");
$urun_sorgu->bind_param("i", $siparis_id);
$urun_sorgu->execute();
$urunler = $urun_sorgu->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="keywords" content="Aksesuar,Takı,Gözlük,Şapka,Çanta,Toka,Saç Aksesuarı">
  <meta name="description" content="Yakamoz Aksesuar’da sipariş detayınızı görüntüleyin">
  <link rel="icon" type="image/png" href="YA-Dükkan Resimleri/icon.png">
  <title>Sipariş Detayı - Yakamoz Aksesuar</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">
<?php include 'navbar.php'; ?>

<div class="container mt-5">
  <h2 class="mb-5 text-center" style="font-family: 'Playfair Display', serif;">Sipariş Detayı</h2>

  <div class="card mb-4 shadow-sm">
    <div class="card-header bg-dark text-white">
      <strong>Tarih:</strong> <?= $tarih ?> |
      <strong>Toplam:</strong> <?= number_format((float)$siparis["toplam_tutar"], 2) ?> TL |
      <strong>Durum:</strong> <?= htmlspecialchars($siparis["durum"]) ?>
    </div>

    <div class="card-body">
      <div class="row g-3 mb-4">
        <div class="col-md-6">
          <div class="card h-100">
            <div class="card-header fw-semibold">Teslimat Adresi</div>
            <div class="card-body">
              <?php if (!empty($siparis["ad_soyad"])): ?>
                <div><strong><?= htmlspecialchars($siparis["ad_soyad"]) ?></strong></div>
                <div><?= htmlspecialchars($siparis["telefon"] ?? "") ?></div>
                <div><?= htmlspecialchars($siparis["mahalle"] ?? "") ?> <?= htmlspecialchars($siparis["ilce"] ?? "") ?>/<?= htmlspecialchars($siparis["il"] ?? "") ?> <?= htmlspecialchars($siparis["posta_kodu"] ?? "") ?></div>
                <div><?= nl2br(htmlspecialchars($siparis["acik_adres"] ?? "")) ?></div>
              <?php else: ?>
                <div class="text-muted">Adres bilgisi mevcut değil.</div>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card h-100">
            <div class="card-header fw-semibold">Ödeme</div>
            <div class="card-body">
              <div class="mb-2"><strong>Yöntem:</strong> <?= htmlspecialchars($siparis["odeme_adi"] ?? "—") ?></div>
              <div class="mb-2"><strong>Kargo:</strong> <?= htmlspecialchars($siparis["kargo_firma"] ?? "—") ?> <?= htmlspecialchars($siparis["kargo_takip_no"] ?? "") ?></div>
              <div class="mb-2"><strong>Fatura No:</strong> <?= htmlspecialchars($siparis["fatura_no"] ?? "—") ?></div>
            </div>
          </div>
        </div>
      </div>

      <div class="d-flex flex-wrap justify-content-center gap-3">
        <?php if (!empty($urunler)): ?>
          <?php foreach ($urunler as $urun): ?>
            <div class="card p-2" style="width: 250px;">
              <img src="Resimler/<?= htmlspecialchars($urun['resim']) ?>" 
                   class="card-img-top img-fluid" 
                   style="height: 250px; object-fit: cover;" 
                   alt="<?= htmlspecialchars($urun['urun_adi']) ?>">
              <div class="card-body text-center">
                <h6 class="card-title"><?= htmlspecialchars($urun["urun_adi"]) ?></h6>
                <p class="card-text mb-1"><strong>Adet:</strong> <?= (int)$urun["adet"] ?></p>
                <p class="card-text mb-1"><strong>Fiyat:</strong> <?= number_format((float)$urun["birim_fiyat"], 2) ?> TL</p>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="alert alert-info w-100 text-center">Bu siparişte ürün bulunamadı.</div>
        <?php endif; ?>
      </div>

      <div class="text-end mt-3">
        <a href="index.php" class="btn btn-dark">Ana Sayfaya Dön</a>
      </div>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
