-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 14, 2025 at 01:53 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33
SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `absensi`
--

-- --------------------------------------------------------

--
-- Table structure for table `diary`
--

CREATE TABLE `diary` (
  `diary_id` int(11) NOT NULL,
  `presensi_id` int(11) NOT NULL,
  `diary_emoticon` enum('1','2','3','4') NOT NULL,
  `diary_foto` text NOT NULL,
  `diary_foto_pred` varchar(50) NOT NULL,
  `diary_text` text NOT NULL,
  `diary_text_pred` varchar(50) NOT NULL,
  `diary_text_ket` varchar(100) NOT NULL,
  `diary_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `diary`
--

INSERT INTO `diary` (`diary_id`, `presensi_id`, `diary_emoticon`, `diary_foto`, `diary_foto_pred`, `diary_text`, `diary_text_pred`, `diary_text_ket`, `diary_time`) VALUES
(1, 1, '2', 'foto_enkripsi.jpg', '  {     \"score\": 0.85,     \"emotion\": \"marah\"   }', 'gue lagi ngamuk', '  {     \"score\": 0.85,     \"emotion\": \"marah\"   }', 'ya tadi duit gue ilang', '2025-11-14 12:44:06');

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `kelas_id` int(11) NOT NULL,
  `kelas_jenjang` int(11) NOT NULL,
  `kelas_jurusan` varchar(50) NOT NULL,
  `kelas_nama` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`kelas_id`, `kelas_jenjang`, `kelas_jurusan`, `kelas_nama`) VALUES
(1, 11, 'IPS', '2'),
(2, 12, 'IPA', '1');

-- --------------------------------------------------------

--
-- Table structure for table `presensi`
--

CREATE TABLE `presensi` (
  `presensi_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `presensi_tgl` date NOT NULL,
  `thak_id` int(11) NOT NULL,
  `presensi_status` enum('H','I','S') NOT NULL DEFAULT 'H',
  `presensi_jam_masuk` time DEFAULT NULL,
  `presensi_ket` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `presensi`
--

INSERT INTO `presensi` (`presensi_id`, `siswa_id`, `presensi_tgl`, `thak_id`, `presensi_status`, `presensi_jam_masuk`, `presensi_ket`) VALUES
(1, 1, '2025-11-14', 2024, 'H', '19:26:55', 'ok');

-- --------------------------------------------------------

--
-- Table structure for table `presensi_libur`
--

CREATE TABLE `presensi_libur` (
  `lbr_id` int(11) NOT NULL,
  `lbr_tgl` date NOT NULL,
  `lbr_ket` varchar(50) NOT NULL,
  `lbr_by` varchar(20) NOT NULL,
  `lbr_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rekap_emosi`
--

CREATE TABLE `rekap_emosi` (
  `re_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `re_tgl` date NOT NULL,
  `re_hasil` enum('0','1') NOT NULL COMMENT '0: cluster normal ; 1: sakit',
  `re_rekap_emoticon` text NOT NULL COMMENT '1;1;2; rekap emosi 14 hari',
  `re_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rekap_emosi`
--

INSERT INTO `rekap_emosi` (`re_id`, `siswa_id`, `re_tgl`, `re_hasil`, `re_rekap_emoticon`, `re_time`) VALUES
(1, 1, '2025-11-14', '1', '1;1;1;1;1;1;1;1;1;1;1;1;1;', '2025-11-14 12:50:06');

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_kelas`
--

CREATE TABLE `riwayat_kelas` (
  `riwayat_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `thak_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `riwayat_kelas`
--

INSERT INTO `riwayat_kelas` (`riwayat_id`, `siswa_id`, `thak_id`, `kelas_id`) VALUES
(2, 1, 2023, 2),
(3, 1, 2024, 1);

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `siswa_id` int(11) NOT NULL,
  `siswa_nis` varchar(25) NOT NULL,
  `siswa_password` text NOT NULL,
  `siswa_nama_lengkap` varchar(100) NOT NULL,
  `siswa_tanggal_lahir` date DEFAULT NULL,
  `siswa_alamat` varchar(50) NOT NULL,
  `siswa_status` enum('0','1') DEFAULT '1',
  `siswa_tanggal_dibuat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`siswa_id`, `siswa_nis`, `siswa_password`, `siswa_nama_lengkap`, `siswa_tanggal_lahir`, `siswa_alamat`, `siswa_status`, `siswa_tanggal_dibuat`) VALUES
(1, '13090036', '', 'noval', '2025-11-05', 'semarang', '1', '2025-11-14 12:18:30'),
(2, '13090037', '', 'deni', '2025-11-04', 'bandung', '1', '2025-11-14 12:18:30');

-- --------------------------------------------------------

--
-- Table structure for table `tahun_akademik`
--

CREATE TABLE `tahun_akademik` (
  `thak_id` int(11) NOT NULL,
  `thak_nama_tahun` varchar(20) NOT NULL,
  `thak_tanggal_mulai` date NOT NULL,
  `thak_tanggal_selesai` date NOT NULL,
  `thak_status` set('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tahun_akademik`
--

INSERT INTO `tahun_akademik` (`thak_id`, `thak_nama_tahun`, `thak_tanggal_mulai`, `thak_tanggal_selesai`, `thak_status`) VALUES
(2023, '2023/2024', '2023-07-15', '2024-06-30', '0'),
(2024, '2024/2025', '2024-07-15', '2025-06-30', '1');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(5) NOT NULL,
  `user_nama` varchar(50) NOT NULL,
  `user_password` text NOT NULL,
  `user_namalengkap` varchar(50) NOT NULL,
  `user_level` int(1) NOT NULL COMMENT '1: super admin; 2: admin tu; 3:guru bk',
  `user_created` datetime NOT NULL DEFAULT current_timestamp(),
  `user_edited` datetime DEFAULT NULL,
  `user_status` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_nama`, `user_password`, `user_namalengkap`, `user_level`, `user_created`, `user_edited`, `user_status`) VALUES
(1, 'hasta', '$2y$10$ZdZIpysS8TWn8cTr5Awao.nEY4RXnkUYijO1YWhqSUQGgfrRLzFyi', 'Hasta Dwi', 1, '2021-11-03 16:45:44', NULL, '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `diary`
--
ALTER TABLE `diary`
  ADD PRIMARY KEY (`diary_id`),
  ADD KEY `diary_presensi` (`presensi_id`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`kelas_id`);

--
-- Indexes for table `presensi`
--
ALTER TABLE `presensi`
  ADD PRIMARY KEY (`presensi_id`),
  ADD KEY `presensi_ibfk_2` (`thak_id`),
  ADD KEY `riwayat_kelas_siswa2` (`siswa_id`);

--
-- Indexes for table `presensi_libur`
--
ALTER TABLE `presensi_libur`
  ADD PRIMARY KEY (`lbr_id`);

--
-- Indexes for table `rekap_emosi`
--
ALTER TABLE `rekap_emosi`
  ADD PRIMARY KEY (`re_id`),
  ADD KEY `siswa_id` (`siswa_id`);

--
-- Indexes for table `riwayat_kelas`
--
ALTER TABLE `riwayat_kelas`
  ADD PRIMARY KEY (`riwayat_id`),
  ADD UNIQUE KEY `siswa_id` (`siswa_id`,`thak_id`),
  ADD KEY `tahun_akademik_id` (`thak_id`),
  ADD KEY `riwayat_kelas_kelas` (`kelas_id`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`siswa_id`),
  ADD UNIQUE KEY `nis` (`siswa_nis`);

--
-- Indexes for table `tahun_akademik`
--
ALTER TABLE `tahun_akademik`
  ADD PRIMARY KEY (`thak_id`),
  ADD UNIQUE KEY `nama_tahun` (`thak_nama_tahun`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `user_nama` (`user_nama`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `diary`
--
ALTER TABLE `diary`
  MODIFY `diary_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `kelas_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `presensi`
--
ALTER TABLE `presensi`
  MODIFY `presensi_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `presensi_libur`
--
ALTER TABLE `presensi_libur`
  MODIFY `lbr_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rekap_emosi`
--
ALTER TABLE `rekap_emosi`
  MODIFY `re_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `riwayat_kelas`
--
ALTER TABLE `riwayat_kelas`
  MODIFY `riwayat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `siswa_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `diary`
--
ALTER TABLE `diary`
  ADD CONSTRAINT `diary_presensi` FOREIGN KEY (`presensi_id`) REFERENCES `presensi` (`presensi_id`);

--
-- Constraints for table `presensi`
--
ALTER TABLE `presensi`
  ADD CONSTRAINT `presensi_ibfk_2` FOREIGN KEY (`thak_id`) REFERENCES `tahun_akademik` (`thak_id`),
  ADD CONSTRAINT `riwayat_kelas_siswa2` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`siswa_id`);

--
-- Constraints for table `rekap_emosi`
--
ALTER TABLE `rekap_emosi`
  ADD CONSTRAINT `rekap_emosi_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`siswa_id`);

--
-- Constraints for table `riwayat_kelas`
--
ALTER TABLE `riwayat_kelas`
  ADD CONSTRAINT `riwayat_kelas_ibfk_2` FOREIGN KEY (`thak_id`) REFERENCES `Tahun_Akademik` (`thak_id`),
  ADD CONSTRAINT `riwayat_kelas_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`kelas_id`),
  ADD CONSTRAINT `riwayat_kelas_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`siswa_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
SET FOREIGN_KEY_CHECKS = 0;

