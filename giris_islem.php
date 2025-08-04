<?php
session_start();
include 'db_baglanti.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $sifre = $_POST['sifre'];

    $stmt = $conn->prepare("SELECT * FROM uye WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (password_verify($sifre, $row['sifre'])) {
            $_SESSION['uye_id'] = $row['uye_id'];
            $_SESSION['uye_ad'] = $row['ad'];

            header("Location: index.php");
            exit();
        } else {
            header("Location: girisyap.php?error=" . urlencode("Hatalı şifre girdiniz!"));
            exit();
        }
    } else {
        header("Location: girisyap.php?error=" . urlencode("Bu e-posta adresiyle kayıtlı kullanıcı bulunamadı."));
        exit();
    }
}
?>
