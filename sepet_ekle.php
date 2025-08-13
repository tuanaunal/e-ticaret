<?php
session_start();
include 'db_baglanti.php';

if (!isset($_SESSION['uye_id'])) {
    echo json_encode([
        "status" => "error",
        "redirect" => "girisyap.php"
    ]);
    exit;
}

$uye_id = $_SESSION['uye_id'];
$urun_id = intval($_POST['urun_id']);

$check_sql = "SELECT * FROM sepet WHERE uye_id = $uye_id AND urun_id = $urun_id";
$check_result = $conn->query($check_sql);

if ($check_result->num_rows > 0) {
    $conn->query("UPDATE sepet SET adet = adet + 1 WHERE uye_id = $uye_id AND urun_id = $urun_id");
} else {
    $fiyat_sorgu = $conn->prepare("SELECT fiyat FROM urun WHERE urun_id = ?");
    $fiyat_sorgu->bind_param("i", $urun_id);
    $fiyat_sorgu->execute();
    $fiyat_result = $fiyat_sorgu->get_result();
    $urun = $fiyat_result->fetch_assoc();

    if ($urun) {
        $birim_fiyat = $urun["fiyat"];

        $ekle = $conn->prepare("INSERT INTO sepet (uye_id, urun_id, adet, birim_fiyat, eklenme_tarihi) VALUES (?, ?, ?, ?, NOW())");
        $adet = 1;
        $ekle->bind_param("iiid", $uye_id, $urun_id, $adet, $birim_fiyat);
        $ekle->execute();
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Ürün bulunamadı."
        ]);
        exit;
    }
}

$total_sql = "SELECT SUM(adet) as toplam FROM sepet WHERE uye_id = $uye_id";
$total_result = $conn->query($total_sql);
$total_count = $total_result->fetch_assoc()['toplam'] ?? 0;

echo json_encode([
    "status" => "success",
    "toplam_adet" => $total_count
]);
?>