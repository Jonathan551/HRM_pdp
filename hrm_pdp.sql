-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 20, 2025 at 05:03 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hrm_pdp`
--

-- --------------------------------------------------------

--
-- Table structure for table `banding_penilaian`
--

CREATE TABLE `banding_penilaian` (
  `id_banding` int(11) NOT NULL,
  `id_penilaian` int(11) DEFAULT NULL,
  `id_users` int(11) DEFAULT NULL,
  `status` enum('Review','Diterima','Ditolak','') DEFAULT 'Review',
  `tanggal_banding` datetime DEFAULT NULL,
  `alasan` text DEFAULT NULL,
  `review` text DEFAULT NULL,
  `tanggal_review` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `banding_penilaian`
--

INSERT INTO `banding_penilaian` (`id_banding`, `id_penilaian`, `id_users`, `status`, `tanggal_banding`, `alasan`, `review`, `tanggal_review`) VALUES
(8, 19, 1, 'Diterima', '2025-08-20 09:40:58', 'Test', 'Test', '2025-08-20 10:47:06'),
(9, 20, 1, 'Review', '2025-08-20 09:42:46', 'Test', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `detail_penilaian`
--

CREATE TABLE `detail_penilaian` (
  `id_detailpenilaian` int(11) NOT NULL,
  `id_penilaian` int(11) DEFAULT NULL,
  `id_kriteria` int(11) DEFAULT NULL,
  `id_anchor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_penilaian`
--

INSERT INTO `detail_penilaian` (`id_detailpenilaian`, `id_penilaian`, `id_kriteria`, `id_anchor`) VALUES
(1, 2, 3, 7),
(2, 2, 2, 5),
(3, 3, 1, 1),
(4, 3, 2, 6),
(5, 3, 3, 8),
(6, 2, 1, 3),
(7, 4, 1, 3),
(8, 4, 2, 6),
(9, 4, 3, 9),
(10, 5, 1, 2),
(11, 5, 2, 4),
(12, 5, 3, 7),
(13, 6, 1, 2),
(14, 6, 3, 7),
(15, 6, 2, 4),
(16, 7, 1, 2),
(17, 7, 2, 4),
(18, 7, 3, 7),
(19, 8, 1, 1),
(20, 8, 2, 4),
(21, 8, 3, 7),
(22, 9, 1, 1),
(23, 9, 2, 4),
(24, 9, 3, 7),
(25, 10, 1, 1),
(26, 10, 2, 4),
(27, 10, 3, 7),
(28, 11, 1, 1),
(29, 11, 2, 4),
(30, 11, 3, 7),
(32, 12, 2, 6),
(33, 12, 2, 5),
(34, 12, 3, 9),
(35, 13, 1, 3),
(36, 13, 2, 6),
(37, 13, 3, 9),
(38, 14, 1, 3),
(39, 14, 2, 4),
(40, 14, 3, 8),
(41, 15, 1, 3),
(42, 15, 2, 5),
(43, 15, 3, 9),
(44, 16, 1, 1),
(45, 16, 2, 6),
(46, 16, 3, 9),
(47, 17, 1, 1),
(48, 17, 2, 4),
(49, 17, 3, 7),
(50, 18, 1, 1),
(51, 18, 3, 9),
(52, 18, 2, 6),
(53, 19, 1, 2),
(54, 19, 3, 9),
(55, 19, 2, 6),
(56, 20, 2, 5),
(57, 20, 1, 3),
(58, 20, 3, 8),
(59, 21, 1, 2),
(60, 21, 2, 6),
(61, 21, 3, 9),
(62, 22, 1, 1),
(63, 22, 2, 5),
(64, 22, 3, 7),
(65, 23, 1, 2),
(66, 23, 2, 6),
(67, 23, 3, 9),
(68, 24, 2, 5),
(69, 24, 1, 1),
(70, 24, 3, 7),
(71, 25, 1, 2),
(72, 25, 3, 9),
(73, 25, 2, 6),
(74, 26, 1, 1),
(75, 26, 2, 6),
(76, 26, 3, 7),
(77, 27, 2, 4),
(78, 27, 1, 3),
(79, 27, 3, 7),
(80, 28, 2, 4),
(81, 28, 1, 3),
(82, 28, 3, 8),
(83, 29, 2, 5),
(84, 29, 1, 2),
(85, 29, 3, 8),
(86, 30, 2, 4),
(87, 30, 1, 2),
(88, 30, 3, 9),
(89, 31, 1, 2),
(90, 31, 3, 9),
(91, 32, 2, 4),
(92, 32, 1, 2),
(93, 32, 3, 9),
(97, 34, 1, 2),
(98, 34, 2, 4),
(99, 34, 3, 7),
(100, 35, 1, 1),
(101, 35, 2, 5),
(102, 35, 3, 9),
(103, 36, 1, 1),
(104, 36, 2, 6),
(105, 36, 3, 8),
(106, 36, 4, 11);

-- --------------------------------------------------------

--
-- Table structure for table `master_anchor`
--

CREATE TABLE `master_anchor` (
  `id_anchor` int(11) NOT NULL,
  `id_kriteria` int(11) DEFAULT NULL,
  `level_anchor` int(11) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `nilai_anchor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `master_anchor`
--

INSERT INTO `master_anchor` (`id_anchor`, `id_kriteria`, `level_anchor`, `deskripsi`, `nilai_anchor`) VALUES
(1, 1, 1, 'Dapat Berkomunikasi Dengan Baik', 1),
(2, 1, 2, 'Mampu Melakukan Komunikasi dengan sangat Baik', 2),
(3, 1, 3, 'Mampu Dengan Sangat Baik dan Perfect Dalam Berkomunikasi', 3),
(4, 2, 1, 'Bisa Bekerja Sama Dengan Baik', 1),
(5, 2, 2, 'Bisa Bekerja Sama Dengan Sangat Baik', 2),
(6, 2, 3, 'Bisa Bekerja Sama Dengan Sangat Baik', 3),
(7, 3, 1, 'Disiplin Normal dan baik', 1),
(8, 3, 2, 'Memiliki Disiplin Extra', 2),
(9, 3, 3, 'Rajin Dan Disiplin dan taat pada aturan', 3),
(11, 4, 1, 'Test', 3);

-- --------------------------------------------------------

--
-- Table structure for table `master_departement`
--

CREATE TABLE `master_departement` (
  `id_departement` int(11) NOT NULL,
  `nama_departement` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `master_departement`
--

INSERT INTO `master_departement` (`id_departement`, `nama_departement`, `deskripsi`) VALUES
(1, 'Produksi', 'Test');

-- --------------------------------------------------------

--
-- Table structure for table `master_event`
--

CREATE TABLE `master_event` (
  `id_event` int(11) NOT NULL,
  `id_users` int(11) DEFAULT NULL,
  `judul` varchar(150) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `jenis_event` varchar(50) DEFAULT NULL,
  `severity` enum('low','medium','high','critical') DEFAULT 'low',
  `lokasi` varchar(100) DEFAULT NULL,
  `id_departement` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `status` enum('open','review','closed') DEFAULT 'open',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `master_event`
--

INSERT INTO `master_event` (`id_event`, `id_users`, `judul`, `deskripsi`, `gambar`, `tanggal`, `jenis_event`, `severity`, `lokasi`, `id_departement`, `created_by`, `status`, `updated_at`) VALUES
(5, 1, 'test', 'test', '68a27e269f538.jpeg', '2025-08-18 00:00:00', '6', 'medium', 'Pabrik', 1, 1, 'open', '2025-08-18 01:13:10'),
(6, 7, 'test', 'test', '68a28841f210e.jpeg', '2025-08-30 00:00:00', 'test', 'critical', 'Pabrik', 1, 1, 'open', '2025-08-18 01:56:17'),
(7, 1, 'Kerusakan Mesin', 'Mesin Rusak', '68a28acae2eee.jpeg', '2025-08-19 00:00:00', 'Kerusakan', 'critical', 'Pabrik', 1, 1, 'open', '2025-08-18 02:07:06'),
(8, 11, 'Kerusakan Mesin', 'Test', '68a2a750e6dc3.jpeg', '2025-08-18 00:00:00', 'Kecelakaan', 'medium', 'Pabrik', 1, 1, 'open', '2025-08-18 04:08:48');

-- --------------------------------------------------------

--
-- Table structure for table `master_jabatan`
--

CREATE TABLE `master_jabatan` (
  `id_jabatan` int(11) NOT NULL,
  `nama_jabatan` varchar(100) NOT NULL,
  `level_jabatan` int(11) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `master_jabatan`
--

INSERT INTO `master_jabatan` (`id_jabatan`, `nama_jabatan`, `level_jabatan`, `deskripsi`) VALUES
(1, 'Karyawan', 1, 'Test'),
(2, 'Super_Admin', 6, 'Test'),
(3, 'Supervisor', 2, 'Test');

-- --------------------------------------------------------

--
-- Table structure for table `master_kategori`
--

CREATE TABLE `master_kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(255) NOT NULL,
  `nilai_min` decimal(5,3) NOT NULL,
  `nilai_max` decimal(5,3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `master_kategori`
--

INSERT INTO `master_kategori` (`id_kategori`, `nama_kategori`, `nilai_min`, `nilai_max`) VALUES
(1, 'Cukup', 0.100, 0.500),
(2, 'Bagus', 1.200, 2.000),
(3, 'Sangat Bagus', 2.000, 3.000);

-- --------------------------------------------------------

--
-- Table structure for table `master_kriteria`
--

CREATE TABLE `master_kriteria` (
  `id_kriteria` int(11) NOT NULL,
  `id_departement` int(11) DEFAULT NULL,
  `nama_kriteria` varchar(100) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `bobot` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `master_kriteria`
--

INSERT INTO `master_kriteria` (`id_kriteria`, `id_departement`, `nama_kriteria`, `deskripsi`, `bobot`) VALUES
(1, 1, 'Komunikasi', 'Bagian Produksi Bisa Menjelaskan Produk yang dibuat pada atasan dan mengkomunikasikan Hasil Produksi Haria', 3),
(2, 1, 'Kerja Sama', 'Mengatur Kerja Sama dalam kegiatan produksi', 5),
(3, 1, 'Disiplin', 'Menunjukan Kedisiplinan User', 7),
(4, 1, 'Tanggung Jawab', 'test', 9);

-- --------------------------------------------------------

--
-- Table structure for table `master_penilaian`
--

CREATE TABLE `master_penilaian` (
  `id_penilaian` int(11) NOT NULL,
  `id_users` int(11) DEFAULT NULL,
  `nilai_akhir` decimal(5,3) DEFAULT NULL,
  `periode_awal` datetime DEFAULT NULL,
  `periode_akhir` datetime DEFAULT NULL,
  `id_kategori` int(11) DEFAULT NULL,
  `presentase_absensi` varchar(50) DEFAULT NULL,
  `catatan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `master_penilaian`
--

INSERT INTO `master_penilaian` (`id_penilaian`, `id_users`, `nilai_akhir`, `periode_awal`, `periode_akhir`, `id_kategori`, `presentase_absensi`, `catatan`) VALUES
(18, 7, 2.600, '2025-08-01 00:00:00', '2025-09-01 00:00:00', 3, '70', 'Tidak Ada'),
(19, 1, 2.800, '2025-08-01 00:00:00', '2025-09-01 00:00:00', 3, '100%', 'Tidak Ada'),
(20, 1, 2.200, '2025-09-01 00:00:00', '2025-10-01 00:00:00', 3, '70%', 'Tidak Ada'),
(21, 1, 2.800, '2025-11-01 00:00:00', '2025-12-01 00:00:00', 3, '100%', 'Tidak Ada'),
(22, 1, 1.333, '2025-12-01 00:00:00', '2026-01-01 00:00:00', 2, '100%', 'Tidak Ada'),
(23, 1, 2.800, '2026-01-01 00:00:00', '2026-02-01 00:00:00', 3, '100', 'Tidak Ada'),
(24, 1, 1.333, '2026-02-01 00:00:00', '2026-03-01 00:00:00', 2, '100', 'Tidak Ada'),
(29, 7, 2.000, '2025-08-06 00:00:00', '2025-08-09 00:00:00', 2, '100', 'Tidak Ada'),
(30, 7, 2.133, '2025-08-06 00:00:00', '2026-04-01 00:00:00', 3, '100', 'Tidak Ada'),
(32, 7, 2.133, '2025-08-01 00:00:00', '2025-09-01 00:00:00', 3, '100', 'Tidak Ada'),
(34, 1, 1.200, '2026-03-01 00:00:00', '2026-04-01 00:00:00', 2, '100', 'Tidak Ada'),
(36, 1, 2.460, '2025-08-01 00:00:00', '2025-09-01 00:00:00', 3, '100', 'Tidak Ada');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id_permission` int(11) NOT NULL,
  `nama_permission` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id_permission`, `nama_permission`, `deskripsi`) VALUES
(1, 'site/index', 'Auto generated'),
(2, 'akses_dashboard', 'Auto generated'),
(3, 'akses_user', 'Auto generated'),
(4, 'akses_jabatan', 'Auto generated'),
(5, 'akses_departement', 'Auto generated'),
(6, 'akses_kriteria', 'Auto generated'),
(7, 'akses_anchor', 'Auto generated'),
(8, 'akses_kategori', 'Auto generated'),
(9, 'akses_penilaian', 'Auto generated'),
(10, 'akses_laporan', 'Auto generated'),
(11, 'akses_event', 'Auto generated'),
(12, 'akses_banding', 'Auto generated'),
(13, 'site/logout', 'Auto generated'),
(18, 'akses_manajemen', 'Auto generated'),
(19, 'akses_admin', 'Auto generated');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `id_jabatan` int(11) NOT NULL,
  `id_permission` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`id_jabatan`, `id_permission`) VALUES
(2, 1),
(2, 2),
(2, 3),
(2, 4),
(2, 5),
(2, 6),
(2, 7),
(2, 8),
(2, 9),
(2, 10),
(2, 11),
(2, 12),
(2, 13),
(2, 18),
(2, 19);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_users` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `id_jabatan` int(11) DEFAULT NULL,
  `id_departement` int(11) DEFAULT NULL,
  `level_jabatan` int(11) DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `tanggal_masuk` datetime DEFAULT NULL,
  `pendidikan_terakhir` varchar(100) DEFAULT NULL,
  `status_karyawan` varchar(50) DEFAULT NULL,
  `lokasi_kerja` varchar(100) DEFAULT NULL,
  `atasan_langsung` varchar(100) DEFAULT NULL,
  `nomor_hp` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('pria','wanita') DEFAULT NULL,
  `golongan` int(11) DEFAULT NULL,
  `penilaian_terakhir` datetime DEFAULT NULL,
  `catatan_khusus` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_users`, `username`, `password_hash`, `id_jabatan`, `id_departement`, `level_jabatan`, `nama`, `tanggal_masuk`, `pendidikan_terakhir`, `status_karyawan`, `lokasi_kerja`, `atasan_langsung`, `nomor_hp`, `email`, `tanggal_lahir`, `jenis_kelamin`, `golongan`, `penilaian_terakhir`, `catatan_khusus`) VALUES
(1, 'Andi', '$2y$10$rYAFY2dsZEQmLfdi70k51OY43eEF6ULYR1F/St.sdeg4JUU36OFbq', 1, 1, 1, 'Andi', '2025-08-11 16:50:29', 'S1 Teknik Elektro Institut Teknologi Surabaya', 'Tetap', 'Pabrik 1', 'Owner', '081252804462', 'andi@gmail.com', '2025-08-11', 'pria', 3, '2025-08-11 16:50:29', 'Tidak Ada'),
(7, 'Robby', '$2y$13$OModWHjGEPW040tqYUvFqOGPqzM3Rv/YHoElqJFWjjvKMiyCK8oLi', 1, 1, 1, 'Doni D', '2025-08-12 00:00:00', 'S2 Universitas Andalas', 'Aktif', 'Office 1', 'Owner', '082144424425', 'Donny@gmail.com', '2025-08-12', 'pria', 1, '2025-08-12 00:00:00', 'Tidak Ada'),
(11, 'Didik', '$2y$10$3JYuM2p4hzhLrr7VGnImXOZ/X6vZW5Z1OgDEC6Ql7XxoYwrHw0iMu', 3, 1, 1, 'Didik', '2025-08-20 00:00:00', 'S3 Universitas Indonesia', 'Aktif', 'Office 1', 'Owner', '082144424425', 'Robby@gmail.com', '2025-08-19', 'pria', 1, '2025-08-18 00:00:00', 'Tidak ada'),
(12, 'Dina', '$2y$10$Qthd4O5wCJJGpciu9gKBYOxOFDKTwS3aKOR6VpGgNtMgZUsjKCgtm', 2, 1, 6, 'Dina .S', '2025-08-20 21:08:42', 'S3 Institut Teknologi Bandung', 'Owner', 'Office Utama', 'Owner', '081252804432', 'Dina@gmail.com', '2025-08-20', 'wanita', 2, '2025-08-21 21:08:42', 'Test');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banding_penilaian`
--
ALTER TABLE `banding_penilaian`
  ADD PRIMARY KEY (`id_banding`),
  ADD KEY `id_penilaian` (`id_penilaian`),
  ADD KEY `id_users` (`id_users`),
  ADD KEY `id_status` (`status`);

--
-- Indexes for table `detail_penilaian`
--
ALTER TABLE `detail_penilaian`
  ADD PRIMARY KEY (`id_detailpenilaian`),
  ADD KEY `id_penilaian` (`id_penilaian`),
  ADD KEY `detail_penilaian_ibfk_2` (`id_kriteria`),
  ADD KEY `detail_penilaian_ibfk_3` (`id_anchor`);

--
-- Indexes for table `master_anchor`
--
ALTER TABLE `master_anchor`
  ADD PRIMARY KEY (`id_anchor`),
  ADD KEY `id_kriteria` (`id_kriteria`);

--
-- Indexes for table `master_departement`
--
ALTER TABLE `master_departement`
  ADD PRIMARY KEY (`id_departement`);

--
-- Indexes for table `master_event`
--
ALTER TABLE `master_event`
  ADD PRIMARY KEY (`id_event`),
  ADD KEY `master_event_ibfk_1` (`id_users`),
  ADD KEY `master_event_ibfk_2` (`id_departement`);

--
-- Indexes for table `master_jabatan`
--
ALTER TABLE `master_jabatan`
  ADD PRIMARY KEY (`id_jabatan`),
  ADD KEY `level_jabatan` (`level_jabatan`);

--
-- Indexes for table `master_kategori`
--
ALTER TABLE `master_kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `master_kriteria`
--
ALTER TABLE `master_kriteria`
  ADD PRIMARY KEY (`id_kriteria`),
  ADD KEY `id_departement` (`id_departement`);

--
-- Indexes for table `master_penilaian`
--
ALTER TABLE `master_penilaian`
  ADD PRIMARY KEY (`id_penilaian`),
  ADD KEY `kategori_nilai` (`id_kategori`),
  ADD KEY `master_penilaian_ibfk_1` (`id_users`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id_permission`),
  ADD UNIQUE KEY `nama_permission` (`nama_permission`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`id_jabatan`,`id_permission`),
  ADD KEY `id_permission` (`id_permission`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_users`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `users_ibfk_1` (`id_jabatan`),
  ADD KEY `users_ibfk_2` (`id_departement`),
  ADD KEY `user_ibfk_3` (`level_jabatan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `banding_penilaian`
--
ALTER TABLE `banding_penilaian`
  MODIFY `id_banding` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `detail_penilaian`
--
ALTER TABLE `detail_penilaian`
  MODIFY `id_detailpenilaian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT for table `master_anchor`
--
ALTER TABLE `master_anchor`
  MODIFY `id_anchor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `master_departement`
--
ALTER TABLE `master_departement`
  MODIFY `id_departement` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `master_event`
--
ALTER TABLE `master_event`
  MODIFY `id_event` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `master_jabatan`
--
ALTER TABLE `master_jabatan`
  MODIFY `id_jabatan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `master_kategori`
--
ALTER TABLE `master_kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `master_kriteria`
--
ALTER TABLE `master_kriteria`
  MODIFY `id_kriteria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `master_penilaian`
--
ALTER TABLE `master_penilaian`
  MODIFY `id_penilaian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id_permission` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_users` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `banding_penilaian`
--
ALTER TABLE `banding_penilaian`
  ADD CONSTRAINT `banding_penilaian_ibfk_1` FOREIGN KEY (`id_penilaian`) REFERENCES `master_penilaian` (`id_penilaian`),
  ADD CONSTRAINT `banding_penilaian_ibfk_2` FOREIGN KEY (`id_users`) REFERENCES `users` (`id_users`);

--
-- Constraints for table `detail_penilaian`
--
ALTER TABLE `detail_penilaian`
  ADD CONSTRAINT `detail_penilaian_ibfk_2` FOREIGN KEY (`id_kriteria`) REFERENCES `master_kriteria` (`id_kriteria`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_penilaian_ibfk_3` FOREIGN KEY (`id_anchor`) REFERENCES `master_anchor` (`id_anchor`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `master_anchor`
--
ALTER TABLE `master_anchor`
  ADD CONSTRAINT `master_anchor_ibfk_1` FOREIGN KEY (`id_kriteria`) REFERENCES `master_kriteria` (`id_kriteria`);

--
-- Constraints for table `master_event`
--
ALTER TABLE `master_event`
  ADD CONSTRAINT `master_event_ibfk_1` FOREIGN KEY (`id_users`) REFERENCES `users` (`id_users`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `master_event_ibfk_2` FOREIGN KEY (`id_departement`) REFERENCES `master_departement` (`id_departement`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `master_kriteria`
--
ALTER TABLE `master_kriteria`
  ADD CONSTRAINT `master_kriteria_ibfk_1` FOREIGN KEY (`id_departement`) REFERENCES `master_departement` (`id_departement`);

--
-- Constraints for table `master_penilaian`
--
ALTER TABLE `master_penilaian`
  ADD CONSTRAINT `master_penilaian_ibfk_1` FOREIGN KEY (`id_users`) REFERENCES `users` (`id_users`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `master_penilaian_ibfk_2` FOREIGN KEY (`id_kategori`) REFERENCES `master_kategori` (`id_kategori`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`id_jabatan`) REFERENCES `master_jabatan` (`id_jabatan`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`id_permission`) REFERENCES `permissions` (`id_permission`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `user_ibfk_3` FOREIGN KEY (`level_jabatan`) REFERENCES `master_jabatan` (`level_jabatan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`id_jabatan`) REFERENCES `master_jabatan` (`id_jabatan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`id_departement`) REFERENCES `master_departement` (`id_departement`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
