<?php
session_start();
include("db_baglanti.php");

if (!isset($_SESSION["uye_id"])) {
    header("Location: giris.php");
    exit();
}

$uye_id = $_SESSION["uye_id"];

$sepet_sorgu = $conn->prepare("SELECT * FROM sepet WHERE uye_id = ?");
$sepet_sorgu->bind_param("i", $uye_id);
$sepet_sorgu->execute();
$sepet_result = $sepet_sorgu->get_result();
$sepet_urunleri = $sepet_result->fetch_all(MYSQLI_ASSOC);

if (count($sepet_urunleri) == 0) {
    echo "Sepetiniz boş. Sipariş oluşturulamadı.";
    exit();
}

$toplam_tutar = 0;
foreach ($sepet_urunleri as $urun) {
    $toplam_tutar += $urun["adet"] * $urun["birim_fiyat"];
}

$siparis_ekle = $conn->prepare("INSERT INTO siparis (uye_id, toplam_tutar) VALUES (?, ?)");
$siparis_ekle->bind_param("id", $uye_id, $toplam_tutar);
$siparis_ekle->execute();
$siparis_id = $conn->insert_id;

foreach ($sepet_urunleri as $urun) {
    $detay_ekle = $conn->prepare("INSERT INTO siparis_detay (siparis_id, urun_id, adet, birim_fiyat) VALUES (?, ?, ?, ?)");
    $detay_ekle->bind_param("iiid", $siparis_id, $urun["urun_id"], $urun["adet"], $urun["birim_fiyat"]);
    $detay_ekle->execute();
}

$ad_soyad = $_POST["ad_soyad"];
$telefon = $_POST["telefon"];
$adres = $_POST["adres"];
$sehir = $_POST["sehir"];
$ilce = $_POST["ilce"];
$odeme_yontemi = $_POST["odeme_yontemi"];

$bilgi_ekle = $conn->prepare("INSERT INTO siparis_bilgi (siparis_id, ad_soyad, telefon, adres, sehir, ilce, odeme_yontemi) VALUES (?, ?, ?, ?, ?, ?, ?)");
$bilgi_ekle->bind_param("issssss", $siparis_id, $ad_soyad, $telefon, $adres, $sehir, $ilce, $odeme_yontemi);
$bilgi_ekle->execute();

$sepet_temizle = $conn->prepare("DELETE FROM sepet WHERE uye_id = ?");
$sepet_temizle->bind_param("i", $uye_id);
$sepet_temizle->execute();

header("Location: siparis_basarili.php");
exit();
?>
