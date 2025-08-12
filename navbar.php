<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include "db_baglanti.php";

if (isset($_SESSION['uye_id'])) {
    $uye_id = $_SESSION['uye_id'];

    $stmt = $conn->prepare("SELECT COUNT(*) AS toplam FROM favoriler WHERE uye_id = ?");
    $stmt->bind_param("i", $uye_id);
    $stmt->execute();
    $favori_adet = (int)($stmt->get_result()->fetch_assoc()['toplam'] ?? 0);

    $stmt = $conn->prepare("SELECT COALESCE(SUM(adet),0) AS toplam FROM sepet WHERE uye_id = ?");
    $stmt->bind_param("i", $uye_id);
    $stmt->execute();
    $toplam_adet = (int)($stmt->get_result()->fetch_assoc()['toplam'] ?? 0);
} else {
    $favori_adet = 0;
    $toplam_adet = 0;
}
?>

<nav>
  <ul class="nav justify-content-end">
    <ul class="navbar-nav ms-auto mb- mb-lg-0 m d-flex flex-row">
      
      <li class="nav-item me-3">
        <a class="nav-link position-relative" href="favoriler.php">
          <i class="bi bi-suit-heart" style="font-size: 1.3rem; position: relative;"></i>
          <span
            id="favori-sayi"
            class="favori-sayac badge rounded-pill"
            style="background-color: black; color: white; font-size: 0.5rem; padding: 1px 4px; position: absolute; top: 0; right: 0;">
            <?php echo $favori_adet; ?>
          </span>
        </a>
      </li>

      <li class="nav-item me-3">
        <a class="nav-link position-relative" href="sepet.php">
          <i class="bi bi-handbag" style="font-size: 1.3rem; position: relative;"></i>
          <span
            id="sepet-sayi"
            class="sepet-sayac badge rounded-pill"
            style="background-color: black; color: white; font-size: 0.5rem; padding: 1px 4px; position: absolute; top: 0; right: 0;">
            <?php echo $toplam_adet; ?>
          </span>
        </a>
      </li>

      <li class="nav-item dropdown me-3">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown"
           aria-expanded="false">
          <i class="bi bi-person" style="font-size: 1.5rem;"></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
          <?php if (isset($_SESSION['uye_id'])): ?>
            <li class="dropdown-item fw-bold" style="font-family: 'Playfair Display', serif;">
              Merhaba, <?php echo htmlspecialchars($_SESSION['uye_ad'] ?? ''); ?>
            </li>

            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
              <!-- Admin menüsü -->
              <li><a class="dropdown-item" style="font-family: 'Playfair Display', serif;" href="hesabim.php">Hesabım</a></li>
              <li><a class="dropdown-item" style="font-family: 'Playfair Display', serif;" href="http://localhost:8888/AKSESUAR-SHOP/admin/admin_panel.php">Admin Paneli</a></li>
              <li><a class="dropdown-item" style="font-family: 'Playfair Display', serif;" href="sifre_degistir.php">Şifre Değiştir</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" style="font-family: 'Playfair Display', serif;" href="cikis.php">Çıkış Yap</a></li>
            <?php else: ?>
              <!-- Normal kullanıcı menüsü -->
              <li><a class="dropdown-item" style="font-family: 'Playfair Display', serif;" href="hesabim.php">Hesabım</a></li>
              <li><a class="dropdown-item" style="font-family: 'Playfair Display', serif;" href="siparislerim.php">Siparişlerim</a></li>
              <li><a class="dropdown-item" style="font-family: 'Playfair Display', serif;" href="sifre_degistir.php">Şifre Değiştir</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" style="font-family: 'Playfair Display', serif;" href="cikis.php">Çıkış Yap</a></li>
            <?php endif; ?>

          <?php else: ?>
            <li><a class="dropdown-item" style="font-family: 'Playfair Display', serif;" href="girisyap.php">Giriş Yap</a></li>
            <li><a class="dropdown-item" style="font-family: 'Playfair Display', serif;" href="uyeol.php">Üye Ol</a></li>
          <?php endif; ?>
        </ul>
      </li>
    </ul>
  </ul>

  <nav class="navbar" style="background-color: #000; color: #fff;">
    <div class="container-fluid d-flex justify-content-between align-items-center">
      <a class="navbar-brand" style="font-family:cursive; font-size: 2rem; color: white">
        Yakamoz Aksesuar
      </a>
      <div class="d-flex align-items-center gap-3">
        <ul class="navbar-nav flex-row">
          <li class="nav-item me-3">
            <a class="nav-link" href="index.php#populer" style="font-family: 'Playfair Display', serif; color: white">Popüler Ürünler</a>
          </li>
          <li class="nav-item me-3">
            <a class="nav-link" href="index.php#yenigelen" style="font-family: 'Playfair Display', serif; color: white">Yeni Gelen Ürünler</a>
          </li>
        </ul>
        <form class="d-flex" role="search" action="arama.php" method="GET">
          <input class="form-control me-2" type="search" name="q" placeholder="Ürün veya kategori ara" aria-label="Ürün veya kategori ara" />
          <button class="btn btn-outline-light" type="submit"><i class="bi bi-search"></i></button>
        </form>
      </div>
    </div>
  </nav>

  <nav class="navbar mt-3" style="background-color: #000; color: #fff;">
    <div class="container-fluid justify-content-center ">
      <ul class="navbar-nav d-flex flex-row flex-nowrap justify-content-center align-items-center gap-3 w-100 overflow-hidden">
        <li class="nav-item">
          <a class="nav-link text-center px-2 small" href="index.php" style="font-family: 'Playfair Display', serif; color: white;">Ana Sayfa</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-center px-2 small" href="taki.php" style="font-family: 'Playfair Display', serif; color: white">Takı</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-center px-2 small" href="gozluk.php" style="font-family: 'Playfair Display', serif; color: white">Gözlük</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-center px-2 small" href="sapka.php" style="font-family: 'Playfair Display', serif; color: white">Şapka</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-center px-2 small" href="canta.php" style="font-family: 'Playfair Display', serif; color: white">Çanta</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-center px-2 small" href="sacaksesuarlari.php" style="font-family: 'Playfair Display', serif; color: white">Saç Aksesuarları</a>
        </li>
      </ul>
    </div>
  </nav>

</nav>
