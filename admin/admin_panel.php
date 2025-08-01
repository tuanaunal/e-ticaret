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
    <meta charset="UTF-8">
    <title>Admin Paneli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Hoş geldiniz, <?= $_SESSION['admin'] ?></h2>
    <a href="kategori_ekle.php" class="btn btn-primary mt-3">Kategori Ekle</a>
    <a href="urun_ekle.php" class="btn btn-success mt-3">Ürün Ekle</a>
    <a href="urun_listele.php" class="btn btn-info mt-3">Ürünleri Listele</a>
    <a href="admin_cikis.php" class="btn btn-danger mt-3">Çıkış Yap</a>
</div>
</body>
</html>
