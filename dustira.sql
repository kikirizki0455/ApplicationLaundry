-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 08 Des 2024 pada 19.27
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dustira`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `bahan`
--

CREATE TABLE `bahan` (
  `id_bahan` smallint(6) UNSIGNED NOT NULL,
  `nama_bahan` varchar(50) NOT NULL,
  `stok_bahan` smallint(6) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `bahan`
--

INSERT INTO `bahan` (`id_bahan`, `nama_bahan`, `stok_bahan`) VALUES
(2, 'ditergen liquid', 103),
(4, 'penetral', 26),
(5, 'concenrated oxygen bleach', 26),
(6, 'karbol sere', 50),
(9, 'soklin', 50),
(16, 'Detol', 50);

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang`
--

CREATE TABLE `barang` (
  `id_barang` int(5) UNSIGNED NOT NULL,
  `nama_barang` varchar(50) NOT NULL,
  `stok` int(5) UNSIGNED NOT NULL,
  `stok_dicuci` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `barang`
--

INSERT INTO `barang` (`id_barang`, `nama_barang`, `stok`, `stok_dicuci`) VALUES
(32, 'S Bantal', 100, 10),
(33, 'Laken', 100, 0),
(34, 'Selimut', 200, 0),
(35, 'Sprei', 100, 0),
(36, 'Bedcover', 100, 23),
(37, 'Gorden', 44, 0),
(39, 'Apd', 123, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_pelipatan`
--

CREATE TABLE `detail_pelipatan` (
  `id_detail_pelipatan` int(5) UNSIGNED NOT NULL,
  `id_pelipatan` int(5) UNSIGNED NOT NULL,
  `id_barang` int(5) UNSIGNED NOT NULL,
  `jumlah_barang` int(5) NOT NULL,
  `status` enum('pending','completed') NOT NULL DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `detail_pelipatan`
--

INSERT INTO `detail_pelipatan` (`id_detail_pelipatan`, `id_pelipatan`, `id_barang`, `jumlah_barang`, `status`, `created_at`, `updated_at`) VALUES
(128, 74, 32, 1, '', '2024-12-06 21:11:51', '2024-12-07 00:10:10'),
(129, 74, 33, 2, '', '2024-12-06 21:13:49', '2024-12-07 00:10:10'),
(132, 74, 35, 1, '', '2024-12-06 21:39:00', '2024-12-07 00:10:10'),
(133, 76, 32, 1, '', '2024-12-07 00:11:02', '2024-12-07 00:53:13'),
(134, 77, 36, 2, '', '2024-12-07 00:39:49', '2024-12-07 04:49:08'),
(135, 78, 36, 1, '', '2024-12-07 04:50:28', '2024-12-07 04:50:45'),
(136, 79, 32, 2, '', '2024-12-07 05:05:36', '2024-12-07 05:05:40'),
(137, 80, 32, 2, '', '2024-12-08 05:45:51', '2024-12-08 05:46:03'),
(138, 80, 34, 2, '', '2024-12-08 05:45:55', '2024-12-08 05:46:03'),
(139, 80, 36, 2, '', '2024-12-08 05:45:58', '2024-12-08 05:46:03'),
(140, 81, 36, 1, '', '2024-12-08 06:39:29', '2024-12-08 06:39:32'),
(141, 83, 36, 2, '', '2024-12-09 01:01:50', '2024-12-09 01:02:12'),
(142, 83, 33, 20, '', '2024-12-09 01:01:56', '2024-12-09 01:02:12');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mesin_bahan`
--

CREATE TABLE `mesin_bahan` (
  `id` int(5) NOT NULL,
  `id_mesin` int(5) NOT NULL,
  `id_bahan` smallint(6) UNSIGNED NOT NULL,
  `jumlah_bahan` smallint(6) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mesin_bahan`
--

INSERT INTO `mesin_bahan` (`id`, `id_mesin`, `id_bahan`, `jumlah_bahan`) VALUES
(1, 13, 2, 12),
(2, 13, 4, 12),
(3, 13, 5, 12),
(4, 14, 2, 12),
(5, 14, 4, 12),
(6, 14, 5, 12),
(15, 14, 9, 25);

-- --------------------------------------------------------

--
-- Struktur dari tabel `mesin_cuci`
--

CREATE TABLE `mesin_cuci` (
  `id_mesin` int(5) NOT NULL,
  `nama_mesin` varchar(255) NOT NULL,
  `kapasitas` smallint(6) NOT NULL,
  `status` enum('aktif','tidak_aktif') NOT NULL,
  `kategori` enum('infeksi','non_infeksi') NOT NULL,
  `id_bahan` smallint(6) UNSIGNED DEFAULT NULL,
  `jumlah_bahan` int(5) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mesin_cuci`
--

INSERT INTO `mesin_cuci` (`id_mesin`, `nama_mesin`, `kapasitas`, `status`, `kategori`, `id_bahan`, `jumlah_bahan`, `created_at`, `updated_at`) VALUES
(13, 'Mesin Infeksi 1.A', 30, 'aktif', 'infeksi', NULL, NULL, NULL, NULL),
(14, 'Mesin Non Infeksi B1', 30, 'aktif', 'non_infeksi', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2024-08-22-165656', 'App\\Database\\Migrations\\CreateTablesBahan', 'default', 'App', 1725212787, 1),
(2, '2024-08-22-165656', 'App\\Database\\Migrations\\CreateTablesbahan', 'default', 'App', 1725212876, 2),
(3, '2024-08-22-165656', 'App\\Database\\Migrations\\CreateTablesTimbangan', 'default', 'App', 1725212933, 3),
(4, '2024-08-22-165656', 'App\\Database\\Migrations\\CreateTablesPegawai', 'default', 'App', 1725213047, 4),
(5, '2024-08-22-165656', 'App\\Database\\Migrations\\CreateTablesPencucian', 'default', 'App', 1725213097, 5),
(6, '2024-08-22-165656', 'App\\Database\\Migrations\\UpdatePencucianTable', 'default', 'App', 1725346871, 6),
(7, '2024-08-22-165656', 'App\\Database\\Migrations\\UpdatePencucian1Table', 'default', 'App', 1725347673, 7),
(8, '2024-08-22-165656', 'App\\Database\\Migrations\\UpdatePencucianStatusTable', 'default', 'App', 1725347732, 8),
(9, '2024-08-22-165656', 'App\\Database\\Migrations\\RemoveStatusColumnFromPencucian', 'default', 'App', 1725347847, 9),
(10, '2024-08-22-165656', 'App\\Database\\Migrations\\UpdateTablePencucian', 'default', 'App', 1725348030, 10),
(15, '2024-08-22-165656', 'App\\Database\\Migrations\\AddForeignKeyToPencucian', 'default', 'App', 1725379167, 11),
(16, '2024-08-22-165656', 'App\\Database\\Migrations\\DropTablePencucian', 'default', 'App', 1725380414, 12),
(18, '2024-08-22-165656', 'App\\Database\\Migrations\\UpadateStatusTablePencucian', 'default', 'App', 1725380886, 13),
(19, '2024-08-22-165656', 'App\\Database\\Migrations\\AddStatusTablePencucian', 'default', 'App', 1725380951, 14),
(20, '2024-08-22-165656', 'App\\Database\\Migrations\\AddBeratBarangTablePencucian', 'default', 'App', 1725381594, 15),
(21, '2024-08-22-165656', 'App\\Database\\Migrations\\AddBeratBarang2TablePencucian', 'default', 'App', 1725381742, 16),
(22, '2024-08-22-165656', 'App\\Database\\Migrations\\AddBeratBarang3TablePencucian', 'default', 'App', 1725381766, 17),
(23, '2024-08-22-165656', 'App\\Database\\Migrations\\AddNamaBarangTablePencucian', 'default', 'App', 1725382097, 18),
(24, '2024-08-22-165656', 'App\\Database\\Migrations\\AddWaktuEstimasiToPencucian', 'default', 'App', 1726640707, 19),
(25, '2024-08-22-165656', 'App\\Database\\Migrations\\CreateTableRusak', 'default', 'App', 1726650641, 20),
(26, '2024-08-22-165656', 'App\\Database\\Migrations\\addKategoriTimbangan', 'default', 'App', 1726652756, 21),
(27, '2024-08-22-165656', 'App\\Database\\Migrations\\RemoveStatusPencucianFromTimbangan', 'default', 'App', 1726653310, 22),
(28, '2024-08-22-165656', 'App\\Database\\Migrations\\AddStatusPencucianToTimbangan', 'default', 'App', 1726653419, 23),
(29, '2024-08-22-165656', 'App\\Database\\Migrations\\CreatePengeringanTable', 'default', 'App', 1726673225, 24),
(30, '2024-09-19-072234', 'App\\Database\\Migrations\\UbahIdPencucian', 'default', 'App', 1726731102, 25),
(31, '2024-09-19-074603', 'App\\Database\\Migrations\\DropIdBahanTablePengeringan', 'default', 'App', 1726732107, 26),
(32, '2024-09-19-074603', 'App\\Database\\Migrations\\RemoveIdBahanPengeringan', 'default', 'App', 1726732447, 27),
(33, '2024-09-19-074603', 'App\\Database\\Migrations\\addDurasiTablePengeringan', 'default', 'App', 1726740100, 28),
(34, '2024-09-19-104528', 'App\\Database\\Migrations\\AddWaktuColumnToPengeringan', 'default', 'App', 1726742746, 29),
(35, '2024-09-19-104528', 'App\\Database\\Migrations\\EditColumnStatusPengeringan', 'default', 'App', 1726744143, 30),
(36, '2024-09-19-104528', 'App\\Database\\Migrations\\UpdatePengeringanTimer', 'default', 'App', 1726763483, 31),
(37, '2024-09-19-134042', 'App\\Database\\Migrations\\CreateTablePenyetrikaan', 'default', 'App', 1726763483, 31),
(39, '2024-09-19-134042', 'App\\Database\\Migrations\\CreatePenyetrikaanTable', 'default', 'App', 1726764965, 32),
(40, '2024-09-19-134042', 'App\\Database\\Migrations\\DropBahanTablePenyetrikaan', 'default', 'App', 1726767353, 33),
(41, '2024-09-19-134042', 'App\\Database\\Migrations\\DropIDBahanTablePenyetrikaan', 'default', 'App', 1726767445, 34),
(50, '2024-10-14-204416', 'App\\Database\\Migrations\\CreateTableMesinCuci', 'default', 'App', 1728990417, 35),
(51, '2024-10-15-095640', 'App\\Database\\Migrations\\AddColumnNoInvoice', 'default', 'App', 1728990417, 35),
(52, '2024-10-15-101350', 'App\\Database\\Migrations\\AddIdBarangAsForeignKeyToTimbangan', 'default', 'App', 1728990417, 35),
(53, '2024-10-15-103255', 'App\\Database\\Migrations\\UpdateTimbanganTable', 'default', 'App', 1728990417, 35),
(54, '2024-10-15-111011', 'App\\Database\\Migrations\\AddForeignKeyToTimbangan', 'default', 'App', 1728990701, 36),
(55, '2024-10-15-111115', 'App\\Database\\Migrations\\RemoveIdBarangFromTimbangan', 'default', 'App', 1728990701, 36),
(56, '2024-10-15-111011', 'App\\Database\\Migrations\\AddIdBarangAsForeign', 'default', 'App', 1728991033, 37),
(57, '2024-10-15-111011', 'App\\Database\\Migrations\\AddIDBarangAsForeign', 'default', 'App', 1728992300, 38),
(58, '2024-10-15-111011', 'App\\Database\\Migrations\\AddToTimbanganIDBarangAsForeign', 'default', 'App', 1728992854, 39);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pegawai`
--

CREATE TABLE `pegawai` (
  `id_pegawai` int(5) UNSIGNED NOT NULL,
  `nomor_pegawai` varchar(100) NOT NULL,
  `nama_pegawai` varchar(100) NOT NULL,
  `role_pegawai` enum('admin','distribusi','pengelola') NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pegawai`
--

INSERT INTO `pegawai` (`id_pegawai`, `nomor_pegawai`, `nama_pegawai`, `role_pegawai`, `username`, `password`) VALUES
(11, '123100001', 'apow', 'pengelola', 'dustira', '$2y$10$DuojJ0tqszDP5gUMym7xcebLDCQZwxmcNXBO1kQ2DuXdvSULXyoZG'),
(12, '001', 'rizki', 'admin', 'admin', '$2y$10$X06ryWlq2o3ZwlpVezARV.WjVExqJVl1PWJIbQLIh0gleFiltJSsO'),
(13, '0021231', 'akmal', 'distribusi', 'akmal1', '$2y$10$5HapVRHtYzEPrLURnbvfye3SRmX7vf0F4ol4h9j8fO/h42G9YhsF6'),
(14, '11231', 'asd', 'pengelola', '123', '$2y$10$hFifnbK8uQtxHK8Ht8lF.ukWIWVz00vupFnqalKJkwdqt/wF7M40C'),
(15, '123005876124', 'Mang Toto', 'distribusi', 'toto', '$2y$10$XFPavgq7/HfZK6Fh9DUIGOz2D/fTM1AVfflhT0vyK/41ObrmdE7Dq'),
(16, '123123', 'rizki', 'admin', 'kikirizki', '$2y$10$6GT/QOgRMOvsTdc3Fr.3EOphfRLXKo.Eq9EVvTXZ2kXp5AXsKc.t2');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelipatan`
--

CREATE TABLE `pelipatan` (
  `id_pelipatan` int(5) UNSIGNED NOT NULL,
  `id_penyetrikaan` int(5) UNSIGNED NOT NULL,
  `status` enum('pending','in_progress','ready_move','completed') NOT NULL DEFAULT 'pending',
  `tanggal_mulai` datetime DEFAULT NULL,
  `tanggal_selesai` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pelipatan`
--

INSERT INTO `pelipatan` (`id_pelipatan`, `id_penyetrikaan`, `status`, `tanggal_mulai`, `tanggal_selesai`, `created_at`, `updated_at`) VALUES
(74, 86, 'completed', '2024-12-06 06:26:12', '2024-12-07 00:10:10', '2024-12-06 06:05:18', '2024-12-07 00:40:20'),
(76, 87, 'completed', '2024-12-06 06:26:44', '2024-12-07 00:53:13', '2024-12-06 06:15:07', '2024-12-07 02:02:11'),
(77, 88, 'completed', '2024-12-06 07:42:47', '2024-12-07 04:49:07', '2024-12-06 06:16:31', '2024-12-07 04:49:11'),
(78, 89, 'completed', '2024-12-07 04:50:11', '2024-12-07 04:50:45', '2024-12-06 06:16:39', '2024-12-07 04:50:48'),
(79, 90, 'completed', '2024-12-07 05:05:31', '2024-12-07 05:05:40', '2024-12-07 05:05:22', '2024-12-07 05:37:04'),
(80, 91, 'completed', '2024-12-08 05:45:42', '2024-12-08 05:46:03', '2024-12-07 05:05:26', '2024-12-08 05:46:05'),
(81, 92, 'completed', '2024-12-08 06:39:21', '2024-12-08 06:39:32', '2024-12-08 06:39:16', '2024-12-08 06:39:34'),
(82, 93, 'pending', '2024-12-08 06:41:04', NULL, '2024-12-08 06:41:04', '2024-12-08 06:41:04'),
(83, 95, 'completed', '2024-12-09 01:01:17', '2024-12-09 01:02:12', '2024-12-09 01:01:09', '2024-12-09 01:02:14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pencucian`
--

CREATE TABLE `pencucian` (
  `id_cuci` int(5) UNSIGNED NOT NULL,
  `id_timbangan` int(5) UNSIGNED NOT NULL,
  `id_bahan` smallint(6) UNSIGNED DEFAULT NULL,
  `id_barang` int(5) UNSIGNED DEFAULT NULL,
  `id_mesin` int(5) DEFAULT NULL,
  `berat_barang` decimal(8,2) NOT NULL,
  `jumlah_bahan` int(3) NOT NULL,
  `status` enum('pending','in_progress','completed') NOT NULL,
  `pencucian_status` enum('pending','in_progress','ready_move','completed') NOT NULL,
  `tanggal_mulai` timestamp NULL DEFAULT NULL,
  `tanggal_selesai` timestamp NULL DEFAULT NULL,
  `waktu_estimasi` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pencucian`
--

INSERT INTO `pencucian` (`id_cuci`, `id_timbangan`, `id_bahan`, `id_barang`, `id_mesin`, `berat_barang`, `jumlah_bahan`, `status`, `pencucian_status`, `tanggal_mulai`, `tanggal_selesai`, `waktu_estimasi`) VALUES
(258, 327, NULL, NULL, 13, 1.00, 0, 'in_progress', 'completed', '2024-12-05 20:43:19', '2024-12-05 20:43:20', NULL),
(259, 328, NULL, NULL, 13, 2.00, 0, 'in_progress', 'completed', '2024-12-05 21:35:09', '2024-12-05 21:35:16', NULL),
(260, 329, NULL, NULL, 13, 3.00, 0, 'in_progress', 'completed', '2024-12-05 21:35:11', '2024-12-05 21:35:19', NULL),
(261, 330, NULL, NULL, 13, 2.00, 0, 'in_progress', 'completed', '2024-12-05 21:35:22', '2024-12-05 21:35:23', NULL),
(262, 331, NULL, NULL, 13, 1.00, 0, 'in_progress', 'completed', '2024-12-05 21:35:27', '2024-12-05 21:35:28', NULL),
(263, 332, NULL, NULL, 13, 1.00, 0, 'in_progress', 'completed', '2024-12-05 21:35:32', '2024-12-05 21:35:33', NULL),
(264, 333, NULL, NULL, 13, 1.00, 0, 'in_progress', 'completed', '2024-12-05 21:35:37', '2024-12-05 21:35:38', NULL),
(265, 334, NULL, NULL, 13, 1.00, 0, 'in_progress', 'completed', '2024-12-05 21:35:42', '2024-12-05 21:35:43', NULL),
(266, 335, NULL, NULL, 13, 1.00, 0, 'in_progress', 'completed', '2024-12-05 21:35:47', '2024-12-05 21:35:48', NULL),
(267, 336, NULL, NULL, 13, 1.00, 0, 'in_progress', 'completed', '2024-12-05 21:35:51', '2024-12-05 21:35:53', NULL),
(268, 337, NULL, NULL, 13, 1.00, 0, 'in_progress', 'completed', '2024-12-05 21:35:58', '2024-12-05 21:36:01', NULL),
(269, 338, NULL, NULL, 13, 1.00, 0, 'in_progress', 'completed', '2024-12-05 21:36:06', '2024-12-05 21:36:09', NULL),
(270, 339, NULL, NULL, 13, 1.00, 0, 'in_progress', 'completed', '2024-12-05 21:36:14', '2024-12-05 21:36:16', NULL),
(271, 340, NULL, NULL, 13, 1.00, 0, 'in_progress', 'completed', '2024-12-05 21:36:23', '2024-12-05 21:36:28', NULL),
(272, 341, NULL, NULL, 13, 1.00, 0, 'in_progress', 'completed', '2024-12-05 21:36:37', '2024-12-05 21:36:40', NULL),
(273, 344, NULL, NULL, 13, 3.00, 0, 'in_progress', 'completed', '2024-12-08 17:59:34', '2024-12-08 17:59:37', NULL),
(274, 345, NULL, NULL, 14, 1.00, 0, 'in_progress', 'completed', '2024-12-08 17:59:43', '2024-12-08 17:59:47', NULL),
(275, 346, NULL, NULL, 13, 23.00, 0, 'in_progress', 'completed', '2024-12-08 17:59:52', '2024-12-08 17:59:54', NULL),
(276, 347, NULL, NULL, 13, 23.00, 0, 'in_progress', 'completed', '2024-12-08 18:00:00', '2024-12-08 18:00:02', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengeringan`
--

CREATE TABLE `pengeringan` (
  `id_pengeringan` int(5) UNSIGNED NOT NULL,
  `id_cuci` int(5) UNSIGNED DEFAULT NULL,
  `tanggal_mulai` datetime NOT NULL,
  `tanggal_selesai` datetime DEFAULT NULL,
  `status` enum('pending','in_progress','ready_move','completed') DEFAULT 'in_progress',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengeringan`
--

INSERT INTO `pengeringan` (`id_pengeringan`, `id_cuci`, `tanggal_mulai`, `tanggal_selesai`, `status`, `created_at`, `updated_at`) VALUES
(203, 258, '2024-12-06 04:08:56', '2024-12-06 04:09:04', 'completed', '2024-12-06 03:50:22', '2024-12-06 04:15:17'),
(204, 259, '2024-12-06 06:05:03', '2024-12-06 06:05:06', 'completed', '2024-12-06 04:35:18', '2024-12-06 06:05:08'),
(205, 260, '2024-12-06 06:15:25', '2024-12-06 06:15:27', 'completed', '2024-12-06 04:35:20', '2024-12-06 06:15:28'),
(206, 261, '2024-12-06 06:15:30', '2024-12-06 06:15:31', 'completed', '2024-12-06 04:35:25', '2024-12-06 06:15:33'),
(207, 262, '2024-12-06 06:15:39', '2024-12-06 06:15:41', 'completed', '2024-12-06 04:35:30', '2024-12-06 06:15:43'),
(208, 263, '2024-12-06 06:16:06', '2024-12-06 06:16:12', 'completed', '2024-12-06 04:35:35', '2024-12-06 06:16:14'),
(209, 264, '2024-12-08 06:38:28', '2024-12-08 06:38:29', 'completed', '2024-12-06 04:35:40', '2024-12-08 06:38:31'),
(210, 265, '2024-12-08 06:40:51', '2024-12-08 06:40:52', 'completed', '2024-12-06 04:35:44', '2024-12-08 06:40:54'),
(211, 266, '2024-12-09 01:00:14', '2024-12-09 01:00:18', 'completed', '2024-12-06 04:35:50', '2024-12-09 01:00:21'),
(212, 267, '2024-12-06 04:35:56', NULL, 'pending', '2024-12-06 04:35:56', '2024-12-06 04:35:56'),
(213, 268, '2024-12-06 04:36:04', NULL, 'pending', '2024-12-06 04:36:04', '2024-12-06 04:36:04'),
(214, 269, '2024-12-06 04:36:11', NULL, 'pending', '2024-12-06 04:36:11', '2024-12-06 04:36:11'),
(215, 270, '2024-12-06 04:36:17', NULL, 'pending', '2024-12-06 04:36:17', '2024-12-06 04:36:17'),
(216, 271, '2024-12-06 04:36:33', NULL, 'pending', '2024-12-06 04:36:33', '2024-12-06 04:36:33'),
(217, 272, '2024-12-06 04:36:43', NULL, 'pending', '2024-12-06 04:36:43', '2024-12-06 04:36:43'),
(218, 273, '2024-12-09 00:59:40', NULL, 'pending', '2024-12-09 00:59:40', '2024-12-09 00:59:40'),
(219, 274, '2024-12-09 00:59:49', NULL, 'pending', '2024-12-09 00:59:49', '2024-12-09 00:59:49'),
(220, 275, '2024-12-09 00:59:56', NULL, 'pending', '2024-12-09 00:59:56', '2024-12-09 00:59:56'),
(221, 276, '2024-12-09 01:00:26', '2024-12-09 01:00:54', 'completed', '2024-12-09 01:00:05', '2024-12-09 01:00:56');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penggunaan_bahan`
--

CREATE TABLE `penggunaan_bahan` (
  `id_penggunaan` int(5) UNSIGNED NOT NULL,
  `id_bahan` smallint(6) UNSIGNED NOT NULL,
  `jumlah_penggunaan` int(10) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `bulan` int(2) NOT NULL,
  `tahun` int(4) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `id_timbangan` int(5) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `penggunaan_bahan`
--

INSERT INTO `penggunaan_bahan` (`id_penggunaan`, `id_bahan`, `jumlah_penggunaan`, `tanggal`, `bulan`, `tahun`, `keterangan`, `id_timbangan`) VALUES
(2, 2, 12, '2024-12-08', 12, 2024, 'Penggunaan bahan untuk timbangan ID: 345', 345),
(3, 4, 12, '2024-12-08', 12, 2024, 'Penggunaan bahan untuk timbangan ID: 345', 345),
(4, 5, 12, '2024-12-08', 12, 2024, 'Penggunaan bahan untuk timbangan ID: 345', 345),
(5, 2, 12, '2024-12-09', 12, 2024, 'Penggunaan bahan untuk timbangan ID: 346', 346),
(6, 4, 12, '2024-12-09', 12, 2024, 'Penggunaan bahan untuk timbangan ID: 346', 346),
(7, 5, 12, '2024-12-09', 12, 2024, 'Penggunaan bahan untuk timbangan ID: 346', 346),
(8, 2, 12, '2024-12-09', 12, 2024, 'Penggunaan bahan untuk timbangan ID: 347', 347),
(9, 4, 12, '2024-12-09', 12, 2024, 'Penggunaan bahan untuk timbangan ID: 347', 347),
(10, 5, 12, '2024-12-09', 12, 2024, 'Penggunaan bahan untuk timbangan ID: 347', 347);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengiriman`
--

CREATE TABLE `pengiriman` (
  `id_pengiriman` int(11) NOT NULL,
  `id_timbangan_bersih` int(5) UNSIGNED DEFAULT NULL,
  `signature_path` varchar(255) DEFAULT NULL,
  `status` enum('pending','completed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengiriman`
--

INSERT INTO `pengiriman` (`id_pengiriman`, `id_timbangan_bersih`, `signature_path`, `status`, `created_at`) VALUES
(1, 35, 'signature_35_1733611262.png', 'completed', '2024-12-07 22:41:02'),
(2, 37, 'signature_37_1733611326.png', 'completed', '2024-12-07 22:42:06'),
(3, 38, 'signature_38_1733611358.png', 'completed', '2024-12-07 22:42:38'),
(4, 39, 'signature_39_1733611597.png', 'completed', '2024-12-07 22:46:37'),
(5, 43, 'signature_43_1733681069.png', 'completed', '2024-12-08 18:04:29');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penyetrikaan`
--

CREATE TABLE `penyetrikaan` (
  `id_penyetrikaan` int(5) UNSIGNED NOT NULL,
  `id_pengeringan` int(5) UNSIGNED NOT NULL,
  `tanggal_mulai` datetime NOT NULL,
  `tanggal_selesai` datetime DEFAULT NULL,
  `status` enum('pending','in_progress','ready_move','completed') NOT NULL DEFAULT 'in_progress',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `is_moved` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `penyetrikaan`
--

INSERT INTO `penyetrikaan` (`id_penyetrikaan`, `id_pengeringan`, `tanggal_mulai`, `tanggal_selesai`, `status`, `created_at`, `updated_at`, `is_moved`) VALUES
(86, 203, '2024-12-06 06:04:49', '2024-12-06 06:04:56', 'completed', '2024-12-06 04:15:17', '2024-12-06 06:05:18', 0),
(87, 204, '2024-12-06 06:05:14', '2024-12-06 06:06:14', 'completed', '2024-12-06 06:05:08', '2024-12-06 06:15:07', 0),
(88, 205, '2024-12-06 06:16:29', '2024-12-06 06:16:30', 'completed', '2024-12-06 06:15:28', '2024-12-06 06:16:31', 0),
(89, 206, '2024-12-06 06:16:35', '2024-12-06 06:16:38', 'completed', '2024-12-06 06:15:33', '2024-12-06 06:16:39', 0),
(90, 207, '2024-12-07 05:05:20', '2024-12-07 05:05:21', 'completed', '2024-12-06 06:15:43', '2024-12-07 05:05:22', 0),
(91, 208, '2024-12-07 05:05:24', '2024-12-07 05:05:25', 'completed', '2024-12-06 06:16:14', '2024-12-07 05:05:26', 0),
(92, 209, '2024-12-08 06:39:13', '2024-12-08 06:39:14', 'completed', '2024-12-08 06:38:31', '2024-12-08 06:39:16', 0),
(93, 210, '2024-12-08 06:41:00', '2024-12-08 06:41:02', 'completed', '2024-12-08 06:40:54', '2024-12-08 06:41:04', 0),
(94, 211, '2024-12-09 01:00:21', NULL, 'pending', '2024-12-09 01:00:21', '2024-12-09 01:00:21', 0),
(95, 221, '2024-12-09 01:01:06', '2024-12-09 01:01:08', 'completed', '2024-12-09 01:00:56', '2024-12-09 01:01:09', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ruangan`
--

CREATE TABLE `ruangan` (
  `id_ruangan` int(5) NOT NULL,
  `nama_ruangan` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `ruangan`
--

INSERT INTO `ruangan` (`id_ruangan`, `nama_ruangan`) VALUES
(31, 'Seroja'),
(32, 'Melur'),
(33, 'Melati'),
(35, 'Anyelir'),
(36, 'Suplier'),
(37, 'Tulip'),
(38, 'Teratai'),
(40, 'Cempaka'),
(41, 'Walini'),
(42, 'Mawar'),
(44, 'Kaktus');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ruangan_barang`
--

CREATE TABLE `ruangan_barang` (
  `id_ruangan_barang` int(5) UNSIGNED NOT NULL,
  `id_ruangan` int(5) DEFAULT NULL,
  `id_barang` int(5) UNSIGNED DEFAULT NULL,
  `jumlah` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `ruangan_barang`
--

INSERT INTO `ruangan_barang` (`id_ruangan_barang`, `id_ruangan`, `id_barang`, `jumlah`) VALUES
(86, 31, 36, 0),
(87, 32, 36, 8),
(88, 33, 36, 8),
(89, 35, 36, 8),
(90, 36, 36, 8),
(91, 37, 36, 10),
(92, 38, 36, 10),
(93, 40, 36, 8),
(94, 41, 36, 10),
(95, 42, 36, 3),
(96, 31, 32, 90),
(97, 32, 33, 20);

-- --------------------------------------------------------

--
-- Struktur dari tabel `rusak`
--

CREATE TABLE `rusak` (
  `id` int(11) UNSIGNED NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `berat_barang` int(11) NOT NULL,
  `tanggal_rusak` date NOT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `timbangan`
--

CREATE TABLE `timbangan` (
  `id_timbangan` int(5) UNSIGNED NOT NULL,
  `id_ruangan` int(5) NOT NULL,
  `berat_barang` decimal(8,2) NOT NULL,
  `no_invoice` varchar(50) NOT NULL,
  `status` enum('pending','in_progress','completed') NOT NULL,
  `id_barang` int(5) UNSIGNED NOT NULL,
  `id_pegawai` int(5) UNSIGNED NOT NULL,
  `id_mesin` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `timbangan`
--

INSERT INTO `timbangan` (`id_timbangan`, `id_ruangan`, `berat_barang`, `no_invoice`, `status`, `id_barang`, `id_pegawai`, `id_mesin`) VALUES
(326, 31, 5.00, 'HJO0512202411002', 'completed', 0, 11, 13),
(327, 42, 1.00, 'SJX0512202413327', 'completed', 0, 11, 13),
(328, 42, 2.00, 'TIJ0612202423328', 'completed', 0, 11, 13),
(329, 33, 3.00, 'RZC0612202423329', 'completed', 0, 14, 13),
(330, 32, 2.00, 'NMZ0612202433330', 'completed', 0, 11, 13),
(331, 42, 1.00, 'AQL0612202423331', 'completed', 0, 11, 13),
(332, 32, 1.00, 'BND0612202423332', 'completed', 0, 11, 13),
(333, 31, 1.00, 'ULN0612202423333', 'completed', 0, 11, 13),
(334, 36, 1.00, 'OWK0612202413334', 'completed', 0, 11, 13),
(335, 40, 1.00, 'TKP0612202463335', 'completed', 0, 11, 13),
(336, 42, 1.00, 'CGG0612202403336', 'completed', 0, 11, 13),
(337, 31, 1.00, 'GPZ0612202423337', 'completed', 0, 11, 13),
(338, 32, 1.00, 'OPK0612202413338', 'completed', 0, 11, 13),
(339, 35, 1.00, 'SXP0612202423339', 'completed', 0, 11, 13),
(340, 36, 1.00, 'CFF0612202453340', 'completed', 0, 11, 13),
(341, 40, 1.00, 'UKH0612202463341', 'completed', 0, 11, 13),
(344, 31, 3.00, 'VGS0712202403342', 'completed', 0, 11, 13),
(345, 31, 1.00, 'BHP0812202413345', 'completed', 0, 11, 14),
(346, 31, 23.00, 'FIV0912202414346', 'completed', 0, 11, 13),
(347, 32, 23.00, 'FOV0912202413347', 'completed', 0, 11, 13),
(348, 35, 2.00, 'FRK0912202423348', 'pending', 0, 11, 14);

-- --------------------------------------------------------

--
-- Struktur dari tabel `timbangan_barang`
--

CREATE TABLE `timbangan_barang` (
  `id` int(5) NOT NULL,
  `id_timbangan` int(5) UNSIGNED DEFAULT NULL,
  `nama_barang` varchar(255) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `id_barang` int(5) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `timbangan_barang`
--

INSERT INTO `timbangan_barang` (`id`, `id_timbangan`, `nama_barang`, `jumlah`, `id_barang`) VALUES
(183, 326, 'Bedcover', 5, 36),
(184, 327, 'Bedcover', 5, 36),
(185, 328, 'Bedcover', 1, 36),
(186, 329, 'Bedcover', 2, 36),
(187, 330, 'Bedcover', 1, 36),
(188, 331, 'Bedcover', 1, 36),
(189, 332, 'Bedcover', 1, 36),
(190, 333, 'Bedcover', 1, 36),
(191, 334, 'Bedcover', 1, 36),
(192, 335, 'Bedcover', 1, 36),
(193, 336, 'Bedcover', 1, 36),
(194, 337, 'Bedcover', 1, 36),
(195, 338, 'Bedcover', 1, 36),
(196, 339, 'Bedcover', 1, 36),
(197, 340, 'Bedcover', 1, 36),
(198, 341, 'Bedcover', 1, 36),
(200, 344, 'Bedcover', 3, 36),
(201, 345, 'Bedcover', 1, 36),
(202, 346, 'S Bantal', 10, 32),
(203, 347, 'Bedcover', 2, 36),
(204, 347, 'Laken', 20, 33),
(205, 348, 'Bedcover', 1, 36);

-- --------------------------------------------------------

--
-- Struktur dari tabel `timbangan_bersih`
--

CREATE TABLE `timbangan_bersih` (
  `id_timbangan_bersih` int(5) UNSIGNED NOT NULL,
  `id_pelipatan` int(5) UNSIGNED NOT NULL,
  `berat_bersih` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','process','delivered') NOT NULL DEFAULT 'pending',
  `tanggal_pengiriman` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ruangan` enum('melati','suplier','tulip','mawar','teratai') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `timbangan_bersih`
--

INSERT INTO `timbangan_bersih` (`id_timbangan_bersih`, `id_pelipatan`, `berat_bersih`, `status`, `tanggal_pengiriman`, `created_at`, `updated_at`, `ruangan`) VALUES
(35, 74, 2.00, 'delivered', NULL, '2024-12-07 00:40:20', '2024-12-08 05:41:02', NULL),
(37, 76, 1.00, 'delivered', NULL, '2024-12-07 02:02:11', '2024-12-08 05:42:07', NULL),
(38, 77, 2.00, 'delivered', NULL, '2024-12-07 04:49:11', '2024-12-08 05:42:39', NULL),
(39, 78, 2.00, 'delivered', '2024-12-08 05:46:37', '2024-12-07 04:50:48', '2024-12-08 05:46:37', NULL),
(40, 79, 3.00, 'process', '2024-12-08 06:32:20', '2024-12-07 05:37:04', '2024-12-08 06:32:20', NULL),
(41, 80, NULL, 'pending', NULL, '2024-12-08 05:46:05', '2024-12-08 05:46:05', NULL),
(42, 81, NULL, 'pending', NULL, '2024-12-08 06:39:34', '2024-12-08 06:39:34', NULL),
(43, 83, 23.00, 'delivered', '2024-12-09 01:04:29', '2024-12-09 01:02:14', '2024-12-09 01:04:29', NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `bahan`
--
ALTER TABLE `bahan`
  ADD PRIMARY KEY (`id_bahan`);

--
-- Indeks untuk tabel `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id_barang`);

--
-- Indeks untuk tabel `detail_pelipatan`
--
ALTER TABLE `detail_pelipatan`
  ADD PRIMARY KEY (`id_detail_pelipatan`),
  ADD KEY `id_pelipatan` (`id_pelipatan`),
  ADD KEY `id_barang` (`id_barang`);

--
-- Indeks untuk tabel `mesin_bahan`
--
ALTER TABLE `mesin_bahan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_mesin_bahan_mesin` (`id_mesin`),
  ADD KEY `fk_mesin_bahan_bahan` (`id_bahan`);

--
-- Indeks untuk tabel `mesin_cuci`
--
ALTER TABLE `mesin_cuci`
  ADD PRIMARY KEY (`id_mesin`),
  ADD KEY `fk_bahan_mesin` (`id_bahan`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`id_pegawai`),
  ADD UNIQUE KEY `email` (`username`);

--
-- Indeks untuk tabel `pelipatan`
--
ALTER TABLE `pelipatan`
  ADD PRIMARY KEY (`id_pelipatan`),
  ADD KEY `id_penyetrikaan` (`id_penyetrikaan`);

--
-- Indeks untuk tabel `pencucian`
--
ALTER TABLE `pencucian`
  ADD PRIMARY KEY (`id_cuci`),
  ADD KEY `pencucian_id_timbangan_foreign` (`id_timbangan`),
  ADD KEY `fk_id_barang` (`id_barang`),
  ADD KEY `fk_id_bahan` (`id_bahan`),
  ADD KEY `fk_pencucian_mesin` (`id_mesin`);

--
-- Indeks untuk tabel `pengeringan`
--
ALTER TABLE `pengeringan`
  ADD PRIMARY KEY (`id_pengeringan`),
  ADD KEY `fk_pengeringan` (`id_cuci`);

--
-- Indeks untuk tabel `penggunaan_bahan`
--
ALTER TABLE `penggunaan_bahan`
  ADD PRIMARY KEY (`id_penggunaan`),
  ADD KEY `id_bahan` (`id_bahan`),
  ADD KEY `fk_timbangan` (`id_timbangan`);

--
-- Indeks untuk tabel `pengiriman`
--
ALTER TABLE `pengiriman`
  ADD PRIMARY KEY (`id_pengiriman`),
  ADD KEY `id_timbangan_bersih` (`id_timbangan_bersih`);

--
-- Indeks untuk tabel `penyetrikaan`
--
ALTER TABLE `penyetrikaan`
  ADD PRIMARY KEY (`id_penyetrikaan`),
  ADD KEY `penyetrikaan_id_pengeringan_foreign` (`id_pengeringan`);

--
-- Indeks untuk tabel `ruangan`
--
ALTER TABLE `ruangan`
  ADD PRIMARY KEY (`id_ruangan`);

--
-- Indeks untuk tabel `ruangan_barang`
--
ALTER TABLE `ruangan_barang`
  ADD PRIMARY KEY (`id_ruangan_barang`),
  ADD KEY `id_ruangan` (`id_ruangan`),
  ADD KEY `id_barang` (`id_barang`);

--
-- Indeks untuk tabel `rusak`
--
ALTER TABLE `rusak`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `timbangan`
--
ALTER TABLE `timbangan`
  ADD PRIMARY KEY (`id_timbangan`),
  ADD KEY `fk_timbangan_barang` (`id_barang`),
  ADD KEY `fk_timbangan_pegawai` (`id_pegawai`),
  ADD KEY `fk_id_mesin` (`id_mesin`),
  ADD KEY `fk_id_ruangan` (`id_ruangan`);

--
-- Indeks untuk tabel `timbangan_barang`
--
ALTER TABLE `timbangan_barang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `timbangan_barang_ibfk_1` (`id_timbangan`),
  ADD KEY `fk_timbangan_barang_barang` (`id_barang`);

--
-- Indeks untuk tabel `timbangan_bersih`
--
ALTER TABLE `timbangan_bersih`
  ADD PRIMARY KEY (`id_timbangan_bersih`),
  ADD KEY `id_pelipatan` (`id_pelipatan`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `bahan`
--
ALTER TABLE `bahan`
  MODIFY `id_bahan` smallint(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `barang`
--
ALTER TABLE `barang`
  MODIFY `id_barang` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT untuk tabel `detail_pelipatan`
--
ALTER TABLE `detail_pelipatan`
  MODIFY `id_detail_pelipatan` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;

--
-- AUTO_INCREMENT untuk tabel `mesin_bahan`
--
ALTER TABLE `mesin_bahan`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `mesin_cuci`
--
ALTER TABLE `mesin_cuci`
  MODIFY `id_mesin` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT untuk tabel `pegawai`
--
ALTER TABLE `pegawai`
  MODIFY `id_pegawai` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `pelipatan`
--
ALTER TABLE `pelipatan`
  MODIFY `id_pelipatan` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT untuk tabel `pencucian`
--
ALTER TABLE `pencucian`
  MODIFY `id_cuci` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=277;

--
-- AUTO_INCREMENT untuk tabel `pengeringan`
--
ALTER TABLE `pengeringan`
  MODIFY `id_pengeringan` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=222;

--
-- AUTO_INCREMENT untuk tabel `penggunaan_bahan`
--
ALTER TABLE `penggunaan_bahan`
  MODIFY `id_penggunaan` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `pengiriman`
--
ALTER TABLE `pengiriman`
  MODIFY `id_pengiriman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `penyetrikaan`
--
ALTER TABLE `penyetrikaan`
  MODIFY `id_penyetrikaan` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT untuk tabel `ruangan`
--
ALTER TABLE `ruangan`
  MODIFY `id_ruangan` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT untuk tabel `ruangan_barang`
--
ALTER TABLE `ruangan_barang`
  MODIFY `id_ruangan_barang` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT untuk tabel `rusak`
--
ALTER TABLE `rusak`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `timbangan`
--
ALTER TABLE `timbangan`
  MODIFY `id_timbangan` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=349;

--
-- AUTO_INCREMENT untuk tabel `timbangan_barang`
--
ALTER TABLE `timbangan_barang`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=206;

--
-- AUTO_INCREMENT untuk tabel `timbangan_bersih`
--
ALTER TABLE `timbangan_bersih`
  MODIFY `id_timbangan_bersih` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `mesin_bahan`
--
ALTER TABLE `mesin_bahan`
  ADD CONSTRAINT `fk_mesin_bahan_bahan` FOREIGN KEY (`id_bahan`) REFERENCES `bahan` (`id_bahan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_mesin_bahan_mesin` FOREIGN KEY (`id_mesin`) REFERENCES `mesin_cuci` (`id_mesin`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `penggunaan_bahan`
--
ALTER TABLE `penggunaan_bahan`
  ADD CONSTRAINT `fk_timbangan` FOREIGN KEY (`id_timbangan`) REFERENCES `timbangan` (`id_timbangan`),
  ADD CONSTRAINT `penggunaan_bahan_ibfk_1` FOREIGN KEY (`id_bahan`) REFERENCES `bahan` (`id_bahan`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pengiriman`
--
ALTER TABLE `pengiriman`
  ADD CONSTRAINT `pengiriman_ibfk_1` FOREIGN KEY (`id_timbangan_bersih`) REFERENCES `timbangan_bersih` (`id_timbangan_bersih`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `ruangan_barang`
--
ALTER TABLE `ruangan_barang`
  ADD CONSTRAINT `ruangan_barang_ibfk_1` FOREIGN KEY (`id_ruangan`) REFERENCES `ruangan` (`id_ruangan`) ON DELETE CASCADE,
  ADD CONSTRAINT `ruangan_barang_ibfk_2` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `timbangan`
--
ALTER TABLE `timbangan`
  ADD CONSTRAINT `fk_id_ruangan` FOREIGN KEY (`id_ruangan`) REFERENCES `ruangan` (`id_ruangan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `timbangan_barang`
--
ALTER TABLE `timbangan_barang`
  ADD CONSTRAINT `fk_timbangan_barang_barang` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `timbangan_barang_ibfk_1` FOREIGN KEY (`id_timbangan`) REFERENCES `timbangan` (`id_timbangan`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
