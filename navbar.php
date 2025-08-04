<?php
// Sadece session başlatılmamışsa başlat
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<nav>
  <ul class="nav justify-content-end">
    <ul class="navbar-nav ms-auto mb- mb-lg-0 m d-flex flex-row">
      <li class="nav-item me-3">
        <a class="nav-link position-relative" href="favoriler.php">
          <i class="bi bi-suit-heart" style="font-size: 1.3rem;"></i>
        </a>
      </li>
      <li class="nav-item me-3">
        <a class="nav-link position-relative" href="sepet.php">
          <i class="bi bi-handbag" style="font-size: 1.3rem;"></i>
        </a>
      </li>
      <li class="nav-item dropdown me-3">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown"
          aria-expanded="false">
          <i class="bi bi-person" style="font-size: 1.5rem;"></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
          <?php if (isset($_SESSION['uye_id'])): ?>
            <li class="dropdown-item" style="font-family: 'Playfair Display', serif;">
              Merhaba, <?php echo $_SESSION['uye_ad']; ?>
            </li>
            <li><a class="dropdown-item" href="cikis.php" style="font-family: 'Playfair Display', serif;">Çıkış Yap</a></li>
          <?php else: ?>
            <li><a class="dropdown-item" href="girisyap.php" style="font-family: 'Playfair Display', serif;">Giriş Yap</a></li>
            <li><a class="dropdown-item" href="uyeol.php" style="font-family: 'Playfair Display', serif;">Üye Ol</a></li>
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
        <form class="d-flex" role="search">
          <input class="form-control me-2" type="search" placeholder="Ürün veya kategori ara" aria-label="Ürün veya kategori ara" />
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
  </ul>
</nav>
