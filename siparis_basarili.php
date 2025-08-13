<?php
session_start();
include 'db_baglanti.php';

if (!isset($_SESSION["uye_id"])) {
    header("Location: girisyap.php");
    exit();
}

$uye_id = (int)$_SESSION['uye_id'];

$s = $conn->prepare("SELECT siparis_no, toplam_tutar FROM siparis WHERE uye_id = ? ORDER BY siparis_id DESC LIMIT 1");
$s->bind_param("i", $uye_id);
$s->execute();
$son = $s->get_result()->fetch_assoc();
$siparis_no = $son['siparis_no'] ?? null;
$toplam     = isset($son['toplam_tutar']) ? number_format((float)$son['toplam_tutar'], 2) : null;
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="keywords" content="Aksesuar,Takı,Gözlük,Şapka,Çanta,Toka,Saç Aksesuarı">
<meta name="description" content="Yakamoz Aksesuar’da favorilediğiniz ürünleri görün">
<link rel="icon" type="image/png" href="YA-Dükkan Resimleri/icon.png">
<title>Sipariş Tamamlandı - Yakamoz Aksesuar</title>
<link rel="stylesheet" href="style.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
  .push-footer { padding-bottom: 45vh; } 
</style>
</head>
<body class="bg-light">
<?php include 'navbar.php'; ?>

  <div class="container mt-5 d-flex justify-content-center push-footer">
    <div class="p-5 shadow-lg rounded" style="max-width: 600px; background-color: #fff;">
      <h1 class="mb-3 text-center">Siparişiniz Başarıyla Alındı!</h1>

      <?php if ($siparis_no): ?>
        <p class="lead mb-1 text-center"><strong>Sipariş No:</strong> <?= htmlspecialchars($siparis_no) ?></p>
        <?php if ($toplam !== null): ?>
          <p class="mb-3 text-center"><strong>Toplam:</strong> <?= $toplam ?> TL</p>
        <?php endif; ?>
        <p class="text-center">En kısa sürede hazırlanıp kargoya verilecektir.</p>
      <?php else: ?>
        <p class="lead text-center">En kısa sürede hazırlanıp kargoya verilecektir.</p>
      <?php endif; ?>

      <div class="text-center mt-4">
        <a href="siparislerim.php" class="btn btn-dark me-2">Siparişlerimi Gör</a>
        <a href="index.php" class="btn btn-outline-dark">Ana Sayfaya Dön</a>
      </div>
    </div>
  </div>

  <?php include 'footer.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
