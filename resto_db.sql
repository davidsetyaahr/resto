-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Waktu pembuatan: 05 Jul 2020 pada 05.26
-- Versi server: 10.1.37-MariaDB
-- Versi PHP: 7.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `resto_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang`
--

CREATE TABLE `barang` (
  `kode_barang` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_kategori_barang` tinyint(3) UNSIGNED DEFAULT NULL,
  `nama` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `satuan` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock` int(11) NOT NULL DEFAULT '0',
  `minimum_stock` int(11) NOT NULL DEFAULT '0',
  `saldo` int(11) NOT NULL DEFAULT '0',
  `exp_date` date DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tempat_penyimpanan` text COLLATE utf8mb4_unicode_ci,
  `stock_awal` int(11) NOT NULL DEFAULT '0',
  `saldo_awal` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_diskon`
--

CREATE TABLE `detail_diskon` (
  `id_detail_diskon` int(10) UNSIGNED NOT NULL,
  `id_diskon` int(10) UNSIGNED DEFAULT NULL,
  `kode_menu` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_pemakaian`
--

CREATE TABLE `detail_pemakaian` (
  `id` int(10) UNSIGNED NOT NULL,
  `kode_pemakaian` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_barang` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` smallint(6) NOT NULL,
  `subtotal_saldo` int(11) NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_pembelian`
--

CREATE TABLE `detail_pembelian` (
  `id` int(10) UNSIGNED NOT NULL,
  `kode_pembelian` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_barang` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` smallint(6) NOT NULL,
  `subtotal` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_penjualan`
--

CREATE TABLE `detail_penjualan` (
  `id_detail_penjualan` int(10) UNSIGNED NOT NULL,
  `kode_penjualan` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_menu` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('Belum','Sudah') COLLATE utf8mb4_unicode_ci NOT NULL,
  `sub_total` int(11) NOT NULL,
  `sub_total_ppn` int(11) NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `qty` int(11) NOT NULL,
  `diskon` int(11) NOT NULL DEFAULT '0',
  `diskon_tambahan` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `diskon`
--

CREATE TABLE `diskon` (
  `id_diskon` int(10) UNSIGNED NOT NULL,
  `nama_diskon` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_diskon` enum('Persen','Rupiah') COLLATE utf8mb4_unicode_ci NOT NULL,
  `diskon` int(11) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kas`
--

CREATE TABLE `kas` (
  `kode_kas` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` date NOT NULL,
  `nominal` int(11) NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `penanggung_jawab` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tipe` enum('Masuk','Keluar') COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori_barang`
--

CREATE TABLE `kategori_barang` (
  `id_kategori_barang` tinyint(3) UNSIGNED NOT NULL,
  `kategori_barang` char(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori_menu`
--

CREATE TABLE `kategori_menu` (
  `id_kategori_menu` tinyint(3) UNSIGNED NOT NULL,
  `kategori_menu` char(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `kategori_menu`
--

INSERT INTO `kategori_menu` (`id_kategori_menu`, `kategori_menu`, `created_at`, `updated_at`) VALUES
(1, 'Coffee', '2020-07-02 04:37:53', '2020-07-02 07:23:33'),
(2, 'Non Coffee', '2020-07-02 07:23:16', '2020-07-02 07:23:16'),
(3, 'Snack', '2020-07-02 07:28:56', '2020-07-02 07:28:56'),
(4, 'Indonesian Food', '2020-07-02 07:38:16', '2020-07-02 07:38:16'),
(5, 'Western Food', '2020-07-03 02:50:58', '2020-07-03 02:50:58'),
(6, 'Japanese Food', '2020-07-03 02:51:27', '2020-07-03 02:51:27'),
(7, 'Ricebowl', '2020-07-04 05:47:47', '2020-07-04 05:47:47');

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_activity`
--

CREATE TABLE `log_activity` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_users` bigint(20) UNSIGNED DEFAULT NULL,
  `date_time` datetime NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `meja`
--

CREATE TABLE `meja` (
  `id_meja` tinyint(3) UNSIGNED NOT NULL,
  `nama_meja` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `meja`
--

INSERT INTO `meja` (`id_meja`, `nama_meja`) VALUES
(1, 'Table 01'),
(2, 'Table 02'),
(3, 'Table 03'),
(4, 'Table 04'),
(5, 'Table 05'),
(6, 'Table 06'),
(7, 'Table 07'),
(8, 'Table 08'),
(9, 'Table 09'),
(10, 'Table 10'),
(11, 'Table 11'),
(12, 'Table 12'),
(13, 'Table 13'),
(14, 'Table 14'),
(15, 'Table 15'),
(16, 'Table 16'),
(17, 'Table 17'),
(18, 'Table 18'),
(19, 'Table 19'),
(20, 'Table 20'),
(21, 'Table 21'),
(22, 'Table 22'),
(23, 'Table 23'),
(24, 'Table 24'),
(25, 'Table 25'),
(26, 'Table 26'),
(27, 'Table 27'),
(28, 'Table 28'),
(29, 'Table 29'),
(30, 'Table 30'),
(31, 'R. Argopuro'),
(32, 'R. Raung'),
(33, 'R. Ijen'),
(34, 'Private Room Bawah');

-- --------------------------------------------------------

--
-- Struktur dari tabel `menu`
--

CREATE TABLE `menu` (
  `kode_menu` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_kategori_menu` tinyint(3) UNSIGNED DEFAULT NULL,
  `nama` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hpp` int(11) NOT NULL,
  `harga_jual` int(11) NOT NULL,
  `foto` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('Habis','Ready') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `jenis_menu` enum('Bar','Dapur') COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `menu`
--

INSERT INTO `menu` (`kode_menu`, `id_kategori_menu`, `nama`, `hpp`, `harga_jual`, `foto`, `status`, `created_at`, `updated_at`, `jenis_menu`) VALUES
('MN-0001', 4, 'NASI GORENG AYAM', 11200, 25000, '1593745978.NASI GORENG EBI.jpg', 'Ready', '2020-07-03 03:12:58', '2020-07-04 02:53:04', 'Dapur'),
('MN-0002', 4, 'NASI GORENG SEAFOOD', 13341, 32000, '1593838957.NASI GORENG SEAFOOD.jpg', 'Ready', '2020-07-04 05:02:37', '2020-07-04 05:02:37', 'Dapur'),
('MN-0003', 4, 'NASI GORENG SPESIAL BARATHA', 12670, 35000, '1593839055.NasiGorengSpesialBaratha1.jpg', 'Ready', '2020-07-04 05:04:15', '2020-07-04 05:04:15', 'Dapur'),
('MN-0004', 4, 'MIE GORENG AYAM', 10395, 28000, '1593839157.MIE GORENG SEAFOOD 2.jpg', 'Ready', '2020-07-04 05:05:57', '2020-07-04 05:05:57', 'Dapur'),
('MN-0005', 4, 'MIE GORENG SEAFOOD', 13521, 33000, '1593839240.MieGorengSeafood1.jpg', 'Ready', '2020-07-04 05:07:20', '2020-07-04 05:07:20', 'Dapur'),
('MN-0006', 4, 'MIE KUAH AYAM', 10355, 28000, '1593839308.MieKuahAyam1.jpg', 'Ready', '2020-07-04 05:08:28', '2020-07-04 05:08:28', 'Dapur'),
('MN-0007', 4, 'MIE KUAH SEAFOOD', 11496, 33000, '1593839409.MieKuahSeafood1.jpg', 'Ready', '2020-07-04 05:10:09', '2020-07-04 05:10:09', 'Dapur'),
('MN-0008', 4, 'KWETIAU GORENG AYAM', 16050, 33000, '1593839518.KWETIAU AYAM.jpg', 'Ready', '2020-07-04 05:11:58', '2020-07-04 05:11:58', 'Dapur'),
('MN-0009', 4, 'KWETIAU GORENG SEAFOOD', 16291, 38000, '1593839584.KWETIAU AYAM.jpg', 'Ready', '2020-07-04 05:13:04', '2020-07-04 05:13:04', 'Dapur'),
('MN-0010', 4, 'KWETIAU GORENG SAPI', 17950, 40000, '1593839633.KWETIAU AYAM.jpg', 'Ready', '2020-07-04 05:13:53', '2020-07-04 05:13:53', 'Dapur'),
('MN-0011', 4, 'BIHUN GORENG AYAM', 14205, 29000, '1593839692.BihunGorengAyam1.jpg', 'Ready', '2020-07-04 05:14:52', '2020-07-04 05:14:52', 'Dapur'),
('MN-0012', 4, 'BIHUN GORENG SEAFOOD', 15346, 34000, '1593839779.BIHUN GORENG AYAM.jpg', 'Ready', '2020-07-04 05:16:19', '2020-07-04 05:16:19', 'Dapur'),
('MN-0013', 4, 'BIHUN GORENG SAPI', 16505, 37000, '1593839829.BIHUN GORENG AYAM.jpg', 'Ready', '2020-07-04 05:17:09', '2020-07-04 05:17:09', 'Dapur'),
('MN-0014', 4, 'TAMIE GORENG AYAM', 15085, 28000, '1593839968.Tamie.jpg', 'Ready', '2020-07-04 05:19:28', '2020-07-04 05:19:28', 'Dapur'),
('MN-0015', 4, 'TAMIE GORENG SEAFOOD', 16226, 33000, '1593840013.Tamie.jpg', 'Ready', '2020-07-04 05:20:13', '2020-07-04 05:20:13', 'Dapur'),
('MN-0016', 4, 'CAPCAY GORENG AYAM', 13121, 30000, '1593841290.capcay.jpg', 'Ready', '2020-07-04 05:41:30', '2020-07-04 05:41:30', 'Dapur'),
('MN-0017', 4, 'CAPCAY GORENG SEAFOOD', 14272, 35000, '1593841346.capcay.jpg', 'Ready', '2020-07-04 05:42:26', '2020-07-04 05:42:26', 'Dapur'),
('MN-0018', 4, 'SAPO TAHU AYAM', 19877, 38000, '1593841395.SapoTahuAyam1.jpg', 'Ready', '2020-07-04 05:43:15', '2020-07-04 05:43:15', 'Dapur'),
('MN-0019', 4, 'SAPO TAHU SEAFOOD', 20027, 43000, '1593841501.SAPO TAHU.jpg', 'Ready', '2020-07-04 05:45:01', '2020-07-04 05:45:01', 'Dapur'),
('MN-0020', 4, 'UDANG TELOR ASIN', 20850, 42000, '1593841568.Udang Telor Asin.jpg', 'Ready', '2020-07-04 05:46:08', '2020-07-04 05:46:08', 'Dapur'),
('MN-0021', 7, 'RICEBOWL AYAM SAMBAL MATAH', 13250, 24000, '1593841998.Rice Bowl Ayam Sambal Matah.jpg', 'Ready', '2020-07-04 05:53:18', '2020-07-04 05:53:18', 'Dapur'),
('MN-0022', 7, 'RICEBOWL AYAM KUNGPAO', 12450, 25000, '1593842109.Rice Bowl Ayam Kungpao.jpg', 'Ready', '2020-07-04 05:55:09', '2020-07-04 05:55:09', 'Dapur'),
('MN-0023', 7, 'RICEBOWL SOSIS SAUS INGGRIS', 12150, 25000, '1593842191.Rice Bowl Sosis Saus Inggris.jpg', 'Ready', '2020-07-04 05:56:31', '2020-07-04 05:56:31', 'Dapur'),
('MN-0024', 7, 'RICEBOWL SAPI SAUS TARTAR', 16550, 32000, '1593842532.Rice Bowl Sapi Saus Tartar.jpg', 'Ready', '2020-07-04 06:02:12', '2020-07-04 06:02:12', 'Dapur'),
('MN-0025', 5, 'CHICKEN CORDON BLEU DEMY GLACE', 12900, 35000, '1593842782.ChickenCordonBleuDemyGlaceSauce1.jpg', 'Ready', '2020-07-04 06:06:22', '2020-07-04 06:06:22', 'Dapur'),
('MN-0026', 5, 'CHICKEN CORDON BLEU CHEESE', 15900, 38000, '1593842868.ChickenCordonBleuCheeseSauce1.jpg', 'Ready', '2020-07-04 06:07:48', '2020-07-04 06:07:48', 'Dapur'),
('MN-0027', 5, 'SPAGHETTI BOLOGNAISE', 13905, 35000, '1593842937.SpaghettiBolognaise1.jpg', 'Ready', '2020-07-04 06:08:57', '2020-07-04 06:08:57', 'Dapur'),
('MN-0028', 5, 'SPAGHETTI AGLIO E OLIO', 11650, 28000, '1593842995.Spaghetti Aglio e Olio.jpg', 'Ready', '2020-07-04 06:09:55', '2020-07-04 06:09:55', 'Dapur'),
('MN-0029', 5, 'FETTUCINI CARBONARA', 18825, 37000, '1593843049.FettuciniCarbonara1.jpg', 'Ready', '2020-07-04 06:10:49', '2020-07-04 06:10:49', 'Dapur'),
('MN-0030', 5, 'FISH N CIPS', 16900, 35000, '1593843108.FishNChips1.jpg', 'Ready', '2020-07-04 06:11:48', '2020-07-04 06:11:48', 'Dapur'),
('MN-0031', 5, 'SANDWICH', 15275, 28000, '1593843206.Sandwich1.jpg', 'Ready', '2020-07-04 06:13:26', '2020-07-04 06:13:26', 'Dapur'),
('MN-0032', 3, 'SNACK PLATTER', 7250, 20000, '1593843287.SnackPlatter1.jpg', 'Ready', '2020-07-04 06:14:47', '2020-07-04 06:14:47', 'Dapur'),
('MN-0033', 3, 'TAHU LADA GARAM', 4480, 13000, '1593843336.TahuLadaGaram1.jpg', 'Ready', '2020-07-04 06:15:36', '2020-07-04 06:15:36', 'Dapur'),
('MN-0034', 3, 'FRENCH FRIES', 7000, 15000, '1593843458.FRENCH FRIES.jpg', 'Ready', '2020-07-04 06:17:38', '2020-07-04 06:17:38', 'Dapur'),
('MN-0035', 3, 'SMOKED BEEF CHEESE FRIES', 10250, 22000, '1593843535.Smoked Beef Cheese Fries.jpg', 'Ready', '2020-07-04 06:18:55', '2020-07-04 06:18:55', 'Dapur'),
('MN-0036', 3, 'BOLOGNAISE FRIES', 9260, 20000, '1593843594.Bolognaise Fries.jpg', 'Ready', '2020-07-04 06:19:54', '2020-07-04 06:19:54', 'Dapur'),
('MN-0037', 3, 'CHURROS', 7340, 17000, '1593843768.CHURROS.jpg', 'Ready', '2020-07-04 06:22:48', '2020-07-04 06:22:48', 'Dapur'),
('MN-0038', 2, 'VIRGIN MOJITO', 8140, 25000, '1593845190.VIRGIN MOJITO.jpg', 'Ready', '2020-07-04 06:46:30', '2020-07-04 06:46:30', 'Bar'),
('MN-0039', 2, 'STRAWBERRY MOJITO', 16540, 28000, '1593845243.StrawberryMojito1.jpg', 'Ready', '2020-07-04 06:47:23', '2020-07-04 06:47:23', 'Bar'),
('MN-0040', 2, 'KIWI MOJITO', 16540, 28000, '1593845292.KIWI MOJITO.jpg', 'Ready', '2020-07-04 06:48:12', '2020-07-04 06:48:12', 'Dapur'),
('MN-0041', 2, 'CHOCOLATE FRAPPE', 11513, 28000, '1593845428.ChocolateFrappe1.jpg', 'Ready', '2020-07-04 06:50:28', '2020-07-04 09:52:46', 'Bar'),
('MN-0042', 2, 'VANILLA REGAL FRAPPE', 16154, 30000, '1593845491.Vanilla Regal Frappe.jpg', 'Ready', '2020-07-04 06:51:31', '2020-07-04 06:51:31', 'Bar'),
('MN-0043', 2, 'VANILLA FRAPPE', 12654, 30000, '1593845689.Chocolate Frappe.jpg', 'Ready', '2020-07-04 06:54:49', '2020-07-04 06:54:49', 'Bar'),
('MN-0044', 1, 'HAZELNUT LATTE HOT', 6159, 23000, '1593845775.DPP07E3061A150C26.jpg', 'Ready', '2020-07-04 06:56:15', '2020-07-04 06:56:15', 'Bar'),
('MN-0045', 1, 'HAZELNUT LATTE ICE', 6659, 25000, '1593845841.Caffe Latte Ice.jpg', 'Ready', '2020-07-04 06:57:21', '2020-07-04 06:57:21', 'Bar'),
('MN-0046', 1, 'ESKOBAR', 7276, 16000, '1593845903.ESKOBAR.jpg', 'Ready', '2020-07-04 06:58:23', '2020-07-04 08:25:58', 'Bar'),
('MN-0047', 1, 'ESKOBAR CREAM', 8776, 20000, '1593846146.ESKOBAR CREAM.jpg', 'Ready', '2020-07-04 07:02:26', '2020-07-04 08:26:36', 'Bar'),
('MN-0048', 1, 'ESKOBAR COKLAT', 10369, 20000, '1593846600.ESKOBAR.jpg', 'Ready', '2020-07-04 07:10:00', '2020-07-04 08:27:12', 'Bar'),
('MN-0049', 1, 'ESKOBAR ARANG', 11702, 20000, '1593846750.ESKOBAR ARANG.jpg', 'Ready', '2020-07-04 07:12:30', '2020-07-04 08:27:51', 'Bar'),
('MN-0050', 2, 'LEMON TEA HOT', 4350, 15000, '1593847172.lemon tea hot.jpg', 'Ready', '2020-07-04 07:19:32', '2020-07-04 07:19:32', 'Bar'),
('MN-0051', 2, 'LEMON TEA ICE', 4850, 16000, '1593847281.265738_Lemon-Tea_PRP58NvY2euNSymL_1552530921.jpg', 'Ready', '2020-07-04 07:21:21', '2020-07-04 07:21:21', 'Bar'),
('MN-0052', 2, 'BLACKCURRANT TEA HOT', 4350, 18000, '1593847382.lemon tea hot.jpg', 'Ready', '2020-07-04 07:23:02', '2020-07-04 07:23:02', 'Bar'),
('MN-0053', 2, 'BLACKCURRANT TEA ICE', 4850, 20000, '1593847510.265738_Lemon-Tea_PRP58NvY2euNSymL_1552530921.jpg', 'Ready', '2020-07-04 07:25:10', '2020-07-04 07:25:10', 'Bar'),
('MN-0054', 2, 'VANILLA OREO FRAPPE', 14154, 30000, '1593847606.Vanilla Oreo Frappe.jpg', 'Ready', '2020-07-04 07:26:46', '2020-07-04 07:26:46', 'Bar'),
('MN-0055', 2, 'THAI TEA', 2790, 16000, '1593847752.Thai Tea.jpg', 'Ready', '2020-07-04 07:29:12', '2020-07-04 07:29:12', 'Bar'),
('MN-0056', 2, 'THAI TEA BOBBA', 3930, 19000, '1593848156.Thai Tea.jpg', 'Ready', '2020-07-04 07:35:56', '2020-07-04 07:35:56', 'Bar'),
('MN-0057', 2, 'GREEN THAI TEA', 3260, 17000, '1593848275.Green Thai Tea.jpg', 'Ready', '2020-07-04 07:37:55', '2020-07-04 07:37:55', 'Bar'),
('MN-0058', 2, 'GREEN THAI TEA BOBBA', 4400, 20000, '1593848372.GREEN THAI TEA BOBBA.jpg', 'Ready', '2020-07-04 07:39:32', '2020-07-04 07:39:32', 'Bar'),
('MN-0059', 1, 'ESPRESSO SINGGLE SHOOT', 3256, 13000, '1593848526.DPP07E3061B0F2D09.jpg', 'Ready', '2020-07-04 07:42:06', '2020-07-04 07:42:06', 'Bar'),
('MN-0060', 1, 'ESPRESSO DOUBLE SHOOT', 4012, 17000, '1593848583.DPP07E3061B0F2D09.jpg', 'Ready', '2020-07-04 07:43:03', '2020-07-04 07:43:03', 'Bar'),
('MN-0061', 1, 'AFFOGATO', 4318, 21000, '1593848664.affogato.jpg', 'Ready', '2020-07-04 07:44:24', '2020-07-04 07:44:24', 'Bar'),
('MN-0062', 1, 'ESPRESSO ARABIKA SINGGLE SHOOT', 4256, 17000, '1593848797.EspressoConPanna.jpg', 'Ready', '2020-07-04 07:46:37', '2020-07-04 07:46:37', 'Bar'),
('MN-0063', 1, 'ESPRESSO ARABIKA DOUBLE SHOOT', 5012, 20000, '1593848871.EspressoConPanna.jpg', 'Ready', '2020-07-04 07:47:51', '2020-07-04 07:47:51', 'Bar'),
('MN-0064', 1, 'ESPRESSO CON PANNA', 10056, 21000, '1593849004.espresso con panna.jpg', 'Ready', '2020-07-04 07:50:04', '2020-07-04 07:50:04', 'Bar'),
('MN-0065', 1, 'AMERICANO HOT', 35245, 15000, '1593849615.DPP07E3061B0F2D09.jpg', 'Ready', '2020-07-04 08:00:15', '2020-07-04 08:00:15', 'Bar'),
('MN-0066', 1, 'AMERICANO ICE', 4024, 16000, '1593849691.DPP07E3061B0F2D09.jpg', 'Ready', '2020-07-04 08:01:31', '2020-07-04 08:01:31', 'Bar'),
('MN-0067', 1, 'CAPPUCINO HOT', 5024, 20000, '1593850676.Cappucino.jpg', 'Ready', '2020-07-04 08:17:56', '2020-07-04 08:20:25', 'Bar'),
('MN-0068', 1, 'CAPPUCINO ICE', 5646, 21000, '1593850722.CAPPUCINO ICE.jpg', 'Ready', '2020-07-04 08:18:42', '2020-07-04 08:23:14', 'Bar'),
('MN-0069', 1, 'CAPPUCINO CINCAU PREMIUM', 8496, 22000, '1593851381.Caffe Latte Ice.jpg', 'Ready', '2020-07-04 08:29:41', '2020-07-04 08:29:41', 'Bar'),
('MN-0070', 2, 'BLUE FIRE SQUASH', 5760, 22000, '1593851450.BlueFireSquash1.jpg', 'Ready', '2020-07-04 08:30:50', '2020-07-04 08:30:50', 'Bar'),
('MN-0071', 2, 'CHARCOAL LATTE HOT', 7375, 24000, '1593851603.Charcoal Latte.jpg', 'Ready', '2020-07-04 08:33:23', '2020-07-04 08:33:23', 'Bar'),
('MN-0072', 2, 'CHARCOAL LATTE ICE', 10980, 25000, '1593851660.Charcoal Latte.jpg', 'Ready', '2020-07-04 08:34:20', '2020-07-04 08:34:20', 'Bar'),
('MN-0073', 1, 'MOCHA LATTE HOT', 7355, 23000, '1593851737.DPP07E3061A150C26.jpg', 'Ready', '2020-07-04 08:35:37', '2020-07-04 08:35:37', 'Bar'),
('MN-0074', 1, 'MOCHA LATTE ICE', 8355, 25000, '1593851816.Caffe Latte Ice.jpg', 'Ready', '2020-07-04 08:36:56', '2020-07-04 08:36:56', 'Bar'),
('MN-0075', 2, 'MATCHA LATTE ICE', 10805, 25000, '1593851916.KLEPON LATTE.jpg', 'Ready', '2020-07-04 08:38:36', '2020-07-04 08:38:36', 'Bar'),
('MN-0076', 2, 'MATCHA LATTE HOT', 7375, 25000, '1593851967.KLEPON LATTE.jpg', 'Ready', '2020-07-04 08:39:27', '2020-07-04 08:39:27', 'Bar'),
('MN-0077', 2, 'KLEPON LATTE HOT', 7375, 24000, '1593852052.KLEPON LATTE.jpg', 'Ready', '2020-07-04 08:40:52', '2020-07-04 08:40:52', 'Bar'),
('MN-0078', 2, 'KLEPON LATTE ICE', 10805, 25000, '1593852099.KLEPON LATTE.jpg', 'Ready', '2020-07-04 08:41:39', '2020-07-04 08:41:39', 'Bar'),
('MN-0079', 2, 'TARO LATTE HOT', 7375, 24000, '1593852176.OREO FRAPPE.jpg', 'Ready', '2020-07-04 08:42:56', '2020-07-04 08:42:56', 'Bar'),
('MN-0080', 2, 'TARO LATTE ICE', 10805, 25000, '1593852233.OREO FRAPPE.jpg', 'Ready', '2020-07-04 08:43:53', '2020-07-04 08:43:53', 'Bar'),
('MN-0081', 2, 'RED VELVET LATTE HOT', 7375, 24000, '1593852404.RED VELVET.jpg', 'Ready', '2020-07-04 08:46:44', '2020-07-04 10:25:04', 'Bar'),
('MN-0082', 2, 'RED VELVET LATTE ICE', 10805, 25000, '1593852449.RED VELVET.jpg', 'Ready', '2020-07-04 08:47:29', '2020-07-04 08:47:29', 'Bar'),
('MN-0083', 2, 'TIRAMISU LATTE HOT', 7375, 24000, '1593852526.Caffe Latte Ice.jpg', 'Ready', '2020-07-04 08:48:46', '2020-07-04 08:48:46', 'Bar'),
('MN-0084', 2, 'TIRAMISU LATTE ICE', 7375, 24000, '1593852622.CAPPUCINO ICE.jpg', 'Ready', '2020-07-04 08:50:22', '2020-07-04 08:50:22', 'Bar'),
('MN-0085', 2, 'KLEPON FRAPPE', 11513, 30000, '1593852783.KLEPON FRAPPE.jpg', 'Ready', '2020-07-04 08:53:03', '2020-07-04 08:53:03', 'Bar'),
('MN-0086', 2, 'TIRAMISU FRAPPE', 11513, 28000, '1593855876.Chocolate Frappe.jpg', 'Ready', '2020-07-04 09:44:36', '2020-07-04 10:24:23', 'Bar'),
('MN-0087', 2, 'RED VELVET FRAPPE', 11513, 28000, '1593855968.RED VELVET.jpg', 'Ready', '2020-07-04 09:46:08', '2020-07-04 09:46:08', 'Bar'),
('MN-0088', 2, 'TARO FRAPPE', 11513, 28000, '1593856014.TaroFrappe1.jpg', 'Ready', '2020-07-04 09:46:54', '2020-07-04 09:46:54', 'Bar'),
('MN-0089', 2, 'CHARCOAL FRAPPE', 11513, 28000, '1593856075.Charcoal Latte.jpg', 'Ready', '2020-07-04 09:47:55', '2020-07-04 09:47:55', 'Bar'),
('MN-0090', 2, 'MATCHA FRAPPE', 11513, 28000, '1593856124.KLEPON FRAPPE.jpg', 'Ready', '2020-07-04 09:48:44', '2020-07-04 09:48:44', 'Bar'),
('MN-0091', 2, 'CHOCOLATE OREO FRAPPE', 14154, 30000, '1593856456.Vanilla Oreo Frappe.jpg', 'Ready', '2020-07-04 09:54:16', '2020-07-04 09:54:16', 'Bar'),
('MN-0092', 1, 'FILTER COFFEE V60', 5400, 15000, '1593856533.V60.jpg', 'Ready', '2020-07-04 09:55:33', '2020-07-04 09:55:33', 'Bar'),
('MN-0093', 1, 'JAPANESSE ICE COFFE', 5900, 15000, '1593856598.V60.jpg', 'Ready', '2020-07-04 09:56:38', '2020-07-04 09:56:38', 'Bar'),
('MN-0094', 1, 'VIETNAM COFFEE HOT', 3480, 13000, '1593856670.kopi vietnam.jpg', 'Ready', '2020-07-04 09:57:50', '2020-07-04 09:57:50', 'Bar'),
('MN-0095', 1, 'VIETNAM COFFEE ICE', 3960, 15000, '1593856715.kopi vietnam.jpg', 'Ready', '2020-07-04 09:58:35', '2020-07-04 09:58:35', 'Bar'),
('MN-0096', 1, 'VIETNAM DRIP ARABIKA', 5268, 14000, '1593856776.vietnam drip.jpg', 'Ready', '2020-07-04 09:59:36', '2020-07-04 09:59:36', 'Bar'),
('MN-0097', 1, 'VIETNAM DRIP ROBUSTA', 4268, 12000, '1593856822.vietnam drip.jpg', 'Ready', '2020-07-04 10:00:22', '2020-07-04 10:00:22', 'Bar'),
('MN-0098', 1, 'CAFE LATTE HOT', 7024, 21000, '1593856878.CafeLatteH.jpg', 'Ready', '2020-07-04 10:01:18', '2020-07-04 10:01:18', 'Bar'),
('MN-0099', 1, 'CAFE LATTE ICE', 6146, 22000, '1593856955.Caffe Latte Ice.jpg', 'Ready', '2020-07-04 10:02:35', '2020-07-04 10:02:35', 'Bar'),
('MN-0100', 1, 'CARAMEL MACHIATO ICE', 11186, 28000, '1593857020.caramel macchiato.jpg', 'Ready', '2020-07-04 10:03:40', '2020-07-04 10:03:40', 'Bar'),
('MN-0101', 1, 'CARAMEL MACHIATO HOT', 12496, 29000, '1593857186.caramel macchiato.jpg', 'Ready', '2020-07-04 10:06:26', '2020-07-04 10:06:26', 'Bar'),
('MN-0102', 1, 'MOCHA FRAPPE', 14376, 29000, '1593857228.cafe mocha.jpg', 'Ready', '2020-07-04 10:07:08', '2020-07-04 10:07:08', 'Bar'),
('MN-0103', 1, 'MOCHACCINO HOT', 8166, 28000, '1593857320.DPP07E3061A150C26.jpg', 'Ready', '2020-07-04 10:08:40', '2020-07-04 10:08:40', 'Bar'),
('MN-0104', 1, 'MOCHACCINO ICE', 8666, 29000, '1593857370.Caffe Latte Ice.jpg', 'Ready', '2020-07-04 10:09:30', '2020-07-04 10:09:30', 'Bar'),
('MN-0105', 1, 'CARAMEL LATTE HOT', 8644, 23000, '1593857504.DPP07E3061A150C26.jpg', 'Ready', '2020-07-04 10:11:44', '2020-07-04 10:11:44', 'Bar'),
('MN-0106', 1, 'CARAMEL LATTE ICE', 9796, 25000, '1593857572.Caffe Latte Ice.jpg', 'Ready', '2020-07-04 10:12:52', '2020-07-04 10:12:52', 'Bar'),
('MN-0107', 1, 'MACADAMIA LATTE HOT', 8644, 23000, '1593857627.DPP07E3061A150C26.jpg', 'Ready', '2020-07-04 10:13:47', '2020-07-04 10:13:47', 'Bar'),
('MN-0108', 1, 'MACADAMIA LATTE ICE', 9796, 25000, '1593857666.Caffe Latte Ice.jpg', 'Ready', '2020-07-04 10:14:26', '2020-07-04 10:14:26', 'Bar'),
('MN-0109', 1, 'VANILLA LATTE HOT', 8644, 23000, '1593857715.DPP07E3061A150C26.jpg', 'Ready', '2020-07-04 10:15:15', '2020-07-04 10:15:15', 'Bar'),
('MN-0110', 1, 'VANILLA LATTE ICE', 9796, 25000, '1593857767.Caffe Latte Ice.jpg', 'Ready', '2020-07-04 10:16:07', '2020-07-04 10:16:07', 'Bar'),
('MN-0111', 2, 'KIWI SQUASH', 12250, 22000, '1593857828.KIWI MOJITO.jpg', 'Ready', '2020-07-04 10:17:08', '2020-07-04 10:20:06', 'Bar'),
('MN-0112', 2, 'STRAWBERRY SQUASH', 12250, 22000, '1593857901.StrawberryMojito1.jpg', 'Ready', '2020-07-04 10:18:21', '2020-07-04 10:18:21', 'Bar'),
('MN-0113', 2, 'MILKY BLUE FIRE SQUASH', 6740, 23000, '1593857955.BlueFireSquash1.jpg', 'Ready', '2020-07-04 10:19:15', '2020-07-04 10:19:15', 'Bar'),
('MN-0114', 2, 'BLUE FIRE LATTE HOT', 5760, 24000, '1593858388.BlueFireSquash1.jpg', 'Ready', '2020-07-04 10:26:28', '2020-07-04 10:26:28', 'Bar'),
('MN-0115', 2, 'BLUE FIR LATTE ICE', 6260, 25000, '1593858434.BlueFireSquash1.jpg', 'Ready', '2020-07-04 10:27:14', '2020-07-04 10:27:14', 'Bar'),
('MN-0116', 2, 'CLASSIC BONTEA TEAPOT', 3000, 16000, '1593858573.teapot.jpg', 'Ready', '2020-07-04 10:29:33', '2020-07-04 10:29:33', 'Bar'),
('MN-0117', 2, 'GREENTEA TEAPOT', 4000, 16000, '1593858641.teapot.jpg', 'Ready', '2020-07-04 10:30:41', '2020-07-04 10:30:41', 'Bar'),
('MN-0118', 2, 'BLACK TEA TEAPOT', 4000, 16000, '1593858694.teapot.jpg', 'Ready', '2020-07-04 10:31:34', '2020-07-04 10:31:34', 'Bar');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2020_06_11_162612_create_table_kategori_barang', 1),
(5, '2020_06_12_093400_create_barang_table', 1),
(6, '2020_06_12_094254_create_pembelian_table', 1),
(7, '2020_06_12_095012_create_detail_pembelian_table', 1),
(8, '2020_06_12_100122_create_pemakaian_table', 1),
(9, '2020_06_12_100419_create_detail_pemakaian_table', 1),
(10, '2020_06_12_111031_create_supplier_table', 1),
(11, '2020_06_12_163439_alter_barang_table', 1),
(12, '2020_06_12_201308_create_kategori_menu', 1),
(13, '2020_06_12_201502_create_menu', 1),
(14, '2020_06_12_225734_create_log_activity', 1),
(15, '2020_06_12_230522_create_perusahaan', 1),
(16, '2020_06_12_230935_create_penjualan', 1),
(17, '2020_06_12_231735_create_detail_penjualan', 1),
(18, '2020_06_12_232312_create_diskon', 1),
(19, '2020_06_12_232533_create_detail_diskon', 1),
(20, '2020_06_13_060524_add_kode_supplier_to_pembelian', 1),
(21, '2020_06_15_054315_create_kas_keluar_table', 1),
(22, '2020_06_15_223534_alter_kas_keluar_table', 1),
(23, '2020_06_15_224123_alter_kas_table', 1),
(24, '2020_06_16_205518_alter_table_penjualan', 1),
(25, '2020_06_16_210953_alter_table_detail_penjualan', 1),
(26, '2020_06_17_170612_alter_table_detail_penjualan2', 1),
(27, '2020_06_17_214350_alter_penjualan_table', 1),
(28, '2020_06_20_132218_alter_barang', 1),
(29, '2020_06_21_164840_alter_supplier_table', 1),
(30, '2020_06_21_195235_add_stock_awal_barang_migration', 1),
(31, '2020_07_02_090836_create_meja_table', 1),
(32, '2020_07_02_090930_add_id_meja_to_penjualan', 1),
(33, '2020_07_02_084651_custom_users_table', 2),
(34, '2020_07_02_101655_add_level_users', 2),
(35, '2020_07_02_160109_add_jenis_menu', 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pemakaian`
--

CREATE TABLE `pemakaian` (
  `kode_pemakaian` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` date NOT NULL,
  `jumlah_qty` smallint(6) NOT NULL,
  `total_saldo` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembelian`
--

CREATE TABLE `pembelian` (
  `kode_pembelian` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` date NOT NULL,
  `jumlah_item` tinyint(4) NOT NULL,
  `jumlah_qty` smallint(6) NOT NULL,
  `total` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `kode_supplier` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `penjualan`
--

CREATE TABLE `penjualan` (
  `kode_penjualan` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_customer` char(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_hp` varchar(13) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis_order` enum('Dine In','Take Away') COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah_item` tinyint(4) NOT NULL,
  `jenis_bayar` enum('Debit','Tunai') COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_kartu` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_bayar` enum('Belum Bayar','Sudah Bayar') COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_harga` int(10) UNSIGNED NOT NULL,
  `total_ppn` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `waktu` datetime NOT NULL,
  `jumlah_qty` int(11) NOT NULL,
  `total_diskon` int(11) NOT NULL DEFAULT '0',
  `total_diskon_tambahan` int(11) NOT NULL DEFAULT '0',
  `bayar` int(11) NOT NULL,
  `kembalian` int(11) NOT NULL,
  `id_meja` tinyint(3) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `perusahaan`
--

CREATE TABLE `perusahaan` (
  `id_perusahaan` tinyint(3) UNSIGNED NOT NULL,
  `nama` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `inisial` char(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `npwp` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `kota` char(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat_kantor` text COLLATE utf8mb4_unicode_ci,
  `telepon` varchar(13) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `perusahaan`
--

INSERT INTO `perusahaan` (`id_perusahaan`, `nama`, `inisial`, `npwp`, `alamat`, `kota`, `alamat_kantor`, `telepon`, `email`, `created_at`, `updated_at`) VALUES
(1, 'Baratha', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `supplier`
--

CREATE TABLE `supplier` (
  `kode_supplier` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_supplier` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_hp` varchar(18) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` enum('Laki-laki','Perempuan') COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_hp` varchar(13) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `level` enum('Accounting','Kasir','Owner','Waiters') COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nama`, `username`, `gender`, `email`, `no_hp`, `alamat`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `level`) VALUES
(1, 'Administrator', 'Administrator', 'Laki-laki', 'administrator@baratha.com', '-', '-', NULL, '$2y$10$miop9RefdV/kVisCvlQjc.blcyLYiglfqfmNmNrikaA91L.nHWwFK', NULL, '2020-07-02 06:57:25', '2020-07-02 06:57:25', 'Owner');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`kode_barang`),
  ADD KEY `barang_id_kategori_barang_foreign` (`id_kategori_barang`);

--
-- Indeks untuk tabel `detail_diskon`
--
ALTER TABLE `detail_diskon`
  ADD PRIMARY KEY (`id_detail_diskon`),
  ADD KEY `detail_diskon_id_diskon_foreign` (`id_diskon`),
  ADD KEY `detail_diskon_kode_menu_foreign` (`kode_menu`);

--
-- Indeks untuk tabel `detail_pemakaian`
--
ALTER TABLE `detail_pemakaian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detail_pemakaian_kode_pemakaian_foreign` (`kode_pemakaian`),
  ADD KEY `detail_pemakaian_kode_barang_foreign` (`kode_barang`);

--
-- Indeks untuk tabel `detail_pembelian`
--
ALTER TABLE `detail_pembelian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detail_pembelian_kode_pembelian_foreign` (`kode_pembelian`),
  ADD KEY `detail_pembelian_kode_barang_foreign` (`kode_barang`);

--
-- Indeks untuk tabel `detail_penjualan`
--
ALTER TABLE `detail_penjualan`
  ADD PRIMARY KEY (`id_detail_penjualan`),
  ADD KEY `detail_penjualan_kode_penjualan_foreign` (`kode_penjualan`),
  ADD KEY `detail_penjualan_kode_menu_foreign` (`kode_menu`);

--
-- Indeks untuk tabel `diskon`
--
ALTER TABLE `diskon`
  ADD PRIMARY KEY (`id_diskon`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kas`
--
ALTER TABLE `kas`
  ADD PRIMARY KEY (`kode_kas`);

--
-- Indeks untuk tabel `kategori_barang`
--
ALTER TABLE `kategori_barang`
  ADD PRIMARY KEY (`id_kategori_barang`);

--
-- Indeks untuk tabel `kategori_menu`
--
ALTER TABLE `kategori_menu`
  ADD PRIMARY KEY (`id_kategori_menu`);

--
-- Indeks untuk tabel `log_activity`
--
ALTER TABLE `log_activity`
  ADD PRIMARY KEY (`id`),
  ADD KEY `log_activity_id_users_foreign` (`id_users`);

--
-- Indeks untuk tabel `meja`
--
ALTER TABLE `meja`
  ADD PRIMARY KEY (`id_meja`);

--
-- Indeks untuk tabel `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`kode_menu`),
  ADD KEY `menu_id_kategori_menu_foreign` (`id_kategori_menu`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indeks untuk tabel `pemakaian`
--
ALTER TABLE `pemakaian`
  ADD PRIMARY KEY (`kode_pemakaian`);

--
-- Indeks untuk tabel `pembelian`
--
ALTER TABLE `pembelian`
  ADD PRIMARY KEY (`kode_pembelian`),
  ADD KEY `pembelian_kode_supplier_foreign` (`kode_supplier`);

--
-- Indeks untuk tabel `penjualan`
--
ALTER TABLE `penjualan`
  ADD PRIMARY KEY (`kode_penjualan`),
  ADD KEY `penjualan_id_meja_foreign` (`id_meja`);

--
-- Indeks untuk tabel `perusahaan`
--
ALTER TABLE `perusahaan`
  ADD PRIMARY KEY (`id_perusahaan`);

--
-- Indeks untuk tabel `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`kode_supplier`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `detail_diskon`
--
ALTER TABLE `detail_diskon`
  MODIFY `id_detail_diskon` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `detail_pemakaian`
--
ALTER TABLE `detail_pemakaian`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `detail_pembelian`
--
ALTER TABLE `detail_pembelian`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `detail_penjualan`
--
ALTER TABLE `detail_penjualan`
  MODIFY `id_detail_penjualan` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `diskon`
--
ALTER TABLE `diskon`
  MODIFY `id_diskon` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kategori_barang`
--
ALTER TABLE `kategori_barang`
  MODIFY `id_kategori_barang` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kategori_menu`
--
ALTER TABLE `kategori_menu`
  MODIFY `id_kategori_menu` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `log_activity`
--
ALTER TABLE `log_activity`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `meja`
--
ALTER TABLE `meja`
  MODIFY `id_meja` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT untuk tabel `perusahaan`
--
ALTER TABLE `perusahaan`
  MODIFY `id_perusahaan` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `barang_id_kategori_barang_foreign` FOREIGN KEY (`id_kategori_barang`) REFERENCES `kategori_barang` (`id_kategori_barang`);

--
-- Ketidakleluasaan untuk tabel `detail_diskon`
--
ALTER TABLE `detail_diskon`
  ADD CONSTRAINT `detail_diskon_id_diskon_foreign` FOREIGN KEY (`id_diskon`) REFERENCES `diskon` (`id_diskon`),
  ADD CONSTRAINT `detail_diskon_kode_menu_foreign` FOREIGN KEY (`kode_menu`) REFERENCES `menu` (`kode_menu`);

--
-- Ketidakleluasaan untuk tabel `detail_pemakaian`
--
ALTER TABLE `detail_pemakaian`
  ADD CONSTRAINT `detail_pemakaian_kode_barang_foreign` FOREIGN KEY (`kode_barang`) REFERENCES `barang` (`kode_barang`),
  ADD CONSTRAINT `detail_pemakaian_kode_pemakaian_foreign` FOREIGN KEY (`kode_pemakaian`) REFERENCES `pemakaian` (`kode_pemakaian`);

--
-- Ketidakleluasaan untuk tabel `detail_pembelian`
--
ALTER TABLE `detail_pembelian`
  ADD CONSTRAINT `detail_pembelian_kode_barang_foreign` FOREIGN KEY (`kode_barang`) REFERENCES `barang` (`kode_barang`),
  ADD CONSTRAINT `detail_pembelian_kode_pembelian_foreign` FOREIGN KEY (`kode_pembelian`) REFERENCES `pembelian` (`kode_pembelian`);

--
-- Ketidakleluasaan untuk tabel `detail_penjualan`
--
ALTER TABLE `detail_penjualan`
  ADD CONSTRAINT `detail_penjualan_kode_menu_foreign` FOREIGN KEY (`kode_menu`) REFERENCES `menu` (`kode_menu`),
  ADD CONSTRAINT `detail_penjualan_kode_penjualan_foreign` FOREIGN KEY (`kode_penjualan`) REFERENCES `penjualan` (`kode_penjualan`);

--
-- Ketidakleluasaan untuk tabel `log_activity`
--
ALTER TABLE `log_activity`
  ADD CONSTRAINT `log_activity_id_users_foreign` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `menu_id_kategori_menu_foreign` FOREIGN KEY (`id_kategori_menu`) REFERENCES `kategori_menu` (`id_kategori_menu`);

--
-- Ketidakleluasaan untuk tabel `pembelian`
--
ALTER TABLE `pembelian`
  ADD CONSTRAINT `pembelian_kode_supplier_foreign` FOREIGN KEY (`kode_supplier`) REFERENCES `supplier` (`kode_supplier`);

--
-- Ketidakleluasaan untuk tabel `penjualan`
--
ALTER TABLE `penjualan`
  ADD CONSTRAINT `penjualan_id_meja_foreign` FOREIGN KEY (`id_meja`) REFERENCES `meja` (`id_meja`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
