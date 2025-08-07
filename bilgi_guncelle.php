<?php
session_start();
include 'db_baglanti.php';

if (!isset($_SESSION['uye_id'])) {
    header("Location: girisyap.php");
    exit();
}

$uye_id = $_SESSION['uye_id'];
$sql = "SELECT ad, soyad, email, telefon, dogum_tarihi FROM uye WHERE uye_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $uye_id);
$stmt->execute();
$result = $stmt->get_result();
$uye = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ad = $_POST["ad"];
    $soyad = $_POST["soyad"];
    $telefon = $_POST["telefon"];
    $email = $_POST["email"];
    $dogum_tarihi = $_POST["dogum_tarihi"];

    if (
        $ad === $uye["ad"] &&
        $soyad === $uye["soyad"] &&
        $telefon === $uye["telefon"] &&
        $email === $uye["email"] &&
        $dogum_tarihi === $uye["dogum_tarihi"]
    ) {
        $basari = "Bilgilerinizde herhangi bir değişiklik yapılmadı.";
    } else {
        $guncelle = $conn->prepare("UPDATE uye SET ad = ?, soyad = ?, telefon = ?, email = ?, dogum_tarihi = ? WHERE uye_id = ?");
        $guncelle->bind_param("sssssi", $ad, $soyad, $telefon, $email, $dogum_tarihi, $uye_id);

        if ($guncelle->execute()) {
            $basari = "Bilgileriniz başarıyla güncellendi.";
            $uye = ["ad" => $ad, "soyad" => $soyad, "telefon" => $telefon, "email" => $email, "dogum_tarihi" => $dogum_tarihi];
        } else {
            $hata = "Güncelleme sırasında bir hata oluştu.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="keywords" content="Aksesuar,Takı,Gözlük,Şapka,Çanta,Toka,Saç Aksesuarı">
  <meta name="description" content="Yakamoz Aksesuar’da favorilediğiniz ürünleri görün">
  <link rel="icon" type="image/png" href="YA-Dükkan Resimleri/icon.png">
  <title>Bilgilerimi Güncelle - Yakamoz Aksesuar</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php include 'navbar.php'; ?>

<div class="container my-5" style="max-width: 600px;">
    <h2 class="mb-4 text-center" style="font-family: 'Playfair Display', serif;">Bilgilerimi Güncelle</h2>

    <?php if (isset($basari)): ?>
        <div class="alert alert-success text-center"><?= $basari ?></div>
    <?php elseif (isset($hata)): ?>
        <div class="alert alert-danger text-center"><?= $hata ?></div>
    <?php endif; ?>

    <div class="card shadow-sm p-4">
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Ad</label>
                <input type="text" name="ad" class="form-control" value="<?= htmlspecialchars($uye['ad']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Soyad</label>
                <input type="text" name="soyad" class="form-control" value="<?= htmlspecialchars($uye['soyad']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Telefon</label>
                <input type="text" name="telefon" class="form-control" value="<?= htmlspecialchars($uye['telefon']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">E-posta</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($uye['email']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Doğum Tarihi</label>
                <input type="date" name="dogum_tarihi" class="form-control" value="<?= htmlspecialchars($uye['dogum_tarihi']) ?>" required>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-dark px-4">Onayla</button>
            </div>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
