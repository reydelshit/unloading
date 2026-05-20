-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 20, 2026 at 08:03 AM
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
-- Database: `ism`
--

-- --------------------------------------------------------

--
-- Table structure for table `ism_header`
--

CREATE TABLE `ism_header` (
  `id` int(11) NOT NULL,
  `ism_id` varchar(100) NOT NULL,
  `unloading_id` int(111) NOT NULL,
  `ism_no` varchar(100) NOT NULL,
  `client_name` varchar(255) DEFAULT NULL,
  `lot_number` varchar(255) DEFAULT NULL,
  `origin_site` varchar(255) DEFAULT NULL,
  `transfer_site` varchar(255) DEFAULT NULL,
  `bin_no` varchar(255) DEFAULT NULL,
  `prepared_by` varchar(255) DEFAULT NULL,
  `verified_by` varchar(255) DEFAULT NULL,
  `flagging` varchar(100) DEFAULT NULL,
  `isJB` tinyint(1) DEFAULT 1,
  `season` varchar(20) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ism_header`
--

INSERT INTO `ism_header` (`id`, `ism_id`, `unloading_id`, `ism_no`, `client_name`, `lot_number`, `origin_site`, `transfer_site`, `bin_no`, `prepared_by`, `verified_by`, `flagging`, `isJB`, `season`, `date`, `created_at`) VALUES
(4, 'ISM6a0bffdb66531', 1, 'SSC-26-82794', 'syngenta', 'LDHANJKABFJKBAJKF', '2', '4', 'test', '1', '0', '2', 1, '1', '2026-05-19', '2026-05-19 06:14:51'),
(5, 'ISM6a0c019eea8ad', 6, 'SSC-26-38212', 'syngenta', 'LDHANJKABFJKBAJKF', '1', '1', 'test', '1', '3', '1', 1, '2', '2026-05-19', '2026-05-19 06:22:22'),
(6, 'ISM6a0c08c617cff', 5, 'SSC-26-35388', 'syngenta', 'LDHANJKABFJKBAJKF', '1', '2', 'test', '0', '1', '2', 1, '1', '2026-05-19', '2026-05-19 06:52:54');

-- --------------------------------------------------------

--
-- Table structure for table `ism_items`
--

CREATE TABLE `ism_items` (
  `id` int(11) NOT NULL,
  `ism_id` varchar(100) NOT NULL,
  `jb_pallet` varchar(255) DEFAULT NULL,
  `variety_hybrid` varchar(255) DEFAULT NULL,
  `material_group` varchar(255) DEFAULT NULL,
  `batch_lot_number` varchar(255) DEFAULT NULL,
  `bags_sacks_no` int(11) DEFAULT 0,
  `weight` decimal(10,2) DEFAULT 0.00,
  `total_weight` decimal(10,2) DEFAULT 0.00,
  `remarks` text DEFAULT NULL,
  `itemorder` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ism_items`
--

INSERT INTO `ism_items` (`id`, `ism_id`, `jb_pallet`, `variety_hybrid`, `material_group`, `batch_lot_number`, `bags_sacks_no`, `weight`, `total_weight`, `remarks`, `itemorder`, `created_at`) VALUES
(1, 'ISM6a0bf8dd6e01d', 'RETEST', 'PH', 'COM 300', '1312313 / LDHANJKABFJKBAJKF', 1, 1.00, 501.00, 'N.A', 1, '2026-05-19 05:45:01'),
(2, 'ISM6a0bf8dd6e01d', 'TE', 'PH', 'COM 300', '1312313 / LDHANJKABFJKBAJKF', 1, 2.00, 321.00, 'N/A', 2, '2026-05-19 05:45:01'),
(3, 'ISM6a0bfa975773f', 'RETEST', 'PH', 'COM 300', '1312313 / LDHANJKABFJKBAJKF', 1, 1.00, 501.00, 'N.A', 1, '2026-05-19 05:52:23'),
(4, 'ISM6a0bfa975773f', 'TE', 'PH', 'COM 300', '1312313 / LDHANJKABFJKBAJKF', 1, 2.00, 321.00, 'N/A', 2, '2026-05-19 05:52:23'),
(5, 'ISM6a0bfb6e7eb16', 'RETEST', 'PH', 'COM 300', '1312313 / LDHANJKABFJKBAJKF', 1, 1.00, 501.00, 'N.A', 1, '2026-05-19 05:55:58'),
(6, 'ISM6a0bfb6e7eb16', 'TE', 'PH', 'COM 300', '1312313 / LDHANJKABFJKBAJKF', 1, 2.00, 321.00, 'N/A', 2, '2026-05-19 05:55:58'),
(7, 'ISM6a0bffdb66531', 'RETEST', 'PH', 'COM 300', '1312313 / LDHANJKABFJKBAJKF', 1, 1.00, 501.00, NULL, 1, '2026-05-19 06:14:51'),
(8, 'ISM6a0bffdb66531', 'TE', 'PH', 'COM 300', '1312313 / LDHANJKABFJKBAJKF', 1, 2.00, 321.00, NULL, 2, '2026-05-19 06:14:51'),
(9, 'ISM6a0c019eea8ad', 'ADADAD', 'PH', 'COM 300', '1312313 / LDHANJKABFJKBAJKF', 2, 32131.00, 32132131.00, NULL, 1, '2026-05-19 06:22:22'),
(10, 'ISM6a0c019eea8ad', 'DSADADA', 'PH', 'COM 300', '1312313 / LDHANJKABFJKBAJKF', 21, 222.00, 11.00, NULL, 2, '2026-05-19 06:22:22');

-- --------------------------------------------------------

--
-- Table structure for table `unloading`
--

CREATE TABLE `unloading` (
  `unloading_id` int(111) NOT NULL,
  `client` varchar(255) NOT NULL,
  `variety_hybrid` varchar(255) NOT NULL,
  `material_group` varchar(255) NOT NULL,
  `lot_number` varchar(255) NOT NULL,
  `batch_number` varchar(255) NOT NULL,
  `time_start` varchar(255) NOT NULL,
  `time_finished` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `prepared_by` varchar(255) NOT NULL,
  `checked_by` varchar(255) DEFAULT NULL,
  `remarks` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `unloading`
--

INSERT INTO `unloading` (`unloading_id`, `client`, `variety_hybrid`, `material_group`, `lot_number`, `batch_number`, `time_start`, `time_finished`, `created_at`, `prepared_by`, `checked_by`, `remarks`) VALUES
(1, 'syngenta', 'PH', 'COM 300', 'LDHANJKABFJKBAJKF', '1312313', '13:12', '13:13', '2026-05-19 07:17:53', 'REYDEL', 'REYDELLL', 'THISIS IS A TEST POST'),
(2, 'syngenta', 'PH', 'COM 300', 'LDHANJKABFJKBAJKF', '1312313', '14:20', '14:20', '2026-05-19 08:19:56', 'REYDEL', 'REYDELLL', 'DASDADAD'),
(3, 'syngenta', 'PH', 'COM 300', 'LDHANJKABFJKBAJKF', '1312313', '14:20', '14:20', '2026-05-19 08:20:41', 'REYDEL', 'REYDELLL', 'DASDADAD'),
(4, 'syngenta', 'PH', 'COM 300', 'LDHANJKABFJKBAJKF', '1312313', '14:20', '14:20', '2026-05-19 08:20:49', 'REYDEL', 'REYDELLL', 'DASDADAD'),
(5, 'syngenta', 'PH', 'COM 300', 'LDHANJKABFJKBAJKF', '1312313', '14:20', '14:20', '2026-05-19 08:21:20', 'REYDEL', 'REYDELLL', 'DASDADAD'),
(6, 'syngenta', 'PH', 'COM 300', 'LDHANJKABFJKBAJKF', '1312313', '14:20', '14:20', '2026-05-19 08:21:58', 'REYDEL', 'REYDELLL', 'DASDADAD'),
(7, 'REYDEL', 'REYDEL', 'REYDEL', 'REYDEL', 'REYDEL', '01:59', '13:59', '2026-05-20 07:59:44', 'REYDEL', 'REYDEL', 'REYDELREYDEL');

-- --------------------------------------------------------

--
-- Table structure for table `unloading_items`
--

CREATE TABLE `unloading_items` (
  `id` int(11) NOT NULL,
  `unloading_id` int(11) NOT NULL,
  `jb_pallet` varchar(100) DEFAULT NULL,
  `bags_sacks_no` int(11) DEFAULT NULL,
  `weight` decimal(10,2) DEFAULT NULL,
  `total_weight` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `itemorder` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `unloading_items`
--

INSERT INTO `unloading_items` (`id`, `unloading_id`, `jb_pallet`, `bags_sacks_no`, `weight`, `total_weight`, `created_at`, `itemorder`) VALUES
(1, 1, 'RETEST', 1, 1.00, 501.00, '2026-05-19 05:17:53', 1),
(2, 1, 'TE', 1, 2.00, 321.00, '2026-05-19 05:17:53', 2),
(3, 6, 'ADADAD', 2, 32131.00, 32132131.00, '2026-05-19 06:21:58', 1),
(4, 6, 'DSADADA', 21, 222.00, 11.00, '2026-05-19 06:21:58', 2),
(5, 7, 'REYDEL', 2, 21.00, 2121.00, '2026-05-20 05:59:44', 1),
(6, 7, '213REYDEL', 222, 111.00, 1222222.00, '2026-05-20 05:59:44', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ism_header`
--
ALTER TABLE `ism_header`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ism_id` (`ism_id`);

--
-- Indexes for table `ism_items`
--
ALTER TABLE `ism_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `unloading`
--
ALTER TABLE `unloading`
  ADD PRIMARY KEY (`unloading_id`);

--
-- Indexes for table `unloading_items`
--
ALTER TABLE `unloading_items`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ism_header`
--
ALTER TABLE `ism_header`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `ism_items`
--
ALTER TABLE `ism_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `unloading`
--
ALTER TABLE `unloading`
  MODIFY `unloading_id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `unloading_items`
--
ALTER TABLE `unloading_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
