<?php
session_start();
if (!isset($_SESSION["uye_id"])) {
    header("Location: giris.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="keywords" content="Aksesuar,Takı,Gözlük,Şapka,Çanta,Toka,Saç Aksesuarı">
  <meta name="description" content="Yakamoz Aksesuar’da sipariş bilgilerinizi girin">
  <link rel="icon" type="image/png" href="YA-Dükkan Resimleri/icon.png">
  <title>Sipariş Formu - Yakamoz Aksesuar</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    .siparis-kart {
      max-width: 600px;
      margin: 0 auto;
      padding: 2rem;
      border-radius: 1rem;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
      background-color: #fff;
    }
    body {
      background-color: #f8f9fa;
    }
  </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<main class="container my-5">
    <div class="siparis-kart">
        <h2 class="mb-4 text-center" style="font-family: 'Playfair Display', serif;">Sipariş Bilgilerinizi Giriniz</h2>
        <form action="siparis_olustur.php" method="POST">
            <div class="mb-3">
                <label class="form-label">Ad Soyad</label>
                <input type="text" name="ad_soyad" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Telefon</label>
                <input type="text" name="telefon" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Adres</label>
                <textarea name="adres" class="form-control" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Şehir</label>
                <input type="text" name="sehir" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">İlçe</label>
                <input type="text" name="ilce" class="form-control" required>
            </div>
            <div class="mb-4">
                <label class="form-label">Ödeme Yöntemi</label>
                <select name="odeme_yontemi" class="form-select" required>
                    <option value="Kapıda Ödeme">Kapıda Ödeme</option>
                    <option value="Kredi Kartı">Kredi Kartı</option>
                </select>
            </div>
            <button type="submit" class="btn btn-dark w-100">Siparişi Tamamla</button>
        </form>
    </div>
</main>

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
