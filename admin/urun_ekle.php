<?php
session_start();
include '../db_baglanti.php';

if (!isset($_SESSION['admin'])) {
    header("Location: admin_giris.php");
    exit();
}


$kategori_sql = "SELECT * FROM kategori";
$kategori_result = $conn->query($kategori_sql);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kategori_id = $_POST['kategori_id'];
    $urun_adi = $_POST['urun_adi'];
    $aciklama = $_POST['aciklama'];
    $fiyat = $_POST['fiyat'];
    $stok = $_POST['stok'];

  
    $resim = $_FILES['resim']['name'];
    $target_dir = "../resimler/";
    $target_file = $target_dir . basename($resim);
    move_uploaded_file($_FILES['resim']['tmp_name'], $target_file);

    
    $sql = "INSERT INTO urun (kategori_id, urun_adi, aciklama, fiyat, stok, resim)
            VALUES ('$kategori_id', '$urun_adi', '$aciklama', '$fiyat', '$stok', '$resim')";

    if ($conn->query($sql) === TRUE) {
        $mesaj = "Ürün başarıyla eklendi!";
    } else {
        $mesaj = "Hata: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Ürün Ekle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Ürün Ekle</h2>
    <?php if(isset($mesaj)) echo "<div class='alert alert-info'>$mesaj</div>"; ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="kategori_id" class="form-control" required>
                <?php while ($row = $kategori_result->fetch_assoc()): ?>
                    <option value="<?= $row['kategori_id'] ?>"><?= $row['kategori_adi'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Ürün Adı</label>
            <input type="text" name="urun_adi" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Açıklama</label>
            <textarea name="aciklama" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Fiyat</label>
            <input type="number" name="fiyat" step="0.01" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Stok</label>
            <input type="number" name="stok" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Resim</label>
            <input type="file" name="resim" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Ekle</button>
    </form>
</div>
</body>
</html>
