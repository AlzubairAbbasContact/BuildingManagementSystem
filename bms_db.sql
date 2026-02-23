-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 23 فبراير 2026 الساعة 02:35
-- إصدار الخادم: 10.4.32-MariaDB
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
-- بنية الجدول `apartments`
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
-- إرجاع أو استيراد بيانات الجدول `apartments`
--

INSERT INTO `apartments` (`id`, `apartment_number`, `floor`, `status`, `notes`, `created_at`) VALUES
(2, '2', 1, 'vacant', 'هاهاهي', '2026-01-26 22:01:18'),
(10, '1', 1, 'occupied', '', '2026-02-22 21:36:47'),
(11, '1', 3, 'vacant', '', '2026-02-22 23:56:18'),
(12, '2', 2, 'vacant', '', '2026-02-23 00:42:23');

-- --------------------------------------------------------

--
-- بنية الجدول `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL,
  `amount_remaining` decimal(10,2) DEFAULT 0.00,
  `payment_date` date NOT NULL,
  `notes` mediumtext DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','canceled') NOT NULL DEFAULT 'active',
  `cancellation_reason` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `payments`
--

INSERT INTO `payments` (`id`, `tenant_id`, `amount_paid`, `amount_remaining`, `payment_date`, `notes`, `created_at`, `status`, `cancellation_reason`) VALUES
(10, 8, 1.00, 99999.00, '2026-02-22', '', '2026-02-22 22:53:03', 'canceled', NULL),
(12, 9, 10000.00, 0.00, '2026-02-23', '', '2026-02-22 23:57:33', 'canceled', 'المستاجر خرج'),
(13, 9, 10000.00, 0.00, '2026-02-23', '', '2026-02-22 23:57:46', 'canceled', NULL),
(14, 9, 10.00, 0.00, '2026-02-23', 'جيب', '2026-02-23 00:12:48', 'canceled', NULL),
(15, 10, 20000.00, 0.00, '2026-02-23', 'كريمي', '2026-02-23 00:18:58', 'canceled', 'غلط في ادخال البيانات'),
(16, 13, 4000.00, 0.00, '2026-02-23', '', '2026-02-23 00:41:36', 'canceled', 'كانت قطع وبطل'),
(17, 14, 20000.00, 0.00, '2026-02-23', '', '2026-02-23 00:56:47', 'active', NULL);

-- --------------------------------------------------------

--
-- بنية الجدول `tenants`
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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `tenants`
--

INSERT INTO `tenants` (`id`, `apartment_id`, `name`, `phone`, `nid`, `rent_amount`, `start_date`, `end_date`, `created_at`, `status`) VALUES
(8, 2, 'محمد', '777777777', '1111111111', 100000.00, '2026-02-22', '2026-02-22', '2026-02-22 22:50:31', 'inactive'),
(9, 10, 'محمد', '777777777', '1111111111', 100000.00, '2026-02-22', '2026-02-22', '2026-02-22 23:56:43', 'inactive'),
(10, 10, 'علي', '777777777', '1111111111', 50000.00, '2026-02-22', '2026-02-22', '2026-02-23 00:17:06', 'inactive'),
(11, 10, 'علي علي', '777777777', '1111111111', 20000.00, '2026-02-22', '2026-02-22', '2026-02-23 00:20:07', 'inactive'),
(12, 10, 'علي علي علي', '777777777', '1111111111', 800000.00, '2026-02-22', '2026-02-22', '2026-02-23 00:27:00', 'inactive'),
(13, 10, 'علي علي علي', '777777777', '1111111111', 20000.00, '2026-02-22', '2026-02-22', '2026-02-23 00:40:11', 'inactive'),
(14, 10, 'علي علي علي', '777777777', '1111111111', 20000.00, '2026-02-22', '2026-02-22', '2026-02-23 00:56:20', 'active');

-- --------------------------------------------------------

--
-- بنية الجدول `users`
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
-- إرجاع أو استيراد بيانات الجدول `users`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tenants`
--
ALTER TABLE `tenants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- قيود الجداول المُلقاة.
--

--
-- قيود الجداول `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`);

--
-- قيود الجداول `tenants`
--
ALTER TABLE `tenants`
  ADD CONSTRAINT `tenants_ibfk_1` FOREIGN KEY (`apartment_id`) REFERENCES `apartments` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
