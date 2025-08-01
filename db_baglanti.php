<?php
$servername = "localhost";   // Sunucu adı
$username = "root";          // MAMP default kullanıcı
$password = "root";          // MAMP default şifre
$dbname = "aksesuar_db";     // Senin veritabanı adı
$port = 8888;                // MAMP port

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>
