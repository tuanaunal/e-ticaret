<?php
session_start();
include '../db_baglanti.php';


if (!isset($_SESSION['admin'])) {
    header("Location: admin_giris.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="keywords" content="Aksesuar,Takı,Gözlük,Şapka,Çanta,Toka,Saç Aksesuarı">
  <meta name="description" content="Yakamoz Aksesuar – Admin Paneli">
  <link rel="icon" type="image/png" href="../YA-Dükkan Resimleri/icon.png">
  <title>Admin Paneli - Yakamoz Aksesuar</title>
  <link rel="stylesheet" href="../style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .panel-card {
      width: 100%;
      max-width: 900px;
      border-radius: 1rem;
      box-shadow: 0 10px 25px rgba(0,0,0,.08);
    }
    .panel-title { font-family: 'Playfair Display', serif; }
    .action-btn {
      min-width: 190px;
    }
  </style>
</head>
<body>

  <div class="container-fluid min-vh-100 d-flex justify-content-center align-items-center py-4">
    <div class="card panel-card p-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="panel-title m-0">Admin Paneli</h2>
        <span class="text-muted small">Hoş geldiniz, <strong><?= htmlspecialchars($_SESSION['admin']) ?></strong></span>
      </div>

      <hr class="my-3">

      <div class="row g-3 justify-content-center">
  <div class="col-6 d-grid">
    <a href="kategori_ekle.php" class="btn btn-primary action-btn">
      <i class="bi bi-folder-plus me-1"></i> Kategori Ekle
    </a>
  </div>
  <div class="col-6 d-grid">
    <a href="urun_ekle.php" class="btn btn-success action-btn">
      <i class="bi bi-plus-circle me-1"></i> Ürün Ekle
    </a>
  </div>
  <div class="col-6 d-grid">
    <a href="urun_listele.php" class="btn btn-info text-white action-btn">
      <i class="bi bi-card-list me-1"></i> Ürünleri Listele
    </a>
  </div>
  <div class="col-6 d-grid">
    <a href="admin_siparisler.php" class="btn btn-warning action-btn">
      <i class="bi bi-receipt me-1"></i> Siparişleri Yönet
    </a>
  </div>

  <div class="col-6 d-grid">
    <a href="admin_kullanicilar.php" class="btn btn-secondary action-btn">
      <i class="bi bi-people-fill me-1"></i> Kullanıcıları Yönet
    </a>
  </div>

  <div class="col-6 d-grid">
    <a href="admin_cikis.php" class="btn btn-danger action-btn">
      <i class="bi bi-box-arrow-right me-1"></i> Çıkış Yap
    </a>
  </div>
</div>

   </div>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
