-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: localhost:8889
-- Üretim Zamanı: 13 Ağu 2025, 20:25:02
-- Sunucu sürümü: 8.0.40
-- PHP Sürümü: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `aksesuar_db`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `adres`
--

CREATE TABLE `adres` (
  `adres_id` int NOT NULL,
  `uye_id` int NOT NULL,
  `ad_soyad` varchar(100) NOT NULL,
  `telefon` varchar(20) NOT NULL,
  `il` varchar(50) NOT NULL,
  `ilce` varchar(50) NOT NULL,
  `mahalle` varchar(100) DEFAULT '',
  `acik_adres` text NOT NULL,
  `posta_kodu` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `adres`
--

INSERT INTO `adres` (`adres_id`, `uye_id`, `ad_soyad`, `telefon`, `il`, `ilce`, `mahalle`, `acik_adres`, `posta_kodu`) VALUES
(1, 5, 'Tuana Ünal', '0 531 461 96 24', 'Denizli', 'Pamukkale', '', 'İncilipınar mahallesi 1236 sokak', '10'),
(2, 1, 'Kaan Uzavcı', '0 530 560 24 42', 'Kocaeli', 'İzmit', '', 'Türkiye Cennet Mahallesi No:12', '12');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `favoriler`
--

CREATE TABLE `favoriler` (
  `favori_id` int NOT NULL,
  `uye_id` int NOT NULL,
  `urun_id` int NOT NULL,
  `eklenme_tarihi` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kategori`
--

CREATE TABLE `kategori` (
  `kategori_id` int NOT NULL,
  `kategori_adi` varchar(100) COLLATE utf8mb4_turkish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `kategori`
--

INSERT INTO `kategori` (`kategori_id`, `kategori_adi`) VALUES
(1, 'Takı'),
(2, 'Gözlük'),
(3, 'Şapka'),
(4, 'Çanta'),
(5, 'Saç Aksesuarları');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `odeme_yontemi`
--

CREATE TABLE `odeme_yontemi` (
  `odeme_yontemi_id` int NOT NULL,
  `ad` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `odeme_yontemi`
--

INSERT INTO `odeme_yontemi` (`odeme_yontemi_id`, `ad`) VALUES
(3, 'Havale/EFT'),
(1, 'Kapıda Ödeme'),
(2, 'Kredi Kartı');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `sepet`
--

CREATE TABLE `sepet` (
  `sepet_id` int NOT NULL,
  `uye_id` int DEFAULT NULL,
  `urun_id` int DEFAULT NULL,
  `adet` int DEFAULT '1',
  `birim_fiyat` decimal(10,2) NOT NULL,
  `eklenme_tarihi` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `siparis`
--

CREATE TABLE `siparis` (
  `siparis_id` int NOT NULL,
  `uye_id` int NOT NULL,
  `toplam_tutar` decimal(10,2) NOT NULL,
  `siparis_tarihi` datetime DEFAULT CURRENT_TIMESTAMP,
  `durum` varchar(50) COLLATE utf8mb4_turkish_ci DEFAULT 'Hazırlanıyor',
  `kargo_firma` varchar(100) COLLATE utf8mb4_turkish_ci DEFAULT 'Belirtilmedi',
  `siparis_no` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `adres_id` int DEFAULT NULL,
  `odeme_yontemi_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `siparis`
--

INSERT INTO `siparis` (`siparis_id`, `uye_id`, `toplam_tutar`, `siparis_tarihi`, `durum`, `kargo_firma`, `siparis_no`, `adres_id`, `odeme_yontemi_id`) VALUES
(21, 5, 999.96, '2025-08-12 17:54:21', 'Teslim Edildi', 'Yurtiçi Kargo', 'SC50917', 1, 1),
(22, 5, 1999.92, '2025-08-12 18:07:36', 'Kargoya Verildi', 'Yurtiçi Kargo', 'SC35407', 1, 1),
(23, 5, 249.99, '2025-08-12 18:07:56', 'Hazırlanıyor', 'Yurtiçi Kargo', 'SC33818', 1, 3),
(24, 5, 499.98, '2025-08-12 18:26:53', 'Hazırlanıyor', 'Yurtiçi Kargo', 'SC33692', 1, 2),
(25, 5, 249.99, '2025-08-13 16:07:59', 'Kargoya Verildi', 'Yurtiçi Kargo', 'SC48061', 1, 1),
(28, 5, 499.98, '2025-08-13 22:48:37', 'Teslim Edildi', 'Yurtiçi Kargo', 'SC72714', 1, 2),
(29, 5, 499.98, '2025-08-13 22:54:11', 'Kargoya Verildi', 'Yurtiçi Kargo', 'SC13782', 1, 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `siparis_bilgi`
--

CREATE TABLE `siparis_bilgi` (
  `bilgi_id` int NOT NULL,
  `siparis_id` int NOT NULL,
  `ad_soyad` varchar(100) COLLATE utf8mb4_turkish_ci NOT NULL,
  `telefon` varchar(20) COLLATE utf8mb4_turkish_ci NOT NULL,
  `adres` text COLLATE utf8mb4_turkish_ci NOT NULL,
  `sehir` varchar(50) COLLATE utf8mb4_turkish_ci NOT NULL,
  `ilce` varchar(50) COLLATE utf8mb4_turkish_ci NOT NULL,
  `odeme_yontemi` varchar(50) COLLATE utf8mb4_turkish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `siparis_bilgi`
--

INSERT INTO `siparis_bilgi` (`bilgi_id`, `siparis_id`, `ad_soyad`, `telefon`, `adres`, `sehir`, `ilce`, `odeme_yontemi`) VALUES
(16, 21, 'Tuana Ünal', '0 531 461 96 24', 'İncilipınar mahallesi 1236 sokak', 'Denizli', 'Pamukkale', 'Kapıda Ödeme'),
(17, 22, 'Tuana Ünal', '0 531 461 96 24', 'İncilipınar mahallesi 1236 sokak', 'Denizli', 'Pamukkale', 'Kapıda Ödeme'),
(18, 23, 'Tuana Ünal', '0 531 461 96 24', 'İncilipınar mahallesi 1236 sokak', 'Denizli', 'Pamukkale', 'Havale/EFT'),
(19, 24, 'Tuana Ünal', '0 531 461 96 24', 'İncilipınar mahallesi 1236 sokak', 'Denizli', 'Pamukkale', 'Kredi Kartı'),
(20, 25, 'Tuana Ünal', '0 531 461 96 24', 'İncilipınar mahallesi 1236 sokak', 'Denizli', 'Pamukkale', 'Kapıda Ödeme'),
(23, 28, 'Tuana Ünal', '0 531 461 96 24', 'İncilipınar mahallesi 1236 sokak', 'Denizli', 'Pamukkale', 'Kredi Kartı'),
(24, 29, 'Tuana Ünal', '0 531 461 96 24', 'İncilipınar mahallesi 1236 sokak', 'Denizli', 'Pamukkale', 'Kapıda Ödeme');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `siparis_detay`
--

CREATE TABLE `siparis_detay` (
  `detay_id` int NOT NULL,
  `siparis_id` int NOT NULL,
  `urun_id` int NOT NULL,
  `adet` int NOT NULL,
  `birim_fiyat` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `siparis_detay`
--

INSERT INTO `siparis_detay` (`detay_id`, `siparis_id`, `urun_id`, `adet`, `birim_fiyat`) VALUES
(47, 21, 8, 1, 249.99),
(48, 21, 7, 1, 249.99),
(49, 21, 11, 1, 249.99),
(50, 21, 12, 1, 249.99),
(51, 22, 22, 1, 249.99),
(52, 22, 21, 1, 249.99),
(53, 22, 30, 1, 249.99),
(54, 22, 31, 1, 249.99),
(55, 22, 36, 1, 249.99),
(56, 22, 45, 1, 249.99),
(57, 22, 54, 1, 249.99),
(58, 22, 64, 1, 249.99),
(59, 23, 6, 1, 249.99),
(60, 24, 44, 1, 249.99),
(61, 24, 43, 1, 249.99),
(62, 25, 53, 1, 249.99),
(66, 28, 22, 1, 249.99),
(67, 28, 21, 1, 249.99),
(68, 29, 41, 1, 249.99),
(69, 29, 42, 1, 249.99);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `urun`
--

CREATE TABLE `urun` (
  `urun_id` int NOT NULL,
  `kategori_id` int DEFAULT NULL,
  `urun_adi` varchar(100) COLLATE utf8mb4_turkish_ci NOT NULL,
  `aciklama` text COLLATE utf8mb4_turkish_ci,
  `fiyat` decimal(10,2) NOT NULL,
  `stok` int NOT NULL,
  `resim` varchar(255) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `populer` tinyint(1) DEFAULT '0',
  `yeni_gelen` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `urun`
--

INSERT INTO `urun` (`urun_id`, `kategori_id`, `urun_adi`, `aciklama`, `fiyat`, `stok`, `resim`, `populer`, `yeni_gelen`) VALUES
(5, 1, 'Boncuk Kolye', '', 249.99, 17, 'takı1.jpeg', 0, 0),
(6, 1, 'Deniz Kabuğu Kolye', '', 249.99, 16, 'takı2.jpeg', 0, 1),
(7, 1, 'Vintage Kolye', '', 249.99, 18, 'takı3.jpeg', 1, 0),
(8, 1, 'Kalp Charm Kolye', '', 249.99, 18, 'takı4.jpeg', 1, 0),
(9, 1, 'Minimal Bileklik', '', 249.99, 20, 'takı5.jpeg', 0, 0),
(10, 1, 'Çiçek Desen Bileklik', '', 249.99, 20, 'takı6.jpeg', 0, 1),
(11, 1, 'Fiyonk Gold Küpe', '', 249.99, 18, 'takı7.jpeg', 0, 0),
(12, 1, 'Çiçek Gold Küpe', '', 249.99, 18, 'takı8.jpeg', 0, 0),
(13, 1, 'Mor Taşlı Yüzük', '', 249.99, 18, 'takı9.jpeg', 0, 0),
(14, 1, 'Mavi Taşlı Yüzük', '', 249.99, 18, 'takı10.jpeg', 0, 0),
(15, 1, 'Charm Halhal', '', 249.99, 20, 'takı11.jpeg', 0, 0),
(16, 1, 'İnce Zincir Halhal', '', 249.99, 20, 'takı12.jpeg', 0, 0),
(17, 2, 'Şeffaf Gözlük', '', 249.99, 20, 'göz1.jpeg', 1, 0),
(18, 2, 'Minimal Gözlük', '', 249.99, 20, 'gözl2.jpeg', 0, 0),
(19, 2, 'Pastel Kanat Gözlük', '', 249.99, 20, 'göz3.jpeg', 0, 0),
(20, 2, 'Mavi Bulut Gözlük', '', 249.99, 20, 'göz4.jpeg', 0, 0),
(21, 2, 'Vintage Gözlük', '', 249.99, 18, 'göz5.jpeg', 0, 1),
(22, 2, 'Renkli Gözlük', '', 249.99, 18, 'göz6.jpeg', 0, 1),
(23, 2, 'Kedi Gözü Gözlük', '', 249.99, 20, 'göz7.jpeg', 0, 0),
(24, 2, 'Şekilli Gözlük', '', 249.99, 20, 'göz8.jpeg', 0, 0),
(25, 2, 'Nude Gözlük', '', 249.99, 20, 'göz9.jpeg', 1, 0),
(26, 2, 'Retro Gözlük', '', 249.99, 20, 'göz10.jpeg', 0, 0),
(27, 2, 'Minimal Gözlük', '', 249.99, 20, 'göz11.jpeg', 0, 0),
(28, 2, 'Mat Siyah Gözlük', '', 249.99, 20, 'göz12.jpeg', 0, 0),
(29, 3, 'Bağcıklı Hasır Şapka', '', 249.99, 20, 'şapk1.jpeg', 0, 0),
(30, 3, 'Dantelli Hasır Şapka', '', 249.99, 19, 'şapk2.jpeg', 1, 0),
(31, 3, 'Peluş Bucket Şapka', '', 249.99, 19, 'şapk3.jpeg', 1, 0),
(32, 3, 'İnek Bucket Şapka', '', 249.99, 20, 'şapk4.jpeg', 0, 0),
(33, 3, 'Sade Bucket Şapka', '', 249.99, 20, 'şapk5.jpeg', 0, 0),
(34, 3, 'Soft Bucket Şapka', '', 249.99, 20, 'şapk6.jpeg', 0, 0),
(35, 3, 'Renkli Bucket Şapka', '', 249.99, 20, 'şapk7.jpeg', 0, 1),
(36, 3, 'Genç Bucket Şapka', '', 249.99, 19, 'şapk8.jpeg', 0, 1),
(37, 3, 'Soft Spor Şapka', '', 249.99, 20, 'şapk9.jpeg', 0, 0),
(38, 3, 'Vintage Spor Şapka', '', 249.99, 20, 'şapk10.jpeg', 0, 0),
(39, 3, 'Pastel Renk Bere', '', 249.99, 20, 'şapk11.jpeg', 0, 0),
(40, 3, 'Basic Bere', '', 249.99, 20, 'şapk12.jpeg', 0, 0),
(41, 4, 'Soft Pastel Cüzdan', '', 249.99, 19, 'çanta1.jpeg', 0, 0),
(42, 4, 'Çiçek Desen Cüzdan', '', 249.99, 19, 'çanta2.jpeg', 0, 0),
(43, 4, 'Kumaş Cüzdan(L)', '', 249.99, 19, 'çanta3.jpeg', 0, 0),
(44, 4, 'Kumaş Cüzdan(M)', '', 249.99, 19, 'çanta4.jpeg', 1, 0),
(45, 4, 'Fiyonk Hasır Çanta', '', 249.99, 19, 'çanta5.jpeg', 1, 0),
(46, 4, 'Hasır Omuz Çantası', '', 249.99, 20, 'çanta6.jpeg', 0, 0),
(47, 4, 'Sade Mini Çanta', '', 249.99, 20, 'çanta7.jpeg', 0, 1),
(48, 4, 'Pastel Mini Çanta', '', 249.99, 20, 'çanta8.jpeg', 0, 1),
(49, 4, 'Pastel Çapraz Çanta', '', 249.99, 20, 'çanta9.jpeg', 0, 0),
(50, 4, 'Koyu Çapraz Çanta', '', 249.99, 20, 'çanta10.jpeg', 0, 0),
(51, 4, 'Haki Omuz Çantası', '', 249.99, 20, 'çanta11.jpeg', 0, 0),
(52, 4, 'Yazlık Bez Çantası', '', 249.99, 20, 'çanta12.jpeg', 0, 0),
(53, 5, 'İnci Toka', '', 249.99, 19, 'saç1.jpeg', 0, 0),
(54, 5, 'Deniz Kabuğu Toka', '', 249.99, 19, 'saç2.jpeg', 1, 0),
(55, 5, 'Pastel Scrunchie', '', 249.99, 19, 'saç3.jpeg', 0, 0),
(56, 5, 'Desenli Scrunchie', '', 249.99, 20, 'saç4.jpeg', 0, 0),
(57, 5, 'Gold Toka', '', 249.99, 20, 'saç5.jpeg', 1, 0),
(58, 5, 'Koyu Tonlar Toka', '', 249.99, 20, 'saç6.jpeg', 0, 0),
(59, 5, 'Mor Çiçek Toka', '', 249.99, 20, 'saç7.jpeg', 0, 0),
(60, 5, 'Mavi Çiçek Toka', '', 249.99, 20, 'saç8.jpeg', 0, 1),
(61, 5, 'Örgü Taç(S/M)', '', 249.99, 20, 'saç9.jpeg', 0, 0),
(62, 5, 'Örgü Taç(L/XL)', '', 249.99, 20, 'saç10.jpeg', 0, 0),
(63, 5, 'Çiçekli Bandana', '', 249.99, 20, 'saç11.jpeg', 0, 0),
(64, 5, 'Kirazlı Bandana', '', 249.99, 19, 'saç12.jpeg', 0, 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `uye`
--

CREATE TABLE `uye` (
  `uye_id` int NOT NULL,
  `ad` varchar(50) COLLATE utf8mb4_turkish_ci NOT NULL,
  `soyad` varchar(50) COLLATE utf8mb4_turkish_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_turkish_ci NOT NULL,
  `sifre` varchar(255) COLLATE utf8mb4_turkish_ci NOT NULL,
  `telefon` varchar(20) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `dogum_tarihi` date DEFAULT NULL,
  `kayit_tarihi` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `role` enum('user','admin') COLLATE utf8mb4_turkish_ci DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `uye`
--

INSERT INTO `uye` (`uye_id`, `ad`, `soyad`, `email`, `sifre`, `telefon`, `dogum_tarihi`, `kayit_tarihi`, `role`) VALUES
(1, 'Kaan', 'Uzavcı', 'ggbestkaan@hotmail.com', '$2y$10$sK8TgWR3J7ufBJzmRCbwxOqAYkQCxzwFowP/PxF6t7H3fckx1rK9W', '0 530 560 24 42', '2003-05-18', '2025-08-04 07:37:24', 'user'),
(4, 'Yıldız', 'Ünal', 'yildizunal@gmail.com', '$2y$10$6UIw/8R.A7fS3a4Ory/nGuJglOwxH5r5Y.H/NbnaEVHE5SVO1gDji', '0 507 884 26 36', '1972-01-23', '2025-08-04 07:43:38', 'user'),
(5, 'Hasan ', 'Ünal', 'hasanunal@gmail.com', '$2y$10$4hcQepGc0ONIc9Yj4JaYCOSXpYnsyJwL/MC2mJwephmY6sgp1Fiz2', '0 546 694 25 87', '1966-12-02', '2025-08-04 07:48:34', 'user'),
(6, 'Tuana', 'Ünal', 'tuanaaunall10@gmail.com', '$2y$10$FWSZsftAzXEE77v2BB4uOe0d2LXOUasjz9IuxeOJOlJZsWSzL8BN.', '0 531 461 96 24', '2005-02-10', '2025-08-12 07:47:54', 'admin');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `adres`
--
ALTER TABLE `adres`
  ADD PRIMARY KEY (`adres_id`),
  ADD KEY `idx_adres_uye` (`uye_id`);

--
-- Tablo için indeksler `favoriler`
--
ALTER TABLE `favoriler`
  ADD PRIMARY KEY (`favori_id`),
  ADD KEY `uye_id` (`uye_id`),
  ADD KEY `urun_id` (`urun_id`);

--
-- Tablo için indeksler `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`kategori_id`);

--
-- Tablo için indeksler `odeme_yontemi`
--
ALTER TABLE `odeme_yontemi`
  ADD PRIMARY KEY (`odeme_yontemi_id`),
  ADD UNIQUE KEY `uk_odeme_ad` (`ad`);

--
-- Tablo için indeksler `sepet`
--
ALTER TABLE `sepet`
  ADD PRIMARY KEY (`sepet_id`),
  ADD KEY `uye_id` (`uye_id`),
  ADD KEY `urun_id` (`urun_id`);

--
-- Tablo için indeksler `siparis`
--
ALTER TABLE `siparis`
  ADD PRIMARY KEY (`siparis_id`),
  ADD KEY `uye_id` (`uye_id`),
  ADD KEY `fk_siparis_adres` (`adres_id`),
  ADD KEY `fk_siparis_odeme` (`odeme_yontemi_id`);

--
-- Tablo için indeksler `siparis_bilgi`
--
ALTER TABLE `siparis_bilgi`
  ADD PRIMARY KEY (`bilgi_id`),
  ADD KEY `siparis_id` (`siparis_id`);

--
-- Tablo için indeksler `siparis_detay`
--
ALTER TABLE `siparis_detay`
  ADD PRIMARY KEY (`detay_id`),
  ADD KEY `siparis_id` (`siparis_id`),
  ADD KEY `urun_id` (`urun_id`);

--
-- Tablo için indeksler `urun`
--
ALTER TABLE `urun`
  ADD PRIMARY KEY (`urun_id`),
  ADD KEY `kategori_id` (`kategori_id`);

--
-- Tablo için indeksler `uye`
--
ALTER TABLE `uye`
  ADD PRIMARY KEY (`uye_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `adres`
--
ALTER TABLE `adres`
  MODIFY `adres_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Tablo için AUTO_INCREMENT değeri `favoriler`
--
ALTER TABLE `favoriler`
  MODIFY `favori_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=222;

--
-- Tablo için AUTO_INCREMENT değeri `kategori`
--
ALTER TABLE `kategori`
  MODIFY `kategori_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Tablo için AUTO_INCREMENT değeri `odeme_yontemi`
--
ALTER TABLE `odeme_yontemi`
  MODIFY `odeme_yontemi_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Tablo için AUTO_INCREMENT değeri `sepet`
--
ALTER TABLE `sepet`
  MODIFY `sepet_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=242;

--
-- Tablo için AUTO_INCREMENT değeri `siparis`
--
ALTER TABLE `siparis`
  MODIFY `siparis_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Tablo için AUTO_INCREMENT değeri `siparis_bilgi`
--
ALTER TABLE `siparis_bilgi`
  MODIFY `bilgi_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Tablo için AUTO_INCREMENT değeri `siparis_detay`
--
ALTER TABLE `siparis_detay`
  MODIFY `detay_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- Tablo için AUTO_INCREMENT değeri `urun`
--
ALTER TABLE `urun`
  MODIFY `urun_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- Tablo için AUTO_INCREMENT değeri `uye`
--
ALTER TABLE `uye`
  MODIFY `uye_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `adres`
--
ALTER TABLE `adres`
  ADD CONSTRAINT `fk_adres_uye` FOREIGN KEY (`uye_id`) REFERENCES `uye` (`uye_id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `favoriler`
--
ALTER TABLE `favoriler`
  ADD CONSTRAINT `favoriler_ibfk_1` FOREIGN KEY (`uye_id`) REFERENCES `uye` (`uye_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favoriler_ibfk_2` FOREIGN KEY (`urun_id`) REFERENCES `urun` (`urun_id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `sepet`
--
ALTER TABLE `sepet`
  ADD CONSTRAINT `sepet_ibfk_1` FOREIGN KEY (`uye_id`) REFERENCES `uye` (`uye_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sepet_ibfk_2` FOREIGN KEY (`urun_id`) REFERENCES `urun` (`urun_id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `siparis`
--
ALTER TABLE `siparis`
  ADD CONSTRAINT `fk_siparis_adres` FOREIGN KEY (`adres_id`) REFERENCES `adres` (`adres_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_siparis_odeme` FOREIGN KEY (`odeme_yontemi_id`) REFERENCES `odeme_yontemi` (`odeme_yontemi_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `siparis_ibfk_1` FOREIGN KEY (`uye_id`) REFERENCES `uye` (`uye_id`);

--
-- Tablo kısıtlamaları `siparis_bilgi`
--
ALTER TABLE `siparis_bilgi`
  ADD CONSTRAINT `siparis_bilgi_ibfk_1` FOREIGN KEY (`siparis_id`) REFERENCES `siparis` (`siparis_id`);

--
-- Tablo kısıtlamaları `siparis_detay`
--
ALTER TABLE `siparis_detay`
  ADD CONSTRAINT `siparis_detay_ibfk_1` FOREIGN KEY (`siparis_id`) REFERENCES `siparis` (`siparis_id`),
  ADD CONSTRAINT `siparis_detay_ibfk_2` FOREIGN KEY (`urun_id`) REFERENCES `urun` (`urun_id`);

--
-- Tablo kısıtlamaları `urun`
--
ALTER TABLE `urun`
  ADD CONSTRAINT `urun_ibfk_1` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`kategori_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
