<?php
session_start();
include("db_baglanti.php");

if (!isset($_GET["id"])) {
    echo "Sipariş ID bulunamadı.";
    exit();
}

$siparis_id = intval($_GET["id"]);

$sorgu = $conn->prepare("SELECT s.*, u.ad_soyad AS musteri_ad, u.email 
                         FROM siparis s 
                         JOIN uye u ON s.uye_id = u.uye_id 
                         WHERE s.siparis_id = ?");
$sorgu->bind_param("i", $siparis_id);
$sorgu->execute();
$siparis = $sorgu->get_result()->fetch_assoc();

$urun_sorgu = $conn->prepare("SELECT sd.*, u.urun_adi 
                              FROM siparis_detay sd 
                              JOIN urun u ON sd.urun_id = u.urun_id 
                              WHERE sd.siparis_id = ?");
$urun_sorgu->bind_param("i", $siparis_id);
$urun_sorgu->execute();
$urunler = $urun_sorgu->get_result();

$bilgi_sorgu = $conn->prepare("SELECT * FROM siparis_bilgi WHERE siparis_id = ?");
$bilgi_sorgu->bind_param("i", $siparis_id);
$bilgi_sorgu->execute();
$bilgi = $bilgi_sorgu->get_result()->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $yeni_durum = $_POST["durum"];
    $kargo_firma = $_POST["kargo_firma"];
    $kargo_takip = $_POST["kargo_takip_no"];

    $guncelle = $conn->prepare("UPDATE siparis SET durum = ?, kargo_firma = ?, kargo_takip_no = ? WHERE siparis_id = ?");
    $guncelle->bind_param("sssi", $yeni_durum, $kargo_firma, $kargo_takip, $siparis_id);
    $guncelle->execute();

    header("Location: admin_siparisler.php");
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
  <title>Admin Sipariş Detayı - Yakamoz Aksesuar</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

</head>
<body class="container mt-5">
    <h2 class="mb-4">Sipariş #<?= $siparis_id ?> Detayları</h2>

    <div class="mb-4">
        <strong>Müşteri:</strong> <?= $siparis["musteri_ad"] ?> (<?= $siparis["email"] ?>)<br>
        <strong>Tarih:</strong> <?= $siparis["siparis_tarihi"] ?><br>
        <strong>Toplam Tutar:</strong> <?= $siparis["toplam_tutar"] ?> TL
    </div>

    <h5>Ürünler:</h5>
    <ul>
        <?php while ($u = $urunler->fetch_assoc()): ?>
            <li><?= $u["urun_adi"] ?> - <?= $u["adet"] ?> adet - <?= $u["birim_fiyat"] ?> TL</li>
        <?php endwhile; ?>
    </ul>

    <h5 class="mt-4">Teslimat ve Ödeme Bilgisi:</h5>
    <p>
        <strong>Ad Soyad:</strong> <?= $bilgi["ad_soyad"] ?><br>
        <strong>Telefon:</strong> <?= $bilgi["telefon"] ?><br>
        <strong>Adres:</strong> <?= $bilgi["adres"] ?> - <?= $bilgi["ilce"] ?>/<?= $bilgi["sehir"] ?><br>
        <strong>Ödeme:</strong> <?= $bilgi["odeme_yontemi"] ?>
    </p>

    <h5 class="mt-4">⚙️ Sipariş Durumu ve Kargo Bilgileri:</h5>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Sipariş Durumu</label>
            <select name="durum" class="form-select">
                <option value="Hazırlanıyor" <?= $siparis["durum"] == "Hazırlanıyor" ? "selected" : "" ?>>Hazırlanıyor</option>
                <option value="Kargoya Verildi" <?= $siparis["durum"] == "Kargoya Verildi" ? "selected" : "" ?>>Kargoya Verildi</option>
                <option value="Teslim Edildi" <?= $siparis["durum"] == "Teslim Edildi" ? "selected" : "" ?>>Teslim Edildi</option>
                <option value="İptal Edildi" <?= $siparis["durum"] == "İptal Edildi" ? "selected" : "" ?>>İptal Edildi</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Kargo Firması</label>
            <input type="text" name="kargo_firma" class="form-control" value="<?= $siparis["kargo_firma"] ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Kargo Takip No</label>
            <input type="text" name="kargo_takip_no" class="form-control" value="<?= $siparis["kargo_takip_no"] ?>">
        </div>
        <button type="submit" class="btn btn-success">Kaydet</button>
        <a href="admin_siparisler.php" class="btn btn-secondary">Geri Dön</a>
    </form>
</body>
</html>
