-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 16, 2025 at 07:14 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `petshop`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking_grooming`
--

CREATE TABLE `booking_grooming` (
  `id_booking` int(11) NOT NULL,
  `id_pelanggan` int(11) DEFAULT NULL,
  `id_hewan` int(11) DEFAULT NULL,
  `id_jadwal` int(11) DEFAULT NULL,
  `tanggal` date NOT NULL,
  `status` enum('Menunggu','Menunggu Pembayaran','Menunggu Konfirmasi','Dikonfirmasi','Selesai','Dibatalkan') DEFAULT 'Menunggu',
  `total_harga` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `konfirmasi_kedatangan` enum('Belum Dikonfirmasi','Akan Datang','Batal Datang') DEFAULT 'Belum Dikonfirmasi'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_grooming`
--

INSERT INTO `booking_grooming` (`id_booking`, `id_pelanggan`, `id_hewan`, `id_jadwal`, `tanggal`, `status`, `total_harga`, `created_at`, `konfirmasi_kedatangan`) VALUES
(4, 1, 2, 5, '2025-04-15', 'Selesai', 85000.00, '2025-04-11 12:15:12', 'Belum Dikonfirmasi'),
(10, 1, 2, 4, '2025-04-24', 'Menunggu', 70000.00, '2025-04-13 07:09:02', 'Belum Dikonfirmasi'),
(11, 1, 2, 2, '2025-04-18', 'Menunggu Pembayaran', 50000.00, '2025-04-13 07:48:20', 'Belum Dikonfirmasi'),
(12, 1, 1, 5, '2025-04-22', 'Menunggu Pembayaran', 40000.00, '2025-04-13 07:52:27', 'Belum Dikonfirmasi'),
(13, 1, 1, 3, '2025-04-14', 'Dibatalkan', 80000.00, '2025-04-13 07:55:56', 'Batal Datang'),
(14, 1, 2, 3, '2025-04-29', 'Menunggu Konfirmasi', 15000.00, '2025-04-13 07:59:37', 'Belum Dikonfirmasi'),
(15, 1, 1, 4, '2025-04-23', 'Menunggu Pembayaran', 60000.00, '2025-04-18 06:52:17', 'Belum Dikonfirmasi'),
(16, 1, 1, 5, '2025-04-18', 'Dibatalkan', 105000.00, '2025-04-18 06:56:12', 'Batal Datang'),
(17, 1, 2, 5, '2025-04-18', 'Selesai', 60000.00, '2025-04-18 07:16:52', 'Akan Datang');

-- --------------------------------------------------------

--
-- Table structure for table `detail_grooming`
--

CREATE TABLE `detail_grooming` (
  `id_detail` int(11) NOT NULL,
  `id_booking` int(11) NOT NULL,
  `id_layanan` int(11) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_grooming`
--

INSERT INTO `detail_grooming` (`id_detail`, `id_booking`, `id_layanan`, `created_at`) VALUES
(3, 4, 3, '2025-04-11 19:15:12'),
(4, 4, 5, '2025-04-11 19:15:12'),
(5, 4, 4, '2025-04-11 19:15:12'),
(18, 10, 3, '2025-04-13 14:09:02'),
(19, 10, 4, '2025-04-13 14:09:02'),
(20, 11, 4, '2025-04-13 14:48:20'),
(21, 12, 2, '2025-04-13 14:52:27'),
(22, 13, 1, '2025-04-13 14:55:56'),
(23, 14, 5, '2025-04-13 14:59:37'),
(24, 15, 3, '2025-04-18 13:52:17'),
(25, 15, 2, '2025-04-18 13:52:17'),
(26, 16, 2, '2025-04-18 13:56:12'),
(27, 16, 5, '2025-04-18 13:56:12'),
(28, 16, 4, '2025-04-18 13:56:13'),
(29, 17, 2, '2025-04-18 14:16:52'),
(30, 17, 3, '2025-04-18 14:16:52');

-- --------------------------------------------------------

--
-- Table structure for table `detail_pesanan`
--

CREATE TABLE `detail_pesanan` (
  `id_detail` int(11) NOT NULL,
  `id_pesanan` int(11) DEFAULT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `jumlah` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_pesanan`
--

INSERT INTO `detail_pesanan` (`id_detail`, `id_pesanan`, `id_produk`, `jumlah`, `subtotal`) VALUES
(15, 9, 2, 2, 380000.00),
(16, 10, 2, 1, 190000.00),
(17, 10, 3, 1, 885000.00),
(18, 11, 1, 2, 250000.00),
(19, 11, 4, 1, 30000.00),
(30, 17, 2, 1, 190000.00),
(31, 17, 4, 2, 60000.00),
(32, 18, 1, 1, 125000.00),
(33, 18, 4, 1, 30000.00),
(35, 20, 1, 2, 250000.00),
(36, 21, 1, 1, 125000.00),
(37, 21, 4, 1, 30000.00),
(38, 22, 3, 1, 885000.00),
(39, 23, 3, 1, 885000.00),
(40, 24, 3, 1, 885000.00),
(42, 26, 1, 1, 125000.00),
(43, 27, 8, 1, 45000.00),
(44, 27, 5, 1, 65000.00),
(45, 27, 10, 1, 43500.00),
(48, 29, 10, 1, 43500.00),
(49, 30, 5, 1, 65000.00),
(50, 30, 1, 2, 250000.00),
(51, 30, 10, 1, 43500.00),
(52, 31, 8, 1, 45000.00),
(53, 32, 3, 1, 885000.00),
(54, 33, 8, 1, 45000.00),
(55, 34, 6, 1, 70000.00),
(56, 35, 8, 1, 45000.00),
(57, 35, 10, 1, 43500.00);

-- --------------------------------------------------------

--
-- Table structure for table `hewan_pelanggan`
--

CREATE TABLE `hewan_pelanggan` (
  `id_hewan` int(11) NOT NULL,
  `id_pelanggan` int(11) DEFAULT NULL,
  `nama_hewan` varchar(255) NOT NULL,
  `jenis` enum('Kucing','Anjing') NOT NULL,
  `ras` varchar(255) DEFAULT NULL,
  `usia` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hewan_pelanggan`
--

INSERT INTO `hewan_pelanggan` (`id_hewan`, `id_pelanggan`, `nama_hewan`, `jenis`, `ras`, `usia`, `created_at`, `updated_at`) VALUES
(1, 1, 'Mochi', 'Anjing', 'Golden Retriever', '8 Bulan', '2025-04-10 03:08:04', '2025-04-10 03:08:46'),
(2, 1, 'Lucy', 'Kucing', 'Persian', '4 Bulan', '2025-04-11 12:12:31', '2025-04-11 12:12:31');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_grooming`
--

CREATE TABLE `jadwal_grooming` (
  `id_jadwal` int(11) NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu') NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `status` enum('Tersedia','Tidak Tersedia') NOT NULL DEFAULT 'Tersedia',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `statusr` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadwal_grooming`
--

INSERT INTO `jadwal_grooming` (`id_jadwal`, `hari`, `jam_mulai`, `jam_selesai`, `status`, `created_at`, `updated_at`, `statusr`) VALUES
(2, 'Jumat', '09:30:00', '10:00:00', 'Tersedia', '2025-04-09 07:22:43', '2025-04-09 10:19:17', 0),
(3, 'Rabu', '17:30:00', '18:30:00', 'Tersedia', '2025-04-11 12:13:42', '2025-04-16 09:30:50', 0),
(4, 'Rabu', '14:00:00', '15:15:00', 'Tersedia', '2025-04-11 12:14:16', '2025-04-16 10:12:49', 0),
(5, 'Jumat', '15:00:00', '16:30:00', 'Tersedia', '2025-04-11 12:14:39', '2025-04-18 07:07:56', 0);

-- --------------------------------------------------------

--
-- Table structure for table `keranjang`
--

CREATE TABLE `keranjang` (
  `id_keranjang` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `id_layanan` int(11) DEFAULT NULL,
  `jumlah` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `keranjang`
--

INSERT INTO `keranjang` (`id_keranjang`, `id_user`, `id_produk`, `id_layanan`, `jumlah`, `created_at`, `updated_at`) VALUES
(63, 4, NULL, 2, 1, '2025-04-18 06:53:03', '2025-04-18 06:53:03'),
(64, 4, NULL, 4, 1, '2025-04-18 06:53:05', '2025-04-18 06:53:05');

-- --------------------------------------------------------

--
-- Table structure for table `layanan_grooming`
--

CREATE TABLE `layanan_grooming` (
  `id_layanan` int(11) NOT NULL,
  `id_petugas` int(11) NOT NULL,
  `nama_layanan` varchar(255) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `durasi` int(11) NOT NULL COMMENT 'Dalam menit',
  `deskripsi` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `layanan_grooming`
--

INSERT INTO `layanan_grooming` (`id_layanan`, `id_petugas`, `nama_layanan`, `harga`, `durasi`, `deskripsi`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Grooming Lengkap', 80000.00, 90, 'Mandi, cukur bulu, potong kuku, bersihkan telinga', 0, '2025-04-08 19:57:12', NULL, NULL),
(2, 1, 'Mandi ', 40000.00, 30, 'Layanan mandi menggunakan shampo khusus hewan.', 0, '2025-04-08 20:05:45', '2025-04-15 08:26:52', NULL),
(3, 1, 'Potong Kuku', 20000.00, 15, 'Potong kuku anjing atau kucing dengan aman.', 0, '2025-04-08 20:06:46', NULL, NULL),
(4, 1, 'Cukur Bulu', 50000.00, 45, 'Cukur bulu sesuai permintaan.', 0, '2025-04-08 20:07:16', '2025-04-08 20:50:32', NULL),
(5, 1, 'Pembersihan Telinga', 15000.00, 15, 'Pembersihan telinga dari kotoran atau serumen', 0, '2025-04-08 20:08:00', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `log_activity`
--
-- Error reading structure for table petshop.log_activity: #1030 - Got error 194 &quot;Tablespace is missing for a table&quot; from storage engine InnoDB
-- Error reading data for table petshop.log_activity: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `petshop`.`log_activity`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `nama` varchar(255) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `alamat` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id_pelanggan`, `id_user`, `nama`, `no_hp`, `alamat`, `created_at`, `updated_at`) VALUES
(1, 2, 'Jihyo', '0842828372', 'Jalan Raja Haji Fisabilillah, Baloi, Taman Baloi, Batam, Riau Islands, Sumatra, 29412, Indonesia', '2025-04-07 02:03:31', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int(11) NOT NULL,
  `id_pesanan` int(11) DEFAULT NULL,
  `id_booking` int(11) DEFAULT NULL,
  `id_transaksi` int(11) DEFAULT NULL,
  `jenis_transaksi` enum('Produk','Grooming') NOT NULL,
  `metode` enum('Transfer Bank','E-Wallet','Cash') NOT NULL,
  `status` enum('Menunggu Konfirmasi','Dikonfirmasi','Ditolak') DEFAULT 'Menunggu Konfirmasi',
  `bukti_pembayaran` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id_pembayaran`, `id_pesanan`, `id_booking`, `id_transaksi`, `jenis_transaksi`, `metode`, `status`, `bukti_pembayaran`, `created_at`) VALUES
(3, NULL, 4, NULL, 'Grooming', 'Transfer Bank', 'Dikonfirmasi', '1744379334_59b48b1ab02598b01a03.jpg', '2025-04-11 13:48:54'),
(7, 9, NULL, NULL, 'Produk', 'E-Wallet', 'Dikonfirmasi', '1744429074_dbbdfc930d8059c3b24f.jpg', '2025-04-12 03:37:54'),
(8, 21, NULL, NULL, 'Produk', 'Transfer Bank', 'Ditolak', '1744534072_453555401007f22eb9a9.jpg', '2025-04-13 08:47:52'),
(9, 20, NULL, NULL, 'Produk', 'E-Wallet', 'Dikonfirmasi', '1744534502_f1375abfa91826ce5e72.jpg', '2025-04-13 08:55:02'),
(10, 17, NULL, NULL, 'Produk', 'E-Wallet', 'Menunggu Konfirmasi', '1744534528_2acd1900aeb452bc82c3.jpg', '2025-04-13 08:55:28'),
(11, NULL, 13, NULL, 'Grooming', 'Transfer Bank', 'Dikonfirmasi', '1744597339_20ed22eaafa266ff5980.jpeg', '2025-04-14 02:22:19'),
(12, NULL, 14, NULL, 'Grooming', 'Transfer Bank', 'Menunggu Konfirmasi', '1744597512_987c529cb59a00f06af2.jpeg', '2025-04-14 02:25:12'),
(13, NULL, 14, NULL, 'Grooming', 'Transfer Bank', 'Menunggu Konfirmasi', '1744597620_ac9a292837b36edd3abe.jpeg', '2025-04-14 02:27:00'),
(14, 23, NULL, NULL, 'Produk', 'Transfer Bank', 'Ditolak', '1744634281_f90fcb53c55a65f81519.png', '2025-04-14 12:38:01'),
(15, 24, NULL, NULL, 'Produk', 'Transfer Bank', 'Menunggu Konfirmasi', '1744634709_5cadcd43a1ff0d8c35da.png', '2025-04-14 12:45:09'),
(16, 26, NULL, NULL, 'Produk', 'Transfer Bank', 'Dikonfirmasi', '1744636176_82857dd9cfa4119b708f.png', '2025-04-14 13:09:36'),
(17, 27, NULL, NULL, 'Produk', 'Transfer Bank', 'Dikonfirmasi', '1744680524_4e3671c82fab033fed91.jpeg', '2025-04-15 01:28:44'),
(18, 28, NULL, NULL, 'Produk', 'Transfer Bank', 'Menunggu Konfirmasi', '1744712323_018a585b93e8aee5970b.jpeg', '2025-04-15 10:18:43'),
(19, 29, NULL, NULL, 'Produk', 'Transfer Bank', 'Menunggu Konfirmasi', '1744712597_4a6f55e716d657ef5ddb.jpeg', '2025-04-15 10:23:17'),
(20, 31, NULL, NULL, 'Produk', 'Transfer Bank', 'Ditolak', '1744812564_63f7df4abcea27a28c98.jpg', '2025-04-16 14:09:24'),
(21, 32, NULL, NULL, 'Produk', 'Cash', '', NULL, '2025-04-17 02:19:00'),
(22, 33, NULL, NULL, 'Produk', 'Transfer Bank', 'Dikonfirmasi', '1744957849_360c430df8f3e6a24c5e.jpeg', '2025-04-18 06:30:49'),
(23, 34, NULL, NULL, 'Produk', 'Transfer Bank', 'Dikonfirmasi', '1744958795_b96f6223c8e5cc0456db.jpeg', '2025-04-18 06:46:35'),
(24, NULL, 16, NULL, 'Grooming', 'Cash', 'Dikonfirmasi', NULL, '2025-04-18 06:57:46'),
(25, 35, NULL, NULL, 'Produk', 'Transfer Bank', 'Dikonfirmasi', '1744960545_db2dcfb4f78e40a46eb3.jpeg', '2025-04-18 07:15:45'),
(26, NULL, 17, NULL, 'Grooming', 'Cash', 'Dikonfirmasi', NULL, '2025-04-18 07:22:26');

-- --------------------------------------------------------

--
-- Table structure for table `pengiriman`
--

CREATE TABLE `pengiriman` (
  `id_pengiriman` int(11) NOT NULL,
  `id_pesanan` int(11) DEFAULT NULL,
  `biaya` decimal(10,2) DEFAULT NULL,
  `status` enum('Diproses','Dikemas','Dalam Proses Pengiriman','Sampai','Selesai') DEFAULT NULL,
  `tanggal_status` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengiriman`
--

INSERT INTO `pengiriman` (`id_pengiriman`, `id_pesanan`, `biaya`, `status`, `tanggal_status`) VALUES
(1, 18, 10000.00, 'Diproses', '2025-04-14 07:40:01'),
(3, 9, 10000.00, 'Selesai', '2025-04-14 14:01:05'),
(4, 20, 25000.00, 'Dikemas', '2025-04-14 14:13:59'),
(5, 26, 13750.00, 'Selesai', '2025-04-14 20:51:24'),
(6, 27, 16885.00, 'Selesai', '2025-04-15 08:33:20'),
(7, 33, 5500.00, 'Diproses', '2025-04-18 13:34:38'),
(8, 34, 8000.00, 'Selesai', '2025-04-18 13:49:35'),
(9, 35, 9850.00, 'Selesai', '2025-04-18 14:23:39');

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id_pesanan` int(11) NOT NULL,
  `id_pelanggan` int(11) DEFAULT NULL,
  `total_harga` decimal(10,2) NOT NULL,
  `status` enum('Menunggu Pembayaran','Menunggu Konfirmasi','Dikonfirmasi','Selesai','Dibatalkan') DEFAULT 'Menunggu Pembayaran',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `jenis_pengiriman` enum('Pick Up','Pengiriman') NOT NULL,
  `biaya_pengiriman` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`id_pesanan`, `id_pelanggan`, `total_harga`, `status`, `created_at`, `updated_at`, `jenis_pengiriman`, `biaya_pengiriman`) VALUES
(9, 1, 380000.00, 'Selesai', '2025-04-09 05:45:33', '2025-04-14 11:48:48', 'Pengiriman', 0),
(10, 1, 1075000.00, 'Menunggu Pembayaran', '2025-04-09 05:45:51', '2025-04-14 11:48:53', 'Pengiriman', 0),
(11, 1, 280000.00, 'Menunggu Pembayaran', '2025-04-09 06:11:02', '2025-04-14 11:48:57', 'Pengiriman', 0),
(17, 1, 250000.00, 'Menunggu Konfirmasi', '2025-04-13 03:54:41', '2025-04-14 11:49:19', 'Pick Up', 0),
(18, 1, 155000.00, 'Menunggu Pembayaran', '2025-04-13 03:59:29', '2025-04-14 11:49:03', 'Pengiriman', 0),
(20, 1, 250000.00, 'Selesai', '2025-04-13 07:37:30', '2025-04-14 11:49:07', 'Pengiriman', 0),
(21, 1, 155000.00, 'Dibatalkan', '2025-04-13 08:00:13', '2025-04-14 11:49:10', 'Pengiriman', 0),
(22, 1, 885000.00, 'Menunggu Pembayaran', '2025-04-14 12:28:16', '2025-04-14 12:28:16', 'Pengiriman', 0),
(23, 1, 973500.00, 'Dibatalkan', '2025-04-14 12:38:01', '2025-04-14 12:44:21', 'Pengiriman', 88500),
(24, 1, 973500.00, 'Menunggu Konfirmasi', '2025-04-14 12:45:09', '2025-04-14 12:45:09', 'Pengiriman', 88500),
(26, 1, 137500.00, 'Selesai', '2025-04-14 13:09:36', '2025-04-14 13:51:18', 'Pengiriman', 12500),
(27, 1, 168850.00, 'Selesai', '2025-04-15 01:28:44', '2025-04-15 01:33:17', 'Pengiriman', 15350),
(29, 1, 53500.00, 'Menunggu Konfirmasi', '2025-04-15 10:23:17', '2025-04-15 10:23:17', 'Pengiriman', 10000),
(30, 1, 358500.00, 'Menunggu Pembayaran', '2025-04-16 10:10:50', '2025-04-16 10:10:50', 'Pick Up', 0),
(31, 1, 55000.00, 'Dibatalkan', '2025-04-16 14:09:24', '2025-04-17 02:20:41', 'Pengiriman', 10000),
(32, 1, 895000.00, 'Menunggu Konfirmasi', '2025-04-17 02:19:00', '2025-04-17 02:19:00', '', 10000),
(33, 1, 55000.00, 'Selesai', '2025-04-18 06:30:49', '2025-04-18 06:33:57', 'Pengiriman', 10000),
(34, 1, 80000.00, 'Selesai', '2025-04-18 06:46:35', '2025-04-18 06:49:02', 'Pengiriman', 10000),
(35, 1, 98500.00, 'Selesai', '2025-04-18 07:15:45', '2025-04-18 07:23:22', 'Pengiriman', 10000);

-- --------------------------------------------------------

--
-- Table structure for table `petugas`
--

CREATE TABLE `petugas` (
  `id_petugas` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `nama` varchar(50) NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `alamat` text NOT NULL,
  `status` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `petugas`
--

INSERT INTO `petugas` (`id_petugas`, `id_user`, `nama`, `jenis_kelamin`, `no_hp`, `alamat`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 4, 'Niel', 'Laki-laki', '082374326424', 'Baloi', 0, '2025-04-07 09:49:09', '2025-04-08 10:57:33', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_produk` int(11) NOT NULL,
  `kode_produk` varchar(50) NOT NULL,
  `nama_produk` varchar(255) NOT NULL,
  `kategori` enum('Makanan','Aksesoris','Kesehatan','Mainan') NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `stok` int(11) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id_produk`, `kode_produk`, `nama_produk`, `kategori`, `harga`, `stok`, `deskripsi`, `gambar`, `status`, `created_at`, `updated_at`) VALUES
(1, 'FD01', 'Whiskas Kitten', 'Makanan', 125000.00, 73, 'Makanan kering untuk kucing. Umur 2-12 bulan.', '1744091451_14b5107b89ba344b0a0a.jpeg', 0, '2025-04-08 04:52:00', '2025-04-08 09:24:44'),
(2, 'FD02', 'Royal Canin Puppy 2kg', 'Makanan', 190000.00, 40, 'Makanan kering untuk anjing usia 2-10 bulan. ', '1744088610_cb65c03cba87afab8f84.jpeg', 0, '2025-04-08 05:03:30', '2025-04-08 07:46:21'),
(3, 'FD03', 'Royal Canin Adult', 'Makanan', 885000.00, 35, 'Makanan kering untuk anjing usia dewasa.', '1744091605_e51a86aacd013be9b67d.jpeg', 0, '2025-04-08 05:38:00', '2025-04-12 08:00:21'),
(4, 'PY01', 'Pressed Bone', 'Mainan', 30000.00, 210, 'Mainan karet untuk peliharaan anda.', '1744177913_2afa26215120aecf9400.jpeg', 0, '2025-04-09 05:51:54', '2025-04-09 05:51:54'),
(5, 'AK01', 'Collar Bone Charm', 'Aksesoris', 65000.00, 25, 'Kalung leher untuk hewan peliharaan anda.', '1744677676_c2fa3b57d72e334ffc56.jpeg', 0, '2025-04-15 00:41:16', '2025-04-15 00:41:32'),
(6, 'AK02', 'Sailor Moon Collar', 'Aksesoris', 70000.00, 15, 'Kalung leher ber seri spesial untuk hewan peliharaan anda.', '1744677764_06aca7860b501cd00dc9.jpeg', 0, '2025-04-15 00:42:44', '2025-04-18 07:28:02'),
(7, 'AK03', 'Handuk Motif', 'Aksesoris', 40000.00, 596, 'Handuk bermotif, dari bahan katun. Tersedia dalam berbagai pilihan warna.', '1744678426_1206efa078c12ee9baa3.jpeg', 0, '2025-04-15 00:53:46', '2025-04-15 00:53:46'),
(8, 'KS01', 'Paw Balm Always', 'Kesehatan', 45000.00, 120, 'Pelembab khusus hewan yang mengandung organic shea butter.', '1744679062_a81d32e95f43586fde42.jpeg', 0, '2025-04-15 01:04:22', '2025-04-15 01:04:22'),
(9, 'KS02', 'Puppy Pads ', 'Kesehatan', 38500.00, 477, 'Alas pipis untuk hewan, ukuran 17x24 inch.', '1744680315_687ad33e8d53ece1c30d.jpeg', 0, '2025-04-15 01:25:15', '2025-04-15 01:25:15'),
(10, 'KS03', 'Kit Potong Kuku ', 'Kesehatan', 43500.00, 220, 'Kit potong kuku hewan.', '1744680401_e5fa9c4902e408555c08.jpeg', 0, '2025-04-15 01:26:41', '2025-04-15 01:26:41'),
(11, 'KS04', 'Alas Pup', 'Kesehatan', 40000.00, 34, 'Bgus bgt', '1744961256_726d219c674712a61e5b.jpeg', 0, '2025-04-18 07:27:36', '2025-04-18 07:27:36');

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE `setting` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `foto` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`id`, `nama`, `foto`) VALUES
(1, 'Toko Perlengkapan Hewan', '1747224349_d9cd1eafba5b5d813166.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `level` enum('1','2','3','4') NOT NULL,
  `foto` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `nama`, `email`, `password`, `level`, `foto`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'admi@n', 'admin', 'admi@n', 'c4ca4238a0b923820dcc509a6f75849b', '1', '1744798632_64527fea2c7314006ba3.jpeg', 0, '2025-04-06 07:42:52', '2025-04-16 17:17:12', NULL),
(2, 'jihy@o', 'Jihyow', 'jihy@o', 'c4ca4238a0b923820dcc509a6f75849b', '2', '1744547640_3662450e04ebf97a26b5.jpeg', 0, '2025-04-07 02:03:31', '2025-04-16 10:03:44', NULL),
(3, 's.adm@1', 'superadm', 's.adm@1', 'c4ca4238a0b923820dcc509a6f75849b', '4', '', 0, '2025-04-07 02:08:01', '2025-04-13 19:26:52', NULL),
(4, 'niel@og', 'Niel', 'niel@og', 'b59c67bf196a4758191e42f76670ceba', '3', '', 0, '2025-04-07 02:49:09', '2025-04-13 19:26:57', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking_grooming`
--
ALTER TABLE `booking_grooming`
  ADD PRIMARY KEY (`id_booking`),
  ADD KEY `id_pelanggan` (`id_pelanggan`),
  ADD KEY `id_hewan` (`id_hewan`);

--
-- Indexes for table `detail_grooming`
--
ALTER TABLE `detail_grooming`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_booking` (`id_booking`),
  ADD KEY `id_layanan` (`id_layanan`);

--
-- Indexes for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_pesanan` (`id_pesanan`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indexes for table `hewan_pelanggan`
--
ALTER TABLE `hewan_pelanggan`
  ADD PRIMARY KEY (`id_hewan`);

--
-- Indexes for table `jadwal_grooming`
--
ALTER TABLE `jadwal_grooming`
  ADD PRIMARY KEY (`id_jadwal`);

--
-- Indexes for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`id_keranjang`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_produk` (`id_produk`),
  ADD KEY `keranjang_ibfk_3` (`id_layanan`);

--
-- Indexes for table `layanan_grooming`
--
ALTER TABLE `layanan_grooming`
  ADD PRIMARY KEY (`id_layanan`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `id_pesanan` (`id_transaksi`);

--
-- Indexes for table `pengiriman`
--
ALTER TABLE `pengiriman`
  ADD PRIMARY KEY (`id_pengiriman`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id_pesanan`),
  ADD KEY `id_pelanggan` (`id_pelanggan`);

--
-- Indexes for table `petugas`
--
ALTER TABLE `petugas`
  ADD PRIMARY KEY (`id_petugas`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`);

--
-- Indexes for table `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking_grooming`
--
ALTER TABLE `booking_grooming`
  MODIFY `id_booking` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `detail_grooming`
--
ALTER TABLE `detail_grooming`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `hewan_pelanggan`
--
ALTER TABLE `hewan_pelanggan`
  MODIFY `id_hewan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `jadwal_grooming`
--
ALTER TABLE `jadwal_grooming`
  MODIFY `id_jadwal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `id_keranjang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `layanan_grooming`
--
ALTER TABLE `layanan_grooming`
  MODIFY `id_layanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelanggan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `pengiriman`
--
ALTER TABLE `pengiriman`
  MODIFY `id_pengiriman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id_pesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `petugas`
--
ALTER TABLE `petugas`
  MODIFY `id_petugas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `setting`
--
ALTER TABLE `setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking_grooming`
--
ALTER TABLE `booking_grooming`
  ADD CONSTRAINT `booking_grooming_ibfk_1` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_grooming_ibfk_2` FOREIGN KEY (`id_hewan`) REFERENCES `hewan_pelanggan` (`id_hewan`) ON DELETE CASCADE;

--
-- Constraints for table `detail_grooming`
--
ALTER TABLE `detail_grooming`
  ADD CONSTRAINT `detail_grooming_ibfk_1` FOREIGN KEY (`id_booking`) REFERENCES `booking_grooming` (`id_booking`),
  ADD CONSTRAINT `detail_grooming_ibfk_2` FOREIGN KEY (`id_layanan`) REFERENCES `layanan_grooming` (`id_layanan`);

--
-- Constraints for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD CONSTRAINT `detail_pesanan_ibfk_1` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_pesanan_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE;

--
-- Constraints for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD CONSTRAINT `keranjang_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `keranjang_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`),
  ADD CONSTRAINT `keranjang_ibfk_3` FOREIGN KEY (`id_layanan`) REFERENCES `layanan_grooming` (`id_layanan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE;

--
-- Constraints for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`) ON DELETE CASCADE;

--
-- Constraints for table `petugas`
--
ALTER TABLE `petugas`
  ADD CONSTRAINT `petugas_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
