<?php
session_start();
include '../db_baglanti.php';

if (!isset($_SESSION['admin'])) {
    header("Location: admin_giris.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["siparis_id"])) {
    $siparis_id = (int)($_POST["siparis_id"] ?? 0);
    $yeni_durum = trim($_POST["durum"] ?? "");

    if ($siparis_id > 0 && $yeni_durum !== "") {
        $stmt = $conn->prepare("UPDATE siparis SET durum = ? WHERE siparis_id = ?");
        $stmt->bind_param("si", $yeni_durum, $siparis_id);
        $stmt->execute();
    }
}

$conn->query("UPDATE siparis SET kargo_firma = 'Yurtiçi Kargo' WHERE kargo_firma IS NULL OR kargo_firma = '' OR kargo_firma = 'Belirtilmedi'");

$sql = "SELECT s.*, u.ad, u.soyad 
        FROM siparis s 
        JOIN uye u ON s.uye_id = u.uye_id 
        ORDER BY s.siparis_tarihi DESC";
$result = $conn->query($sql);

function uretilen_siparis_no($id) {
    return 'YA' . date('Ymd') . '-' . str_pad((string)$id, 6, '0', STR_PAD_LEFT);
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="keywords" content="Aksesuar,Takı,Gözlük,Şapka,Çanta,Toka,Saç Aksesuarı">
  <meta name="description" content="Yakamoz Aksesuar – Admin Girişi">
  <link rel="icon" type="image/png" href="../YA-Dükkan Resimleri/icon.png">
  <title>Admin Siparişler - Yakamoz Aksesuar</title>
  <link rel="stylesheet" href="../style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body { background-color:#f8f9fa; }
    .panel-card { max-width:1200px; width:100%; border-radius:1rem; box-shadow:0 10px 25px rgba(0,0,0,.08); }
    .urun-resim { width:60px; height:60px; object-fit:cover; border-radius:8px; margin-right:8px; }
    .table thead th { white-space:nowrap; }
    .table td { vertical-align:middle; }
  </style>
</head>
<body>
  <div class="container-fluid min-vh-100 d-flex justify-content-center align-items-start align-items-md-center py-4">
    <div class="card panel-card p-4">
      <h2 class="mb-4 text-center">Tüm Siparişler</h2>

      <?php if ($result && $result->num_rows > 0): ?>
        <div class="table-responsive">
          <table class="table table-bordered align-middle">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Kullanıcı</th>
                <th>Tarih</th>
                <th>Ürünler</th>
                <th>Toplam</th>
                <th>Durum</th>
                <th>Kargo Firması</th>
                <th>Sipariş No</th>
                <th class="text-center">Kargo Takip</th>
                <th>İşlem</th>
              </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
              <?php
                $siparis_id = (int)$row['siparis_id'];

                $urun_sql = "SELECT sd.adet, sd.birim_fiyat, u.urun_adi, u.resim
                             FROM siparis_detay sd
                             JOIN urun u ON sd.urun_id = u.urun_id
                             WHERE sd.siparis_id = ?";
                $ps = $conn->prepare($urun_sql);
                $ps->bind_param("i", $siparis_id);
                $ps->execute();
                $urunler = $ps->get_result();

                if (empty($row['siparis_no'])) {
                    $yeni_sn = uretilen_siparis_no($siparis_id);
                    $upd = $conn->prepare("UPDATE siparis SET siparis_no = ? WHERE siparis_id = ?");
                    $upd->bind_param("si", $yeni_sn, $siparis_id);
                    $upd->execute();
                    $row['siparis_no'] = $yeni_sn;
                }

                $durum_raw = $row['durum'] ?? '';
                $durum = trim($durum_raw);
                $is_kargoda = (mb_strtolower($durum, 'UTF-8') === mb_strtolower('Kargoya Verildi', 'UTF-8'));

                $form_id = "f".$siparis_id;
              ?>
              <tr>
                <td><?= $siparis_id ?></td>
                <td><?= htmlspecialchars($row['ad']." ".$row['soyad']) ?></td>
                <td><?= htmlspecialchars($row['siparis_tarihi']) ?></td>

                <td style="min-width:260px;">
                  <?php if ($urunler && $urunler->num_rows > 0): ?>
                    <?php while ($u = $urunler->fetch_assoc()): ?>
                      <div class="d-flex align-items-center mb-1">
                        <img src="../Resimler/<?= htmlspecialchars($u['resim']) ?>" class="urun-resim" alt="">
                        <div>
                          <div class="fw-semibold" style="line-height:1.1"><?= htmlspecialchars($u['urun_adi']) ?></div>
                          <small><?= (int)$u['adet'] ?> adet</small>
                        </div>
                      </div>
                    <?php endwhile; ?>
                  <?php else: ?>
                    <span class="text-muted">Ürün yok</span>
                  <?php endif; ?>
                </td>

                <td><?= number_format((float)$row['toplam_tutar'], 2) ?> TL</td>

                <td>
                  <select name="durum" class="form-select form-select-sm" form="<?= $form_id ?>">
                    <option value="Hazırlanıyor"   <?= $durum==='Hazırlanıyor'   ? 'selected':'' ?>>Hazırlanıyor</option>
                    <option value="Kargoya Verildi"<?= $durum==='Kargoya Verildi'? 'selected':'' ?>>Kargoya Verildi</option>
                    <option value="Teslim Edildi"  <?= $durum==='Teslim Edildi'  ? 'selected':'' ?>>Teslim Edildi</option>
                    <option value="İptal Edildi"   <?= $durum==='İptal Edildi'   ? 'selected':'' ?>>İptal Edildi</option>
                  </select>
                </td>

                <td><?= htmlspecialchars($row['kargo_firma'] ?? '—') ?></td>

                <td><?= htmlspecialchars($row['siparis_no'] ?? '—') ?></td>

                <td class="text-center">
                  <?php if ($is_kargoda): ?>
                    <a class="btn btn-secondary btn-sm"
                       href="https://www.yurticikargo.com/" target="_blank" rel="noopener">
                       Takip
                    </a>
                  <?php else: ?>
                    <span class="text-muted">—</span>
                  <?php endif; ?>
                </td>

                <td class="text-center">
                  <form method="POST" id="<?= $form_id ?>">
                    <input type="hidden" name="siparis_id" value="<?= $siparis_id ?>">
                    <button type="submit" class="btn btn-primary btn-sm">Kaydet</button>
                  </form>
                </td>
              </tr>
            <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div class="alert alert-info m-0">Henüz sipariş bulunmuyor.</div>
      <?php endif; ?>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
