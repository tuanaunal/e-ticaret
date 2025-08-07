<?php
session_start();
include '../db_baglanti.php';

if (!isset($_SESSION['admin'])) {
    header("Location: admin_giris.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["siparis_id"])) {
    $siparis_id = $_POST["siparis_id"];
    $yeni_durum = $_POST["durum"];
    $kargo_firma = $_POST["kargo_firma"];
    $kargo_takip_no = $_POST["kargo_takip_no"];

    $update_sql = "UPDATE siparis SET durum = ?, kargo_firma = ?, kargo_takip_no = ? WHERE siparis_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sssi", $yeni_durum, $kargo_firma, $kargo_takip_no, $siparis_id);
    $stmt->execute();
}

$conn->query("UPDATE siparis SET kargo_firma = 'Yurtiçi Kargo' WHERE kargo_firma IS NULL OR kargo_firma = '' OR kargo_firma = 'Belirtilmedi'");


$sql = "SELECT s.*, u.ad, u.soyad FROM siparis s 
        JOIN uye u ON s.uye_id = u.uye_id 
        ORDER BY s.siparis_tarihi DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="keywords" content="Aksesuar,Takı,Gözlük,Şapka,Çanta,Toka,Saç Aksesuarı">
  <meta name="description" content="Yakamoz Aksesuar’da zarif takılar, gözlükler, şapkalar, çantalar ve saç aksesuarlarıyla tarzına ışıltı kat. Hızlı kargo, güvenli ödeme ve uygun fiyat seni bekliyor!">
  <link rel="icon" type="image/png" href="YA-Dükkan Resimleri/icon.png">
  <title>Admin Siparişler - Yakamoz Aksesuar</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        .urun-resim {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 8px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Tüm Siparişler</h2>
    <?php if ($result->num_rows > 0): ?>
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Kullanıcı</th>
                    <th>Tarih</th>
                    <th>Toplam</th>
                    <th>Ürünler</th>
                    <th>Durum</th>
                    <th>Kargo Firması</th>
                    <th>Takip No</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <?php
                $siparis_id = $row['siparis_id'];

                $urun_sql = "SELECT sd.*, u.urun_adi, u.resim 
                             FROM siparis_detay sd 
                             JOIN urun u ON sd.urun_id = u.urun_id 
                             WHERE sd.siparis_id = $siparis_id";
                $urunler = $conn->query($urun_sql);

                if (empty($row["kargo_takip_no"])) {
                    $yeni_takip_no = "SC" . $siparis_id . rand(1000, 9999);
                    $conn->query("UPDATE siparis SET kargo_takip_no = '$yeni_takip_no' WHERE siparis_id = $siparis_id");
                    $row["kargo_takip_no"] = $yeni_takip_no;
                }
                ?>
                <tr>
                    <form method="POST">
                        <input type="hidden" name="siparis_id" value="<?= $siparis_id ?>">
                        <td><?= $siparis_id ?></td>
                        <td><?= $row['ad'] . ' ' . $row['soyad'] ?></td>
                        <td><?= $row['siparis_tarihi'] ?></td>
                        <td>
                            <?php while ($u = $urunler->fetch_assoc()): ?>
                                <div class="d-flex align-items-center mb-1">
                                    <img src="../Resimler/<?= htmlspecialchars($u['resim']) ?>" class="urun-resim">
                                    <div>
                                        <strong><?= htmlspecialchars($u['urun_adi']) ?></strong><br>
                                        <?= $u['adet'] ?> adet
                                    </div>
                                </div>
                            <?php endwhile; ?>
                            <td><?= number_format($row['toplam_tutar'], 2) ?> TL</td>
                        </td>
                        <td>
                            <select name="durum" class="form-select">
                                <option value="Hazırlanıyor" <?= $row['durum'] == 'Hazırlanıyor' ? 'selected' : '' ?>>Hazırlanıyor</option>
                                <option value="Kargoya Verildi" <?= $row['durum'] == 'Kargoya Verildi' ? 'selected' : '' ?>>Kargoya Verildi</option>
                                <option value="Teslim Edildi" <?= $row['durum'] == 'Teslim Edildi' ? 'selected' : '' ?>>Teslim Edildi</option>
                                <option value="İptal Edildi" <?= $row['durum'] == 'İptal Edildi' ? 'selected' : '' ?>>İptal Edildi</option>
                            </select>
                        </td>
                        <td><input type="text" name="kargo_firma" class="form-control" value="<?= htmlspecialchars($row['kargo_firma']) ?>"></td>
                        <td><input type="text" name="kargo_takip_no" class="form-control" value="<?= htmlspecialchars($row['kargo_takip_no']) ?>"></td>
                        <td><button type="submit" class="btn btn-primary btn-sm">Kaydet</button></td>
                    </form>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">Henüz sipariş bulunmuyor.</div>
    <?php endif; ?>
</div>
</body>
</html>
