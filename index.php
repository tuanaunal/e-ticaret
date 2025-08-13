<?php
session_start();
include 'db_baglanti.php';

$favoriler = [];
if (isset($_SESSION['uye_id'])) {
    $uye_id = $_SESSION['uye_id'];
    $fav_sql = "SELECT urun_id FROM favoriler WHERE uye_id = $uye_id";
    $fav_result = $conn->query($fav_sql);

    while ($row = $fav_result->fetch_assoc()) {
        $favoriler[] = $row['urun_id'];
    }
}
?>
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

    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="YA-Dükkan Resimleri/index1.jpg" class="d-block w-100" alt="Yakamoz Aksesuar">
      </div>
      <div class="carousel-item">
        <img src="YA-Dükkan Resimleri/index2.jpg" class="d-block w-100" alt="Yakamoz Aksesuar">
      </div>
      <div class="carousel-item">
        <img src="YA-Dükkan Resimleri/index3.jpg" class="d-block w-100" alt="Yakamoz Aksesuar">
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>

</div>


<div class="text-black py-1 mt-3" style="background-color: #000; color: #fff;">
  <marquee behavior="scroll" direction="left" scrollamount="5">
    🛍️ <strong style="font-family: 'Playfair Display', serif; font-size: 1rem; color: white">1.500 TL ve Üzeri Alışverişlerinizde Kargo Ücretsiz!</strong>
  </marquee>
</div>


<section id="populer" class="container py-5 mt-5">
  <h2 class="mb-4" style="font-family: 'Playfair Display', serif; font-size: 1.5rem;">Popüler Ürünler</h2>

  <div class="d-flex overflow-auto gap-4 pb-3">
    <?php
    $sql_populer = "SELECT * FROM urun WHERE populer = 1 LIMIT 10";
    $result_populer = $conn->query($sql_populer);
    
    if ($result_populer && $result_populer->num_rows > 0):
      while ($row = $result_populer->fetch_assoc()):
        $favoride_mi = in_array($row['urun_id'], $favoriler);
    ?>
        <div class="col-6 col-md-4 col-lg-3 mb-4">
          <div class="card product-card position-relative">
            <button class="btn btn-link position-absolute top-0 end-0 m-2 favori-btn" 
                    data-id="<?= $row['urun_id'] ?>" 
                    style="font-size: 1.5rem; color: black;">
                <i class="bi <?= $favoride_mi ? 'bi-suit-heart-fill' : 'bi-suit-heart'; ?>"></i>
            </button>

            <img src="Resimler/<?= $row['resim'] ?>" class="img-fluid d-block w-100" alt="<?= htmlspecialchars($row['urun_adi']) ?>">

            <div class="card-body product-card-body d-flex flex-column">
              <h5 class="card-title"><?= htmlspecialchars($row['urun_adi']) ?></h5>
              <p class="card-text mb-auto"><?= $row['fiyat'] ?> TL</p>
              <a href="#" 
                 class="btn float-end sepete-ekle" 
                 style="background-color: #000; color: #fff;" 
                 data-id="<?= $row['urun_id'] ?>">
                 Sepete Ekle
              </a>
            </div>
          </div>
        </div>
    <?php endwhile; else: ?>
      <p>Popüler ürün bulunamadı.</p>
    <?php endif; ?>
  </div>
</section>


<section id="yenigelen" class="container py-5 mt-0">
  <h2 class="mb-4" style="font-family: 'Playfair Display', serif; font-size: 1.5rem;">Yeni Gelen Ürünler</h2>

  <div class="d-flex overflow-auto gap-4 pb-3">
    <?php
    $sql_yeni = "SELECT * FROM urun WHERE yeni_gelen = 1 LIMIT 10";
    $result_yeni = $conn->query($sql_yeni);
    
    if ($result_yeni && $result_yeni->num_rows > 0):
      while ($row = $result_yeni->fetch_assoc()):
        $favoride_mi = in_array($row['urun_id'], $favoriler);
    ?>
        <div class="col-6 col-md-4 col-lg-3 mb-4">
          <div class="card product-card position-relative">
            <button class="btn btn-link position-absolute top-0 end-0 m-2 favori-btn" 
                    data-id="<?= $row['urun_id'] ?>" 
                    style="font-size: 1.5rem; color: black;">
                <i class="bi <?= $favoride_mi ? 'bi-suit-heart-fill' : 'bi-suit-heart'; ?>"></i>
            </button>

            <img src="Resimler/<?= $row['resim'] ?>" class="img-fluid d-block w-100" alt="<?= htmlspecialchars($row['urun_adi']) ?>">

            <div class="card-body product-card-body d-flex flex-column">
              <h5 class="card-title"><?= htmlspecialchars($row['urun_adi']) ?></h5>
              <p class="card-text mb-auto"><?= $row['fiyat'] ?> TL</p>
              <a href="#" 
                 class="btn float-end sepete-ekle" 
                 style="background-color: #000; color: #fff;" 
                 data-id="<?= $row['urun_id'] ?>">
                 Sepete Ekle
              </a>
            </div>
          </div>
        </div>
    <?php endwhile; else: ?>
      <p>Yeni gelen ürün bulunamadı.</p>
    <?php endif; ?>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){

  $(".favori-btn").click(function(e){
      e.preventDefault();
      var urunId = $(this).data("id");
      var btn = $(this);

      $.ajax({
          url: "favori_toggle.php",
          type: "POST",
          data: { urun_id: urunId },
          success: function(response){
              var data;
              try { data = (typeof response === 'object') ? response : JSON.parse(response); }
              catch(e){
                if (typeof bildirimGoster === 'function') bildirimGoster('Beklenmedik cevap!', 'danger', 4000);
                return;
              }

              if(data.status === "error" && data.redirect){
                  window.location.href = data.redirect;
                  return;
              }

              if(data.status === "success"){
                  if ($("#favori-sayi").length && typeof data.favori_sayi !== "undefined"){
                      $("#favori-sayi").text(data.favori_sayi);
                  }

                  if(data.action === "added"){
                      btn.find("i").removeClass("bi-suit-heart").addClass("bi-suit-heart-fill");
                      if (typeof bildirimGoster === 'function') bildirimGoster("Favorilere eklendi ❤️", "success");
                  } else {
                      btn.find("i").removeClass("bi-suit-heart-fill").addClass("bi-suit-heart");
                      if (typeof bildirimGoster === 'function') bildirimGoster("Favorilerden kaldırıldı", "secondary");
                  }
              } else {
                  var hata = data.message || data.mesaj || "İşlem başarısız.";
                  if (typeof bildirimGoster === 'function') bildirimGoster(hata, 'danger', 4000);
              }
          },
          error: function(){
              if (typeof bildirimGoster === 'function') bildirimGoster('Ağ hatası!', 'danger', 4000);
          }
      });
  });

  $(".sepete-ekle").click(function(e){
      e.preventDefault();
      var urunId = $(this).data("id");

      $.ajax({
          url: "sepet_ekle.php",
          type: "POST",
          data: { urun_id: urunId },
          success: function(response){
              var data;
              try { data = (typeof response === 'object') ? response : JSON.parse(response); }
              catch(e){
                if (typeof bildirimGoster === 'function') bildirimGoster('Beklenmedik cevap!', 'danger', 4000);
                return;
              }

              if(data.status === "error" && data.redirect){
                  window.location.href = data.redirect;
                  return;
              }

              if(data.status === "success"){
                  if ($("#sepet-sayi").length && typeof data.toplam_adet !== "undefined"){
                      $("#sepet-sayi").text(data.toplam_adet);
                  }
                  if (typeof bildirimGoster === 'function') bildirimGoster('Sepete eklendi ✅', 'primary');
              } else {
                  var hata = data.message || data.mesaj || "Sepete ekleme başarısız.";
                  if (typeof bildirimGoster === 'function') bildirimGoster(hata, 'danger', 4000);
              }
          },
          error: function(){
              if (typeof bildirimGoster === 'function') bildirimGoster('Ağ hatası!', 'danger', 4000);
          }
      });
  });

});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
