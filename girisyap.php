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
</head>

<body>
  <?php include 'navbar.php'; ?>

  <div class="d-flex justify-content-center align-items-center" style="min-height: 40vh;">
    <div class="card text-center shadow" style="width: 100%; max-width: 400px;">
      <div class="card-body">
        <h5 class="card-title mb-4">Giriş Yap</h5>

        <?php if (isset($_GET['error'])): ?>
          <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>

        <form method="POST" action="giris_islem.php">
          <div class="form-floating mb-3">
            <input type="email" class="form-control" name="email" placeholder="name@example.com" required>
            <label for="floatingInput">E-posta Adresi</label>
          </div>
          <div class="form-floating mb-3">
            <input type="password" class="form-control" name="sifre" placeholder="Şifre" required>
            <label for="floatingPassword">Şifre</label>
            <div id="passwordHelpBlock" class="form-text fs-smaller">
              Şifreniz 8 karakterli ve boşluk içermemeli
            </div>
          </div>
          <button type="submit" class="btn w-100" style="background-color: #000; color: #fff;">Giriş Yap</button>
        </form>
      </div>
    </div>
  </div>

  <?php include 'footer.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
