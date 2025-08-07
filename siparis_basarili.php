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
  <meta charset="UTF-8">
  <title>Sipariş Tamamlandı</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

  <div class="container text-center mt-5">
    <div class="alert alert-success p-5 shadow-lg">
      <h1 class="mb-4">🎉 Siparişiniz Başarıyla Alındı!</h1>
      <p class="lead">En kısa sürede hazırlanıp kargoya verilecektir.</p>
      <a href="siparislerim.php" class="btn btn-primary mt-4">📦 Siparişlerimi Gör</a>
      <a href="index.php" class="btn btn-outline-secondary mt-4 ms-2">🏠 Ana Sayfaya Dön</a>
    </div>
  </div>

</body>
</html>
