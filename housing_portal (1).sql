-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 11, 2026 at 08:34 AM
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
(2, 1, NULL, 'draft', 'anshul kumar', '641107520610', 'bhanwar', 25, '01234567890', 'jaipur', 'danta', 'sikr', NULL, 'Rajasthan', 59999.00, 'SC', 1, 1, '2026-02-10 10:48:26', '2026-02-11 11:54:32');

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
(1, 1, 2, 1, 1, 1, '[\"uploads\\/documents\\/1\\/1770722219_4ae5841e2e6dc50e29f3.pdf\"]', '[\"uploads\\/documents\\/1\\/1770722219_d7c17925fc1b74f6527b.pdf\"]', '[\"uploads\\/documents\\/1\\/1770722219_6ca88283cea5be93101b.pdf\"]', '[\"uploads\\/documents\\/1\\/1770722219_4f17bf76d21518de8ce1.pdf\"]', 'shjghdkf', '2026-02-10 11:16:59', '2026-02-10 11:16:59');

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
(4, 2, 33, 333333333, 'state', 'none', 1, '2026-02-11 05:51:47', '2026-02-11 05:51:47');

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
(1, 'test plot ', '1230456', 'EWS:10,MIG-A:5', '100*1000', 1000.00, 'kgjnkdskg', 'uploads/plots/1770794605_09f0026721b5034d60ef.jpg', 15, 15, 1350.00, 'available', 'agzh', '2026-02-11 07:23:25', '2026-02-11 07:23:25');

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
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `mobile`, `email`, `password_hash`, `name`, `language`, `created_at`, `updated_at`) VALUES
(1, '9602964437', 'anshulkumar969602@gmail.com', '$2y$10$pCROr1T/TGC0rjdjJyFBBuzTLvY5/jBvXzzw1Cyf1jJMlQQJMh9eS', 'anshul kumar', 'en', '2026-02-10 07:41:57', '2026-02-10 07:50:15'),
(2, '8949465158', NULL, '$2y$10$0KCIzQBIFHocWU8bO.qcgOTZkNFNETQLydgFx4jVF1owEb0VUHywm', 'User 5158', 'en', '2026-02-11 05:49:55', '2026-02-11 05:49:55');

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
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `allotments`
--
ALTER TABLE `allotments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `application_documents`
--
ALTER TABLE `application_documents`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `eligibilities`
--
ALTER TABLE `eligibilities`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plots`
--
ALTER TABLE `plots`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `schemes`
--
ALTER TABLE `schemes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
