<?php
session_start();
include 'db_baglanti.php';

if (!isset($_SESSION['uye_id'])) {
  $_SESSION['redirect_to'] = 'siparis_form.php';
  header("Location: girisyap.php");
  exit();
}
$uye_id = (int)$_SESSION['uye_id'];

$stmt = $conn->prepare("SELECT * FROM adres WHERE uye_id = ? ORDER BY adres_id DESC");
$stmt->bind_param("i", $uye_id);
$stmt->execute();
$adresler = $stmt->get_result();

$odemeler = $conn->query("SELECT odeme_yontemi_id, ad FROM odeme_yontemi ORDER BY odeme_yontemi_id");

$sqlToplam = "SELECT COALESCE(SUM(s.adet * COALESCE(s.birim_fiyat, u.fiyat)),0) AS toplam
              FROM sepet s
              JOIN urun u ON u.urun_id = s.urun_id
              WHERE s.uye_id = ?";
$tstmt = $conn->prepare($sqlToplam);
$tstmt->bind_param("i", $uye_id);
$tstmt->execute();
$toplam = (float)($tstmt->get_result()->fetch_assoc()['toplam'] ?? 0);

$hata = "";
if (isset($_GET['hata'])) {
  $map = [
    'odeme' => 'Lütfen bir ödeme yöntemi seçin.',
    'adres' => 'Lütfen kayıtlı bir adres seçin veya yeni adres alanlarını doldurun.',
    'sepet' => 'Sepetiniz boş. Ürün ekledikten sonra tekrar deneyin.',
    'genel' => 'İşlem sırasında bir hata oluştu. Lütfen tekrar deneyin.'
  ];
  $hata = $map[$_GET['hata']] ?? '';
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="keywords" content="Aksesuar,Şapka,Takı,Gözlük,Çanta,Saç Aksesuarı">
  <meta name="description" content="Yakamoz Aksesuar’da şık ve modern şapkalar">
  <link rel="icon" type="image/png" href="YA-Dükkan Resimleri/icon.png">
  <title>Sipariş – Adres & Ödeme - Yakamoz Aksesuar</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>.checkout-card{max-width:900px;margin:0 auto}</style>
</head>
<body class="bg-light">
<?php include 'navbar.php'; ?>

<main class="container my-5 checkout-card">
  <h2 class="mb-4 text-center" style="font-family:'Playfair Display',serif;">Teslimat Adresi & Ödeme</h2>

  <?php if ($hata): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($hata) ?></div>
  <?php endif; ?>

  <form method="post" action="siparis_olustur.php" class="card p-3 shadow-sm" id="siparisForm">
    <div class="mb-3">
      <div class="d-flex justify-content-between align-items-center">
        <label class="form-label fw-semibold m-0">Kayıtlı Adreslerim</label>
        <a class="btn btn-sm btn-outline-secondary" data-bs-toggle="collapse" href="#yeniAdres" role="button">
          Yeni Adres Ekle
        </a>
      </div>

      <?php if ($adresler->num_rows > 0): ?>
        <?php while($a = $adresler->fetch_assoc()): ?>
          <div class="form-check mt-2">
            <input class="form-check-input adres-radio" type="radio" name="adres_id" value="<?= (int)$a['adres_id'] ?>">
            <label class="form-check-label">
              <strong><?= htmlspecialchars($a['ad_soyad']) ?></strong> - <?= htmlspecialchars($a['telefon']) ?><br>
              <?= htmlspecialchars($a['ilce']) ?>/<?= htmlspecialchars($a['il']) ?> <?= htmlspecialchars($a['posta_kodu']) ?><br>
              <?= nl2br(htmlspecialchars($a['acik_adres'])) ?>
            </label>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <div class="alert alert-warning my-2">Kayıtlı adresiniz yok. Aşağıdan yeni bir adres ekleyin.</div>
      <?php endif; ?>
    </div>

    <div class="collapse <?= ($adresler->num_rows===0 ? 'show' : '') ?>" id="yeniAdres">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Ad Soyad *</label>
          <input name="ad_soyad" class="form-control">
        </div>
        <div class="col-md-6">
          <label class="form-label">Telefon *</label>
          <input name="telefon" class="form-control">
        </div>
        <div class="col-md-4">
          <label class="form-label">İl *</label>
          <input name="il" class="form-control">
        </div>
        <div class="col-md-4">
          <label class="form-label">İlçe *</label>
          <input name="ilce" class="form-control">
        </div>
        <div class="col-md-4">
          <label class="form-label">Posta Kodu</label>
          <input name="posta_kodu" class="form-control">
        </div>
        <div class="col-12">
          <label class="form-label">Açık Adres *</label>
          <textarea name="acik_adres" rows="3" class="form-control"></textarea>
        </div>
      </div>
      <hr class="my-3">
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">Ödeme Yöntemi</label>
      <?php if ($odemeler && $odemeler->num_rows): ?>
        <?php while($o = $odemeler->fetch_assoc()): ?>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="odeme_yontemi_id" value="<?= (int)$o['odeme_yontemi_id'] ?>" required>
            <label class="form-check-label"><?= htmlspecialchars($o['ad']) ?></label>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <div class="alert alert-danger">Ödeme yöntemi bulunamadı.</div>
      <?php endif; ?>
    </div>

    <div class="d-flex justify-content-between align-items-center border-top pt-3">
      <div class="fw-semibold fs-5">Toplam: <?= number_format($toplam, 2) ?> TL</div>
      <button class="btn btn-dark" <?= $toplam<=0 ? 'disabled' : '' ?>>Siparişi Tamamla</button>
    </div>
  </form>
</main>

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

<script>
(function(){
  const form = document.getElementById('siparisForm');
  const adresRadios = document.querySelectorAll('.adres-radio');
  const yeniAdresFields = ['ad_soyad','telefon','il','ilce','acik_adres'].map(n => form.querySelector('[name="'+n+'"]'));

  function anyAdresSelected(){
    for (const r of adresRadios) if (r.checked) return true;
    return false;
  }

  function setYeniAdresRequired(on){
    yeniAdresFields.forEach(el => on ? el.setAttribute('required','required') : el.removeAttribute('required'));
  }

  setYeniAdresRequired(!anyAdresSelected());

  adresRadios.forEach(radio=>{
    radio.addEventListener('change', ()=> setYeniAdresRequired(!anyAdresSelected()));
  });

  form.addEventListener('submit', function(e){
    setYeniAdresRequired(!anyAdresSelected());
  });
})();
</script>
</body>
</html>
