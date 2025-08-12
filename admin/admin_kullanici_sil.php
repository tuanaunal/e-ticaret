<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include '../db_baglanti.php';

if (!isset($_SESSION['uye_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: ../girisyap.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: admin_kullanicilar.php");
    exit();
}

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
    die('Geçersiz istek (CSRF).');
}

$uye_id = isset($_POST['uye_id']) ? (int)$_POST['uye_id'] : 0;
if ($uye_id <= 0) {
    header("Location: admin_kullanicilar.php");
    exit();
}

$conn->begin_transaction();

try {
    $q = $conn->prepare("DELETE FROM sepet WHERE uye_id = ?");
    $q->bind_param("i", $uye_id);
    $q->execute();

    $q = $conn->prepare("DELETE FROM favoriler WHERE uye_id = ?");
    $q->bind_param("i", $uye_id);
    $q->execute();

    $q = $conn->prepare("DELETE FROM uye WHERE uye_id = ?");
    $q->bind_param("i", $uye_id);
    $q->execute();

    $conn->commit();
} catch (Exception $e) {
    $conn->rollback();
    header("Location: admin_kullanicilar.php?durum=hata");
    exit();
}

header("Location: admin_kullanicilar.php?durum=ok");
exit();
