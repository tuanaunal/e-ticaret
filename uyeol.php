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
  <div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card text-center shadow" style="width: 100%; max-width: 400px;">
      <div class="card-body">
        <h5 class="card-title mb-4">Üye Ol</h5>
        <form>
          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="validationDefault01" placeholder="Ad">
            <label for="floatingAd">Ad</label>
          </div>
          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="validationDefault02" placeholder="Soyad">
            <label for="floatingSoyad">Soyad</label>
          </div>
          <div class="form-floating mb-3">
            <input type="email" class="form-control" id="validationDefault03" placeholder="name@example.com">
            <label for="floatingInput">E-posta Adresi</label>
          </div>
          <div class="form-floating mb-3">
            <input type="tel" class="form-control" id="validationDefault04" placeholder="Tel">
            <label for="floatingTel">Telefon Numarası</label>
          </div>
          <div class="form-floating mb-3">
            <input type="date" class="form-control" id="validationDefault05" placeholder="Date">
            <label for="floatingDate">Doğum Tarihi</label>
          </div>
          <div class="form-floating mb-3">
            <input type="password" class="form-control" id="validationDefault06" placeholder="Şifre">
            <label for="floatingPassword">Şifre</label>
            <div id="passwordHelpBlock" class="form-text fs-smaller">
              Şifreniz 8 karakterli ve karakterler arası boşluk olmamalı
            </div>
          </div>
          <div class="col-12">
            <div class="form-check text-start">
              <input class="form-check-input" type="checkbox" style="transform: scale(1.5);" value="" id="invalidCheck1" aria-describedby="invalidCheck1Feedback" required>
              <label class="form-check-label " for="invalidCheck1">
                Tarafıma 'Elektronik İleti' gönderilmesini kabul ediyorum.
              </label>
            </div>
            <div class="form-check mt-2 text-start">
              <input class="form-check-input " type="checkbox" style="transform: scale(1.5);" value="" id="invalidCheck2" aria-describedby="invalidCheck2Feedback" required>
              <label class="form-check-label" for="invalidCheck2">
                Yakamoz Aksesuar tarafından kişisel verilerimin işlenmesine açık rıza veriyorum.
              </label>
            </div>
          </div>
          <button type="submit" class="btn w-100 mt-3" style="background-color: #000; color: #fff;">Giriş Yap</button>
        </form>
      </div>
    </div>
  </div>
  <?php include 'footer.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

