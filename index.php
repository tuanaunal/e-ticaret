<!DOCTYPE html>
<html lang="tr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="keywords" content="Aksesuar,Takı,Gözlük,Şapka,Çanta,Toka,Saç Aksesuarı">
  <meta name="description" content="Yakamoz Aksesuar’da zarif takılar, gözlükler, şapkalar, çantalar ve saç aksesuarlarıyla tarzına ışıltı kat. Hızlı kargo, güvenli ödeme ve uygun fiyat seni bekliyor!">
  <link rel="icon" type="image/png" href="YA-Dükkan Resimleri/icon.png">
  <title>Yakamoz Aksesuar</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap" rel="stylesheet">
</head>

<body data-bs-spy="scroll" data-bs-target="#navbar-example" data-bs-offset="100" tabindex="0">
  
<?php include 'navbar.php'; ?>


<div class="container my-4">
  <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
<div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="YA-Dükkan Resimleri/index1.jpg" class="d-block w-100 style=height: 400px; object-fit: cover;" alt="Yakamoz Aksesuar">
    </div>
    <div class="carousel-item">
      <img src="YA-Dükkan Resimleri/index2.jpg" class="d-block w-100 style=height: 400px; object-fit: cover;" alt="Yakamoz Aksesuar">
    </div>
    <div class="carousel-item">
      <img src="YA-Dükkan Resimleri/index3.jpg" class="d-block w-100 style=height: 400px; object-fit: cover;" alt="Yakamoz Aksesuar">
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>
</div>
</div>

<div class="text-black py-1 mt-3" style="background-color: #000; color: #fff;">
  <marquee behavior="scroll" direction="left" scrollamount="5">
    🛍️ <strong style="font-family: 'Playfair Display', serif; font-size: 1rem; color: white">1.500 TL ve Üzeri Alışverişlerinizde Kargo Ücretsiz!</strong>
  </marquee>
</div>

<!-- POPÜLER ÜRÜNLER -->
<section id="populer" class="container py-5 mt-5" style="margin-top: 100px;">
  <h2 class="mb-4" style="font-family: 'Playfair Display', serif; font-size: 1.5rem;">Popüler Ürünler</h2>

  <div class="d-flex overflow-auto gap-4 pb-3">
    <?php
    include 'db_baglanti.php'; 
    
    $sql = "SELECT * FROM urun WHERE kategori_id = 7";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '
            <div class="col-6 col-md-4 col-lg-3 mb-4">
              <div class="card product-card">
                <img src="Resimler/' . $row['resim'] . '" class="img-fluid d-block w-100" alt="' . $row['urun_adi'] . '">
                <div class="card-body product-card-body d-flex flex-column">
                  <h5 class="card-title">' . $row['urun_adi'] . '</h5>
                  <p class="card-text mb-auto">' . $row['fiyat'] . ' TL</p>
                  <a href="#" class="btn float-end" style="background-color: #000; color: #fff;">Sepete Ekle</a>
                </div>
              </div>
            </div>
            ';
        }
    } else {
        echo "<p>Popüler ürün bulunamadı.</p>";
    }
    ?>
  </div>
</section>

<!-- YENİ GELEN ÜRÜNLER -->

<section id="yenigelen" class="container py-5 mt-0" style="margin-top: 100px;">
  <h2 class="mb-4" style="font-family: 'Playfair Display', serif; font-size: 1.5rem;">Yeni Gelen Ürünler</h2>

  <div class="d-flex overflow-auto gap-4 pb-3">
    <?php
    include 'db_baglanti.php'; 

    $sql = "SELECT * FROM urun WHERE kategori_id = 8";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '
            <div class="col-6 col-md-4 col-lg-3 mb-4">
              <div class="card product-card">
                <img src="Resimler/' . $row['resim'] . '" class="img-fluid d-block w-100" alt="' . $row['urun_adi'] . '">
                <div class="card-body product-card-body d-flex flex-column">
                  <h5 class="card-title">' . $row['urun_adi'] . '</h5>
                  <p class="card-text mb-auto">' . $row['fiyat'] . ' TL</p>
                  <a href="#" class="btn float-end" style="background-color: #000; color: #fff;">Sepete Ekle</a>
                </div>
              </div>
            </div>
            ';
        }
    } else {
        echo "<p>Yeni gelen ürün bulunamadı.</p>";
    }
    ?>
  </div>
</section>


<div class="container w-100 mt-3">
  <div class="card mx-auto">
  <div class="row">

      <div class="col-12 col-md-4">
        <div style="height: 100%; max-height: 320px; overflow: hidden;">
          <img src="YA-Dükkan Resimleri/renklika1.jpeg"
               class="img-fluid rounded-start w-100 h-100"
               alt="Yakamoz Aksesuar"
               style="object-fit: cover;">
        </div>
      </div>
      
      <div class="col-12 col-md-8 p-4 text-center">
        <div class="card-body" style="padding: 1rem;">
          <strong class="card-title mt-3" style="font-family: 'Playfair Display', serif; font-size: 1.5rem;">
            <h2>✨ Yakamoz Gibi ✨</h2>
          </strong>
          <p class="card-text d-flex flex-column justify-content-md-center" style="font-size: 0.9rem;">
            Kimi zaman zarif bir kolye, kimi zaman rüzgârla dans eden bir saç tokası… Tarzını tamamlayan o küçük ama etkileyici dokunuş, seni sen yapan en özel ayrıntı olabilir. Yakamoz Aksesuar; takıdan çantaya, tokadan saç aksesuarlarına uzanan zarif koleksiyonlarıyla, her anına romantik bir ışıltı katmak için burada. Göz alıcı ama sade, iddialı ama samimi parçalarla hem kalbini hem stilini yansıtabilirsin. Çünkü bazen bir detay, tüm görünümün ruhunu değiştirebilir. Ve sen her zaman o parıltıyı hak ediyorsun.
          </p>
        </div>
      </div>

    </div>
  </div>
</div>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>





