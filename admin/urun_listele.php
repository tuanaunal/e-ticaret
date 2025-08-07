<?php
session_start();
include '../db_baglanti.php';

if (!isset($_SESSION['admin'])) {
    header("Location: admin_giris.php");
    exit();
}

$sql = "SELECT urun.urun_id, urun.urun_adi, urun.fiyat, urun.stok, urun.resim, kategori.kategori_adi
        FROM urun
        LEFT JOIN kategori ON urun.kategori_id = kategori.kategori_id";
$result = $conn->query($sql);


if (isset($_GET['sil'])) {
    $urun_id = $_GET['sil'];
    $conn->query("DELETE FROM urun WHERE urun_id = $urun_id");
    header("Location: urun_listele.php");
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
  <title>Admin Ürün Listele - Yakamoz Aksesuar</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

</head>
<body>
<div class="container mt-5">
    <h2>Ürün Listesi</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Resim</th>
                <th>Ürün Adı</th>
                <th>Kategori</th>
                <th>Fiyat</th>
                <th>Stok</th>
                <th>Sil</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><img src="../resimler/<?= $row['resim'] ?>" width="50"></td>
                <td><?= $row['urun_adi'] ?></td>
                <td><?= $row['kategori_adi'] ?></td>
                <td><?= $row['fiyat'] ?> TL</td>
                <td><?= $row['stok'] ?></td>
                <td><a href="urun_listele.php?sil=<?= $row['urun_id'] ?>" class="btn btn-danger btn-sm">Sil</a></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
