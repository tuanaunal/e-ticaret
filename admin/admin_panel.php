<?php
session_start();
include '../db_baglanti.php';


if (!isset($_SESSION['admin'])) {
    header("Location: admin_giris.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="keywords" content="Aksesuar,Takı,Gözlük,Şapka,Çanta,Toka,Saç Aksesuarı">
  <meta name="description" content="Yakamoz Aksesuar’da zarif takılar, gözlükler, şapkalar, çantalar ve saç aksesuarlarıyla tarzına ışıltı kat. Hızlı kargo, güvenli ödeme ve uygun fiyat seni bekliyor!">
  <link rel="icon" type="image/png" href="YA-Dükkan Resimleri/icon.png">
  <title>Admin Paneli - Yakamoz Aksesuar</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

</head>
<body>

<div class="container mt-5">
    <h2>Hoş geldiniz, <?= $_SESSION['admin'] ?></h2>

    <div class="d-grid gap-2 d-md-block mt-4">
        <a href="kategori_ekle.php" class="btn btn-primary">Kategori Ekle</a>
        <a href="urun_ekle.php" class="btn btn-success">Ürün Ekle</a>
        <a href="urun_listele.php" class="btn btn-info">Ürünleri Listele</a>
        <a href="admin_siparisler.php" class="btn btn-warning">Siparişleri Yönet</a>
        <a href="admin_cikis.php" class="btn btn-danger">Çıkış Yap</a>
    </div>
</div>

</body>
</html>
