<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include '../db_baglanti.php';

if (!isset($_SESSION['uye_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: ../girisyap.php");
    exit();
}

$ara = isset($_GET['q']) ? trim($_GET['q']) : '';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$sql = "SELECT uye_id, ad, soyad, email,
               IFNULL(telefon,'') AS telefon,
               kayit_tarihi
        FROM uye";

$params = [];
$types  = '';

if ($ara !== '') {
    $sql .= " WHERE (ad LIKE CONCAT('%', ?, '%')
              OR soyad LIKE CONCAT('%', ?, '%')
              OR email LIKE CONCAT('%', ?, '%'))";
    $params = [$ara, $ara, $ara];
    $types  = 'sss';
}

$sql .= " ORDER BY kayit_tarihi DESC";

$stmt = $conn->prepare($sql);
if ($types) { $stmt->bind_param($types, ...$params); }
$stmt->execute();
$kullanicilar = $stmt->get_result();
?>

<!doctype html>
<html lang="tr">
<head>
<meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="keywords" content="Aksesuar,Takı,Gözlük,Şapka,Çanta,Toka,Saç Aksesuarı">
  <meta name="description" content="Yakamoz Aksesuar – Admin Girişi">
  <link rel="icon" type="image/png" href="../YA-Dükkan Resimleri/icon.png">
  <title>Admin Kullanıcı - Yakamoz Aksesuar</title>
  <link rel="stylesheet" href="../style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Kullanıcılar</h3>

    <form class="d-flex" method="get" action="">
      <input name="q" type="search" class="form-control me-2" placeholder="Ad, soyad veya e-posta" value="<?= htmlspecialchars($ara) ?>">
      <button class="btn btn-dark">Ara</button>
    </form>
  </div>

  <div class="card">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Ad Soyad</th>
              <th>E-posta</th>
              <th>Telefon</th>
              <th>Kayıt Tarihi</th>
              <th class="text-end">İşlemler</th>
            </tr>
          </thead>
          <tbody>
          <?php if ($kullanicilar->num_rows > 0): ?>
            <?php while($u = $kullanicilar->fetch_assoc()): ?>
              <tr>
                <td><?= (int)$u['uye_id'] ?></td>
                <td><?= htmlspecialchars($u['ad'].' '.$u['soyad']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><?= htmlspecialchars($u['telefon']) ?></td>
                <td><?= htmlspecialchars($u['kayit_tarihi']) ?></td>
                <td class="text-end">
                  <form action="admin_kullanici_sil.php" method="post" class="d-inline"
                        onsubmit="return confirm('Bu kullanıcıyı silmek istediğinize emin misiniz? Bu işlem geri alınamaz.');">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="hidden" name="uye_id" value="<?= (int)$u['uye_id'] ?>">
                    <button class="btn btn-sm btn-outline-danger">
                      Sil
                    </button>
                  </form>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="6" class="text-center py-4">Kayıtlı kullanıcı bulunamadı.</td>
            </tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
