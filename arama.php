<?php
include 'db_baglanti.php';

$arama = isset($_GET['q']) ? trim($_GET['q']) : '';

if ($arama === '') {
    header("Location: index.php");
    exit();
}

$sql = "SELECT * FROM urun WHERE urun_adi LIKE '%$arama%'";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="keywords" content="Aksesuar,Çanta,Takı,Gözlük,Şapka,Saç Aksesuarı">
  <meta name="description" content="Yakamoz Aksesuar’da şık ve modern çantalar">
  <link rel="icon" type="image/png" href="YA-Dükkan Resimleri/icon.png">
  <title>Arama Sonuçları - Yakamoz Aksesuar</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container my-5">
  <h2 class="text-center mb-4">
    "<?= htmlspecialchars($arama) ?>" için arama sonuçları
  </h2>

  <div class="row gx-3 gy-4 justify-content-center">
    <?php if ($result && $result->num_rows > 0): ?>
      <?php while ($urun = $result->fetch_assoc()): ?>
        <div class="col-6 col-md-4 col-lg-3 mb-4">
          <div class="card product-card position-relative">
            <img src="resimler/<?= $urun['resim'] ?>" 
                 class="img-fluid d-block w-100" 
                 alt="<?= htmlspecialchars($urun['urun_adi']) ?>">

            <div class="card-body product-card-body d-flex flex-column">
              <h5 class="card-title"><?= htmlspecialchars($urun['urun_adi']) ?></h5>
              <p class="card-text mb-auto"><?= $urun['fiyat'] ?> TL</p>
              <a href="#"
                 class="btn btn-dark sepete-ekle mt-2"
                 data-id="<?= $urun['urun_id'] ?>">
                 Sepete Ekle
              </a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="text-center">Aradığınız kriterlere uygun ürün bulunamadı.</p>
    <?php endif; ?>
  </div>
</div>

<?php include 'footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
