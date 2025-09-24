-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 24, 2025 at 06:51 AM
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
(2, 'Super_Admin', 6, 'Test');

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
(1, 2),
(1, 10),
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
  `catatan_khusus` varchar(255) DEFAULT NULL,
  `foto` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_users`, `username`, `password_hash`, `id_jabatan`, `id_departement`, `level_jabatan`, `nama`, `tanggal_masuk`, `pendidikan_terakhir`, `status_karyawan`, `lokasi_kerja`, `atasan_langsung`, `nomor_hp`, `email`, `tanggal_lahir`, `jenis_kelamin`, `golongan`, `penilaian_terakhir`, `catatan_khusus`, `foto`) VALUES
(12, 'Dina', '$2y$10$Qthd4O5wCJJGpciu9gKBYOxOFDKTwS3aKOR6VpGgNtMgZUsjKCgtm', 2, 1, 6, 'Dina .S', '2025-08-20 21:08:42', 'S3 Institut Teknologi Bandung', 'Owner', 'Office Utama', 'Owner', '081252804432', 'Dina@gmail.com', '2025-08-20', 'wanita', 2, '2025-08-21 21:08:42', 'Test', '');

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
