-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 16, 2026 at 11:58 AM
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
(2, 2, NULL, 'verified', NULL, NULL, 1, '2026-02-13 09:51:03', '2026-02-13 09:51:03'),
(3, 6, NULL, 'verified', NULL, NULL, 1, '2026-02-16 07:34:06', '2026-02-16 07:34:06'),
(4, 7, NULL, 'verified', NULL, NULL, 1, '2026-02-16 09:44:42', '2026-02-16 09:44:42');

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
(4, 7, 'L9', 'Housing Scheme', 'provisional', '2026-02-16 10:53:39'),
(5, 6, 'E18', 'Housing Scheme', 'provisional', '2026-02-16 10:53:39');

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
  `caste_category` varchar(50) DEFAULT NULL COMMENT 'SC, ST, OBC, GENERAL',
  `is_disabled` tinyint(1) DEFAULT 0 COMMENT '5% reservation for disabled',
  `is_single_woman` tinyint(1) DEFAULT 0 COMMENT '10% reservation for single woman/widow',
  `is_transgender` tinyint(1) DEFAULT 0 COMMENT 'Transgender reservation',
  `is_army` tinyint(1) DEFAULT 0 COMMENT 'Army/Ex-serviceman reservation',
  `is_media` tinyint(1) DEFAULT 0 COMMENT 'Media reservation',
  `is_govt_employee` tinyint(1) DEFAULT 0 COMMENT 'Govt employee reservation',
  `declaration_truth` tinyint(1) NOT NULL DEFAULT 0,
  `declaration_cancellation` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `user_id`, `scheme_id`, `status`, `full_name`, `aadhaar`, `father_husband_name`, `age`, `mobile`, `address`, `tehsil`, `district`, `city`, `state`, `income`, `income_category`, `caste_category`, `is_disabled`, `is_single_woman`, `is_transgender`, `is_army`, `is_media`, `is_govt_employee`, `declaration_truth`, `declaration_cancellation`, `created_at`, `updated_at`) VALUES
(6, 1, NULL, 'selected', 'anshul kumar', '641107520610', 'bhanwar', 26, '9602964437', 'jaipur', 'danta', 'sikr', NULL, 'Rajasthan', 56000.00, 'EWS', 'SC', 0, 0, 0, 0, 0, 0, 1, 1, '2026-02-16 07:31:35', '2026-02-16 10:53:39'),
(7, 6, NULL, 'selected', 'anshul kumar', '641107520612', 'bhanwar', 25, '1234567879', 'jaipur', 'danta', 'sikr', NULL, 'Rajasthan', 50000.00, 'LIG', 'SC', 1, 0, 0, 0, 0, 0, 1, 1, '2026-02-16 09:19:26', '2026-02-16 10:53:39');

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
(4, 1, 6, 1, 1, 1, '[\"uploads\\/documents\\/1\\/1771227223_4c1d2971a07409974e36.jpg\"]', '[\"uploads\\/documents\\/1\\/1771227223_5465e7164c21e1a97d71.jpg\"]', '[\"uploads\\/documents\\/1\\/1771227223_e2c9a65067e1360d7bbd.jpg\"]', '[\"uploads\\/documents\\/1\\/1771227223_8688d8fbd8240660ac41.jpg\"]', '', '2026-02-16 07:33:43', '2026-02-16 07:33:43'),
(5, 6, 7, 1, 1, 1, '[\"uploads\\/documents\\/6\\/1771233760_06af89af6f95187a430e.jpg\"]', '[\"uploads\\/documents\\/6\\/1771233760_9735a6566c407aeb4054.jpg\"]', '[\"uploads\\/documents\\/6\\/1771233760_b4551da1e8f105930b56.jpg\"]', '[\"uploads\\/documents\\/6\\/1771233760_9df1c32ae6be60bf4e47.jpg\"]', '', '2026-02-16 09:22:40', '2026-02-16 09:22:40');

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
(6, 5, 18, 45000, 'state', 'none', 1, '2026-02-13 06:13:43', '2026-02-13 06:13:43'),
(7, 6, 25, 40000, 'state', 'none', 1, '2026-02-16 08:59:28', '2026-02-16 08:59:28'),
(8, 7, 33, 45466, 'state', 'none', 1, '2026-02-16 10:53:11', '2026-02-16 10:53:11');

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
(3, 1, 6, 1000, 'success', NULL, '2026-02-16 07:32:54', '2026-02-16 07:32:54'),
(4, 6, 7, 1000, 'success', NULL, '2026-02-16 09:20:35', '2026-02-16 09:20:35');

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
(4, 'EWS', 'E1', 'EWS', NULL, 45.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(5, 'EWS', 'E2', 'EWS', NULL, 45.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(6, 'EWS', 'E3', 'EWS', NULL, 45.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(7, 'EWS', 'E4', 'EWS', NULL, 45.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(8, 'EWS', 'E5', 'EWS', NULL, 45.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(9, 'EWS', 'E6', 'EWS', NULL, 45.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(10, 'EWS', 'E7', 'EWS', NULL, 45.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(11, 'EWS', 'E8', 'EWS', NULL, 45.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(12, 'EWS', 'E9', 'EWS', NULL, 45.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(13, 'EWS', 'E10', 'EWS', NULL, 45.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(14, 'EWS', 'E11', 'EWS', NULL, 45.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(15, 'EWS', 'E12', 'EWS', NULL, 45.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(16, 'EWS', 'E13', 'EWS', NULL, 45.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(17, 'EWS', 'E14', 'EWS', NULL, 45.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(18, 'EWS', 'E15', 'EWS', NULL, 45.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(19, 'EWS', 'E16', 'EWS', NULL, 45.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(20, 'EWS', 'E17', 'EWS', NULL, 45.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(21, 'EWS', 'E18', 'EWS', NULL, 45.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'allotted', NULL, '2026-02-16 06:04:32', '2026-02-16 10:53:39'),
(22, 'EWS', 'E19', 'EWS', NULL, 45.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'allotted', NULL, '2026-02-16 06:04:32', '2026-02-16 10:46:54'),
(23, 'EWS', 'E20', 'EWS', NULL, 45.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(24, 'EWS', 'E21', 'EWS', NULL, 45.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(25, 'EWS', 'E22', 'EWS', NULL, 45.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(26, 'EWS', 'E23', 'EWS', NULL, 45.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(27, 'EWS', 'E24', 'EWS', NULL, 45.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(28, 'EWS', 'E25', 'EWS', NULL, 45.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(29, 'EWS', 'E26', 'EWS', NULL, 45.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(30, 'EWS', 'E27', 'EWS', NULL, 45.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(31, 'EWS', 'E28', 'EWS', NULL, 45.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(32, 'EWS', 'E29', 'EWS', NULL, 45.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(33, 'EWS', 'E30', 'EWS', NULL, 45.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(34, 'EWS', 'E31', 'EWS', NULL, 45.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(35, 'EWS', 'E32', 'EWS', NULL, 45.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(36, 'EWS', 'E33', 'EWS', NULL, 45.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(37, 'EWS', 'E34', 'EWS', NULL, 45.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(38, 'EWS', 'E35', 'EWS', NULL, 45.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(39, 'EWS', 'E36', 'EWS', NULL, 45.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(40, 'EWS', 'E37', 'EWS', NULL, 45.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'allotted', NULL, '2026-02-16 06:04:32', '2026-02-16 10:46:54'),
(41, 'EWS', 'E38', 'EWS', NULL, 45.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(42, 'EWS', 'E39', 'EWS', NULL, 45.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(43, 'EWS', 'E40', 'EWS', NULL, 45.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(44, 'EWS', 'E41', 'EWS', NULL, 45.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(45, 'EWS', 'E42', 'EWS', NULL, 45.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(46, 'LIG', 'L1', 'LIG', NULL, 48.22, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(47, 'LIG', 'L2', 'LIG', NULL, 49.77, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(48, 'LIG', 'L3', 'LIG', NULL, 48.22, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(49, 'LIG', 'L4', 'LIG', NULL, 49.77, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(50, 'LIG', 'L5', 'LIG', NULL, 61.27, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(51, 'LIG', 'L6', 'LIG', NULL, 64.17, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(52, 'LIG', 'L7', 'LIG', NULL, 48.22, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(53, 'LIG', 'L8', 'LIG', NULL, 49.77, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(54, 'LIG', 'L9', 'LIG', NULL, 61.27, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'allotted', NULL, '2026-02-16 06:04:32', '2026-02-16 10:53:39'),
(55, 'LIG', 'L10', 'LIG', NULL, 64.17, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(56, 'LIG', 'L11', 'LIG', NULL, 70.15, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:32', '2026-02-16 11:44:04'),
(57, 'LIG', 'L12', 'LIG', '', 73.78, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, 0.00, 'available', '', '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(58, 'LIG', 'L13', 'LIG', NULL, 48.22, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(59, 'LIG', 'L14', 'LIG', NULL, 49.77, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(60, 'LIG', 'L15', 'LIG', NULL, 61.27, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(61, 'LIG', 'L16', 'LIG', NULL, 64.17, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(62, 'LIG', 'L17', 'LIG', NULL, 70.15, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(63, 'LIG', 'L18', 'LIG', NULL, 73.78, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(64, 'LIG', 'L19', 'LIG', NULL, 79.27, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(65, 'LIG', 'L20', 'LIG', NULL, 48.22, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(66, 'LIG', 'L21', 'LIG', NULL, 49.77, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(67, 'LIG', 'L22', 'LIG', NULL, 61.27, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(68, 'LIG', 'L23', 'LIG', NULL, 64.17, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(69, 'LIG', 'L24', 'LIG', NULL, 70.15, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(70, 'LIG', 'L25', 'LIG', NULL, 73.78, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(71, 'LIG', 'L26', 'LIG', NULL, 79.27, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(72, 'LIG', 'L27', 'LIG', NULL, 48.22, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(73, 'LIG', 'L28', 'LIG', NULL, 49.77, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(74, 'LIG', 'L29', 'LIG', NULL, 61.27, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(75, 'LIG', 'L30', 'LIG', NULL, 64.17, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(76, 'LIG', 'L31', 'LIG', NULL, 70.15, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(77, 'LIG', 'L32', 'LIG', NULL, 73.78, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(78, 'LIG', 'L33', 'LIG', NULL, 79.27, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(79, 'LIG', 'L34', 'LIG', NULL, 48.22, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(80, 'LIG', 'L35', 'LIG', NULL, 49.77, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(81, 'LIG', 'L36', 'LIG', NULL, 61.27, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(82, 'LIG', 'L37', 'LIG', NULL, 64.17, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(83, 'LIG', 'L38', 'LIG', NULL, 70.15, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(84, 'LIG', 'L39', 'LIG', NULL, 73.78, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(85, 'LIG', 'L40', 'LIG', NULL, 79.27, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(86, 'LIG', 'L41', 'LIG', NULL, 48.22, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(87, 'LIG', 'L42', 'LIG', NULL, 49.77, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(88, 'LIG', 'L43', 'LIG', NULL, 61.27, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(89, 'LIG', 'L44', 'LIG', NULL, 64.17, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(90, 'LIG', 'L45', 'LIG', NULL, 70.15, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(91, 'LIG', 'L46', 'LIG', NULL, 73.78, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(92, 'Residential', '1', 'Residential', NULL, 108.35, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(93, 'Residential', '2', 'Residential', NULL, 108.46, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(94, 'Residential', '3', 'Residential', NULL, 114.30, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(95, 'Residential', '4', 'Residential', NULL, 108.35, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(96, 'Residential', '5', 'Residential', NULL, 108.46, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(97, 'Residential', '6', 'Residential', NULL, 114.30, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(98, 'Residential', '7', 'Residential', NULL, 129.54, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(99, 'Residential', '8', 'Residential', NULL, 108.35, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(100, 'Residential', '9', 'Residential', NULL, 108.46, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(101, 'Residential', '10', 'Residential', NULL, 114.30, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(102, 'Residential', '11', 'Residential', NULL, 129.54, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(103, 'Residential', '12', 'Residential', NULL, 137.16, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(104, 'Residential', '13', 'Residential', NULL, 108.35, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(105, 'Residential', '14', 'Residential', NULL, 108.46, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(106, 'Residential', '15', 'Residential', NULL, 114.30, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(107, 'Residential', '16', 'Residential', NULL, 129.54, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(108, 'Residential', '17', 'Residential', NULL, 137.16, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(109, 'Residential', '18', 'Residential', NULL, 140.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(110, 'Residential', '19', 'Residential', NULL, 108.35, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(111, 'Residential', '20', 'Residential', NULL, 108.46, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(112, 'Residential', '21', 'Residential', NULL, 114.30, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(113, 'Residential', '22', 'Residential', NULL, 129.54, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(114, 'Residential', '23', 'Residential', NULL, 137.16, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(115, 'Residential', '24', 'Residential', NULL, 140.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(116, 'Residential', '25', 'Residential', NULL, 152.40, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(117, 'Residential', '26', 'Residential', NULL, 108.35, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(118, 'Residential', '27', 'Residential', NULL, 108.46, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(119, 'Residential', '28', 'Residential', NULL, 114.30, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(120, 'Residential', '29', 'Residential', NULL, 129.54, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(121, 'Residential', '30', 'Residential', NULL, 137.16, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(122, 'Residential', '31', 'Residential', NULL, 140.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(123, 'Residential', '32', 'Residential', NULL, 152.40, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(124, 'Residential', '33', 'Residential', NULL, 153.59, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(125, 'Residential', '34', 'Residential', NULL, 108.35, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(126, 'Residential', '35', 'Residential', NULL, 108.46, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(127, 'Residential', '36', 'Residential', NULL, 114.30, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(128, 'Residential', '37', 'Residential', NULL, 129.54, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(129, 'Residential', '38', 'Residential', NULL, 137.16, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(130, 'Residential', '39', 'Residential', NULL, 140.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(131, 'Residential', '40', 'Residential', NULL, 152.40, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(132, 'Residential', '41', 'Residential', NULL, 153.59, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(133, 'Residential', '42', 'Residential', NULL, 108.35, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(134, 'Residential', '43', 'Residential', NULL, 108.46, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(135, 'Residential', '44', 'Residential', NULL, 114.30, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(136, 'Residential', '45', 'Residential', NULL, 129.54, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(137, 'Residential', '46', 'Residential', NULL, 137.16, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(138, 'Residential', '47', 'Residential', NULL, 140.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(139, 'Residential', '48', 'Residential', NULL, 152.40, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(140, 'Residential', '49', 'Residential', NULL, 153.59, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(141, 'Residential', '50', 'Residential', NULL, 108.35, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(142, 'Residential', '51', 'Residential', NULL, 108.46, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(143, 'Residential', '52', 'Residential', NULL, 114.30, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(144, 'Residential', '53', 'Residential', NULL, 129.54, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(145, 'Residential', '54', 'Residential', NULL, 137.16, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(146, 'Residential', '55', 'Residential', NULL, 140.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(147, 'Residential', '56', 'Residential', NULL, 152.40, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(148, 'Residential', '57', 'Residential', NULL, 153.59, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(149, 'Residential', '58', 'Residential', NULL, 108.35, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(150, 'Residential', '59', 'Residential', NULL, 108.46, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(151, 'Residential', '60', 'Residential', NULL, 114.30, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(152, 'Residential', '61', 'Residential', NULL, 129.54, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(153, 'Residential', '62', 'Residential', NULL, 137.16, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(154, 'Residential', '63', 'Residential', NULL, 140.00, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04'),
(155, 'Residential', '64', 'Residential', NULL, 152.40, 'Housing Scheme', 'uploads/plots/1771222181_a97e776d724c70dce040.jpg', 1, 1, NULL, 'available', NULL, '2026-02-16 06:04:33', '2026-02-16 11:44:04');

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
(5, '1234567899', 'Admin@gmail.com', '$2y$10$sBKEvBmZb2orGDW/vvzWuOf3xqJbTrtM/MuYAgdQcGSaSBmkH6AUO', 'anshul kumar', 'en', 'General', '2026-02-13 05:52:58', '2026-02-13 05:52:58'),
(6, '1234567879', 'admin1@gmail.com', '$2y$10$3n8HGgposJwvMk2Zv.1VvefkugG2ap3nhGF7pZ0JenLAxXznpZWrS', 'anshulA kumar', 'en', 'SC', '2026-02-16 08:59:13', '2026-02-16 08:59:13'),
(7, '1234567833', 'anshulkumar96960211@gmail.com', '$2y$10$qJmFRVmMvGuLLQCn7WktjOgWA.sBT3At9SaulUXmnvK0LRkyNHXBu', 'anshul kumar', 'en', 'ST', '2026-02-16 10:52:54', '2026-02-16 10:52:54');

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
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `allotments`
--
ALTER TABLE `allotments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `application_documents`
--
ALTER TABLE `application_documents`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `eligibilities`
--
ALTER TABLE `eligibilities`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `plots`
--
ALTER TABLE `plots`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=156;

--
-- AUTO_INCREMENT for table `schemes`
--
ALTER TABLE `schemes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
