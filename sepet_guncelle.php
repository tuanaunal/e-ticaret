<?php
session_start();
include "baglanti.php";

if (!isset($_SESSION['uye_id'])) {
    echo json_encode(["status" => "error", "message" => "Giriş yapılmamış"]);
    exit();
}

$uye_id = $_SESSION['uye_id'];
$id = intval($_POST['id']);
$action = $_POST['action'];

$stmt = $conn->prepare("SELECT adet, urun_id FROM sepet WHERE sepet_id = ? AND uye_id = ?");
$stmt->execute([$id, $uye_id]);
$urun = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$urun) {
    echo json_encode(["status" => "error", "message" => "Ürün bulunamadı"]);
    exit();
}

$adet = $urun['adet'];

if ($action == "increase") {
    $adet++;
    $update = $conn->prepare("UPDATE sepet SET adet = ? WHERE sepet_id = ? AND uye_id = ?");
    $update->execute([$adet, $id, $uye_id]);
} elseif ($action == "decrease") {
    $adet--;
    if ($adet > 0) {
        $update = $conn->prepare("UPDATE sepet SET adet = ? WHERE sepet_id = ? AND uye_id = ?");
        $update->execute([$adet, $id, $uye_id]);
    } else {
        $delete = $conn->prepare("DELETE FROM sepet WHERE sepet_id = ? AND uye_id = ?");
        $delete->execute([$id, $uye_id]);
    }
}

$sql = "SELECT s.adet, u.fiyat FROM sepet s JOIN urunler u ON s.urun_id = u.urun_id WHERE s.uye_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$uye_id]);
$urunler = $stmt->fetchAll(PDO::FETCH_ASSOC);

$genelToplam = 0;
foreach ($urunler as $u) {
    $genelToplam += $u['adet'] * $u['fiyat'];
}

$fiyatStmt = $conn->prepare("SELECT fiyat FROM urunler WHERE urun_id = ?");
$fiyatStmt->execute([$urun['urun_id']]);
$fiyat = $fiyatStmt->fetchColumn();

echo json_encode([
    "status" => "success",
    "adet" => $adet > 0 ? $adet : 0,
    "toplam" => $adet > 0 ? $adet * $fiyat : 0,
    "genel_toplam" => $genelToplam
]);
?>
