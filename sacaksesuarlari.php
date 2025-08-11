<?php
session_start();
include 'db_baglanti.php';

$sac_kategori_id = 5;

$favoriler = [];
if (isset($_SESSION['uye_id'])) {
    $uye_id = $_SESSION['uye_id'];
    $fav_sql = "SELECT urun_id FROM favoriler WHERE uye_id = $uye_id";
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
  <meta name="keywords" content="Aksesuar,Saç Aksesuarı,Takı,Gözlük,Şapka,Çanta">
  <meta name="description" content="Yakamoz Aksesuar’da şık ve modern saç aksesuarları">
  <link rel="icon" type="image/png" href="YA-Dükkan Resimleri/icon.png">
  <title>Saç Aksesuarları - Yakamoz Aksesuar</title>
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
        <?php $favoride_mi = in_array($urun['urun_id'], $favoriler); ?>
        <div class="col-6 col-md-4 col-lg-3 mb-4">
          <div class="card product-card position-relative">

            <button class="btn btn-link position-absolute top-0 end-0 m-2 favori-btn" 
                    data-id="<?= $urun['urun_id'] ?>" 
                    style="font-size: 1.5rem; color: black;">
                <i class="bi <?= $favoride_mi ? 'bi-suit-heart-fill' : 'bi-suit-heart'; ?>"></i>
            </button>

            <img src="resimler/<?= $urun['resim'] ?>" 
                 class="img-fluid d-block w-100" 
                 alt="<?= htmlspecialchars($urun['urun_adi']) ?>">

            <div class="card-body product-card-body d-flex flex-column">
              <h5 class="card-title"><?= htmlspecialchars($urun['urun_adi']) ?></h5>
              <p class="card-text mb-auto"><?= $urun['fiyat'] ?> TL</p>
              <a href="#" 
                 class="btn float-end sepete-ekle" 
                 style="background-color: #000; color: #fff;" 
                 data-id="<?= $urun['urun_id'] ?>">
                 Sepete Ekle
              </a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="text-center">Bu kategoride ürün bulunmamaktadır.</p>
    <?php endif; ?>
  </div>
</div>

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

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
