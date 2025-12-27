-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 08, 2025 at 12:10 PM
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
  `status` enum('Review','Diterima','Ditolak') NOT NULL DEFAULT 'Review',
  `tanggal_banding` datetime DEFAULT NULL,
  `alasan` text DEFAULT NULL,
  `review` text DEFAULT NULL,
  `tanggal_review` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `banding_penilaian`
--
DELIMITER $$
CREATE TRIGGER `trg_banding_lock_status` BEFORE UPDATE ON `banding_penilaian` FOR EACH ROW BEGIN
                IF NEW.status <> OLD.status THEN
                    IF NOT (OLD.status = 'Review' AND (NEW.status IN ('Diterima','Ditolak'))) THEN
                        SIGNAL SQLSTATE '45000'
                          SET MESSAGE_TEXT = 'Keputusan banding sudah final dan tidak dapat diubah.';
                    END IF;
                    IF NEW.tanggal_review IS NULL THEN
                        SET NEW.tanggal_review = NOW();
                    END IF;
                END IF;

                -- Opsional: kunci teks setelah final
                IF OLD.status <> 'Review' THEN
                    IF NEW.review <> OLD.review OR NEW.alasan <> OLD.alasan THEN
                        SIGNAL SQLSTATE '45000'
                          SET MESSAGE_TEXT = 'Data banding sudah final dan tidak dapat diubah.';
                    END IF;
                END IF;
            END
$$
DELIMITER ;

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
-- Table structure for table `komentar`
--

CREATE TABLE `komentar` (
  `id_komentar` int(11) NOT NULL,
  `id_users` int(11) NOT NULL,
  `id_event` int(11) NOT NULL,
  `deskripsi` longtext NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
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
  `nilai_anchor` decimal(5,3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `master_anchor`
--

INSERT INTO `master_anchor` (`id_anchor`, `id_kriteria`, `level_anchor`, `deskripsi`, `nilai_anchor`) VALUES
(50, 14, 1, 'Lebih dari 20% produk cacat (jahitan longgar, bordir salah desain), perlu perbaikan ulang.', 1.000),
(51, 14, 2, 'Lebih dari 15% produk cacat (jahitan longgar, bordir salah desain), perlu perbaikan ulang.', 2.000),
(52, 14, 3, '5-10% produk memiliki cacat kecil, memerlukan pengawasan minimal.', 3.000),
(53, 14, 4, 'hampir selalu rapi, hanya 2-3% cacat kecil', 4.000),
(54, 14, 5, '0% cacat, jahitan rapi, sesuai desain tanpa pengawasan.', 5.000),
(55, 15, 1, 'Menyelesaikan tugas >30% di bawah target (misalnya, 10 kaos kaki/jam dari target 30).', 1.000),
(56, 15, 2, 'Menyelesaikan tugas >20% di bawah target (misalnya, 20 kaos kaki/jam dari target 30).', 2.000),
(57, 15, 3, 'Menyelesaikan tugas sesuai target dengan sedikit keterlambatan.', 3.000),
(60, 16, 1, 'Sering menimbulkan konflik atau tidak membantu rekan, menghambat produksi.', 1.000),
(61, 16, 2, 'jarang konflik atau tidak membantu rekan, menghambat produksi.', 2.000),
(62, 16, 3, 'Bekerja sama dengan baik, tetapi kadang perlu dorongan untuk membantu.', 3.000),
(63, 16, 4, 'Terkadang butuh dorongan untuk membantu tapi biasanya aman', 4.000),
(64, 16, 5, 'Selalu proaktif membantu rekan dan berkontribusi pada kelancaran tim.', 5.000),
(65, 17, 1, 'Tidak pernah menunjukkan inisiatif, hanya bekerja tidak sesuai instruksi', 1.000),
(66, 17, 2, 'Tidak pernah menunjukkan inisiatif, hanya bekerja sesuai instruksi.', 2.000),
(67, 17, 3, 'Jarang menunjukkan inisiatif, seperti membantu rekan saat diminta.', 3.000),
(68, 17, 4, 'Kadang menunjukkan inisiatif, seperti membantu rekan saat diminta.', 4.000),
(69, 17, 5, 'Sering menyarankan ide perbaikan proses atau membantu tanpa diminta.', 5.000),
(70, 15, 4, 'Menyelesaikan Tugas dengan ada nya sangat sedikit keterlambatan', 3.623),
(71, 15, 5, 'Menyelesaikan Tugas Melebihi Target dan Tepat Waktu', 5.000),
(77, 21, 1, 'Tidak bisa bekerja sama dengan tim dan merusak ritme kerja tim', 0.500),
(78, 21, 2, 'Kurang bisa bekerja sama dengan tim dan merusak ritme kerja tim', 1.000),
(79, 21, 3, 'Cukup bisa bekerja sama dengan tim dan merusak ritme kerja tim', 1.750),
(80, 21, 4, 'Bisa bekerja sama dengan tim dan merusak ritme kerja tim', 3.000),
(81, 21, 5, 'Sangat Bisa dan membuat kerja tim jadi lebih baik', 4.950),
(82, 19, 1, 'Tidak bisa berkomunikasi dengan customer dan owner', 1.000),
(83, 19, 2, 'Kurang bisa berkomunikasi dengan customer dan owner', 1.950),
(84, 19, 3, 'Cukup bisa berkomunikasi dengan customer dan owner', 2.250),
(85, 19, 4, 'Bisa berkomunikasi dengan customer dan owner', 3.950),
(86, 19, 5, 'Sangat berkomunikasi dengan customer dan owner ', 4.850),
(87, 20, 1, 'Tidak bisa bertanggung jawab dalam pekerjaan', 1.000),
(88, 20, 2, 'Kurang bisa bertanggung jawab dalam pekerjaan', 2.000),
(89, 20, 3, 'Cukup bisa bertanggung jawab dalam pekerjaan', 3.000),
(90, 20, 4, 'Bisa bertanggung jawab dalam pekerjaan', 4.550),
(91, 20, 5, 'Sangat bertanggung jawab dalam pekerjaan', 5.000);

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
(2, 'Owner', 'Hak Owner Di Sistem\r\n'),
(3, 'Produksi', 'test'),
(4, 'Marketing', 'Departemen ini bertujuan untuk memasarkan produk\r\n');

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
(14, 1, 'Ada Makanan Gratis', 'Ada Pisang Goreng di meja ambil aja', '6908b2832f420.jpeg', '2025-11-03 00:00:00', 'Makan Gratis', 'low', 'Pabrik', 2, 1, 'open', '2025-11-03 13:47:47');

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
(2, 'Super_Admin', 6, 'Test'),
(6, 'Operator Produksi', 3, '	Operator yang sudah memasuki masa bakti kerja 0-5 tahun'),
(7, 'Operator Produksi Senior', 5, 'Operator yang sudah memasuki masa bakti kerja 5-10 tahun\r\n'),
(8, 'Marketing Staff', 2, 'Staff Divisi Marketing');

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
(9, 'Perlu Perbaikan', 1.000, 2.400),
(10, 'Memenuhi Harapan', 2.500, 3.400),
(11, 'Melebihi Harapan', 3.500, 4.400),
(12, 'Sangat Memuaskan', 4.500, 5.000);

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
(14, 3, 'Ketelitian Dalam Produksi', 'Kualitas jahitan kaos kaki atau bordir, penting untuk memenuhi standar pelanggan', 3),
(15, 3, 'Efisiensi Waktu', 'Kecepatan menyelesaikan tugas tanpa mengorbankan kualitas, krusial untuk tenggat waktu.', 3),
(16, 3, 'Kerjasama Tim', 'Kemampuan bekerja sama dalam tim kecil untuk mendukung kelancaran produksi.', 2),
(17, 3, 'Inisiatif Kerja', 'Kemampuan karyawan untuk mengambil inisiatif, seperti menyarankan perbaikan proses atau membantu tanpa diminta', 2),
(19, 4, 'Komunikasi', 'Bisa Berkomunikasi dengan owner maupun customer', 7),
(20, 4, 'Tanggung Jawab', 'Bertanggung jawab terhadap pemasaran produk pada owner', 3),
(21, 4, 'Kerja Sama', 'Dapat bekerja sama dengan tim untuk memasarkan produk', 3);

-- --------------------------------------------------------

--
-- Table structure for table `master_penilaian`
--

CREATE TABLE `master_penilaian` (
  `id_penilaian` int(11) NOT NULL,
  `id_users` int(11) DEFAULT NULL,
  `id_periode` int(11) NOT NULL,
  `nilai_akhir` decimal(5,3) DEFAULT NULL,
  `id_kategori` int(11) DEFAULT NULL,
  `presentase_absensi` varchar(50) DEFAULT NULL,
  `catatan` varchar(255) DEFAULT NULL,
  `rekomendasi` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `master_periode`
--

CREATE TABLE `master_periode` (
  `id_periode` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `id_user` int(11) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'draft',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `master_profile`
--

CREATE TABLE `master_profile` (
  `id_profile` int(11) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `notelfon` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `logo` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `master_profile`
--

INSERT INTO `master_profile` (`id_profile`, `nama`, `notelfon`, `email`, `alamat`, `logo`) VALUES
(1, 'CV Mandiri Persada', '082122325575', 'Mandiripersada.co.id', 'Jalan Siak no 15 , Kecamatan Mawar , Kota Semarang, Jawa Tengah', 'logo_1762611911.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `migration`
--

CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1759698464),
('m251005_210700_lock_banding_status', 1759698539),
('m251110_140212_create_master_periode', 1762783479),
('m251110_140218_alter_master_penilaian_add_periode_rekomendasi', 1762783623);

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `id` int(255) NOT NULL,
  `target_id_user` int(255) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` varchar(255) NOT NULL,
  `model_class` varchar(255) DEFAULT NULL,
  `model_pk` varchar(255) DEFAULT NULL,
  `aksi` varchar(255) NOT NULL,
  `dibaca` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
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
(366, 'akses_master-anchor', 'Auto generated'),
(367, 'akses_dashboard', 'Auto generated'),
(368, 'akses_master-jabatan', 'Auto generated'),
(369, 'akses_master-departement', 'Auto generated'),
(370, 'akses_user', 'Auto generated'),
(371, 'akses_master-kriteria', 'Auto generated'),
(372, 'akses_master-kategori', 'Auto generated'),
(373, 'akses_role-permission', 'Auto generated'),
(374, 'akses_master-penilaian', 'Auto generated'),
(375, 'akses_laporan', 'Auto generated'),
(376, 'akses_master-event', 'Auto generated'),
(377, 'akses_banding-penilaian', 'Auto generated'),
(378, 'akses_pengajuan-banding', 'Auto generated'),
(379, 'akses_statistik', 'Auto generated'),
(380, 'akses_manajemen', 'Auto generated'),
(381, 'akses_site', 'Auto generated'),
(382, 'akses_penilaian', 'Auto generated'),
(383, 'akses_report', 'Auto generated'),
(384, 'akses_master-periode', 'Auto generated');

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
(2, 366),
(2, 367),
(2, 368),
(2, 369),
(2, 370),
(2, 371),
(2, 372),
(2, 373),
(2, 374),
(2, 375),
(2, 376),
(2, 377),
(2, 378),
(2, 379),
(2, 380),
(2, 381),
(2, 382),
(2, 383),
(2, 384),
(6, 375),
(6, 376),
(6, 378),
(6, 381),
(6, 382),
(7, 375),
(7, 376),
(7, 378),
(7, 381),
(7, 382);

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
  `penilaian_terakhir` date DEFAULT NULL,
  `catatan_khusus` varchar(255) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_users`, `username`, `password_hash`, `id_jabatan`, `id_departement`, `level_jabatan`, `nama`, `tanggal_masuk`, `pendidikan_terakhir`, `status_karyawan`, `lokasi_kerja`, `atasan_langsung`, `nomor_hp`, `email`, `tanggal_lahir`, `jenis_kelamin`, `golongan`, `penilaian_terakhir`, `catatan_khusus`, `foto`) VALUES
(1, 'Dina', '$2y$10$.sS9XvlA14F7Ama5rhhPeukyZnZ9XApFqMwFVXTUlTK0zy2bM0T96', 2, 2, 6, 'Dina', '2025-10-01 00:07:57', 'S3 ITB', 'Owner', 'Kantor', 'Owner', '082144424425', 'test@gmail.com', '2025-10-01', 'pria', 1, '2025-10-01 00:07:57', 'tidak ada', NULL),
(19, 'Ani', '$2y$13$7KtdBY6qjY90oXjETwdLNeq/9IeRtGFXnCDbUauPYjN54Pjxf6KBm', 6, 3, 3, 'Ani', '2025-10-01 00:00:00', 'S2 Universitas Andalas', 'Aktif', 'Pabrik ', 'Pemilik', '081252804432', 'jr0807200412345@gmail.com', '2025-10-01', 'wanita', 1, NULL, 'Tidak ada', 'Ex6rIr9PdD-PGATY.jpeg'),
(20, 'Budi', '$2y$13$gVgOha9pVx6YpxxejIeZrOS32jGvu69zNYrB.0SWzS4MB8CYGWhtC', 7, 3, 5, 'Budi', '2025-11-01 00:00:00', 'S2 Universitas Indonesia', 'Aktif', 'Office 1', 'Owner', '082144424421', 'jr0807200412345@gmail.com', '1995-11-01', 'pria', 1, NULL, 'Tidak ada', 'J629TcCYnV2x9lgV.jpeg'),
(21, 'Andika', '$2y$13$DJ1JieEP4e8N7KP6bZb2YO31wK0.no5lh6h7i7PAMY.JUC5Qs0Ifi', 8, 4, 2, 'Andika', '2025-11-01 00:00:00', 'S3 Institut Teknologi Bandung', 'Aktif', 'Office 1', 'Owner', '082144424475', 'jr0807200412345@gmail.com', '1992-11-01', 'pria', 1, NULL, 'Tidak ada', NULL);

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
-- Indexes for table `komentar`
--
ALTER TABLE `komentar`
  ADD PRIMARY KEY (`id_komentar`),
  ADD KEY `idx_komen_event_created` (`id_event`,`created_at`),
  ADD KEY `idx_komen_user` (`id_users`);

--
-- Indexes for table `master_anchor`
--
ALTER TABLE `master_anchor`
  ADD PRIMARY KEY (`id_anchor`),
  ADD UNIQUE KEY `uniq_kriteria_level` (`id_kriteria`,`level_anchor`);

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
  ADD UNIQUE KEY `ux_penilaian_periode_user` (`id_periode`,`id_users`),
  ADD KEY `kategori_nilai` (`id_kategori`),
  ADD KEY `master_penilaian_ibfk_1` (`id_users`);

--
-- Indexes for table `master_periode`
--
ALTER TABLE `master_periode`
  ADD PRIMARY KEY (`id_periode`),
  ADD KEY `idx_master_periode_date` (`tanggal_mulai`,`tanggal_selesai`),
  ADD KEY `fk_master_periode_owner` (`id_user`);

--
-- Indexes for table `master_profile`
--
ALTER TABLE `master_profile`
  ADD PRIMARY KEY (`id_profile`);

--
-- Indexes for table `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id_banding` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `detail_penilaian`
--
ALTER TABLE `detail_penilaian`
  MODIFY `id_detailpenilaian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=229;

--
-- AUTO_INCREMENT for table `komentar`
--
ALTER TABLE `komentar`
  MODIFY `id_komentar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `master_anchor`
--
ALTER TABLE `master_anchor`
  MODIFY `id_anchor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `master_departement`
--
ALTER TABLE `master_departement`
  MODIFY `id_departement` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `master_event`
--
ALTER TABLE `master_event`
  MODIFY `id_event` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `master_jabatan`
--
ALTER TABLE `master_jabatan`
  MODIFY `id_jabatan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `master_kategori`
--
ALTER TABLE `master_kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `master_kriteria`
--
ALTER TABLE `master_kriteria`
  MODIFY `id_kriteria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `master_penilaian`
--
ALTER TABLE `master_penilaian`
  MODIFY `id_penilaian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `master_periode`
--
ALTER TABLE `master_periode`
  MODIFY `id_periode` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `master_profile`
--
ALTER TABLE `master_profile`
  MODIFY `id_profile` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id_permission` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=385;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_users` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `banding_penilaian`
--
ALTER TABLE `banding_penilaian`
  ADD CONSTRAINT `banding_penilaian_ibfk_1` FOREIGN KEY (`id_penilaian`) REFERENCES `master_penilaian` (`id_penilaian`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `banding_penilaian_ibfk_2` FOREIGN KEY (`id_users`) REFERENCES `users` (`id_users`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `detail_penilaian`
--
ALTER TABLE `detail_penilaian`
  ADD CONSTRAINT `detail_penilaian_ibfk_2` FOREIGN KEY (`id_kriteria`) REFERENCES `master_kriteria` (`id_kriteria`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_penilaian_ibfk_3` FOREIGN KEY (`id_anchor`) REFERENCES `master_anchor` (`id_anchor`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `komentar`
--
ALTER TABLE `komentar`
  ADD CONSTRAINT `fk_komen_event` FOREIGN KEY (`id_event`) REFERENCES `master_event` (`id_event`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_komen_user` FOREIGN KEY (`id_users`) REFERENCES `users` (`id_users`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `fk_penilaian_periode` FOREIGN KEY (`id_periode`) REFERENCES `master_periode` (`id_periode`) ON DELETE CASCADE,
  ADD CONSTRAINT `master_penilaian_ibfk_1` FOREIGN KEY (`id_users`) REFERENCES `users` (`id_users`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `master_penilaian_ibfk_2` FOREIGN KEY (`id_kategori`) REFERENCES `master_kategori` (`id_kategori`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `master_periode`
--
ALTER TABLE `master_periode`
  ADD CONSTRAINT `fk_master_periode_owner` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_users`) ON DELETE CASCADE;

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
