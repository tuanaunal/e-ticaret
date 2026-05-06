-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Anamakine: localhost:3306
-- Üretim Zamanı: 06 May 2026, 12:47:21
-- Sunucu sürümü: 5.7.24
-- PHP Sürümü: 8.3.1

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
  `adres_id` int(11) NOT NULL,
  `uye_id` int(11) NOT NULL,
  `adres_basligi` varchar(50) COLLATE utf8mb4_turkish_ci NOT NULL,
  `adres_detay` text COLLATE utf8mb4_turkish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `favori`
--

CREATE TABLE `favori` (
  `favori_id` int(11) NOT NULL,
  `uye_id` int(11) NOT NULL,
  `urun_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kategori`
--

CREATE TABLE `kategori` (
  `kategori_id` int(11) NOT NULL,
  `kategori_ad` varchar(50) COLLATE utf8mb4_turkish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `sepet`
--

CREATE TABLE `sepet` (
  `sepet_id` int(11) NOT NULL,
  `uye_id` int(11) NOT NULL,
  `urun_id` int(11) NOT NULL,
  `adet` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `siparis`
--

CREATE TABLE `siparis` (
  `siparis_id` int(11) NOT NULL,
  `uye_id` int(11) NOT NULL,
  `adres_id` int(11) NOT NULL,
  `toplam_tutar` decimal(10,2) NOT NULL,
  `durum` varchar(50) COLLATE utf8mb4_turkish_ci DEFAULT 'Hazırlanıyor',
  `kargo_takip` varchar(100) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `tarih` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `siparis_detay`
--

CREATE TABLE `siparis_detay` (
  `detay_id` int(11) NOT NULL,
  `siparis_id` int(11) NOT NULL,
  `urun_id` int(11) NOT NULL,
  `adet` int(11) NOT NULL,
  `birim_fiyat` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `urun`
--

CREATE TABLE `urun` (
  `urun_id` int(11) NOT NULL,
  `kategori_id` int(11) NOT NULL,
  `urun_ad` varchar(100) COLLATE utf8mb4_turkish_ci NOT NULL,
  `fiyat` decimal(10,2) NOT NULL,
  `stok` int(11) NOT NULL,
  `gorsel` varchar(255) COLLATE utf8mb4_turkish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `uye`
--

CREATE TABLE `uye` (
  `uye_id` int(11) NOT NULL,
  `ad` varchar(50) COLLATE utf8mb4_turkish_ci NOT NULL,
  `soyad` varchar(50) COLLATE utf8mb4_turkish_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_turkish_ci NOT NULL,
  `telefon` varchar(20) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `sifre` varchar(255) COLLATE utf8mb4_turkish_ci NOT NULL,
  `role` varchar(20) COLLATE utf8mb4_turkish_ci DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `adres`
--
ALTER TABLE `adres`
  ADD PRIMARY KEY (`adres_id`),
  ADD KEY `fk_adres_uye` (`uye_id`);

--
-- Tablo için indeksler `favori`
--
ALTER TABLE `favori`
  ADD PRIMARY KEY (`favori_id`),
  ADD KEY `fk_favori_uye` (`uye_id`),
  ADD KEY `fk_favori_urun` (`urun_id`);

--
-- Tablo için indeksler `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`kategori_id`);

--
-- Tablo için indeksler `sepet`
--
ALTER TABLE `sepet`
  ADD PRIMARY KEY (`sepet_id`),
  ADD KEY `fk_sepet_uye` (`uye_id`),
  ADD KEY `fk_sepet_urun` (`urun_id`);

--
-- Tablo için indeksler `siparis`
--
ALTER TABLE `siparis`
  ADD PRIMARY KEY (`siparis_id`),
  ADD KEY `fk_siparis_uye` (`uye_id`),
  ADD KEY `fk_siparis_adres` (`adres_id`);

--
-- Tablo için indeksler `siparis_detay`
--
ALTER TABLE `siparis_detay`
  ADD PRIMARY KEY (`detay_id`),
  ADD KEY `fk_detay_siparis` (`siparis_id`),
  ADD KEY `fk_detay_urun` (`urun_id`);

--
-- Tablo için indeksler `urun`
--
ALTER TABLE `urun`
  ADD PRIMARY KEY (`urun_id`),
  ADD KEY `fk_urun_kategori` (`kategori_id`);

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
  MODIFY `adres_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `favori`
--
ALTER TABLE `favori`
  MODIFY `favori_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `kategori`
--
ALTER TABLE `kategori`
  MODIFY `kategori_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `sepet`
--
ALTER TABLE `sepet`
  MODIFY `sepet_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `siparis`
--
ALTER TABLE `siparis`
  MODIFY `siparis_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `siparis_detay`
--
ALTER TABLE `siparis_detay`
  MODIFY `detay_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `urun`
--
ALTER TABLE `urun`
  MODIFY `urun_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `uye`
--
ALTER TABLE `uye`
  MODIFY `uye_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `adres`
--
ALTER TABLE `adres`
  ADD CONSTRAINT `fk_adres_uye` FOREIGN KEY (`uye_id`) REFERENCES `uye` (`uye_id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `favori`
--
ALTER TABLE `favori`
  ADD CONSTRAINT `fk_favori_urun` FOREIGN KEY (`urun_id`) REFERENCES `urun` (`urun_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_favori_uye` FOREIGN KEY (`uye_id`) REFERENCES `uye` (`uye_id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `sepet`
--
ALTER TABLE `sepet`
  ADD CONSTRAINT `fk_sepet_urun` FOREIGN KEY (`urun_id`) REFERENCES `urun` (`urun_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_sepet_uye` FOREIGN KEY (`uye_id`) REFERENCES `uye` (`uye_id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `siparis`
--
ALTER TABLE `siparis`
  ADD CONSTRAINT `fk_siparis_adres` FOREIGN KEY (`adres_id`) REFERENCES `adres` (`adres_id`),
  ADD CONSTRAINT `fk_siparis_uye` FOREIGN KEY (`uye_id`) REFERENCES `uye` (`uye_id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `siparis_detay`
--
ALTER TABLE `siparis_detay`
  ADD CONSTRAINT `fk_detay_siparis` FOREIGN KEY (`siparis_id`) REFERENCES `siparis` (`siparis_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_detay_urun` FOREIGN KEY (`urun_id`) REFERENCES `urun` (`urun_id`);

--
-- Tablo kısıtlamaları `urun`
--
ALTER TABLE `urun`
  ADD CONSTRAINT `fk_urun_kategori` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`kategori_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
