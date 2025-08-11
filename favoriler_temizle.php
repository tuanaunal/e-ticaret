<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
include 'db_baglanti.php';

if (!isset($_SESSION['uye_id'])) {
    echo json_encode([
        'status' => 'error',
        'redirect' => 'girisyap.php',
        'message' => 'Giriş yapınız'
    ]);
    exit;
}

$uye_id = (int)$_SESSION['uye_id'];

$stmt = $conn->prepare("DELETE FROM favoriler WHERE uye_id = ?");
$stmt->bind_param("i", $uye_id);
$ok = $stmt->execute();

$t = $conn->prepare("SELECT COUNT(*) AS toplam FROM favoriler WHERE uye_id = ?");
$t->bind_param("i", $uye_id);
$t->execute();
$favori_sayac = (int)($t->get_result()->fetch_assoc()['toplam'] ?? 0);

echo json_encode([
    'status' => $ok ? 'success' : 'error',
    'favori_sayac' => $favori_sayac,
    'message' => $ok ? 'Favoriler temizlendi' : 'İşlem başarısız'
]);
