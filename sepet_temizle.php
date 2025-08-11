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

$conn->begin_transaction();

try {
  $stmt = $conn->prepare("DELETE FROM sepet WHERE uye_id = ?");
  if (!$stmt) {
    throw new Exception("Hazırlama hatası: ".$conn->error);
  }
  $stmt->bind_param("i", $uye_id);
  if (!$stmt->execute()) {
    throw new Exception("Silme hatası: ".$stmt->error);
  }
  $deleted_count = $stmt->affected_rows;

  $t = $conn->prepare("SELECT COALESCE(SUM(adet),0) AS toplam FROM sepet WHERE uye_id = ?");
  if (!$t) {
    throw new Exception("Toplam sorgu hatası: ".$conn->error);
  }
  $t->bind_param("i", $uye_id);
  if (!$t->execute()) {
    throw new Exception("Toplam çalıştırma hatası: ".$t->error);
  }
  $res = $t->get_result()->fetch_assoc();
  $toplam_adet = (int)($res['toplam'] ?? 0);

  $conn->commit();

  echo json_encode([
    'status' => 'success',
    'deleted_count' => $deleted_count,
    'toplam_adet' => $toplam_adet,
    'message' => $deleted_count > 0 ? 'Sepet temizlendi' : 'Sepette silinecek ürün yoktu'
  ]);
} catch (Exception $e) {
  $conn->rollback();
  echo json_encode([
    'status' => 'error',
    'message' => 'İşlem başarısız: '.$e->getMessage()
  ]);
}
