<?php
session_start();
include 'db_baglanti.php';

header('Content-Type: application/json');

if (!isset($_SESSION['uye_id'])) {
    echo json_encode(["status" => "not_logged_in"]);
    exit();
}

if (isset($_GET['urun_id']) && is_numeric($_GET['urun_id'])) {
    $urun_id = intval($_GET['urun_id']);
    $uye_id = $_SESSION['uye_id'];

    $check_sql = "SELECT favori_id FROM favori WHERE uye_id = ? AND urun_id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ii", $uye_id, $urun_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $delete_sql = "DELETE FROM favori WHERE uye_id = ? AND urun_id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("ii", $uye_id, $urun_id);
        $delete_stmt->execute();

        echo json_encode(["status" => "removed"]);
    } else {
        $insert_sql = "INSERT INTO favori (uye_id, urun_id, eklenme_tarihi) VALUES (?, ?, NOW())";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("ii", $uye_id, $urun_id);
        $insert_stmt->execute();

        echo json_encode(["status" => "added"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Geçersiz ürün ID"]);
}
?>
