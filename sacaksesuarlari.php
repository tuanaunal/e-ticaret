<?php
session_start();
include 'db_baglanti.php';

$sac_kategori_id = 5;

$favoriler = [];
if (isset($_SESSION['uye_id'])) {
    $uye_id = $_SESSION['uye_id'];
    $fav_sql = "SELECT urun_id FROM favori WHERE uye_id = $uye_id";
    $fav_result = $conn->query($fav_sql);
    while ($row = $fav_result->fetch_assoc()) {
        $favoriler[] = $row['urun_id'];
    }
}

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
      <?php while ($urun = $result->fetch_assoc()):
        $favoride_mi = in_array($urun['urun_id'], $favoriler);
      ?>
        <div class="col-6 col-md-4 col-lg-3 mb-4">
          <div class="card product-card position-relative">

            <a href="javascript:void(0);" 
               onclick="toggleFavori(<?= $urun['urun_id'] ?>, this)" 
               class="position-absolute top-0 end-0 m-2" 
               style="font-size: 1.5rem; color: black;">
              <?php if ($favoride_mi): ?>
                <i class="bi bi-heart-fill"></i> 
              <?php else: ?>
                <i class="bi bi-heart"></i> 
              <?php endif; ?>
            </a>

            <img src="resimler/<?= $urun['resim'] ?>" 
                 class="img-fluid d-block w-100" 
                 alt="<?= htmlspecialchars($urun['urun_adi']) ?>">

            <div class="card-body product-card-body d-flex flex-column">
              <h5 class="card-title"><?= htmlspecialchars($urun['urun_adi']) ?></h5>
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

<script>
function toggleFavori(urunId, element) {
    fetch('favori_ekle.php?urun_id=' + urunId)
        .then(response => response.json())
        .then(data => {
            if (data.status === "not_logged_in") {
                window.location.href = 'girisyap.php';
                return;
            }

            let icon = element.querySelector('i');
            if (data.status === "added") {
                icon.classList.remove('bi-heart');
                icon.classList.add('bi-heart-fill');
            } else if (data.status === "removed") {
                icon.classList.remove('bi-heart-fill');
                icon.classList.add('bi-heart');
            }
        })
        .catch(err => console.error('Favori ekleme hatası:', err));
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
