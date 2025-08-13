<?php
session_start();
include 'db_baglanti.php';

if (!isset($_SESSION['uye_id'])) {
    header("Location: girisyap.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="keywords" content="Aksesuar,Takı,Gözlük,Şapka,Çanta,Toka,Saç Aksesuarı">
  <meta name="description" content="Yakamoz Aksesuar’da güvenli şifre değiştirme sayfası.">
  <link rel="icon" type="image/png" href="YA-Dükkan Resimleri/icon.png">
  <title>Şifre Değiştir - Yakamoz Aksesuar</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    html, body {
      height: 100%;
      margin: 0;
      display: flex;
      flex-direction: column;
    }
    main {
      flex: 1;
      min-height: calc(100vh - 200px);
    }
  </style>
</head>

<body class="bg-light">

<?php include 'navbar.php'; ?>

<main>
  <div class="container mt-5 d-flex justify-content-center">
    <div class="card p-4 shadow w-100" style="max-width: 500px;">
      <h2 class="mb-4 text-center" style="font-family: 'Playfair Display', serif;">Şifre Değiştir</h2>

      <form action="sifre_degistir_kontrol.php" method="POST">
        <div class="mb-3">
          <label for="eski_sifre" class="form-label">Mevcut Şifre</label>
          <input type="password" class="form-control" id="eski_sifre" name="eski_sifre" required>
        </div>

        <div class="mb-3">
          <label for="yeni_sifre" class="form-label">Yeni Şifre</label>
          <input type="password" class="form-control" id="yeni_sifre" name="yeni_sifre" required>
        </div>

        <div class="mb-3">
          <label for="yeni_sifre_tekrar" class="form-label">Yeni Şifre (Tekrar)</label>
          <input type="password" class="form-control" id="yeni_sifre_tekrar" name="yeni_sifre_tekrar" required>
        </div>

        <div class="d-grid">
          <button type="submit" class="btn btn-dark">Şifreyi Güncelle</button>
        </div>
      </form>
    </div>
  </div>
</main>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
