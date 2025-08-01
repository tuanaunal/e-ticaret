<?php
include 'db_baglanti.php';

$sac_kategori_id = 5; 

$sql = "SELECT * FROM urun WHERE kategori_id = $sac_kategori_id";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="keywords" content="Aksesuar,Saç Aksesuarı">
  <meta name="description" content="Yakamoz Aksesuar’da saç aksesuarları">
  <link rel="icon" type="image/png" href="YA-Dükkan Resimleri/icon.png">
  <title>Yakamoz Aksesuar - Saç Aksesuarları</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container my-5">
  <div class="row gx-3 gy-4 justify-content-center my-4">
    <?php if ($result && $result->num_rows > 0): ?>
      <?php while ($urun = $result->fetch_assoc()): ?>
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
