<?php
session_start();
include 'db_baglanti.php';

if (!isset($_SESSION['uye_id'])) {
    header("Location: girisyap.php");
    exit();
}

$uye_id = $_SESSION['uye_id'];

$sql = "SELECT sepet.*, urun.urun_adi, urun.fiyat, urun.resim 
        FROM sepet 
        JOIN urun ON sepet.urun_id = urun.urun_id 
        WHERE sepet.uye_id = $uye_id";
$result = $conn->query($sql);

$total_price = 0;
$items = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
        $total_price += $row['fiyat'] * $row['adet'];
    }
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
<title>Sepetim - Yakamoz Aksesuar</title>
<link rel="stylesheet" href="style.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
.card.product-card {
  display: flex;
  flex-direction: column;
  height: 100%;
  min-height: 500px; 
  border: 1px solid #ddd;
  border-radius: 8px;
}
.product-card-body {
  display: flex;
  flex-direction: column;
  flex-grow: 1;
  padding: 0.75rem 0.75rem 1rem; 
}
.quantity-controls { margin-top: auto; display: flex; justify-content: flex-end; gap: .5rem; }
.quantity-controls button { width: 35px; height: 35px; font-size: 1.4rem; padding: 0; }
.total-checkout {
  display: flex; justify-content: center; align-items: center; gap: 1.5rem;
  margin: 4rem 0; flex-wrap: wrap;
}

.empty-state { text-align:center; margin-top: 1.25rem; padding-bottom: 45vh; }
</style>
</head>
<body>

<?php include 'navbar.php'; ?>

<main class="container my-5">
  <h2 class="mb-2 text-center" style="font-family: 'Playfair Display', serif;">
    Sepetim
  </h2>

  <div id="sepet-listesi" class="row gx-3 gy-4 justify-content-center my-4">
    <?php if (!empty($items)): ?>
      <?php foreach ($items as $urun): ?>
        <div class="col-6 col-md-4 col-lg-3 mb-4 product-card-container">
          <div class="card product-card position-relative">
            <img src="resimler/<?= $urun['resim'] ?>" 
                 class="img-fluid d-block w-100" 
                 alt="<?= htmlspecialchars($urun['urun_adi']) ?>">

            <div class="card-body product-card-body">
              <h5 class="card-title"><?= htmlspecialchars($urun['urun_adi']) ?></h5>
              <p class="card-text mb-auto"><?= $urun['fiyat'] ?> TL</p>
              <p class="card-text">Adet: <strong id="adet-<?= $urun['urun_id'] ?>"><?= $urun['adet'] ?></strong></p>

              <div class="quantity-controls">
                <button class="btn btn-sm btn-dark sepet-arttir" data-id="<?= $urun['urun_id'] ?>">+</button>
                <button class="btn btn-sm btn-outline-dark sepet-azalt" data-id="<?= $urun['urun_id'] ?>">-</button>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>

      <div class="total-checkout">
  <div class="card shadow-sm px-5 py-4" style="border: 3px solid black; border-radius: 10px;">
    <h4 class="text-center mb-3">Toplam: <?= $total_price ?> TL</h4>
    <div class="d-flex justify-content-center gap-3 flex-wrap">
      <a href="siparis_form.php" class="btn btn-dark btn-lg">Sepeti Onayla</a>
      <a href="sepet_temizle.php" class="btn btn-dark btn-lg sepet-temizle-btn">Sepeti Temizle</a>
    </div>
  </div>
</div>


    <?php else: ?>
      <div class="col-12">
        <div class="empty-state">
          <p class="mb-2" style="font-size: 1.2rem;">
            Sepetiniz boş. <br> Hadi ürünlere bakalım! 🛍️
          </p>
          <a href="index.php" class="btn btn-dark">Alışverişe Başla</a>
        </div>
      </div>
    <?php endif; ?>
  </div>
</main>

<?php include 'footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function(){
  function asJson(resp){ if (typeof resp === 'object') return resp; try { return JSON.parse(resp); } catch(e){ return null; } }
  function show(msg, type, ms){ if (typeof bildirimGoster === 'function') bildirimGoster(msg, type, ms||3000); }

  $(document).on('click', '.sepet-arttir', function(e){
    e.preventDefault();
    var urunId = $(this).data('id');
    $.ajax({
      url: 'sepet_ekle.php', type: 'POST', data: { urun_id: urunId },
      success: function(resp){
        var data = asJson(resp);
        if (!data){ show('Beklenmedik cevap!', 'danger', 4000); return; }
        if (data.redirect){ window.location.href = data.redirect; return; }
        if (data.status === 'success'){
          if (typeof data.toplam_adet !== 'undefined') $('#sepet-sayi').text(data.toplam_adet);
          show('Adet artırıldı', 'success'); setTimeout(function(){ location.reload(); }, 500);
        } else { show(data.message || data.mesaj || 'Adet artırılamadı.', 'danger', 4000); }
      }, error: function(xhr){ show('Ağ hatası: ' + (xhr.responseText || ''), 'danger', 4000); }
    });
  });

  $(document).on('click', '.sepet-azalt', function(e){
    e.preventDefault();
    var urunId = $(this).data('id');
    $.ajax({
      url: 'sepet_sil.php', type: 'POST', data: { urun_id: urunId },
      success: function(resp){
        var data = asJson(resp);
        if (!data){ show('Beklenmedik cevap!', 'danger', 4000); return; }
        if (data.redirect){ window.location.href = data.redirect; return; }
        if (data.status === 'success'){
          if (typeof data.toplam_adet !== 'undefined') $('#sepet-sayi').text(data.toplam_adet);
          show('Adet azaltıldı', 'secondary'); setTimeout(function(){ location.reload(); }, 500);
        } else { show(data.message || data.mesaj || 'Adet azaltılamadı.', 'danger', 4000); }
      }, error: function(xhr){ show('Ağ hatası: ' + (xhr.responseText || ''), 'danger', 4000); }
    });
  });

  $(document).on('click', '.sepet-temizle-btn, a[href="sepet_temizle.php"]', function(e){
    e.preventDefault();
    $.ajax({
      url: 'sepet_temizle.php', type: 'POST', dataType: 'json',
      success: function(res){
        if (res && res.redirect){ window.location.href = res.redirect; return; }
        if (res && res.status === 'success'){
          show(res.message || 'Sepet temizlendi', 'secondary');
          $('#sepet-listesi').html(`
            <div class="col-12">
              <div class="empty-state">
                <p class="mb-2" style="font-size: 1.2rem;">
                  Sepetiniz boş. <br> Hadi ürünlere bakalım! 🛍️
                </p>
                <a href="index.php" class="btn btn-dark">Alışverişe Başla</a>
              </div>
            </div>
          `);
          $('#sepet-sayi').text( typeof res.toplam_adet !== 'undefined' ? res.toplam_adet : 0 );
        } else {
          show( (res && (res.message||res.mesaj)) ? (res.message||res.mesaj) : 'Sepet temizlenemedi.', 'danger', 4000);
        }
      }, error: function(xhr){ show('Ağ hatası: ' + (xhr.responseText || ''), 'danger', 4000); }
    });
  });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
