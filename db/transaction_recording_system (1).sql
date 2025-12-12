-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 12, 2025 at 09:22 AM
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
(1, 'MAIN BRANCH', 'CALBAYOG', 15000.00, 1, '2025-12-09 03:33:49', '2025-12-12 14:57:16'),
(4, 'TEST', 'TEST ADDRESS', 10000.00, 1, '2025-12-12 11:14:53', '2025-12-12 14:34:24'),
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
(50, 1, 1, NULL, 'daily_adjustment', 15000.00, 100000.00, 15000.00, '', '2025-12-12 14:57:16');

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
(53, 1, '::1', 'login', '2025-12-12 15:00:50');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `wallet_id` int(10) UNSIGNED NOT NULL,
  `type` enum('cashin','cashout') NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `charge` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total` decimal(15,2) NOT NULL,
  `payment_mode` enum('cash','wallet','bank_transfer','other') NOT NULL DEFAULT 'wallet',
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
(32, 4, 'kenken', 'kenken123', '$2y$10$JJErU0xbSPsrney666DZ2eFT5CEdRE187yaoCHKC46ZKpBaDQz2/i', 'admin', 1, '2025-12-11 23:22:05', '2025-12-12 11:34:17'),
(33, 1, 'ashnuahsud', 'xxx', '$2y$10$OPs.ac2lMtbxpt8kXgUO3uUUaLZRmf6BkW0FlQaSjfDFP0tWMlb9u', 'admin', 0, '2025-12-12 11:01:52', '2025-12-12 11:27:47'),
(34, 4, 'NEW', 'new', '$2y$10$fn.hskT1qNNnjNPpFSY9jOtDIq64mbkRGASX4N.85weZE0zosIjpG', 'admin', 1, '2025-12-12 11:35:59', '2025-12-12 11:35:59');

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
(1, 1, 'GCash', '09123456789', 'Primary Account', 500.00, 1, '2025-12-12 15:18:32', '2025-12-12 16:19:49'),
(2, 1, 'Maya', '09123456789', 'Second Account', 5000.00, 1, '2025-12-12 15:18:32', '2025-12-12 16:19:35');

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `daily_coh`
--
ALTER TABLE `daily_coh`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login_logs`
--
ALTER TABLE `login_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `wallet_accounts`
--
ALTER TABLE `wallet_accounts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
