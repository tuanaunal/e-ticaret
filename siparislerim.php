<?php
session_start();
include("db_baglanti.php");

if (!isset($_SESSION["uye_id"])) {
    header("Location: giris.php");
    exit();
}

$uye_id = $_SESSION["uye_id"];
$sorgu = $conn->prepare("SELECT * FROM siparis WHERE uye_id = ? ORDER BY siparis_tarihi DESC");
$sorgu->bind_param("i", $uye_id);
$sorgu->execute();
$siparisler = $sorgu->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="keywords" content="Aksesuar,Takı,Gözlük,Şapka,Çanta,Toka,Saç Aksesuarı">
  <meta name="description" content="Yakamoz Aksesuar’da siparişlerinizi görüntüleyin">
  <link rel="icon" type="image/png" href="YA-Dükkan Resimleri/icon.png">
  <title>Siparişlerim - Yakamoz Aksesuar</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">
<?php include 'navbar.php'; ?>

<div class="container mt-5">
  <h2 class="mb-5 text-center" style="font-family: 'Playfair Display', serif;">Siparişlerim</h2>

  <?php if (empty($siparisler)): ?>
    <div class="alert alert-info">Henüz hiçbir siparişiniz yok.</div>
  <?php else: ?>
    <?php foreach ($siparisler as $siparis): ?>
      <?php
        $siparis_id = (int)$siparis["siparis_id"];
        $siparis_no = trim($siparis["siparis_no"] ?? "");
        if ($siparis_no === "") { $siparis_no = "#".$siparis_id; }

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

        $durum_raw = $siparis["durum"] ?? '';
        $durum = trim($durum_raw);
        $is_kargoda = (mb_strtolower($durum, 'UTF-8') === mb_strtolower('Kargoya Verildi', 'UTF-8'));
      ?>

      <div class="card mb-4 shadow-sm">
        <div class="card-header bg-dark text-white">
          <strong>Sipariş No:</strong> <?= htmlspecialchars($siparis_no) ?> |
          <strong>Tarih:</strong> <?= htmlspecialchars($tarih) ?> |
          <strong>Toplam:</strong> <?= htmlspecialchars($siparis["toplam_tutar"]) ?> TL |
          <strong>Durum:</strong> <?= htmlspecialchars($durum) ?>
        </div>

        <div class="card-body">
          <div class="d-flex flex-wrap justify-content-center gap-3">
            <?php foreach ($urunler as $urun): ?>
              <div class="card p-2" style="width: 250px;">
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
            <?php endforeach; ?>
          </div>

          <div class="text-end mt-3 d-flex justify-content-end gap-2">
            <?php if ($is_kargoda): ?>
              <a href="https://www.yurticikargo.com/" target="_blank" rel="noopener"
                 class="btn btn-dark">Kargo Takip</a>
            <?php endif; ?>
            <a href="fatura_bilgisi.php?siparis_id=<?= $siparis_id ?>" class="btn btn-dark">Fatura Bilgisi</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
