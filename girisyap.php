<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include 'db_baglanti.php';

$aktifSekme = (isset($_GET['tab']) && $_GET['tab'] === 'register') ? 'register' : 'login';

$redirect = isset($_GET['redirect']) ? trim($_GET['redirect']) : (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php');

$flashError = isset($_GET['error']) ? $_GET['error'] : '';
$flashOk    = isset($_GET['success']) ? $_GET['success'] : '';
?>
<!DOCTYPE html>
<html lang="tr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="keywords" content="Aksesuar,Takı,Gözlük,Şapka,Çanta,Toka,Saç Aksesuarı">
  <meta name="description" content="Yakamoz Aksesuar’da hesabınıza giriş yapın veya üye olun">
  <link rel="icon" type="image/png" href="YA-Dükkan Resimleri/icon.png">
  <title>Giriş / Üye Ol - Yakamoz Aksesuar</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body>
  <?php include 'navbar.php'; ?>

  <div class="d-flex justify-content-center align-items-center mt-4" style="min-height: 80vh;">
    <div class="card text-center shadow" style="width: 100%; max-width: 400px;">
      <div class="card-body">
        <ul class="nav nav-tabs justify-content-center mb-4" id="authTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link <?= $aktifSekme==='login' ? 'active' : '' ?>"
                    id="giris-tab" data-bs-toggle="tab" data-bs-target="#giris"
                    type="button" role="tab" aria-controls="giris" aria-selected="<?= $aktifSekme==='login'?'true':'false' ?>">
              Giriş Yap
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link <?= $aktifSekme==='register' ? 'active' : '' ?>"
                    id="uyeol-tab" data-bs-toggle="tab" data-bs-target="#uyeol"
                    type="button" role="tab" aria-controls="uyeol" aria-selected="<?= $aktifSekme==='register'?'true':'false' ?>">
              Üye Ol
            </button>
          </li>
        </ul>

        <?php if ($flashError): ?>
          <div class="alert alert-danger py-2"><?= htmlspecialchars($flashError) ?></div>
        <?php endif; ?>
        <?php if ($flashOk): ?>
          <div class="alert alert-success py-2"><?= htmlspecialchars($flashOk) ?></div>
        <?php endif; ?>

        <div class="tab-content text-start">
          <div class="tab-pane fade <?= $aktifSekme==='login' ? 'show active' : '' ?>" id="giris" role="tabpanel" aria-labelledby="giris-tab" tabindex="0">
            <form method="POST" action="giris_islem.php">
              <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect) ?>">
              <div class="form-floating mb-3">
                <input type="email" class="form-control" name="email" placeholder="name@example.com" required>
                <label>E-posta Adresi</label>
              </div>
              <div class="form-floating mb-3">
                <input type="password" class="form-control" name="sifre" placeholder="Şifre" required>
                <label>Şifre</label>
                <div id="passwordHelpBlock" class="form-text fs-smaller">
                  Şifreniz 8 karakterli ve boşluk içermemeli
                </div>
              </div>
              <button type="submit" class="btn w-100" style="background-color: #000; color: #fff;">Giriş Yap</button>
            </form>
          </div>

          <div class="tab-pane fade <?= $aktifSekme==='register' ? 'show active' : '' ?>" id="uyeol" role="tabpanel" aria-labelledby="uyeol-tab" tabindex="0">
            <form action="uye_kayit.php" method="POST">
              <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect) ?>">

              <div class="form-floating mb-3">
                <input type="text" class="form-control" name="ad" placeholder="Ad" required>
                <label>Ad</label>
              </div>
              <div class="form-floating mb-3">
                <input type="text" class="form-control" name="soyad" placeholder="Soyad" required>
                <label>Soyad</label>
              </div>
              <div class="form-floating mb-3">
                <input type="email" class="form-control" name="email" placeholder="name@example.com" required>
                <label>E-posta Adresi</label>
              </div>
              <div class="form-floating mb-3">
                <input type="tel" class="form-control" name="telefon" placeholder="Telefon" required>
                <label>Telefon Numarası</label>
              </div>
              <div class="form-floating mb-3">
                <input type="date" class="form-control" name="dogum_tarihi" required>
                <label>Doğum Tarihi</label>
              </div>
              <div class="form-floating mb-3">
                <input type="password" class="form-control" name="sifre" placeholder="Şifre" required>
                <label>Şifre</label>
                <div id="passwordHelpBlock2" class="form-text fs-smaller">
                  Şifreniz 8 karakterli ve boşluk içermemeli
                </div>
              </div>

              <div class="col-12">
                <div class="form-check text-start">
                  <input class="form-check-input" type="checkbox" name="iletisim_izin" id="chkIleti" style="transform: scale(1.5);" required>
                  <label class="form-check-label" for="chkIleti">
                    Tarafıma 'Elektronik İleti' gönderilmesini kabul ediyorum.
                  </label>
                </div>
                <div class="form-check mt-2 text-start">
                  <input class="form-check-input" type="checkbox" name="kvkk_izin" id="chkKvkk" style="transform: scale(1.5);" required>
                  <label class="form-check-label" for="chkKvkk">
                    Yakamoz Aksesuar tarafından kişisel verilerimin işlenmesine açık rıza veriyorum.
                  </label>
                </div>
              </div>

              <button type="submit" class="btn w-100 mt-3" style="background-color: #000; color: #fff;">Kayıt Ol</button>
            </form>
          </div>
        </div>

      </div>
    </div>
  </div>

  <?php include 'footer.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    (function(){
      var hash = window.location.hash;
      if (hash === '#uye-ol') {
        var tabBtn = document.querySelector('#uyeol-tab');
        if (tabBtn) new bootstrap.Tab(tabBtn).show();
      } else if (hash === '#giris') {
        var tabBtn2 = document.querySelector('#giris-tab');
        if (tabBtn2) new bootstrap.Tab(tabBtn2).show();
      }
    })();
  </script>
</body>
</html>
