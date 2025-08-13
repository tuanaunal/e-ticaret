<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_baglanti.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ad = $_POST['ad'];
    $soyad = $_POST['soyad'];
    $email = $_POST['email'];
    $telefon = $_POST['telefon'];
    $dogum_tarihi = $_POST['dogum_tarihi'];
    $sifre = password_hash($_POST['sifre'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO uye (ad, soyad, email, telefon, dogum_tarihi, sifre) 
            VALUES ('$ad', '$soyad', '$email', '$telefon', '$dogum_tarihi', '$sifre')";

    if ($conn->query($sql) === TRUE) {
        $last_id = $conn->insert_id;

        $_SESSION['uye_id'] = $last_id;
        $_SESSION['uye_ad'] = $ad;
        $_SESSION['uye_email'] = $email;

        header("Location: index.php");
        exit();
    } else {
        echo "<div style='text-align:center; margin-top:20px; color:red;'>Hata: " . $conn->error . "</div>";
    }
}
?>
