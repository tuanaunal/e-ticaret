<?php
session_start();
include '../db_baglanti.php';

if (!isset($_SESSION['admin'])) {
    header("Location: admin_giris.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kategori_adi = $_POST['kategori_adi'];

    $sql = "INSERT INTO kategori (kategori_adi) VALUES ('$kategori_adi')";
    if ($conn->query($sql) === TRUE) {
        $mesaj = "Kategori başarıyla eklendi!";
    } else {
        $mesaj = "Hata: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kategori Ekle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Kategori Ekle</h2>
    <?php if(isset($mesaj)) echo "<div class='alert alert-info'>$mesaj</div>"; ?>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Kategori Adı</label>
            <input type="text" name="kategori_adi" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Ekle</button>
    </form>
</div>
</body>
</html>
