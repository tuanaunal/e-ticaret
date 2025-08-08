<?php
session_start();
include 'db_baglanti.php';

if (!isset($_SESSION['uye_id'])) {
    header("Location: girisyap.php");
    exit();
}
$uye_id = (int)$_SESSION['uye_id'];

$sqlToplam = "SELECT COALESCE(SUM(s.adet * COALESCE(s.birim_fiyat, u.fiyat)),0) AS toplam
              FROM sepet s
              JOIN urun u ON u.urun_id = s.urun_id
              WHERE s.uye_id = ?";
$t = $conn->prepare($sqlToplam);
$t->bind_param("i", $uye_id);
$t->execute();
$toplam = (float)($t->get_result()->fetch_assoc()['toplam'] ?? 0);
$t->close();
if ($toplam <= 0) {
    header("Location: siparis_form.php?hata=sepet");
    exit();
}

$odeme_yontemi_id = isset($_POST['odeme_yontemi_id']) ? (int)$_POST['odeme_yontemi_id'] : 0;
if ($odeme_yontemi_id <= 0) {
    header("Location: siparis_form.php?hata=odeme");
    exit();
}
$oy = $conn->prepare("SELECT ad FROM odeme_yontemi WHERE odeme_yontemi_id = ?");
$oy->bind_param("i", $odeme_yontemi_id);
$oy->execute();
$resOy = $oy->get_result();
if ($resOy->num_rows === 0) {
    header("Location: siparis_form.php?hata=odeme");
    exit();
}
$odeme_adi = $resOy->fetch_assoc()['ad'];
$oy->close();

$adres_id = isset($_POST['adres_id']) ? (int)$_POST['adres_id'] : 0;
$adresRow = null;

if ($adres_id > 0) {
    $a = $conn->prepare("SELECT * FROM adres WHERE adres_id = ? AND uye_id = ?");
    $a->bind_param("ii", $adres_id, $uye_id);
    $a->execute();
    $adresRes = $a->get_result();
    $a->close();
    if ($adresRes->num_rows === 0) {
        header("Location: siparis_form.php?hata=adres");
        exit();
    }
    $adresRow = $adresRes->fetch_assoc();
} else {
    $ad_soyad   = trim($_POST['ad_soyad'] ?? '');
    $telefon    = trim($_POST['telefon'] ?? '');
    $il         = trim($_POST['il'] ?? '');
    $ilce       = trim($_POST['ilce'] ?? '');
    $posta_kodu = trim($_POST['posta_kodu'] ?? '');
    $acik_adres = trim($_POST['acik_adres'] ?? '');
    $mahalle    = trim($_POST['mahalle'] ?? ''); 

    if ($ad_soyad === '' || $telefon === '' || $il === '' || $ilce === '' || $acik_adres === '') {
        header("Location: siparis_form.php?hata=adres");
        exit();
    }

    $ins = $conn->prepare("INSERT INTO adres 
        (uye_id, ad_soyad, telefon, il, ilce, mahalle, acik_adres, posta_kodu)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $ins->bind_param("isssssss", $uye_id, $ad_soyad, $telefon, $il, $ilce, $mahalle, $acik_adres, $posta_kodu);
    if (!$ins->execute()) {
        header("Location: siparis_form.php?hata=genel");
        exit();
    }
    $adres_id = $ins->insert_id;
    $ins->close();

    $adresRow = [
        'ad_soyad'   => $ad_soyad,
        'telefon'    => $telefon,
        'il'         => $il,
        'ilce'       => $ilce,
        'mahalle'    => $mahalle,
        'acik_adres' => $acik_adres,
        'posta_kodu' => $posta_kodu
    ];
}

if (!$adresRow) {
    $a2 = $conn->prepare("SELECT * FROM adres WHERE adres_id = ?");
    $a2->bind_param("i", $adres_id);
    $a2->execute();
    $adresRow = $a2->get_result()->fetch_assoc();
    $a2->close();
}

$conn->begin_transaction();

try {
    do {
        $siparis_no = 'SC' . str_pad((string)random_int(0, 99999), 5, '0', STR_PAD_LEFT);
        $chk = $conn->prepare("SELECT 1 FROM siparis WHERE siparis_no = ?");
        $chk->bind_param("s", $siparis_no);
        $chk->execute();
        $exists = $chk->get_result()->num_rows > 0;
        $chk->close();
    } while ($exists);

    $durum = 'Hazırlanıyor';
    $kargo_firma = 'Yurtiçi Kargo';

    $s = $conn->prepare("INSERT INTO siparis 
        (uye_id, toplam_tutar, siparis_tarihi, durum, kargo_firma, siparis_no, adres_id, odeme_yontemi_id)
        VALUES (?, ?, NOW(), ?, ?, ?, ?, ?)");
    $s->bind_param("idsssii", $uye_id, $toplam, $durum, $kargo_firma, $siparis_no, $adres_id, $odeme_yontemi_id);
    if (!$s->execute()) { throw new Exception("siparis eklenemedi"); }
    $siparis_id = $s->insert_id;
    $s->close();

    $q = $conn->prepare("SELECT s.urun_id, s.adet, COALESCE(s.birim_fiyat, u.fiyat) AS fiyat
                         FROM sepet s
                         JOIN urun u ON u.urun_id = s.urun_id
                         WHERE s.uye_id = ?");
    $q->bind_param("i", $uye_id);
    $q->execute();
    $rs = $q->get_result();

    $sd = $conn->prepare("INSERT INTO siparis_detay (siparis_id, urun_id, adet, birim_fiyat)
                          VALUES (?, ?, ?, ?)");
    while ($row = $rs->fetch_assoc()) {
        $uid  = (int)$row['urun_id'];
        $adet = (int)$row['adet'];
        $fiyat= (float)$row['fiyat'];
        $sd->bind_param("iiid", $siparis_id, $uid, $adet, $fiyat);
        if (!$sd->execute()) { throw new Exception("siparis_detay eklenemedi"); }
    }
    $sd->close();
    $q->close();

    $adres_text = $adresRow['acik_adres']; 
    $sehir      = $adresRow['il'];        
    $ilce       = $adresRow['ilce'];

    $sb = $conn->prepare("INSERT INTO siparis_bilgi (siparis_id, ad_soyad, telefon, adres, sehir, ilce, odeme_yontemi)
                          VALUES (?, ?, ?, ?, ?, ?, ?)");
    $sb->bind_param("issssss", $siparis_id, $adresRow['ad_soyad'], $adresRow['telefon'], $adres_text, $sehir, $ilce, $odeme_adi);
    if (!$sb->execute()) { throw new Exception("siparis_bilgi eklenemedi"); }
    $sb->close();

    $del = $conn->prepare("DELETE FROM sepet WHERE uye_id = ?");
    $del->bind_param("i", $uye_id);
    if (!$del->execute()) { throw new Exception("sepet temizlenemedi"); }
    $del->close();

    $conn->commit();

    header("Location: siparis_basarili.php");
    exit();

} catch (Exception $e) {
    $conn->rollback();
    header("Location: siparis_form.php?hata=genel");
    exit();
}
