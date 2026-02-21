-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 27, 2026 at 02:05 AM
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
-- Database: `bms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `apartments`
--

CREATE TABLE `apartments` (
  `id` int(11) NOT NULL,
  `apartment_number` varchar(50) NOT NULL,
  `floor` int(11) NOT NULL,
  `status` enum('vacant','occupied') DEFAULT 'vacant',
  `notes` mediumtext DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `apartments`
--

INSERT INTO `apartments` (`id`, `apartment_number`, `floor`, `status`, `notes`, `created_at`) VALUES
(2, '2', 1, 'occupied', 'هاهاهي', '2026-01-26 22:01:18'),
(6, '1', 1, 'occupied', '', '2026-01-26 23:34:03'),
(7, '3', 1, 'occupied', '', '2026-01-27 00:06:58');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL,
  `amount_remaining` decimal(10,2) DEFAULT 0.00,
  `payment_date` date NOT NULL,
  `notes` mediumtext DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `tenant_id`, `amount_paid`, `amount_remaining`, `payment_date`, `notes`, `created_at`) VALUES
(4, 2, 10.00, 69990.00, '2026-01-27', 'جيب', '2026-01-26 23:23:14'),
(5, 4, 20000.00, 30000.00, '2026-01-27', 'كريمي', '2026-01-27 00:10:18'),
(6, 4, 10000.00, 40000.00, '2026-01-27', '%%%%', '2026-01-27 00:10:51');

-- --------------------------------------------------------

--
-- Table structure for table `tenants`
--

CREATE TABLE `tenants` (
  `id` int(11) NOT NULL,
  `apartment_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `nid` varchar(50) DEFAULT NULL COMMENT 'National ID',
  `rent_amount` decimal(10,2) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tenants`
--

INSERT INTO `tenants` (`id`, `apartment_id`, `name`, `phone`, `nid`, `rent_amount`, `start_date`, `end_date`, `created_at`) VALUES
(2, 2, 'alzi', '777777777', '110110110110', 70000.00, '2026-01-27', '2027-01-27', '2026-01-26 22:14:43'),
(4, 7, 'الزبير', '777777777', '110110110110', 50000.00, '2026-01-27', '2026-01-27', '2026-01-27 00:08:05'),
(5, 6, 'محمد', '777777777', '110110110110', 20000.00, '2026-01-27', '2026-01-27', '2026-01-27 00:12:12');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('admin','user') DEFAULT 'user',
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `image`, `created_at`, `role`, `is_active`) VALUES
(5, 'الزبير عباس', 'test@gmail.com', '$2y$10$OldwpfjtBxlNfk4.60r94Oo4mG88RlZP07xwJP/NHJegpET2N8WEO', '777777777', '6977fe22ac7a1.png', '2026-01-26 23:52:02', 'admin', 1),
(6, 'حسام الحاشدي', 'hosam@gmail.com', '$2y$10$9CcmFKvyxbUn08Oiz865ueqSSVj.vgBwjbFUcNlWDjFiJaJ6ixxIO', '777888999', '69780a0a11c20.png', '2026-01-27 00:42:50', 'user', 1),
(7, 'علي', 'ali@gmail.com', '$2y$10$VTbx9jIsIKe5aVG/FsYuyuvzoUjbAOm73SfWgagftV5jGrfVUhwz6', '777777777', '69780ce79ede0.png', '2026-01-27 00:55:03', 'user', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `apartments`
--
ALTER TABLE `apartments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tenant_id` (`tenant_id`);

--
-- Indexes for table `tenants`
--
ALTER TABLE `tenants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `apartment_id` (`apartment_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `apartments`
--
ALTER TABLE `apartments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tenants`
--
ALTER TABLE `tenants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tenants`
--
ALTER TABLE `tenants`
  ADD CONSTRAINT `tenants_ibfk_1` FOREIGN KEY (`apartment_id`) REFERENCES `apartments` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
