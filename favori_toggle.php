<?php
session_start();
include "db_baglanti.php"; 

if (!isset($_SESSION['uye_id'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Lütfen giriş yapın",
        "redirect" => "girisyap.php"
    ]);
    exit();
}

$uye_id = $_SESSION['uye_id'];
$urun_id = intval($_POST['urun_id']);

$kontrol = $conn->prepare("SELECT * FROM favoriler WHERE uye_id = ? AND urun_id = ?");
$kontrol->bind_param("ii", $uye_id, $urun_id);
$kontrol->execute();
$result = $kontrol->get_result();

if ($result->num_rows > 0) {
    $sil = $conn->prepare("DELETE FROM favoriler WHERE uye_id = ? AND urun_id = ?");
    $sil->bind_param("ii", $uye_id, $urun_id);
    $sil->execute();
    $durum = "removed";
} else {
    $ekle = $conn->prepare("INSERT INTO favoriler (uye_id, urun_id) VALUES (?, ?)");
    $ekle->bind_param("ii", $uye_id, $urun_id);
    $ekle->execute();
    $durum = "added";
}

$sayiSorgu = $conn->prepare("SELECT COUNT(*) AS toplam FROM favoriler WHERE uye_id = ?");
$sayiSorgu->bind_param("i", $uye_id);
$sayiSorgu->execute();
$sayiSonuc = $sayiSorgu->get_result()->fetch_assoc();
$favori_sayi = $sayiSonuc['toplam'];

echo json_encode([
    "status" => "success",
    "action" => $durum,
    "favori_sayi" => $favori_sayi
]);
?>
