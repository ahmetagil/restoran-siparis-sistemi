-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 15 Haz 2025, 23:40:26
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `restoran_db`
--

DELIMITER $$
--
-- Yordamlar
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_AktifSiparisleriListele` ()   BEGIN
    SELECT s.siparisID, m.masaNo, g.ad AS garsonAdi, s.siparisTarihi, s.toplamTutar, s.durum
    FROM Siparisler s
    JOIN Masalar m ON s.masaID = m.masaID
    JOIN Garsonlar g ON s.garsonID = g.garsonID
    WHERE s.durum != 'Ödendi' AND s.durum != 'İptal'
    ORDER BY s.siparisTarihi;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_GarsonEkle` (IN `p_ad` VARCHAR(50), IN `p_soyad` VARCHAR(50), IN `p_iseGirisTarihi` DATE)   BEGIN
    INSERT INTO Garsonlar(ad, soyad, iseGirisTarihi) VALUES (p_ad, p_soyad, p_iseGirisTarihi);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_GarsonGuncelle` (IN `p_garsonID` INT, IN `p_yeniAd` VARCHAR(50), IN `p_yeniSoyad` VARCHAR(50))   BEGIN
    UPDATE Garsonlar SET ad = p_yeniAd, soyad = p_yeniSoyad WHERE garsonID = p_garsonID;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_GarsonlariListele` ()   BEGIN
    SELECT * FROM Garsonlar ORDER BY ad;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_GarsonSil` (IN `p_garsonID` INT)   BEGIN
    DELETE FROM Garsonlar WHERE garsonID = p_garsonID;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_HesabiKapat` (IN `p_siparisID` INT)   BEGIN
    DECLARE v_masaID INT;
    SELECT masaID INTO v_masaID FROM Siparisler WHERE siparisID = p_siparisID;
    UPDATE Siparisler SET durum = 'Ödendi' WHERE siparisID = p_siparisID;
    UPDATE Masalar SET durum = 'Boş' WHERE masaID = v_masaID;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_KategoriEkle` (IN `p_kategoriAdi` VARCHAR(50))   BEGIN
    INSERT INTO Kategoriler(kategoriAdi) VALUES (p_kategoriAdi);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_KategoriGuncelle` (IN `p_kategoriID` INT, IN `p_yeniAd` VARCHAR(50))   BEGIN
    UPDATE Kategoriler SET kategoriAdi = p_yeniAd WHERE kategoriID = p_kategoriID;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_KategorileriListele` ()   BEGIN
    SELECT * FROM Kategoriler ORDER BY kategoriAdi;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_KategoriSil` (IN `p_kategoriID` INT)   BEGIN
    DELETE FROM Kategoriler WHERE kategoriID = p_kategoriID;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_MasaDurumGuncelle` (IN `p_masaID` INT, IN `p_yeniDurum` VARCHAR(20))   BEGIN
    UPDATE Masalar SET durum = p_yeniDurum WHERE masaID = p_masaID;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_MasaEkle` (IN `p_masaNo` VARCHAR(20), IN `p_kapasite` INT)   BEGIN
    INSERT INTO Masalar(masaNo, kapasite) VALUES (p_masaNo, p_kapasite);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_MasalariListele` ()   BEGIN
    SELECT * FROM Masalar ORDER BY masaID;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_MasaSil` (IN `p_masaID` INT)   BEGIN
    DELETE FROM Masalar WHERE masaID = p_masaID;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_SiparisDetayGetir` (IN `p_siparisID` INT)   BEGIN
    SELECT sd.siparisDetayID, u.urunAdi, sd.adet, sd.birimFiyat, (sd.adet * sd.birimFiyat) AS araToplam
    FROM SiparisDetaylari sd
    JOIN Urunler u ON sd.urunID = u.urunID
    WHERE sd.siparisID = p_siparisID;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_SiparisDetaySil` (IN `p_siparisDetayID` INT)   BEGIN
    DELETE FROM SiparisDetaylari WHERE siparisDetayID = p_siparisDetayID;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_SipariseUrunEkle` (IN `p_siparisID` INT, IN `p_urunID` INT, IN `p_adet` INT)   BEGIN
    DECLARE v_fiyat DECIMAL(10,2);
    SELECT fiyat INTO v_fiyat FROM Urunler WHERE urunID = p_urunID;
    INSERT INTO SiparisDetaylari(siparisID, urunID, adet, birimFiyat)
    VALUES (p_siparisID, p_urunID, p_adet, v_fiyat);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_SiparisSil` (IN `p_siparisID` INT)   BEGIN
    DELETE FROM Siparisler WHERE siparisID = p_siparisID;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_UrunEkle` (IN `p_urunAdi` VARCHAR(100), IN `p_fiyat` DECIMAL(10,2), IN `p_kategoriID` INT)   BEGIN
    INSERT INTO Urunler(urunAdi, fiyat, kategoriID) VALUES (p_urunAdi, p_fiyat, p_kategoriID);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_UrunGuncelle` (IN `p_urunID` INT, IN `p_yeniAd` VARCHAR(100), IN `p_yeniFiyat` DECIMAL(10,2), IN `p_yeniStokDurumu` BOOLEAN, IN `p_yeniKategoriID` INT)   BEGIN
    UPDATE Urunler SET urunAdi = p_yeniAd, fiyat = p_yeniFiyat, stokDurumu = p_yeniStokDurumu, kategoriID = p_yeniKategoriID WHERE urunID = p_urunID;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_UrunleriListele` ()   BEGIN
    SELECT 
        u.urunID, 
        u.urunAdi, 
        u.fiyat, 
        u.stokDurumu, 
        k.kategoriAdi 
    FROM Urunler u
    LEFT JOIN Kategoriler k ON u.kategoriID = k.kategoriID
    -- SIRALAMAYI ARTIK urunID'ye GÖRE YAPIYORUZ:
    ORDER BY u.urunID ASC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_UrunSil` (IN `p_urunID` INT)   BEGIN
    DELETE FROM Urunler WHERE urunID = p_urunID;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_YeniSiparisOlustur` (IN `p_masaID` INT, IN `p_garsonID` INT)   BEGIN
    -- Siparisler tablosuna yeni kaydı ekle.
    INSERT INTO Siparisler(masaID, garsonID) VALUES (p_masaID, p_garsonID);
    
    -- İlgili masanın durumunu 'Dolu' olarak güncelle.
    UPDATE Masalar SET durum = 'Dolu' WHERE masaID = p_masaID;
END$$

--
-- İşlevler
--
CREATE DEFINER=`root`@`localhost` FUNCTION `fn_SiparisTutariHesapla` (`p_siparisID` INT) RETURNS DECIMAL(10,2) DETERMINISTIC BEGIN
    DECLARE toplam DECIMAL(10, 2);

    -- SiparisDetaylari tablosundan ilgili siparişin adet*birimFiyat toplamını al.
    SELECT SUM(adet * birimFiyat) INTO toplam
    FROM SiparisDetaylari
    WHERE siparisID = p_siparisID;
    
    -- Eğer siparişin hiç detayı yoksa (toplam NULL dönerse), 0 döndür.
    RETURN IFNULL(toplam, 0);
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `garsonlar`
--

CREATE TABLE `garsonlar` (
  `garsonID` int(11) NOT NULL,
  `ad` varchar(50) NOT NULL,
  `soyad` varchar(50) NOT NULL,
  `iseGirisTarihi` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `garsonlar`
--

INSERT INTO `garsonlar` (`garsonID`, `ad`, `soyad`, `iseGirisTarihi`) VALUES
(1, 'Ahmet', 'Yılmaz', '2023-01-10'),
(2, 'Ayşe', 'Kaya', '2023-03-15'),
(3, 'Mehmet', 'Çelik', '2023-06-01');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `islemlog`
--

CREATE TABLE `islemlog` (
  `logID` int(11) NOT NULL,
  `islemTipi` varchar(50) DEFAULT NULL,
  `aciklama` text DEFAULT NULL,
  `islemTarihi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kategoriler`
--

CREATE TABLE `kategoriler` (
  `kategoriID` int(11) NOT NULL,
  `kategoriAdi` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `kategoriler`
--

INSERT INTO `kategoriler` (`kategoriID`, `kategoriAdi`) VALUES
(2, 'Ana Yemekler'),
(1, 'Çorbalar'),
(5, 'İçecekler'),
(3, 'Salatalar'),
(4, 'Tatlılar');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `masalar`
--

CREATE TABLE `masalar` (
  `masaID` int(11) NOT NULL,
  `masaNo` varchar(20) NOT NULL,
  `kapasite` int(11) NOT NULL,
  `durum` varchar(20) DEFAULT 'Boş' CHECK (`durum` in ('Boş','Dolu','Rezerve'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `masalar`
--

INSERT INTO `masalar` (`masaID`, `masaNo`, `kapasite`, `durum`) VALUES
(1, 'Masa-1', 4, 'Boş'),
(2, 'Masa-2', 2, 'Boş'),
(3, 'Masa-3', 6, 'Dolu'),
(4, 'Masa-4', 4, 'Boş'),
(5, 'Masa-5', 2, 'Boş'),
(6, 'Bahçe-1', 8, 'Boş'),
(7, 'Bahçe-2', 4, 'Boş'),
(8, 'Teras-1', 2, 'Boş');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `siparisdetaylari`
--

CREATE TABLE `siparisdetaylari` (
  `siparisDetayID` int(11) NOT NULL,
  `siparisID` int(11) NOT NULL,
  `urunID` int(11) NOT NULL,
  `adet` int(11) NOT NULL CHECK (`adet` > 0),
  `birimFiyat` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `siparisdetaylari`
--

INSERT INTO `siparisdetaylari` (`siparisDetayID`, `siparisID`, `urunID`, `adet`, `birimFiyat`) VALUES
(1, 1, 3, 1, 180.00),
(2, 1, 11, 2, 25.00),
(3, 1, 12, 1, 15.00),
(5, 3, 8, 1, 80.00),
(6, 3, 5, 1, 150.00),
(7, 3, 3, 1, 180.00),
(8, 3, 11, 1, 25.00),
(9, 3, 10, 1, 30.00),
(10, 3, 10, 1, 30.00),
(11, 3, 8, 1, 80.00),
(12, 3, 12, 1, 15.00),
(15, 5, 2, 1, 40.00),
(18, 5, 4, 1, 220.50),
(19, 6, 9, 1, 55.00),
(24, 8, 2, 1, 40.00),
(25, 8, 3, 1, 180.00),
(26, 8, 11, 1, 25.00),
(27, 8, 6, 1, 60.00),
(28, 8, 6, 3, 60.00),
(29, 5, 5, 1, 150.00),
(30, 7, 11, 1, 25.00),
(31, 7, 11, 1, 25.00),
(32, 7, 11, 1, 25.00),
(33, 7, 2, 1, 40.00),
(35, 7, 9, 1, 55.00),
(40, 11, 3, 1, 180.00),
(43, 13, 5, 2, 150.00),
(44, 13, 10, 2, 30.00),
(46, 14, 3, 1, 180.00),
(47, 14, 3, 1, 180.00),
(48, 14, 4, 1, 220.50),
(49, 15, 6, 3, 60.00),
(50, 16, 7, 1, 65.00),
(51, 16, 9, 1, 55.00),
(52, 16, 11, 1, 25.00);

--
-- Tetikleyiciler `siparisdetaylari`
--
DELIMITER $$
CREATE TRIGGER `trg_after_detay_ekle` AFTER INSERT ON `siparisdetaylari` FOR EACH ROW BEGIN
    -- Siparisler tablosundaki toplamTutar'ı, az önce yazdığımız fonksiyonu kullanarak güncelle.
    UPDATE Siparisler
    SET toplamTutar = fn_SiparisTutariHesapla(NEW.siparisID)
    WHERE siparisID = NEW.siparisID;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `siparisler`
--

CREATE TABLE `siparisler` (
  `siparisID` int(11) NOT NULL,
  `masaID` int(11) NOT NULL,
  `garsonID` int(11) NOT NULL,
  `siparisTarihi` timestamp NOT NULL DEFAULT current_timestamp(),
  `toplamTutar` decimal(10,2) DEFAULT 0.00,
  `durum` varchar(30) DEFAULT 'Alındı'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `siparisler`
--

INSERT INTO `siparisler` (`siparisID`, `masaID`, `garsonID`, `siparisTarihi`, `toplamTutar`, `durum`) VALUES
(1, 2, 1, '2025-06-13 16:48:53', 245.00, 'Ödendi'),
(2, 1, 1, '2025-06-13 17:56:15', 0.00, 'Ödendi'),
(3, 3, 3, '2025-06-13 17:56:43', 590.00, 'Ödendi'),
(4, 4, 1, '2025-06-13 18:07:44', 0.00, 'Ödendi'),
(5, 2, 1, '2025-06-13 18:57:59', 510.50, 'Ödendi'),
(6, 1, 3, '2025-06-13 19:09:49', 55.00, 'Ödendi'),
(7, 3, 2, '2025-06-13 19:47:22', 235.00, 'Ödendi'),
(8, 4, 1, '2025-06-13 19:47:42', 485.00, 'Ödendi'),
(9, 3, 1, '2025-06-13 20:49:34', 40.00, 'Ödendi'),
(10, 2, 2, '2025-06-13 20:49:42', 465.50, 'Ödendi'),
(11, 3, 3, '2025-06-13 20:55:16', 180.00, 'Ödendi'),
(12, 8, 1, '2025-06-13 20:55:26', 630.00, 'Ödendi'),
(13, 1, 1, '2025-06-13 20:56:33', 360.00, 'Ödendi'),
(14, 1, 1, '2025-06-13 21:06:04', 580.50, 'Ödendi'),
(15, 3, 1, '2025-06-15 12:37:56', 180.00, 'Ödendi'),
(16, 3, 1, '2025-06-15 12:38:10', 145.00, 'Alındı');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `urunler`
--

CREATE TABLE `urunler` (
  `urunID` int(11) NOT NULL,
  `urunAdi` varchar(100) NOT NULL,
  `fiyat` decimal(10,2) NOT NULL CHECK (`fiyat` >= 0),
  `stokDurumu` tinyint(1) DEFAULT 1,
  `kategoriID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `urunler`
--

INSERT INTO `urunler` (`urunID`, `urunAdi`, `fiyat`, `stokDurumu`, `kategoriID`) VALUES
(1, 'Mercimek Çorbası', 35.00, 1, 1),
(2, 'Ezogelin Çorbası', 40.00, 1, 1),
(3, 'Adana Kebap', 180.00, 1, 2),
(4, 'İskender Kebap', 220.50, 1, 2),
(5, 'Tavuk Şiş', 150.00, 1, 2),
(6, 'Çoban Salata', 60.00, 1, 3),
(7, 'Mevsim Salata', 65.00, 1, 3),
(8, 'Künefe', 80.00, 1, 4),
(9, 'Sütlaç', 55.00, 1, 4),
(10, 'Kola', 30.00, 1, 5),
(11, 'Ayran', 25.00, 1, 5),
(12, 'Su', 15.00, 1, 5);

--
-- Tetikleyiciler `urunler`
--
DELIMITER $$
CREATE TRIGGER `trg_after_urun_guncelle` AFTER UPDATE ON `urunler` FOR EACH ROW BEGIN
    -- Sadece fiyat değişmişse bu bloğu çalıştır.
    IF OLD.fiyat <> NEW.fiyat THEN
        INSERT INTO IslemLog(islemTipi, aciklama)
        VALUES ('ÜRÜN FİYAT GÜNCELLEME',
                CONCAT('ID: ', OLD.urunID, ', Adı: ''', OLD.urunAdi, 
                       ''' - Eski Fiyat: ', OLD.fiyat, ', Yeni Fiyat: ', NEW.fiyat));
    END IF;

    -- İstersen isim değişikliğini de loglayabilirsin.
    IF OLD.urunAdi <> NEW.urunAdi THEN
        INSERT INTO IslemLog(islemTipi, aciklama)
        VALUES ('ÜRÜN İSİM GÜNCELLEME',
                CONCAT('ID: ', OLD.urunID, ' - Eski Ad: ''', OLD.urunAdi, 
                       ''', Yeni Ad: ''', NEW.urunAdi, ''''));
    END IF;
END
$$
DELIMITER ;

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `garsonlar`
--
ALTER TABLE `garsonlar`
  ADD PRIMARY KEY (`garsonID`);

--
-- Tablo için indeksler `islemlog`
--
ALTER TABLE `islemlog`
  ADD PRIMARY KEY (`logID`);

--
-- Tablo için indeksler `kategoriler`
--
ALTER TABLE `kategoriler`
  ADD PRIMARY KEY (`kategoriID`),
  ADD UNIQUE KEY `kategoriAdi` (`kategoriAdi`);

--
-- Tablo için indeksler `masalar`
--
ALTER TABLE `masalar`
  ADD PRIMARY KEY (`masaID`),
  ADD UNIQUE KEY `masaNo` (`masaNo`);

--
-- Tablo için indeksler `siparisdetaylari`
--
ALTER TABLE `siparisdetaylari`
  ADD PRIMARY KEY (`siparisDetayID`),
  ADD KEY `fk_detay_siparis` (`siparisID`),
  ADD KEY `fk_detay_urun` (`urunID`);

--
-- Tablo için indeksler `siparisler`
--
ALTER TABLE `siparisler`
  ADD PRIMARY KEY (`siparisID`),
  ADD KEY `fk_siparis_masa` (`masaID`),
  ADD KEY `fk_siparis_garson` (`garsonID`);

--
-- Tablo için indeksler `urunler`
--
ALTER TABLE `urunler`
  ADD PRIMARY KEY (`urunID`),
  ADD KEY `fk_urun_kategori` (`kategoriID`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `garsonlar`
--
ALTER TABLE `garsonlar`
  MODIFY `garsonID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `islemlog`
--
ALTER TABLE `islemlog`
  MODIFY `logID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `kategoriler`
--
ALTER TABLE `kategoriler`
  MODIFY `kategoriID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Tablo için AUTO_INCREMENT değeri `masalar`
--
ALTER TABLE `masalar`
  MODIFY `masaID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Tablo için AUTO_INCREMENT değeri `siparisdetaylari`
--
ALTER TABLE `siparisdetaylari`
  MODIFY `siparisDetayID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- Tablo için AUTO_INCREMENT değeri `siparisler`
--
ALTER TABLE `siparisler`
  MODIFY `siparisID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Tablo için AUTO_INCREMENT değeri `urunler`
--
ALTER TABLE `urunler`
  MODIFY `urunID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `siparisdetaylari`
--
ALTER TABLE `siparisdetaylari`
  ADD CONSTRAINT `fk_detay_siparis` FOREIGN KEY (`siparisID`) REFERENCES `siparisler` (`siparisID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_detay_urun` FOREIGN KEY (`urunID`) REFERENCES `urunler` (`urunID`);

--
-- Tablo kısıtlamaları `siparisler`
--
ALTER TABLE `siparisler`
  ADD CONSTRAINT `fk_siparis_garson` FOREIGN KEY (`garsonID`) REFERENCES `garsonlar` (`garsonID`),
  ADD CONSTRAINT `fk_siparis_masa` FOREIGN KEY (`masaID`) REFERENCES `masalar` (`masaID`);

--
-- Tablo kısıtlamaları `urunler`
--
ALTER TABLE `urunler`
  ADD CONSTRAINT `fk_urun_kategori` FOREIGN KEY (`kategoriID`) REFERENCES `kategoriler` (`kategoriID`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
