<?php
session_start();
include '../db_baglanti.php';

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(16));
}

$hata = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST['csrf']) || !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
        $hata = "Oturum doğrulaması başarısız. Lütfen tekrar deneyin.";
    } else {
        $email = trim($_POST['email'] ?? "");
        $sifre = $_POST['sifre'] ?? "";

        $stmt = $conn->prepare("SELECT * FROM admin WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $admin = $stmt->get_result()->fetch_assoc();

        if ($admin) {
            $kayitliSifre = $admin['sifre'];

            $dogru = false;
            if (strlen($kayitliSifre) >= 60 && str_starts_with($kayitliSifre, '$')) {
                $dogru = password_verify($sifre, $kayitliSifre);
            }
            if (!$dogru) {
                $dogru = hash_equals($kayitliSifre, $sifre);
            }

            if ($dogru) {
                $_SESSION['admin'] = $admin['email'];
                unset($_SESSION['csrf']);
                header("Location: admin_panel.php");
                exit();
            }
        }
        $hata = "Hatalı e-posta veya şifre!";
    }
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
  <title>Admin Girişi - Yakamoz Aksesuar</title>
  <link rel="stylesheet" href="../style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .login-card { width: 380px; border-radius: 1rem; box-shadow: 0 10px 25px rgba(0,0,0,.08); }
    .login-title { font-family: 'Playfair Display', serif; }
  </style>
</head>
<body class="d-flex justify-content-center align-items-center vh-100">

  <div class="card p-4 login-card">
    <h3 class="text-center mb-3 login-title">Admin Girişi</h3>

    <?php if (!empty($hata)): ?>
      <div class="alert alert-danger py-2"><?= htmlspecialchars($hata) ?></div>
    <?php endif; ?>

    <form method="POST" novalidate>
      <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">

      <div class="mb-3">
        <label class="form-label">E-posta</label>
        <input type="email" name="email" class="form-control" required autocomplete="username">
      </div>

      <div class="mb-3">
        <label class="form-label">Şifre</label>
        <div class="input-group">
          <input type="password" name="sifre" id="pwd" class="form-control" required autocomplete="current-password">
          <button class="btn btn-outline-secondary" type="button" id="togglePwd" aria-label="Şifreyi göster/gizle">
            <i class="bi bi-eye"></i>
          </button>
        </div>
      </div>

      <button type="submit" class="btn btn-dark w-100">Giriş Yap</button>
    </form>
  </div>

  <script>
    const btn = document.getElementById('togglePwd');
    const pwd = document.getElementById('pwd');
    btn.addEventListener('click', () => {
      const isPass = pwd.getAttribute('type') === 'password';
      pwd.setAttribute('type', isPass ? 'text' : 'password');
      btn.querySelector('i').className = isPass ? 'bi bi-eye-slash' : 'bi bi-eye';
    });
  </script>
</body>
</html>
