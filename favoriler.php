<?php
session_start();
include 'db_baglanti.php';

if (!isset($_SESSION['uye_id'])) {
    header("Location: girisyap.php");
    exit();
}

$uye_id = $_SESSION['uye_id'];

$sql = "SELECT urun.* FROM favori 
        JOIN urun ON favori.urun_id = urun.urun_id 
        WHERE favori.uye_id = $uye_id";
$result = $conn->query($sql);

$favoriler = [];
$fav_sql = "SELECT urun_id FROM favori WHERE uye_id = $uye_id";
$fav_result = $conn->query($fav_sql);
while ($row = $fav_result->fetch_assoc()) {
    $favoriler[] = $row['urun_id'];
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="keywords" content="Aksesuar,Takı,Gözlük,Şapka,Çanta,Toka,Saç Aksesuarı">
  <meta name="description" content="Yakamoz Aksesuar’da favorilediğiniz ürünleri görün">
  <link rel="icon" type="image/png" href="YA-Dükkan Resimleri/icon.png">
  <title>Favoriler - Yakamoz Aksesuar</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container my-5">
<h2 class="mb-4 text-center" style="font-family: 'Playfair Display', serif;">
  Favorilerim
</h2>


  <div class="row gx-3 gy-4 justify-content-center my-4">
    <?php if ($result && $result->num_rows > 0): ?>
      <?php while ($urun = $result->fetch_assoc()):
        $favoride_mi = in_array($urun['urun_id'], $favoriler);
      ?>
        <div class="col-6 col-md-4 col-lg-3 mb-4 product-card-container">
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
      <div class="text-center my-5">
        <p style="font-size: 1.2rem;">Favorilediğiniz ürün bulunmamaktadır. <br> Hadi ürünlere bakalım! 🛍️</p>
        <a href="index.php" class="btn btn-dark mt-3">Alışverişe Başla</a>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php include 'footer.php'; ?>


<script>
function toggleFavori(urunId, element) {
    fetch('favori_ekle.php?urun_id=' + urunId)
        .then(response => {
            if (response.redirected) {
                window.location.href = response.url; 
                return;
            }

            let icon = element.querySelector('i');
            let card = element.closest('.product-card-container');

            if (icon.classList.contains('bi-heart')) {
                icon.classList.remove('bi-heart');
                icon.classList.add('bi-heart-fill');
            } else {
                icon.classList.remove('bi-heart-fill');
                icon.classList.add('bi-heart');

                if (window.location.pathname.includes('favoriler.php')) {
                    card.remove();
                    if (document.querySelectorAll('.product-card-container').length === 0) {
                        document.querySelector('.row').innerHTML = `
                          <div class="text-center my-5">
                            <p style="font-size: 1.2rem;">Favorilediğiniz ürün bulunmamaktadır. <br> Hadi ürünlere bakalım! 🛍️</p>
                            <a href="index.php" class="btn btn-dark mt-3">Alışverişe Başla</a>
                          </div>`;
                    }
                }
            }
        })
        .catch(err => console.error('Favori ekleme hatası:', err));
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
