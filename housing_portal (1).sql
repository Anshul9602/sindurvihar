-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 13, 2026 at 01:35 PM
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
-- Database: `housing_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `mobile` varchar(20) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` varchar(50) DEFAULT 'admin',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `mobile`, `email`, `username`, `password_hash`, `role`, `created_at`, `updated_at`) VALUES
(1, 'anshul kumar', '1234567890', 'Admin@gmail.com', '', '$2y$10$Y/tVpaP5TtTKJkZsw.DB.e/4J4QmGFCB86fxetgRt/at/xq8O3PlO', 'admin', '2026-02-10 11:29:54', '2026-02-10 11:29:54');

-- --------------------------------------------------------

--
-- Table structure for table `admin_actions`
--

CREATE TABLE `admin_actions` (
  `id` int(11) UNSIGNED NOT NULL,
  `application_id` int(11) UNSIGNED NOT NULL,
  `admin_id` int(11) UNSIGNED DEFAULT NULL,
  `action_type` varchar(20) NOT NULL COMMENT 'verified or rejected',
  `reason` text DEFAULT NULL COMMENT 'Reason for rejection (if rejected)',
  `notes` text DEFAULT NULL COMMENT 'Additional notes or comments',
  `confirmed` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Whether admin confirmed the action',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_actions`
--

INSERT INTO `admin_actions` (`id`, `application_id`, `admin_id`, `action_type`, `reason`, `notes`, `confirmed`, `created_at`, `updated_at`) VALUES
(1, 4, NULL, 'verified', NULL, NULL, 1, '2026-02-13 07:13:41', '2026-02-13 07:13:41'),
(2, 2, NULL, 'verified', NULL, NULL, 1, '2026-02-13 09:51:03', '2026-02-13 09:51:03');

-- --------------------------------------------------------

--
-- Table structure for table `allotments`
--

CREATE TABLE `allotments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `application_id` bigint(20) UNSIGNED NOT NULL,
  `plot_number` varchar(50) NOT NULL,
  `block_name` varchar(50) DEFAULT NULL,
  `status` enum('provisional','final') DEFAULT 'provisional',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `allotments`
--

INSERT INTO `allotments` (`id`, `application_id`, `plot_number`, `block_name`, `status`, `created_at`) VALUES
(1, 2, '1230456', 'kgjnkdskg', 'provisional', '2026-02-13 12:22:57');

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `scheme_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('draft','submitted','paid','under_verification','verified','rejected','clarification','selected','allotted','possession') DEFAULT 'draft',
  `full_name` varchar(191) NOT NULL,
  `aadhaar` varchar(20) NOT NULL,
  `father_husband_name` varchar(150) NOT NULL,
  `age` int(3) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `tehsil` varchar(100) NOT NULL,
  `district` varchar(100) NOT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `income` decimal(12,2) NOT NULL,
  `income_category` varchar(100) NOT NULL,
  `declaration_truth` tinyint(1) NOT NULL DEFAULT 0,
  `declaration_cancellation` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `user_id`, `scheme_id`, `status`, `full_name`, `aadhaar`, `father_husband_name`, `age`, `mobile`, `address`, `tehsil`, `district`, `city`, `state`, `income`, `income_category`, `declaration_truth`, `declaration_cancellation`, `created_at`, `updated_at`) VALUES
(2, 1, NULL, 'selected', 'anshul kumar', '641107520610', 'bhanwar', 25, '9602964437', 'jaipur', 'danta', 'sikr', NULL, 'Rajasthan', 59999.00, '', 1, 1, '2026-02-10 10:48:26', '2026-02-13 12:22:57'),
(3, 4, NULL, 'draft', 'anshul kumar', '641107520611', 'bhanwar', 25, '9602964438', '894, SANTINAGR DURGAPRA', 'danta', 'sikr', NULL, 'Rajasthan', 500000.00, 'SC', 1, 1, '2026-02-12 08:37:14', '2026-02-12 08:37:14'),
(4, 5, NULL, 'verified', 'anshul kumar', '641107520612', 'bhanwar', 18, '01234567899', 'jaipur', 'danta', 'sikr', NULL, 'Rajasthan', 600000.00, 'State Govt Employee', 1, 1, '2026-02-13 06:15:27', '2026-02-13 07:13:41');

-- --------------------------------------------------------

--
-- Table structure for table `application_documents`
--

CREATE TABLE `application_documents` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `application_id` int(11) UNSIGNED NOT NULL,
  `has_identity_proof` tinyint(1) NOT NULL DEFAULT 0,
  `has_income_proof` tinyint(1) NOT NULL DEFAULT 0,
  `has_residence_proof` tinyint(1) NOT NULL DEFAULT 0,
  `identity_files` text DEFAULT NULL,
  `income_files` text DEFAULT NULL,
  `residence_files` text DEFAULT NULL,
  `annexure_files` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application_documents`
--

INSERT INTO `application_documents` (`id`, `user_id`, `application_id`, `has_identity_proof`, `has_income_proof`, `has_residence_proof`, `identity_files`, `income_files`, `residence_files`, `annexure_files`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 1, 1, 1, '[\"uploads\\/documents\\/1\\/1770722219_4ae5841e2e6dc50e29f3.pdf\"]', '[\"uploads\\/documents\\/1\\/1770722219_d7c17925fc1b74f6527b.pdf\"]', '[\"uploads\\/documents\\/1\\/1770722219_6ca88283cea5be93101b.pdf\"]', '[\"uploads\\/documents\\/1\\/1770722219_4f17bf76d21518de8ce1.pdf\"]', 'shjghdkf', '2026-02-10 11:16:59', '2026-02-10 11:16:59'),
(2, 4, 3, 1, 1, 1, '[\"uploads\\/documents\\/4\\/1770885552_209b40da1f7cb26707c6.jpg\"]', '[\"uploads\\/documents\\/4\\/1770885552_5ecc9c4fe3b7195dc01f.jpg\"]', '[\"uploads\\/documents\\/4\\/1770885552_3148787a075cac6a59f7.jpg\"]', '[\"uploads\\/documents\\/4\\/1770885552_45f25fb8dcbd9cb8c4fd.jpg\"]', '', '2026-02-12 08:39:12', '2026-02-12 08:39:12'),
(3, 5, 4, 1, 1, 1, '[\"uploads\\/documents\\/5\\/1770966347_8b78f7e9f47ae096f751.jpg\"]', '[\"uploads\\/documents\\/5\\/1770966347_63fc73730073dc5e22c2.png\"]', '[\"uploads\\/documents\\/5\\/1770966347_f464439d5afc3c4a97b6.png\"]', '[\"uploads\\/documents\\/5\\/1770966347_6ed8d815761b23b33fbb.jpg\"]', '', '2026-02-13 07:05:47', '2026-02-13 07:05:47');

-- --------------------------------------------------------

--
-- Table structure for table `eligibilities`
--

CREATE TABLE `eligibilities` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `age` int(3) DEFAULT NULL,
  `income` bigint(20) DEFAULT NULL,
  `residency` varchar(50) DEFAULT NULL,
  `property_status` varchar(50) DEFAULT NULL,
  `is_eligible` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `eligibilities`
--

INSERT INTO `eligibilities` (`id`, `user_id`, `age`, `income`, `residency`, `property_status`, `is_eligible`, `created_at`, `updated_at`) VALUES
(3, 1, 25, 45000, 'state', 'none', 1, '2026-02-10 09:02:07', '2026-02-11 06:12:35'),
(4, 2, 33, 333333333, 'state', 'none', 1, '2026-02-11 05:51:47', '2026-02-11 05:51:47'),
(5, 4, 25, 100000, 'state', 'none', 1, '2026-02-12 08:34:26', '2026-02-12 08:34:26'),
(6, 5, 18, 45000, 'state', 'none', 1, '2026-02-13 06:13:43', '2026-02-13 06:13:43');

-- --------------------------------------------------------

--
-- Table structure for table `forgot_otps`
--

CREATE TABLE `forgot_otps` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `otp` varchar(10) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lotteries`
--

CREATE TABLE `lotteries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `scheme_id` bigint(20) UNSIGNED NOT NULL,
  `run_at` datetime NOT NULL,
  `seed_hash` varchar(191) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `application_id` int(11) UNSIGNED DEFAULT NULL,
  `amount` bigint(20) NOT NULL DEFAULT 0,
  `status` varchar(20) NOT NULL DEFAULT 'success',
  `transaction_ref` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `user_id`, `application_id`, `amount`, `status`, `transaction_ref`, `created_at`, `updated_at`) VALUES
(1, 5, 4, 1000, 'success', NULL, '2026-02-13 07:04:53', '2026-02-13 07:04:53'),
(2, 1, 2, 1000, 'success', NULL, '2026-02-13 09:41:30', '2026-02-13 09:41:30');

-- --------------------------------------------------------

--
-- Table structure for table `plots`
--

CREATE TABLE `plots` (
  `id` int(11) UNSIGNED NOT NULL,
  `plot_name` varchar(255) NOT NULL,
  `plot_number` varchar(50) DEFAULT NULL,
  `category` varchar(50) NOT NULL COMMENT 'EWS, LIG, MIG-A, MIG-B, HIG, General, etc.',
  `dimensions` varchar(100) DEFAULT NULL COMMENT 'e.g., 30x40, 40x60, etc.',
  `area` decimal(10,2) DEFAULT NULL COMMENT 'Area in square feet or square meters',
  `location` varchar(255) NOT NULL,
  `plot_image` varchar(255) DEFAULT NULL COMMENT 'Path to plot image',
  `quantity` int(11) UNSIGNED NOT NULL DEFAULT 1,
  `available_quantity` int(11) UNSIGNED NOT NULL DEFAULT 1,
  `price` decimal(12,2) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'available' COMMENT 'available, allocated, reserved',
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `plots`
--

INSERT INTO `plots` (`id`, `plot_name`, `plot_number`, `category`, `dimensions`, `area`, `location`, `plot_image`, `quantity`, `available_quantity`, `price`, `status`, `description`, `created_at`, `updated_at`) VALUES
(3, 'test plot ', '1230456', 'SC', '100x150', 1500.00, 'kgjnkdskg', 'uploads/plots/1770983449_5ec66ca0a70bba2d6206.jpg', 1, 0, 5000.00, 'allotted', '', '2026-02-13 11:50:49', '2026-02-13 12:22:57');

-- --------------------------------------------------------

--
-- Table structure for table `schemes`
--

CREATE TABLE `schemes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `lottery_date` date DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `email` varchar(191) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `name` varchar(191) NOT NULL,
  `language` varchar(5) DEFAULT 'en',
  `category` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `mobile`, `email`, `password_hash`, `name`, `language`, `category`, `created_at`, `updated_at`) VALUES
(1, '9602964437', 'anshulkumar969602@gmail.com', '$2y$10$KwbgeI0p8KCMnDXJ.Lj3.eUv.H/S257qwaQVvfsmvJE7xy4UQEn1C', 'anshul kumar', 'en', 'SC', '2026-02-10 07:41:57', '2026-02-13 09:48:58'),
(2, '8949465158', NULL, '$2y$10$0KCIzQBIFHocWU8bO.qcgOTZkNFNETQLydgFx4jVF1owEb0VUHywm', 'User 5158', 'en', NULL, '2026-02-11 05:49:55', '2026-02-11 05:49:55'),
(3, '1234567890', NULL, '$2y$10$lwAhNG8fmChkIg.TQ9mFOObRFZrUMTdH8Zl3WRQ63ZAGpuwPGGpvS', 'User 7890', 'en', NULL, '2026-02-12 07:10:10', '2026-02-12 07:10:10'),
(4, '9602964438', NULL, '$2y$10$0Vk.C6Um1tyRlPWULsgySuLv.WnzdDCtyuDiMPaKzZh/NCxGLzzg2', 'User 4438', 'en', NULL, '2026-02-12 08:32:37', '2026-02-12 08:32:37'),
(5, '1234567899', 'Admin@gmail.com', '$2y$10$sBKEvBmZb2orGDW/vvzWuOf3xqJbTrtM/MuYAgdQcGSaSBmkH6AUO', 'anshul kumar', 'en', 'General', '2026-02-13 05:52:58', '2026-02-13 05:52:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `uniq_admin_mobile` (`mobile`);

--
-- Indexes for table `admin_actions`
--
ALTER TABLE `admin_actions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_id` (`application_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `allotments`
--
ALTER TABLE `allotments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_allot_app` (`application_id`);

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_app_mobile` (`mobile`),
  ADD UNIQUE KEY `uniq_app_aadhaar` (`aadhaar`),
  ADD KEY `fk_app_user` (`user_id`);

--
-- Indexes for table `application_documents`
--
ALTER TABLE `application_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_app` (`user_id`,`application_id`);

--
-- Indexes for table `eligibilities`
--
ALTER TABLE `eligibilities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_elig_user` (`user_id`);

--
-- Indexes for table `forgot_otps`
--
ALTER TABLE `forgot_otps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `forgot_otps_user_id` (`user_id`);

--
-- Indexes for table `lotteries`
--
ALTER TABLE `lotteries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_lot_scheme` (`scheme_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pay_user` (`user_id`),
  ADD KEY `idx_pay_app` (`application_id`);

--
-- Indexes for table `plots`
--
ALTER TABLE `plots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category` (`category`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `schemes`
--
ALTER TABLE `schemes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mobile` (`mobile`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admin_actions`
--
ALTER TABLE `admin_actions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `allotments`
--
ALTER TABLE `allotments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `application_documents`
--
ALTER TABLE `application_documents`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `eligibilities`
--
ALTER TABLE `eligibilities`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `forgot_otps`
--
ALTER TABLE `forgot_otps`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `lotteries`
--
ALTER TABLE `lotteries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `plots`
--
ALTER TABLE `plots`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `schemes`
--
ALTER TABLE `schemes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `allotments`
--
ALTER TABLE `allotments`
  ADD CONSTRAINT `fk_allot_app` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`);

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `fk_app_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `lotteries`
--
ALTER TABLE `lotteries`
  ADD CONSTRAINT `fk_lot_scheme` FOREIGN KEY (`scheme_id`) REFERENCES `schemes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
