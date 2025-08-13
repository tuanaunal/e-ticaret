<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (!empty($_SESSION['uye_id'])) {
  header("Location: index.php");
  exit();
}

$redirect = isset($_GET['redirect'])
  ? trim($_GET['redirect'])
  : (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php');

$target = 'girisyap.php?tab=register&redirect=' . urlencode($redirect);
header("Location: $target", true, 302);
exit();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Yönlendiriliyor…</title>
  <meta http-equiv="refresh" content="0;url=<?= htmlspecialchars($target ?? 'girisyap.php?tab=register') ?>">
</head>
<body>
  Yönlendiriliyor… Eğer otomatik yönlenmezse
  <a href="<?= htmlspecialchars($target ?? 'girisyap.php?tab=register') ?>">buraya tıklayın</a>.
</body>
</html>
