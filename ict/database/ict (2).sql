-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 16, 2026 at 07:15 AM
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
-- Database: `ict`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `activity_log_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `date` datetime NOT NULL,
  `action` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `borrowing`
--

CREATE TABLE `borrowing` (
  `borrow_id` int(10) NOT NULL,
  `pemohon_college_id` varchar(50) NOT NULL,
  `borrow_item` varchar(50) NOT NULL,
  `request_date` datetime NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `status` enum('Baru','Lulus','Ditolak') NOT NULL,
  `type` enum('Luaran','Dalaman','Luaran Berkala') DEFAULT NULL,
  `purpose` varchar(100) NOT NULL,
  `location` varchar(50) NOT NULL,
  `need_technician` enum('Ya','Tidak') NOT NULL,
  `approved_date` datetime NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `notes` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrowing`
--

INSERT INTO `borrowing` (`borrow_id`, `pemohon_college_id`, `borrow_item`, `request_date`, `start_date`, `end_date`, `status`, `type`, `purpose`, `location`, `need_technician`, `approved_date`, `user_name`, `notes`) VALUES
(1, 'STDN00777', 'Laptop', '2025-01-23 03:20:31', '2025-01-24 08:00:00', '2025-01-27 04:00:00', 'Lulus', 'Dalaman', '0', '0', 'Tidak', '2025-03-04 03:40:54', 'aida', ''),
(2, 'FP00652', 'Laptop', '2025-01-23 10:26:36', '2025-01-30 08:30:00', '2025-01-31 05:00:00', 'Ditolak', 'Luaran', '0', '0', 'Ya', '0000-00-00 00:00:00', 'Danish Haiqal', ''),
(3, 'FP00652', 'Keyboard', '2025-02-03 09:15:24', '2025-02-05 08:59:00', '2025-02-05 17:30:00', 'Lulus', 'Luaran', '0', '0', 'Tidak', '2025-03-04 03:47:07', 'aida', ''),
(4, 'FP00652', 'Speaker', '2025-02-03 09:20:23', '2025-02-19 08:00:00', '2025-02-20 16:30:00', 'Lulus', 'Dalaman', 'Khidmat Masyarat Program at Rumah anak yatim kluang', 'Rumah anak yatim kluang', 'Ya', '2025-02-03 03:04:46', 'aida', ''),
(5, 'BPN231210136', 'DELL I7', '2026-01-21 10:53:40', '2026-01-22 10:53:00', '2026-01-24 10:53:00', '', 'Dalaman', 'PLAY GAME', 'JTM 2', 'Ya', '2026-01-21 03:54:15', 'aida', ''),
(6, 'BPN231210136', 'HP', '2026-01-21 11:42:51', '2026-01-21 11:42:00', '2026-01-21 02:42:00', '', 'Dalaman', 'PLAY GAME', 'JTM 2', 'Tidak', '2026-01-21 04:43:19', 'aida', ''),
(7, 'BPN231210136', 'Laptop (2)', '2026-01-21 15:05:35', '2026-01-21 15:05:00', '2026-01-22 15:05:00', '', 'Dalaman', 'BENGKEL', 'LR2', 'Tidak', '2026-01-21 08:08:27', '', ''),
(8, 'BPN231210136', 'laptop 1', '2026-01-22 09:42:01', '2026-01-22 10:41:00', '2026-01-23 00:41:00', '', 'Dalaman', 'BENGKEL', 'LR2', 'Tidak', '2026-01-22 02:42:31', 'Danish Haiqal', 'good'),
(9, 'BPN231210136', 'dada', '2026-01-22 09:45:08', '2026-01-22 09:44:00', '2026-01-22 10:44:00', '', 'Dalaman', 'PLAY GAME', 'JTM 2', 'Tidak', '2026-01-22 02:45:19', 'Danish Haiqal', 'good'),
(10, 'BPN231210136', 'gaga', '2026-01-22 09:48:35', '2026-01-22 09:48:00', '2026-01-23 09:48:00', '', 'Dalaman', 'PLAY GAME', 'gaaga', 'Tidak', '2026-01-22 02:49:46', 'Danish Haiqal', 'good'),
(11, 'BPN231210136', 'adadad', '2026-01-22 09:53:33', '2026-01-22 09:53:00', '2026-01-29 09:53:00', '', 'Luaran', 'PLAY GAME', 'gaaga', 'Ya', '2026-01-22 02:53:45', 'Danish Haiqal', 'good'),
(12, 'BPN231210136', 'adadd', '2026-01-22 09:55:29', '2026-01-22 09:55:00', '2026-01-24 09:55:00', '', 'Dalaman', 'PLAY GAME', 'gaaga', 'Tidak', '2026-01-22 02:55:52', 'Danish Haiqal', 'good'),
(13, 'BPN231210136', 'laptop (2)', '2026-01-27 12:10:21', '2026-01-27 12:10:00', '2026-01-28 12:10:00', 'Lulus', 'Luaran', 'BENGKEL', 'LR2', 'Ya', '2026-01-27 05:10:38', 'Danish Haiqal', ''),
(14, 'BPN231210136', 'laptop 1', '2026-01-27 12:50:05', '2026-01-27 12:49:00', '2026-01-28 12:50:00', 'Lulus', 'Luaran', 'PLAY GAME', 'LR2', 'Ya', '2026-01-27 06:05:01', 'Danish Haiqal', ''),
(15, 'BPN231210136', 'speaker', '2026-01-27 13:12:01', '2026-01-29 13:11:00', '2026-01-30 13:11:00', 'Lulus', 'Luaran', 'dengar dugem', 'Kelab Dangdut Parit Sameon', 'Tidak', '2026-01-27 06:12:41', 'Danish Haiqal', ''),
(16, 'BPN231210136', 'laptop', '2026-01-28 16:05:27', '2026-01-28 16:05:00', '2026-01-30 16:05:00', 'Lulus', 'Dalaman', 'Bengkel it', 'LR2', 'Tidak', '2026-01-28 09:07:28', 'admin', ''),
(17, 'BPN231210136', 'laptop', '2026-01-28 16:21:23', '2026-01-28 16:21:00', '2026-01-29 16:21:00', 'Lulus', 'Dalaman', 'PLAY GAME', 'LR2', 'Tidak', '2026-01-28 09:21:35', 'admin', ''),
(18, 'BPN231210136', 'laptop', '2026-01-28 16:22:41', '2026-01-29 16:22:00', '2026-01-30 16:22:00', 'Lulus', 'Luaran', 'PLAY GAME', 'LR2', 'Ya', '2026-01-28 09:22:51', 'admin', ''),
(19, 'BPN231210136', 'laptop 2', '2026-01-29 11:53:39', '2026-01-30 11:53:00', '2026-02-06 11:53:00', 'Ditolak', '', 'PLAY GAME', 'JTM 2', 'Tidak', '0000-00-00 00:00:00', 'Danish Haiqal', ''),
(20, 'BPN231210136', 'laptop', '2026-01-29 11:54:53', '2026-01-30 11:54:00', '2026-01-31 11:54:00', 'Lulus', 'Dalaman', 'BENGKEL', 'Kelab Dangdut Parit Sameon', 'Tidak', '2026-01-29 05:34:57', 'Danish Haiqal', ''),
(21, 'BPN231210136', 'laptop', '2026-01-29 11:59:18', '2026-02-07 11:59:00', '2026-02-08 11:59:00', 'Lulus', 'Luaran Berkala', 'Bengkel it', 'gaaga', 'Tidak', '2026-01-29 05:04:02', 'Danish Haiqal', ''),
(22, 'FP00652', 'laptop', '2026-02-01 20:13:03', '2026-03-07 20:12:00', '2026-03-08 20:12:00', 'Lulus', 'Dalaman', 'penaksiran', 'Kelab  Inovasi', 'Tidak', '2026-02-07 07:43:03', 'Danish Haiqal', ''),
(23, 'BPN231210136', '3 laptop', '2026-02-06 16:24:14', '2026-02-13 16:24:00', '2026-02-15 16:24:00', 'Lulus', 'Dalaman', 'presentation', 'Lab 19', 'Tidak', '2026-02-06 09:26:17', 'admin', ''),
(24, 'BPN231210136', 'laptop 6', '2026-02-07 14:19:41', '2026-02-20 14:19:00', '2026-02-22 14:19:00', 'Lulus', 'Dalaman', 'Bengkel it', 'Kelab Dangdut Parit Sameon', 'Tidak', '2026-02-07 07:20:15', 'Danish Haiqal', ''),
(25, 'BPN231210136', 'LAPTOP 1', '2026-02-07 14:47:28', '2026-03-06 14:47:00', '2026-03-07 14:47:00', 'Lulus', 'Dalaman', 'presentation', 'gaaga', 'Tidak', '2026-02-07 07:47:38', 'Danish Haiqal', ''),
(26, 'BPN231210136', 'laptop', '2026-02-08 14:45:21', '2026-02-05 14:45:00', '2026-02-06 14:45:00', 'Lulus', 'Dalaman', 'PLAY GAME', 'LR2', 'Tidak', '2026-02-08 07:49:47', 'Danish Haiqal', ''),
(27, 'BPN231210136', 'Laptop 5', '2026-02-12 11:34:00', '2026-02-12 12:00:00', '2026-02-13 13:30:00', 'Lulus', 'Dalaman', 'Bengkel IT ', 'DEWAN UTAMA KPTM', 'Tidak', '2026-02-12 04:34:28', 'Danish Haiqal', '');

-- --------------------------------------------------------

--
-- Table structure for table `borrowing_item`
--

CREATE TABLE `borrowing_item` (
  `borrow_item_id` int(11) NOT NULL,
  `borrow_id` varchar(50) NOT NULL,
  `pemohon_college_id` varchar(50) NOT NULL,
  `item_tag` varchar(50) NOT NULL,
  `item_qty` int(10) NOT NULL,
  `status` enum('Borrowed','Returned','') NOT NULL,
  `return_date` datetime NOT NULL,
  `notes` varchar(100) NOT NULL,
  `item_siri` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrowing_item`
--

INSERT INTO `borrowing_item` (`borrow_item_id`, `borrow_id`, `pemohon_college_id`, `item_tag`, `item_qty`, `status`, `return_date`, `notes`, `item_siri`) VALUES
(1, '4', 'FP00652', 'Tag 11', 1, 'Returned', '0000-00-00 00:00:00', '', 'Series 11'),
(2, '1', 'STDN00777', 'tag1', 1, 'Returned', '0000-00-00 00:00:00', 'good', ''),
(3, '3', 'FP00652', 'Series 10', 1, 'Returned', '0000-00-00 00:00:00', 'Good condition', ''),
(15, '13', 'BPN231210136', 'Tag21', 1, 'Returned', '0000-00-00 00:00:00', 'screen tercabut', 'Series21'),
(17, '14', 'BPN231210136', 'tag1', 1, 'Returned', '0000-00-00 00:00:00', 'good', ''),
(18, '15', 'BPN231210136', 'Series 10', 1, 'Returned', '2026-01-29 10:06:44', '', ''),
(19, '16', 'BPN231210136', 'Tag21', 1, 'Returned', '2026-01-29 12:02:44', '', 'Series21'),
(20, '18', 'BPN231210136', 'tagging 1', 1, 'Returned', '2026-01-29 12:02:50', '', ''),
(22, '21', 'BPN231210136', 'Tag21', 1, 'Returned', '2026-02-06 16:23:29', '', 'Series21'),
(25, '20', 'BPN231210136', 'tag1', 1, 'Returned', '2026-02-06 16:23:36', '', ''),
(26, '23', 'BPN231210136', 'tag 20261', 1, 'Returned', '2026-02-07 14:18:47', '', '1001'),
(27, '23', 'BPN231210136', 'tag 20262', 1, 'Returned', '2026-02-07 14:18:52', '', '1002'),
(28, '23', 'BPN231210136', 'tag 20263', 1, 'Returned', '2026-02-07 14:18:57', '', '1003'),
(29, '24', 'BPN231210136', 'tag1', 1, 'Returned', '2026-02-07 14:25:33', '', NULL),
(30, '24', 'BPN231210136', 'Tag21', 1, 'Returned', '2026-02-07 14:25:38', '', NULL),
(31, '24', 'BPN231210136', 'tagging 1', 1, 'Returned', '2026-02-07 14:25:42', '', NULL),
(32, '24', 'BPN231210136', 'tag 20261', 1, 'Returned', '2026-02-07 14:25:48', '', NULL),
(33, '24', 'BPN231210136', 'tag 20262', 1, 'Returned', '2026-02-07 14:25:52', '', NULL),
(34, '24', 'BPN231210136', 'tag 20263', 1, 'Returned', '2026-02-07 14:25:56', '', NULL),
(35, '22', 'FP00652', 'tag 20261', 1, 'Returned', '2026-02-08 14:51:02', '', NULL),
(36, '22', 'FP00652', 'tag 20262', 1, 'Returned', '2026-02-08 14:51:17', '', NULL),
(37, '22', 'FP00652', 'tag 20263', 1, 'Returned', '2026-02-08 14:51:21', '', NULL),
(38, '22', 'FP00652', 'tag 20265', 1, 'Returned', '2026-02-08 14:51:25', '', NULL),
(39, '22', 'FP00652', 'tag 20266', 1, 'Returned', '2026-02-08 14:51:29', '', NULL),
(40, '22', 'FP00652', 'tag 20267', 1, 'Returned', '2026-02-08 14:51:35', '', NULL),
(41, '22', 'FP00652', 'tag 20268', 1, 'Returned', '2026-02-08 14:51:44', '', NULL),
(42, '25', 'BPN231210136', 'tag 20269', 1, 'Returned', '2026-02-08 14:50:54', '', NULL),
(43, '26', 'BPN231210136', 'tag 20270', 1, 'Returned', '2026-02-08 14:50:58', '', '1010'),
(44, '27', 'BPN231210136', 'tag 20261', 1, 'Borrowed', '0000-00-00 00:00:00', '', '1001'),
(45, '27', 'BPN231210136', 'tag 20262', 1, 'Borrowed', '0000-00-00 00:00:00', '', '1002'),
(46, '27', 'BPN231210136', 'tag 20263', 1, 'Borrowed', '0000-00-00 00:00:00', '', '1003'),
(47, '27', 'BPN231210136', 'tag 20265', 1, 'Borrowed', '0000-00-00 00:00:00', '', '1005'),
(48, '27', 'BPN231210136', 'tag 20266', 1, 'Borrowed', '0000-00-00 00:00:00', '', '1006');

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `item_id` int(11) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `item_tag` varchar(50) NOT NULL,
  `item_category` varchar(50) NOT NULL,
  `brand` varchar(11) DEFAULT NULL,
  `model` varchar(11) DEFAULT NULL,
  `item_status` enum('Available','Borrowed','Maintenance') NOT NULL,
  `item_quantity` int(10) NOT NULL,
  `item_description` varchar(100) NOT NULL,
  `item_siri` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`item_id`, `item_name`, `item_tag`, `item_category`, `brand`, `model`, `item_status`, `item_quantity`, `item_description`, `item_siri`) VALUES
(4, 'Speaker Marshall', 'Series 10', 'Speaker', 'MARSHALL', 'Z313', 'Available', 1, '', ''),
(5, 'Portable Speaker', 'Tag 11', 'Speaker', 'DELL', 'V120', 'Available', 1, '', 'Series 11'),
(10, 'DELL I5', 'tag 20261', 'LAPTOP', 'DELL', 'I5', 'Borrowed', 1, '', '1001'),
(11, 'DELL I5', 'tag 20262', 'LAPTOP', 'DELL', 'I5', 'Borrowed', 1, '', '1002'),
(12, 'DELL I5', 'tag 20263', 'LAPTOP', 'DELL', 'I5', 'Borrowed', 1, '', '1003'),
(25, 'DELL-I5', 'tag 20265', 'LAPTOP', 'DELL', 'DELL-I5', 'Borrowed', 1, 'I5 9THGEN', '1005'),
(26, 'DELL-I5', 'tag 20266', 'LAPTOP', 'DELL', 'DELL-I5', 'Borrowed', 1, 'I5 9THGEN', '1006'),
(27, 'DELL-I5', 'tag 20267', 'LAPTOP', 'DELL', 'DELL-I5', 'Available', 1, 'I5 9THGEN', '1007'),
(28, 'DELL-I5', 'tag 20268', 'LAPTOP', 'DELL', 'DELL-I5', 'Available', 1, 'I5 9THGEN', '1008'),
(29, 'DELL-I5', 'tag 20269', 'LAPTOP', 'DELL', 'DELL-I5', 'Available', 1, 'I5 9THGEN', '1009'),
(30, 'DELL-I7', 'tag 20270', 'LAPTOP', 'DELL', 'DELL-I7', 'Available', 1, 'I7 9THGEN', '1010');

-- --------------------------------------------------------

--
-- Table structure for table `pemohon`
--

CREATE TABLE `pemohon` (
  `pemohon_id` int(11) NOT NULL,
  `pemohon_name` varchar(100) NOT NULL,
  `pemohon_college_id` varchar(50) NOT NULL,
  `pemohon_position` varchar(50) NOT NULL,
  `pemohon_no_tel` varchar(20) NOT NULL,
  `pemohon_email` varchar(255) DEFAULT NULL,
  `pemohon_status` varchar(50) NOT NULL,
  `pemohon_dept` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pemohon`
--

INSERT INTO `pemohon` (`pemohon_id`, `pemohon_name`, `pemohon_college_id`, `pemohon_position`, `pemohon_no_tel`, `pemohon_email`, `pemohon_status`, `pemohon_dept`, `password`) VALUES
(2, 'Aidahani Binti Ahmad', 'FP00652', 'Pensyarah Kanan', '0139848110', NULL, 'Tetap', 'Jabatan Sains Komputer Matematik', '1234'),
(3, 'Sarina Binti Ibrahim', 'STDN00777', 'Student', '019222222222', NULL, 'DIT', 'FSKTM', '1234'),
(4, 'Mohd Fauzi Bin Wahab', 'FP00777', 'Juruteknik', '011333333333', NULL, 'Tetap', 'Jabatan Teknologi Maklumat', '1234'),
(6, 'Danish Haiqal', 'BPN231210136', 'Staff IT', '01991022001', 'dnshqll6269@gmail.com', 'PELAJAR INDUSTRI', 'jtm', '1234'),
(7, 'AKIL', 'BPN231210138', 'PELAJAR', '111110101', 'JDTDANISH1@GMAIL.COM', 'AKTIF', 'ICT', '');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `adminthumbnails` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `name`, `username`, `password`, `adminthumbnails`) VALUES
(1, 'admin', 'admin', 'admin', 'uploads/Python-vs-C_Watermarked.webp'),
(2, 'aida', 'aida', '1234', 'images/NO-IMAGE-AVAILABLE.jpg'),
(3, 'Danish Haiqal', 'admin1', '1234', 'images/NO-IMAGE-AVAILABLE.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`activity_log_id`);

--
-- Indexes for table `borrowing`
--
ALTER TABLE `borrowing`
  ADD PRIMARY KEY (`borrow_id`);

--
-- Indexes for table `borrowing_item`
--
ALTER TABLE `borrowing_item`
  ADD PRIMARY KEY (`borrow_item_id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `pemohon`
--
ALTER TABLE `pemohon`
  ADD PRIMARY KEY (`pemohon_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `activity_log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `borrowing`
--
ALTER TABLE `borrowing`
  MODIFY `borrow_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `borrowing_item`
--
ALTER TABLE `borrowing_item`
  MODIFY `borrow_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `pemohon`
--
ALTER TABLE `pemohon`
  MODIFY `pemohon_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
