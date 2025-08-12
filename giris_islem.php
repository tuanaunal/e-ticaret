<?php
session_start();
include 'db_baglanti.php';

function guvenli_redirect($url) {
    if (!$url) return 'index.php';
    $p = parse_url($url);
    if ($p === false || isset($p['scheme']) || isset($p['host'])) return 'index.php';
    if (preg_match('#^/[^\0]*$#', $url) || preg_match('#^[a-zA-Z0-9_\-\/\.]+\z#', $url)) {
        return $url;
    }
    return 'index.php';
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email   = trim($_POST['email'] ?? '');
    $sifre   = $_POST['sifre'] ?? '';
    $redirect = guvenli_redirect($_POST['redirect'] ?? 'index.php');

    $stmt = $conn->prepare("SELECT uye_id, ad, soyad, email, sifre, role FROM uye WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();

        if (password_verify($sifre, $row['sifre'])) {
            $_SESSION['uye_id'] = (int)$row['uye_id'];
            $_SESSION['ad']     = $row['ad'];       
            $_SESSION['uye_ad'] = $row['ad'];    
            $_SESSION['soyad']  = $row['soyad'] ?? '';
            $_SESSION['email']  = $row['email'];
            $_SESSION['role']   = $row['role'] ?: 'user';

            header("Location: " . $redirect);
            exit();
        } else {
            header("Location: girisyap.php?error=" . urlencode("Hatalı şifre girdiniz!"));
            exit();
        }
    } else {
        header("Location: girisyap.php?error=" . urlencode("Bu e-posta adresiyle kayıtlı kullanıcı bulunamadı."));
        exit();
    }
}
?>
