-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 20, 2025 at 06:17 AM
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
-- Database: `transaction_recording_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `branch_id` int(10) UNSIGNED DEFAULT NULL,
  `transaction_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `action_type` varchar(55) DEFAULT NULL,
  `old_data` longtext DEFAULT NULL,
  `new_data` longtext DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` int(10) UNSIGNED NOT NULL,
  `branch_name` varchar(255) NOT NULL,
  `address` varchar(500) DEFAULT NULL,
  `current_coh` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `branch_name`, `address`, `current_coh`, `status`, `created_at`, `updated_at`) VALUES
(1, 'MAIN BRANCH', 'CALBAYOG', 1000.00, 1, '2025-12-09 03:33:49', '2025-12-20 13:16:48'),
(4, 'TEST', 'TEST ADDRESS', 2500.00, 1, '2025-12-12 11:14:53', '2025-12-12 22:31:48'),
(7, 'CATBALOGAN BRANCH', 'CATBALOGAN', 20000.00, 1, '2025-12-12 13:05:27', '2025-12-12 13:05:27');

-- --------------------------------------------------------

--
-- Table structure for table `coh_logs`
--

CREATE TABLE `coh_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `transaction_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` varchar(55) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `previous_balance` decimal(15,2) NOT NULL,
  `new_balance` decimal(15,2) NOT NULL,
  `note` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coh_logs`
--

INSERT INTO `coh_logs` (`id`, `branch_id`, `user_id`, `transaction_id`, `type`, `amount`, `previous_balance`, `new_balance`, `note`, `created_at`) VALUES
(45, 1, 1, NULL, 'daily_adjustment', 1500.00, 2222.00, 1500.00, 'test', '2025-12-12 14:54:04'),
(46, 1, 1, NULL, 'cash_deposit', 2000.00, 1500.00, 2000.00, 'test', '2025-12-12 14:54:17'),
(47, 1, 1, NULL, 'daily_adjustment', 5000.00, 2000.00, 5000.00, 'test', '2025-12-12 14:55:44'),
(48, 1, 1, NULL, 'daily_adjustment', 50000.00, 5000.00, 50000.00, 'test teststt', '2025-12-12 14:56:20'),
(49, 1, 1, NULL, 'cash_deposit', 100000.00, 50000.00, 100000.00, '', '2025-12-12 14:56:37'),
(50, 1, 1, NULL, 'daily_adjustment', 15000.00, 100000.00, 15000.00, '', '2025-12-12 14:57:16'),
(51, 4, 34, NULL, 'daily_adjustment', 5000.00, 10000.00, 5000.00, 'test adjustment', '2025-12-12 22:30:15'),
(52, 4, 32, NULL, 'replenishment', 2500.00, 5000.00, 2500.00, 'test', '2025-12-12 22:31:48'),
(53, 1, 1, NULL, 'daily_adjustment', 1000.00, 15000.00, 1000.00, '', '2025-12-16 22:37:17'),
(54, 1, 1, NULL, 'daily_adjustment', 1000.00, 1110.00, 1000.00, '', '2025-12-16 22:50:23'),
(55, 1, 1, NULL, 'daily_adjustment', 1000.00, 1100.00, 1000.00, '', '2025-12-16 22:55:32'),
(56, 1, 1, NULL, 'daily_adjustment', 1000.00, 910.00, 1000.00, '', '2025-12-16 22:58:05'),
(57, 1, 1, NULL, 'daily_adjustment', 1000.00, 900.00, 1000.00, '', '2025-12-16 23:01:20'),
(58, 1, 1, NULL, 'daily_adjustment', 1000.00, 1110.00, 1000.00, '', '2025-12-16 23:03:41'),
(59, 1, 1, NULL, 'daily_adjustment', 1000.00, 1100.00, 1000.00, '', '2025-12-16 23:05:05'),
(60, 1, 1, NULL, 'daily_adjustment', 1000.00, 900.00, 1000.00, '', '2025-12-16 23:06:51'),
(61, 1, 1, NULL, 'daily_adjustment', 1000.00, 10.00, 1000.00, '', '2025-12-16 23:08:13'),
(62, 1, 1, NULL, 'daily_adjustment', 1000.00, 910.00, 1000.00, '', '2025-12-16 23:09:29'),
(63, 1, 1, NULL, 'daily_adjustment', 1000.00, 900.00, 1000.00, '', '2025-12-16 23:16:28'),
(64, 1, 1, NULL, 'daily_adjustment', 1000.00, 1100.00, 1000.00, '', '2025-12-16 23:18:10'),
(65, 1, 1, NULL, 'daily_adjustment', 1000.00, 1100.00, 1000.00, '', '2025-12-16 23:20:43'),
(66, 1, 1, NULL, 'daily_adjustment', 1000.00, 1100.00, 1000.00, '', '2025-12-16 23:31:43'),
(67, 1, 1, NULL, 'daily_adjustment', 1000.00, 900.00, 1000.00, '', '2025-12-16 23:46:31'),
(68, 1, 1, NULL, 'daily_adjustment', 1000.00, 1210.00, 1000.00, '', '2025-12-17 00:00:02'),
(69, 1, 1, NULL, 'daily_adjustment', 1000.00, 910.00, 1000.00, '', '2025-12-17 00:08:44'),
(70, 1, 1, NULL, 'daily_adjustment', 1000.00, 5270.00, 1000.00, '', '2025-12-17 22:27:17'),
(71, 1, 1, NULL, 'daily_adjustment', 1000.00, 2050.00, 1000.00, '', '2025-12-18 00:57:36'),
(72, 1, 1, NULL, 'daily_adjustment', 1000.00, 4012.00, 1000.00, '', '2025-12-18 15:14:01'),
(73, 1, 1, NULL, 'daily_adjustment', 1000.00, 930.00, 1000.00, '', '2025-12-20 11:07:22'),
(74, 1, 1, NULL, 'daily_adjustment', 1000.00, 2050.00, 1000.00, '', '2025-12-20 13:16:48');

-- --------------------------------------------------------

--
-- Table structure for table `daily_coh`
--

CREATE TABLE `daily_coh` (
  `id` int(10) UNSIGNED NOT NULL,
  `branch_id` int(10) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `opening_balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `closing_balance` decimal(15,2) DEFAULT NULL,
  `status` enum('open','closed') NOT NULL DEFAULT 'open',
  `is_auto_opening` enum('yes','no') NOT NULL DEFAULT 'yes',
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login_logs`
--

CREATE TABLE `login_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ip_address` varchar(55) DEFAULT NULL,
  `login_type` enum('login','logout') DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login_logs`
--

INSERT INTO `login_logs` (`id`, `user_id`, `ip_address`, `login_type`, `created_at`) VALUES
(1, 2, '::1', 'login', '2025-12-12 10:20:12'),
(2, 2, '::1', 'login', '2025-12-12 10:21:42'),
(3, 2, '::1', 'logout', '2025-12-12 10:26:11'),
(4, 1, '::1', 'login', '2025-12-12 10:26:27'),
(5, 1, '::1', 'logout', '2025-12-12 10:27:11'),
(6, 2, '::1', 'login', '2025-12-12 10:27:36'),
(7, 2, '::1', 'logout', '2025-12-12 10:32:38'),
(8, 2, '::1', 'login', '2025-12-12 10:32:40'),
(9, 2, '::1', 'logout', '2025-12-12 11:34:22'),
(10, 32, '::1', 'login', '2025-12-12 11:34:30'),
(11, 32, '::1', 'logout', '2025-12-12 11:34:35'),
(12, 2, '::1', 'login', '2025-12-12 11:34:39'),
(13, 2, '::1', 'logout', '2025-12-12 11:35:07'),
(14, 32, '::1', 'login', '2025-12-12 11:35:14'),
(15, 32, '::1', 'logout', '2025-12-12 11:35:40'),
(16, 2, '::1', 'login', '2025-12-12 11:35:41'),
(17, 2, '::1', 'logout', '2025-12-12 11:36:03'),
(18, 34, '::1', 'login', '2025-12-12 11:36:07'),
(19, 34, '::1', 'logout', '2025-12-12 11:37:41'),
(20, 34, '::1', 'login', '2025-12-12 11:37:49'),
(21, 34, '::1', 'login', '2025-12-12 11:40:48'),
(22, 34, '::1', 'logout', '2025-12-12 11:46:32'),
(23, 34, '::1', 'login', '2025-12-12 11:46:41'),
(24, 34, '::1', 'logout', '2025-12-12 11:46:48'),
(25, 2, '::1', 'login', '2025-12-12 11:47:35'),
(26, 2, '::1', 'logout', '2025-12-12 11:47:48'),
(27, 2, '::1', 'login', '2025-12-12 11:51:52'),
(28, 2, '::1', 'logout', '2025-12-12 11:51:55'),
(29, 1, '::1', 'login', '2025-12-12 11:52:07'),
(30, 1, '::1', 'logout', '2025-12-12 11:52:28'),
(31, 2, '::1', 'login', '2025-12-12 11:52:31'),
(32, 2, '::1', 'logout', '2025-12-12 11:52:46'),
(33, 34, '::1', 'login', '2025-12-12 11:52:50'),
(34, 34, '::1', 'logout', '2025-12-12 11:52:53'),
(35, 2, '::1', 'login', '2025-12-12 11:52:56'),
(36, 2, '::1', 'logout', '2025-12-12 13:11:33'),
(37, 1, '::1', 'login', '2025-12-12 13:11:38'),
(38, 1, '::1', 'logout', '2025-12-12 14:15:23'),
(39, 2, '::1', 'login', '2025-12-12 14:15:30'),
(40, 2, '::1', 'logout', '2025-12-12 14:15:54'),
(41, 32, '::1', 'login', '2025-12-12 14:16:04'),
(42, 32, '::1', 'logout', '2025-12-12 14:37:02'),
(43, 1, '::1', 'login', '2025-12-12 14:37:09'),
(44, 1, '::1', 'logout', '2025-12-12 14:37:36'),
(45, 2, '::1', 'login', '2025-12-12 14:37:38'),
(46, 2, '::1', 'logout', '2025-12-12 14:38:12'),
(47, 1, '::1', 'login', '2025-12-12 14:38:17'),
(48, 1, '::1', 'logout', '2025-12-12 14:58:07'),
(49, 2, '::1', 'login', '2025-12-12 14:58:09'),
(50, 2, '::1', 'logout', '2025-12-12 15:00:38'),
(51, 2, '::1', 'login', '2025-12-12 15:00:40'),
(52, 2, '::1', 'logout', '2025-12-12 15:00:44'),
(53, 1, '::1', 'login', '2025-12-12 15:00:50'),
(54, 2, '::1', 'login', '2025-12-12 21:37:49'),
(55, 2, '::1', 'logout', '2025-12-12 21:38:13'),
(56, 1, '::1', 'login', '2025-12-12 21:38:16'),
(57, 1, '::1', 'logout', '2025-12-12 21:41:56'),
(58, 2, '::1', 'login', '2025-12-12 21:41:58'),
(59, 2, '::1', 'logout', '2025-12-12 21:58:18'),
(60, 1, '::1', 'login', '2025-12-12 21:58:21'),
(61, 1, '::1', 'logout', '2025-12-12 21:58:24'),
(62, 2, '::1', 'login', '2025-12-12 21:58:27'),
(63, 2, '::1', 'logout', '2025-12-12 22:03:59'),
(64, 1, '::1', 'login', '2025-12-12 22:04:01'),
(65, 1, '::1', 'logout', '2025-12-12 22:04:05'),
(66, 1, '::1', 'login', '2025-12-12 22:04:10'),
(67, 1, '::1', 'logout', '2025-12-12 22:04:22'),
(68, 1, '::1', 'login', '2025-12-12 22:04:27'),
(69, 1, '::1', 'logout', '2025-12-12 22:04:52'),
(70, 2, '::1', 'login', '2025-12-12 22:04:54'),
(71, 2, '::1', 'logout', '2025-12-12 22:09:44'),
(72, 34, '::1', 'login', '2025-12-12 22:09:48'),
(73, 34, '::1', 'logout', '2025-12-12 22:10:08'),
(74, 2, '::1', 'login', '2025-12-12 22:10:10'),
(75, 2, '::1', 'logout', '2025-12-12 22:15:35'),
(76, 1, '::1', 'login', '2025-12-12 22:15:37'),
(77, 1, '::1', 'logout', '2025-12-12 22:15:41'),
(78, 2, '::1', 'login', '2025-12-12 22:15:45'),
(79, 2, '::1', 'logout', '2025-12-12 22:16:19'),
(80, 34, '::1', 'login', '2025-12-12 22:16:22'),
(81, 34, '::1', 'logout', '2025-12-12 22:16:37'),
(82, 2, '::1', 'login', '2025-12-12 22:16:39'),
(83, 2, '::1', 'logout', '2025-12-12 22:25:27'),
(84, 34, '::1', 'login', '2025-12-12 22:25:30'),
(85, 34, '::1', 'logout', '2025-12-12 22:25:52'),
(86, 2, '::1', 'login', '2025-12-12 22:25:55'),
(87, 2, '::1', 'logout', '2025-12-12 22:29:36'),
(88, 1, '::1', 'login', '2025-12-12 22:29:39'),
(89, 1, '::1', 'logout', '2025-12-12 22:29:55'),
(90, 34, '::1', 'login', '2025-12-12 22:29:58'),
(91, 34, '::1', 'logout', '2025-12-12 22:30:40'),
(92, 2, '::1', 'login', '2025-12-12 22:30:43'),
(93, 2, '::1', 'logout', '2025-12-12 22:31:30'),
(94, 32, '::1', 'login', '2025-12-12 22:31:33'),
(95, 32, '::1', 'logout', '2025-12-12 22:43:22'),
(96, 1, '::1', 'login', '2025-12-12 22:43:30'),
(97, 1, '::1', 'logout', '2025-12-12 22:43:39'),
(98, 1, '::1', 'login', '2025-12-12 22:43:44'),
(99, 1, '::1', 'logout', '2025-12-13 00:14:45'),
(100, 1, '::1', 'login', '2025-12-16 22:23:21'),
(101, 1, '::1', 'logout', '2025-12-17 00:22:49'),
(102, 1, '::1', 'login', '2025-12-17 22:27:04'),
(103, 1, '::1', 'logout', '2025-12-18 01:35:37'),
(104, 32, '::1', 'login', '2025-12-18 01:35:39'),
(105, 32, '::1', 'logout', '2025-12-18 01:35:45'),
(106, 2, '::1', 'login', '2025-12-18 01:35:47'),
(107, 2, '::1', 'logout', '2025-12-18 01:37:22'),
(108, 2, '::1', 'login', '2025-12-18 14:21:52'),
(109, 2, '::1', 'logout', '2025-12-18 14:38:02'),
(110, 1, '::1', 'login', '2025-12-18 14:38:05'),
(111, 1, '::1', 'logout', '2025-12-18 16:41:20'),
(112, 1, '::1', 'login', '2025-12-19 22:30:28'),
(113, 1, '::1', 'logout', '2025-12-19 22:30:38'),
(114, 1, '::1', 'login', '2025-12-20 10:43:49');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `wallet_id` int(10) UNSIGNED NOT NULL,
  `type` char(55) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `charge` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total` decimal(15,2) NOT NULL,
  `tendered_amount` decimal(15,2) DEFAULT NULL,
  `change_amount` decimal(15,2) DEFAULT NULL,
  `payment_thru` char(55) NOT NULL,
  `reference_no` varchar(200) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `branch_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('super_admin','admin','cashier') NOT NULL DEFAULT 'cashier',
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `branch_id`, `name`, `username`, `password`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'kenneth.admin', 'admin', '$2y$10$zv5Z8i1r1IF.4/D7RrVC4uBnBXnbTx3yFihjAxPtHa07CHbXboJiW', 'admin', 1, '2025-12-09 03:35:57', '2025-12-12 14:38:09'),
(2, 0, 'super admin', 'super_admin', '$2y$10$B8DHSKdCEG0kDzSGwNz.Re8vUWGOhLg5jwHGnYK5QcBBx/sqgnHIS', 'super_admin', 1, '2025-12-09 04:47:16', '2025-12-10 21:12:07'),
(31, 1, 'cashier', 'cashier', '$2y$10$AaOj4VTtJ9Cafen/RkOpP.mXo2jk7MRQ8QU6awi09dGf9WTDKgiUW', 'cashier', 1, '2025-12-11 22:02:39', '2025-12-11 23:21:03'),
(32, 4, 'test.cashier', 'test_cashier', '$2y$10$JTE0.I9/e96hU72/WhZFFOxqgreGDBuocGDXWhfjgP2PW1a8ZJS/u', 'cashier', 1, '2025-12-11 23:22:05', '2025-12-12 22:31:20'),
(33, 1, 'ashnuahsud', 'xxx', '$2y$10$OPs.ac2lMtbxpt8kXgUO3uUUaLZRmf6BkW0FlQaSjfDFP0tWMlb9u', 'admin', 0, '2025-12-12 11:01:52', '2025-12-12 11:27:47'),
(34, 4, 'test.user', 'test_user', '$2y$10$szIUpDEZAxjfELqN4aNPHO2T6WgasWelLvQIyC6AVWP8F4eoku9Cy', 'admin', 1, '2025-12-12 11:35:59', '2025-12-12 22:16:12');

-- --------------------------------------------------------

--
-- Table structure for table `wallet_accounts`
--

CREATE TABLE `wallet_accounts` (
  `id` int(10) UNSIGNED NOT NULL,
  `branch_id` int(10) UNSIGNED NOT NULL,
  `account_name` char(55) NOT NULL,
  `account_number` varchar(50) DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  `current_balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wallet_accounts`
--

INSERT INTO `wallet_accounts` (`id`, `branch_id`, `account_name`, `account_number`, `label`, `current_balance`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'GCash', '-', 'Main Account', 1000.00, 1, '2025-12-12 15:18:32', '2025-12-20 13:16:34'),
(2, 1, 'Maya', '-', 'Main Account', 1000.00, 1, '2025-12-12 15:18:32', '2025-12-20 13:16:39'),
(8, 4, 'GCash', 'test', 'test', 5000.00, 1, '2025-12-12 22:10:05', '2025-12-12 22:25:00'),
(9, 4, 'Maya', 'test', 'test', 2000.00, 1, '2025-12-12 22:16:32', '2025-12-12 22:24:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_audit_user` (`user_id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coh_logs`
--
ALTER TABLE `coh_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_coh_logs_user` (`user_id`),
  ADD KEY `fk_coh_logs_transaction` (`transaction_id`),
  ADD KEY `idx_coh_branch_date` (`branch_id`,`created_at`);

--
-- Indexes for table `daily_coh`
--
ALTER TABLE `daily_coh`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_daily_coh_branch_date` (`branch_id`,`date`),
  ADD KEY `fk_daily_coh_created_by` (`created_by`);

--
-- Indexes for table `login_logs`
--
ALTER TABLE `login_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_transactions_branch` (`branch_id`),
  ADD KEY `idx_transactions_user` (`user_id`),
  ADD KEY `idx_transactions_wallet` (`wallet_id`),
  ADD KEY `idx_transactions_type_created` (`type`,`created_at`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `wallet_accounts`
--
ALTER TABLE `wallet_accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_wallet_accounts_branch` (`branch_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `coh_logs`
--
ALTER TABLE `coh_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `daily_coh`
--
ALTER TABLE `daily_coh`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login_logs`
--
ALTER TABLE `login_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `wallet_accounts`
--
ALTER TABLE `wallet_accounts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `fk_audit_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `coh_logs`
--
ALTER TABLE `coh_logs`
  ADD CONSTRAINT `fk_coh_logs_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_coh_logs_transaction` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_coh_logs_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `daily_coh`
--
ALTER TABLE `daily_coh`
  ADD CONSTRAINT `fk_daily_coh_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_daily_coh_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `fk_transactions_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_transactions_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_transactions_wallet` FOREIGN KEY (`wallet_id`) REFERENCES `wallet_accounts` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
