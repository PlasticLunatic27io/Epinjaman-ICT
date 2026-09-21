-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Generation Time: Jan 20, 2026 at 10:41 AM
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
  `type` enum('Luaran','Dalaman') NOT NULL,
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
(2, 'FP00652', 'Laptop', '2025-01-23 10:26:36', '2025-01-30 08:30:00', '2025-01-31 05:00:00', 'Baru', 'Luaran', '0', '0', 'Ya', '0000-00-00 00:00:00', '', ''),
(3, 'FP00652', 'Keyboard', '2025-02-03 09:15:24', '2025-02-05 08:59:00', '2025-02-05 17:30:00', 'Lulus', 'Luaran', '0', '0', 'Tidak', '2025-03-04 03:47:07', 'aida', ''),
(4, 'FP00652', 'Speaker', '2025-02-03 09:20:23', '2025-02-19 08:00:00', '2025-02-20 16:30:00', 'Lulus', 'Dalaman', 'Khidmat Masyarat Program at Rumah anak yatim kluang', 'Rumah anak yatim kluang', 'Ya', '2025-02-03 03:04:46', 'aida', '');

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
  `notes` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrowing_item`
--

INSERT INTO `borrowing_item` (`borrow_item_id`, `borrow_id`, `pemohon_college_id`, `item_tag`, `item_qty`, `status`, `return_date`, `notes`) VALUES
(1, '4', 'FP00652', 'Tag 11', 1, 'Returned', '0000-00-00 00:00:00', ''),
(2, '1', 'STDN00777', 'tag1', 1, 'Borrowed', '0000-00-00 00:00:00', ''),
(3, '3', 'FP00652', 'Series 10', 1, 'Returned', '0000-00-00 00:00:00', 'Good condition'),
(4, '2', 'FP00652', 'Tag21', 1, 'Borrowed', '0000-00-00 00:00:00', '');

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `item_id` int(11) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `item_tag` varchar(50) NOT NULL,
  `item_category` varchar(50) NOT NULL,
  `item_status` enum('Available','Borrowed','Maintenance') NOT NULL,
  `item_quantity` int(10) NOT NULL,
  `item_description` varchar(100) NOT NULL,
  `item_siri` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`item_id`, `item_name`, `item_tag`, `item_category`, `item_status`, `item_quantity`, `item_description`, `item_siri`) VALUES
(1, 'Laptop HP ZBook', 'tag1', 'Laptop', 'Borrowed', 1, '', ''),
(4, 'Speaker Marshall', 'Series 10', 'Speaker', 'Available', 1, '', ''),
(5, 'Portable Speaker', 'Tag 11', 'Speaker', 'Available', 1, '', 'Series 11'),
(6, 'Laptop 2', 'Tag21', 'Laptop', 'Borrowed', 1, '', 'Series21');

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
  `pemohon_status` varchar(50) NOT NULL,
  `pemohon_dept` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pemohon`
--

INSERT INTO `pemohon` (`pemohon_id`, `pemohon_name`, `pemohon_college_id`, `pemohon_position`, `pemohon_no_tel`, `pemohon_status`, `pemohon_dept`) VALUES
(2, 'Aidahani Binti Ahmad', 'FP00652', 'Pensyarah Kanan', '0139848110', 'Tetap', 'Jabatan Sains Komputer Matematik'),
(3, 'Sarina Binti Ibrahim', 'STDN00777', 'Student', '019222222222', 'DIT', 'FSKTM'),
(4, 'Mohd Fauzi Bin Wahab', 'FP00777', 'Juruteknik', '011333333333', 'Tetap', 'Jabatan Teknologi Maklumat');

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
(1, 'admin', 'admin', 'admin', 'uploads/unavailable_image.png'),
(2, 'aida', 'aida', '1234', 'images/NO-IMAGE-AVAILABLE.jpg');

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
  MODIFY `borrow_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `borrowing_item`
--
ALTER TABLE `borrowing_item`
  MODIFY `borrow_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pemohon`
--
ALTER TABLE `pemohon`
  MODIFY `pemohon_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
