<?php
include 'db_baglanti.php';

$kategori_id = isset($_GET['kategori']) ? intval($_GET['kategori']) : 0;


$kategori_adi_sql = "SELECT kategori_adi FROM kategori WHERE kategori_id = $kategori_id";
$kategori_adi_result = $conn->query($kategori_adi_sql);
$kategori_adi = $kategori_adi_result->num_rows > 0 ? $kategori_adi_result->fetch_assoc()['kategori_adi'] : "Tüm Ürünler";


if ($kategori_id > 0) {
    $urun_sql = "SELECT * FROM urun WHERE kategori_id = $kategori_id";
} else {
    $urun_sql = "SELECT * FROM urun";
}
$urun_result = $conn->query($urun_sql);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="keywords" content="Aksesuar,Takı,Gözlük,Şapka,Çanta,Toka,Saç Aksesuarı">
  <meta name="description" content="Yakamoz Aksesuar’da zarif takılar, gözlükler, şapkalar, çantalar ve saç aksesuarlarıyla tarzına ışıltı kat. Hızlı kargo, güvenli ödeme ve uygun fiyat seni bekliyor!">
  <link rel="icon" type="image/png" href="YA-Dükkan Resimleri/icon.png">
  <title><?= $kategori_adi ?> - Yakamoz Aksesuar</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container my-5">
  <h2 class="mb-4" style="font-family: 'Playfair Display', serif;"><?= $kategori_adi ?></h2>
  <div class="row gx-3 gy-4 justify-content-center my-4">
    <?php if ($urun_result->num_rows > 0): ?>
      <?php while($urun = $urun_result->fetch_assoc()): ?>
        <div class="col-6 col-md-4 col-lg-3 mb-4">
          <div class="card product-card">
            <img src="resimler/<?= $urun['resim'] ?>" class="img-fluid d-block w-100" alt="<?= $urun['urun_adi'] ?>">
            <div class="card-body product-card-body d-flex flex-column">
              <h5 class="card-title"><?= $urun['urun_adi'] ?></h5>
              <p class="card-text mb-auto"><?= $urun['fiyat'] ?> TL</p>
              <a href="#" class="btn float-end" style="background-color: #000; color: #fff;">Sepete Ekle</a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="text-center">Bu kategoride ürün bulunmamaktadır.</p>
    <?php endif; ?>
  </div>
</div>

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
