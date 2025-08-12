<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include '../db_baglanti.php';

if (!isset($_SESSION['uye_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: ../girisyap.php");
    exit();
}

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(16));
}

$mesaj = "";
$mesaj_tur = "info";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST['csrf']) || !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
        $mesaj = "Oturum doğrulaması başarısız. Lütfen tekrar deneyin.";
        $mesaj_tur = "danger";
    } else {
        $kategori_adi = trim($_POST['kategori_adi'] ?? "");

        if ($kategori_adi === "") {
            $mesaj = "Kategori adı boş bırakılamaz.";
            $mesaj_tur = "warning";
        } else {
            $kontrol = $conn->prepare("SELECT 1 FROM kategori WHERE kategori_adi = ? LIMIT 1");
            $kontrol->bind_param("s", $kategori_adi);
            $kontrol->execute();
            $kontrol->store_result();

            if ($kontrol->num_rows > 0) {
                $mesaj = "Bu kategori zaten mevcut.";
                $mesaj_tur = "warning";
            } else {
                $stmt = $conn->prepare("INSERT INTO kategori (kategori_adi) VALUES (?)");
                $stmt->bind_param("s", $kategori_adi);

                if ($stmt->execute()) {
                    $mesaj = "Kategori başarıyla eklendi! 🎉";
                    $mesaj_tur = "success";
                } else {
                    $mesaj = "Hata: " . htmlspecialchars($conn->error);
                    $mesaj_tur = "danger";
                }
            }
        }
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="keywords" content="Aksesuar,Takı,Gözlük,Şapka,Çanta,Toka,Saç Aksesuarı">
<meta name="description" content="Yakamoz Aksesuar – Admin Kategori Ekle">
<link rel="icon" type="image/png" href="../YA-Dükkan Resimleri/icon.png">
<title>Admin Kategori Ekle - Yakamoz Aksesuar</title>
<link rel="stylesheet" href="../style.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
    body { background-color:#f8f9fa; }
    .panel-card {
        width:100%;
        max-width:600px;
        border-radius:1rem;
        box-shadow:0 10px 25px rgba(0,0,0,.08);
    }
    .panel-title { font-family:'Playfair Display', serif; }
</style>
</head>
<body>

<div class="container-fluid min-vh-100 d-flex justify-content-center align-items-center py-4">
    <div class="card panel-card p-4">
        <h2 class="panel-title text-center mb-3">Kategori Ekle</h2>

        <?php if(!empty($mesaj)): ?>
            <div class="alert alert-<?= $mesaj_tur ?> py-2"><?= $mesaj ?></div>
        <?php endif; ?>

        <form method="POST" class="mt-2" novalidate>
            <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">
            <div class="mb-3">
                <label class="form-label">Kategori Adı</label>
                <input type="text" name="kategori_adi" class="form-control" required>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-dark">Ekle</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
