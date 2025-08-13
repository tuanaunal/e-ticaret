<?php
session_start();
include 'db_baglanti.php';

if (!isset($_SESSION['uye_id'])) {
    header("Location: girisyap.php");
    exit();
}

$uye_id = $_SESSION['uye_id'];
$sql = "SELECT ad, soyad, email, telefon, dogum_tarihi, kayit_tarihi FROM uye WHERE uye_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $uye_id);
$stmt->execute();
$result = $stmt->get_result();
$uye = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="keywords" content="Aksesuar,Takı,Gözlük,Şapka,Çanta,Toka,Saç Aksesuarı">
  <meta name="description" content="Yakamoz Aksesuar’da hesabınıza ait bilgileri görüntüleyin">
  <link rel="icon" type="image/png" href="YA-Dükkan Resimleri/icon.png">
  <title>Hesabım - Yakamoz Aksesuar</title>
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
  <div class="container mt-5">
    <div class="card p-4 shadow mx-auto text-center" style="max-width: 500px;">
      <h2 class="mb-4" style="font-family: 'Playfair Display', serif;">Hesabım</h2>
      <p><strong>Ad Soyad:</strong> <?= htmlspecialchars(($uye['ad'] ?? '').' '.($uye['soyad'] ?? '')) ?></p>
      <p><strong>Telefon:</strong> <?= htmlspecialchars($uye['telefon'] ?? '') ?></p>
      <p><strong>Doğum Tarihi:</strong> <?= htmlspecialchars($uye['dogum_tarihi'] ?? '') ?></p>
      <p><strong>E-posta:</strong> <?= htmlspecialchars($uye['email'] ?? '') ?></p>
      <p><strong>Kayıt Tarihi:</strong> <?= htmlspecialchars($uye['kayit_tarihi'] ?? '') ?></p>

      <div class="mt-4">
        <a href="bilgi_guncelle.php" class="btn btn-dark">Bilgilerimi Güncelle</a>
      </div>
    </div>
  </div>
</main>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
