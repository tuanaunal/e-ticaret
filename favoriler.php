<?php
session_start();
include 'db_baglanti.php';

if (!isset($_SESSION['uye_id'])) {
    header("Location: girisyap.php");
    exit();
}

$uye_id = $_SESSION['uye_id'];

$sql = "SELECT urun.* FROM favoriler 
        JOIN urun ON favoriler.urun_id = urun.urun_id 
        WHERE favoriler.uye_id = $uye_id";
$result = $conn->query($sql);

$favoriler = [];
$fav_sql = "SELECT urun_id FROM favoriler WHERE uye_id = $uye_id";
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
  <title>Favorilerim - Yakamoz Aksesuar</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body { display:flex; flex-direction:column; min-height:100vh; }
    main { flex:1; display:flex; flex-direction:column; min-height:calc(100vh - 150px); }
  </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<main class="container my-5">
  <h2 class="mb-4 text-center" style="font-family: 'Playfair Display', serif;">
    Favorilerim
  </h2>

  <div id="favori-listesi" class="row gx-3 gy-4 justify-content-center my-4">
    <?php if ($result && $result->num_rows > 0): ?>
      <?php while ($urun = $result->fetch_assoc()):
        $favoride_mi = in_array($urun['urun_id'], $favoriler);
      ?>
        <div class="col-6 col-md-4 col-lg-3 mb-4 product-card-container">
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

      <div class="d-flex justify-content-center my-4">
        <a href="favoriler_temizle.php" class="btn btn-dark btn-lg">Favorileri Temizle</a>
      </div>

    <?php else: ?>
      <div class="text-center my-5">
        <p style="font-size: 1.2rem;">
          Favorilediğiniz ürün bulunmamaktadır. <br> Hadi ürünlere bakalım! 🛍️
        </p>
        <a href="index.php" class="btn btn-dark mt-3">Alışverişe Başla</a>
      </div>
    <?php endif; ?>

  </div>
</main>

<?php include 'footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function(){

  $(".favori-btn").on("click", function(e){
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

              if (data.status === "error" && data.redirect){
                  window.location.href = data.redirect;
                  return;
              }

              if (data.status === "success"){
                  if ($("#favori-sayi").length && typeof data.favori_sayi !== "undefined"){
                      $("#favori-sayi").text(data.favori_sayi);
                  }

                  if (data.action === "added"){
                      btn.find("i").removeClass("bi-suit-heart").addClass("bi-suit-heart-fill");
                      if (typeof bildirimGoster === 'function') bildirimGoster("Favorilere eklendi ❤️", "success");
                  } else {
                      btn.find("i").removeClass("bi-suit-heart-fill").addClass("bi-suit-heart");
                      if (typeof bildirimGoster === 'function') bildirimGoster("Favorilerden kaldırıldı", "secondary");

                      btn.closest('.product-card-container').remove();
                      if ($(".product-card-container").length === 0) {
                          $("#favori-listesi").html(`
                            <div class="text-center my-5">
                              <p style="font-size: 1.2rem;">Favorilediğiniz ürün bulunmamaktadır. <br> Hadi ürünlere bakalım! 🛍️</p>
                              <a href="index.php" class="btn btn-dark mt-3">Alışverişe Başla</a>
                            </div>
                          `);
                      }
                  }
              } else {
                  var hata = data.message || data.mesaj || "Bir şey ters gitti.";
                  if (typeof bildirimGoster === 'function') bildirimGoster(hata, 'danger', 4000);
              }
          },
          error: function(){
              if (typeof bildirimGoster === 'function') bildirimGoster('Ağ hatası!', 'danger', 4000);
          }
      });
  });

  $(".sepete-ekle").on("click", function(e){
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

          if (data.status === "error" && data.redirect){
            window.location.href = data.redirect;
            return;
          }

          if (data.status === "success"){
            if ($("#sepet-sayi").length && typeof data.toplam_adet !== "undefined"){
              $("#sepet-sayi").text(data.toplam_adet);
            }
            if (typeof bildirimGoster === 'function') bildirimGoster('Sepete eklendi ✅', 'primary');
          } else {
            var msg = data.message || data.mesaj || "Sepete ekleme başarısız.";
            if (typeof bildirimGoster === 'function') bildirimGoster(msg, 'danger', 4000);
          }
        },
        error: function(){
          if (typeof bildirimGoster === 'function') bildirimGoster('Ağ hatası!', 'danger', 4000);
        }
      });
  });

  $(document).on('click','a[href="favoriler_temizle.php"]', function(e){
      e.preventDefault();
      var href = this.href;

      $.ajax({
        url: 'favoriler_temizle.php',
        type: 'POST',
        dataType: 'json',
        success: function(res){
          if (res && (res.status === 'success' || res.ok === true || res.ok === 'true')) {
            if (typeof bildirimGoster === 'function') bildirimGoster('Favoriler temizlendi', 'secondary');
            $("#favori-listesi").html(`
              <div class="text-center my-5">
                <p style="font-size: 1.2rem;">Favorilediğiniz ürün bulunmamaktadır. <br> Hadi ürünlere bakalım! 🛍️</p>
                <a href="index.php" class="btn btn-dark mt-3">Alışverişe Başla</a>
              </div>
            `);
            if (typeof res.favori_sayi !== 'undefined') {
              $("#favori-sayi").text(res.favori_sayi);
            } else {
              $("#favori-sayi").text('0');
            }
          } else {
            window.location.href = href;
          }
        },
        error: function(){
          window.location.href = href;
        }
      });
  });

});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
