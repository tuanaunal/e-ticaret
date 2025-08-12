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
$mesaj_tur = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sil_id'])) {
    if (!isset($_POST['csrf']) || !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
        $mesaj = "Oturum doğrulaması başarısız.";
        $mesaj_tur = "danger";
    } else {
        $sil_id = (int)$_POST['sil_id'];

        $res = $conn->prepare("SELECT resim FROM urun WHERE urun_id = ?");
        $res->bind_param("i", $sil_id);
        $res->execute();
        $resRow = $res->get_result()->fetch_assoc();
        $resim_yolu = !empty($resRow['resim']) ? "../resimler/" . $resRow['resim'] : null;

        $stmt = $conn->prepare("DELETE FROM urun WHERE urun_id = ?");
        $stmt->bind_param("i", $sil_id);
        if ($stmt->execute()) {
            if ($resim_yolu && file_exists($resim_yolu)) {
                unlink($resim_yolu);
            }
            $mesaj = "Ürün başarıyla silindi.";
            $mesaj_tur = "success";
        } else {
            $mesaj = "Silme sırasında hata oluştu.";
            $mesaj_tur = "danger";
        }

        $_SESSION['csrf'] = bin2hex(random_bytes(16));
    }
}

$sql = "SELECT 
            u.urun_id, u.urun_adi, u.fiyat, u.stok, u.resim, 
            u.populer, u.yeni_gelen,
            k.kategori_adi
        FROM urun u
        LEFT JOIN kategori k ON u.kategori_id = k.kategori_id
        ORDER BY u.urun_id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="keywords" content="Aksesuar,Takı,Gözlük,Şapka,Çanta,Toka,Saç Aksesuarı">
<meta name="description" content="Yakamoz Aksesuar – Admin Ürün Listele">
<link rel="icon" type="image/png" href="../YA-Dükkan Resimleri/icon.png">
<title>Admin Ürün Listele - Yakamoz Aksesuar</title>
<link rel="stylesheet" href="../style.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
    body { background:#f8f9fa; }
    .panel-card { max-width:1200px; width:100%; border-radius:1rem; box-shadow:0 10px 25px rgba(0,0,0,.08); }
    .urun-thumb { width:70px; height:70px; object-fit:cover; border-radius:.5rem; }
    .table td, .table th { vertical-align: middle; }
    .nowrap { white-space: nowrap; }
    .badge-light { background:#f1f3f5; color:#495057; border:1px solid #dee2e6; }
</style>
</head>
<body>

<div class="container-fluid min-vh-100 d-flex justify-content-center align-items-start align-items-md-center py-4">
  <div class="card panel-card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2 class="m-0">Ürün Listesi</h2>
      <a href="urun_ekle.php" class="btn btn-dark btn-sm"><i class="bi bi-plus-circle me-1"></i> Yeni Ürün</a>
    </div>

    <?php if (!empty($mesaj)): ?>
      <div class="alert alert-<?= $mesaj_tur ?> py-2"><?= htmlspecialchars($mesaj) ?></div>
    <?php endif; ?>

    <?php if ($result && $result->num_rows > 0): ?>
      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th>Resim</th>
              <th>Ürün Adı</th>
              <th>Kategori</th>
              <th>Etiketler</th>
              <th class="nowrap">Fiyat (₺)</th>
              <th>Stok</th>
              <th class="text-center">Sil</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                <td>
                  <?php if (!empty($row['resim'])): ?>
                    <img src="../resimler/<?= htmlspecialchars($row['resim']) ?>" class="urun-thumb" alt="">
                  <?php else: ?>
                    <span class="text-muted">—</span>
                  <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($row['urun_adi']) ?></td>
                <td><?= htmlspecialchars($row['kategori_adi'] ?? '—') ?></td>

                <td style="min-width:160px;">
                  <?php
                    $badges = [];
                    if ((int)$row['populer'] === 1)    $badges[] = '<span class="badge bg-warning text-dark me-1">Popüler</span>';
                    if ((int)$row['yeni_gelen'] === 1) $badges[] = '<span class="badge bg-success me-1">Yeni Gelen</span>';
                    echo $badges ? implode(' ', $badges) : '<span class="badge badge-light">—</span>';
                  ?>
                </td>
                
                <td class="nowrap"><?= number_format((float)$row['fiyat'], 2) ?></td>
                <td><?= (int)$row['stok'] ?></td>
                <td class="text-center">
                  <form method="POST" onsubmit="return confirm('Bu ürünü silmek istediğinize emin misiniz?');" class="d-inline">
                    <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">
                    <input type="hidden" name="sil_id" value="<?= (int)$row['urun_id'] ?>">
                    <button type="submit" class="btn btn-danger btn-sm">
                      <i class="bi bi-trash"></i> Sil
                    </button>
                  </form>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="alert alert-info m-0">Henüz ürün bulunmuyor.</div>
    <?php endif; ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
