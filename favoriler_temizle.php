<?php
session_start();
include 'db_baglanti.php';

if (!isset($_SESSION['uye_id'])) {
    header("Location: girisyap.php");
    exit();
}

$uye_id = $_SESSION['uye_id'];

$temizle = $conn->prepare("DELETE FROM favoriler WHERE uye_id = ?");
$temizle->bind_param("i", $uye_id);
$temizle->execute();

header("Location: favoriler.php");
exit();
?>
