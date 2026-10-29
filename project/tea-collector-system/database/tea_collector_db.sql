-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 23, 2026 at 07:04 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tea_collector_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_id` int(11) DEFAULT NULL,
  `old_values` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `new_values` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `old_values`, `new_values`, `ip_address`, `created_at`) VALUES
(1, 2, 'login', 'users', 2, NULL, NULL, '::1', '2026-09-21 14:39:00'),
(2, 2, 'logout', 'users', 2, NULL, NULL, '::1', '2026-09-21 14:41:29'),
(3, 3, 'login', 'users', 3, NULL, NULL, '::1', '2026-09-21 14:49:02'),
(4, 3, 'create', 'farmers', 1, NULL, '{\":farmer_code\":\"FRM-20260921-0001\",\":national_id\":\"1199980045567743\",\":first_name\":\"ELISABETH\",\":last_name\":\"MUHAWENIMANA\",\":phone\":\"0790841856\",\":email\":\"muheliza100@gmail.com\",\":address\":\"miyove\",\":cooperative_id\":\"1\",\":collection_center_id\":\"2\",\":bank_account\":\"09092787\",\":mobile_money_number\":\"0790841856\"}', '::1', '2026-09-21 14:52:05'),
(5, 3, 'create', 'tea_collections', 1, NULL, '{\":transaction_code\":\"TXN-20260921-0001\",\":farmer_id\":1,\":collector_id\":3,\":collection_center_id\":1,\":gross_weight_kg\":10,\":net_weight_kg\":8,\":quality_grade\":\"B\",\":moisture_content\":70,\":collection_date\":\"2026-09-21\",\":collection_time\":\"16:56\",\":notes\":\"\"}', '::1', '2026-09-21 14:57:22'),
(6, 3, 'create', 'tea_collections', 2, NULL, '{\":transaction_code\":\"TXN-20260921-0002\",\":farmer_id\":1,\":collector_id\":3,\":collection_center_id\":1,\":gross_weight_kg\":4,\":net_weight_kg\":4,\":quality_grade\":\"A\",\":moisture_content\":0.22,\":collection_date\":\"2026-09-21\",\":collection_time\":\"17:08\",\":notes\":\"\"}', '::1', '2026-09-21 15:08:59'),
(7, 3, 'update', 'tea_collections', 2, '{\"id\":2,\"transaction_code\":\"TXN-20260921-0002\",\"farmer_id\":1,\"collector_id\":3,\"collection_center_id\":1,\"gross_weight_kg\":\"4.00\",\"net_weight_kg\":\"4.00\",\"quality_grade\":\"A\",\"moisture_content\":\"0.22\",\"collection_date\":\"2026-09-21\",\"collection_time\":\"17:08:00\",\"notes\":\"\",\"status\":\"pending\",\"created_at\":\"2026-09-21 17:08:59\"}', '{\":farmer_id\":1,\":collection_center_id\":1,\":gross_weight_kg\":4,\":net_weight_kg\":4,\":quality_grade\":\"A\",\":moisture_content\":0.22,\":collection_date\":\"2026-09-21\",\":collection_time\":\"17:08:00\",\":notes\":\"\",\":id\":2}', '::1', '2026-09-21 15:09:11'),
(8, 3, 'create', 'users', 4, NULL, NULL, '::1', '2026-09-21 15:14:23'),
(9, 3, 'create', 'users', 5, NULL, NULL, '::1', '2026-09-21 15:16:12'),
(10, 5, 'login', 'users', 5, NULL, NULL, '::1', '2026-09-21 15:17:15'),
(11, 5, 'update', 'farmers', 1, '{\"id\":1,\"farmer_code\":\"FRM-20260921-0001\",\"national_id\":\"1199980045567743\",\"first_name\":\"ELISABETH\",\"last_name\":\"MUHAWENIMANA\",\"phone\":\"0790841856\",\"email\":\"muheliza100@gmail.com\",\"address\":\"miyove\",\"cooperative_id\":1,\"collection_center_id\":2,\"bank_account\":\"09092787\",\"mobile_money_number\":\"0790841856\",\"is_active\":1,\"registered_at\":\"2026-09-21 16:52:05\"}', '{\":national_id\":\"1199980045567743\",\":first_name\":\"ELISABETH\",\":last_name\":\"MUHAWENIMANA\",\":phone\":\"0790841856\",\":email\":\"muheliza100@gmail.com\",\":address\":\"miyove\",\":cooperative_id\":\"1\",\":collection_center_id\":\"2\",\":bank_account\":\"09092787\",\":mobile_money_number\":\"0790841856\",\":id\":1}', '::1', '2026-09-21 15:17:34'),
(12, 3, 'create', 'payments', 1, NULL, NULL, '::1', '2026-09-21 15:19:30'),
(13, 3, 'process', 'payments', 1, NULL, NULL, '::1', '2026-09-21 15:19:37'),
(14, 5, 'create', 'farmers', 3, NULL, '{\":farmer_code\":\"FRM-20260921-0002\",\":national_id\":\"1199970045567743\",\":first_name\":\"Gratien\",\":last_name\":\"IRADUKUNDA\",\":phone\":\"+250782679199\",\":email\":\"iradukunda@gmail.com\",\":address\":\"rulindo\",\":cooperative_id\":\"2\",\":collection_center_id\":\"2\",\":bank_account\":\"+250782679199\",\":mobile_money_number\":\"+250782679199\"}', '::1', '2026-09-21 15:23:23'),
(15, 5, 'create', 'tea_collections', 3, NULL, '{\":transaction_code\":\"TXN-20260921-0003\",\":farmer_id\":3,\":collector_id\":5,\":collection_center_id\":2,\":gross_weight_kg\":30,\":net_weight_kg\":30,\":quality_grade\":\"A\",\":moisture_content\":3,\":collection_date\":\"2026-09-21\",\":collection_time\":\"17:23\",\":notes\":\"\"}', '::1', '2026-09-21 15:24:11'),
(16, 5, 'create', 'payments', 2, NULL, NULL, '::1', '2026-09-21 15:24:56'),
(17, 3, 'process', 'payments', 2, NULL, NULL, '::1', '2026-09-21 15:25:43'),
(18, 3, 'create', 'users', 6, NULL, NULL, '::1', '2026-09-21 15:28:14'),
(19, 5, 'logout', 'users', 5, NULL, NULL, '::1', '2026-09-21 15:28:49'),
(20, 4, 'login', 'users', 4, NULL, NULL, '::1', '2026-09-21 15:29:00'),
(21, 4, 'create', 'transport_schedules', 1, NULL, NULL, '::1', '2026-09-21 15:30:43'),
(22, 3, 'logout', 'users', 3, NULL, NULL, '::1', '2026-09-21 15:40:45'),
(23, 6, 'login', 'users', 6, NULL, NULL, '::1', '2026-09-21 15:40:47'),
(24, 6, 'logout', 'users', 6, NULL, NULL, '::1', '2026-09-21 15:41:06'),
(25, 4, 'login', 'users', 4, NULL, NULL, '::1', '2026-09-21 15:41:11'),
(26, 4, 'logout', 'users', 4, NULL, NULL, '::1', '2026-09-21 15:41:34'),
(27, 5, 'login', 'users', 5, NULL, NULL, '::1', '2026-09-21 15:41:56'),
(28, 5, 'logout', 'users', 5, NULL, NULL, '::1', '2026-09-21 15:43:15'),
(29, 2, 'login', 'users', 2, NULL, NULL, '::1', '2026-09-21 15:43:19'),
(30, 2, 'create', 'users', 7, NULL, NULL, '::1', '2026-09-21 15:44:19'),
(31, 2, 'logout', 'users', 2, NULL, NULL, '::1', '2026-09-21 15:44:29'),
(32, 7, 'login', 'users', 7, NULL, NULL, '::1', '2026-09-21 15:44:39'),
(33, 7, 'logout', 'users', 7, NULL, NULL, '::1', '2026-09-21 15:55:56'),
(34, 2, 'login', 'users', 2, NULL, NULL, '::1', '2026-09-21 15:56:18'),
(35, 2, 'logout', 'users', 2, NULL, NULL, '::1', '2026-09-21 15:56:36'),
(36, 6, 'login', 'users', 6, NULL, NULL, '::1', '2026-09-21 15:56:41'),
(37, 6, 'update', 'tea_collections', 2, '{\"id\":2,\"transaction_code\":\"TXN-20260921-0002\",\"farmer_id\":1,\"collector_id\":3,\"collection_center_id\":1,\"gross_weight_kg\":\"4.00\",\"net_weight_kg\":\"4.00\",\"quality_grade\":\"A\",\"moisture_content\":\"0.22\",\"collection_date\":\"2026-09-21\",\"collection_time\":\"17:08:00\",\"notes\":\"\",\"status\":\"processed\",\"created_at\":\"2026-09-21 17:08:59\"}', '{\":farmer_id\":1,\":collection_center_id\":1,\":gross_weight_kg\":4,\":net_weight_kg\":4,\":quality_grade\":\"A\",\":moisture_content\":0.22,\":collection_date\":\"2026-09-21\",\":collection_time\":\"17:08:00\",\":notes\":\"\",\":id\":2}', '::1', '2026-09-21 15:57:42'),
(38, 6, 'logout', 'users', 6, NULL, NULL, '::1', '2026-09-21 15:59:04'),
(39, 7, 'login', 'users', 7, NULL, NULL, '::1', '2026-09-21 15:59:13'),
(40, 7, 'logout', 'users', 7, NULL, NULL, '::1', '2026-09-21 16:09:32'),
(41, 2, 'login', 'users', 2, NULL, NULL, '::1', '2026-09-21 16:09:39'),
(42, 4, 'logout', 'users', 4, NULL, NULL, '::1', '2026-09-21 16:41:38');

-- --------------------------------------------------------

--
-- Table structure for table `collection_centers`
--

CREATE TABLE `collection_centers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `manager_id` int(11) DEFAULT NULL,
  `capacity_kg` decimal(10,2) DEFAULT 0.00,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `collection_centers`
--

INSERT INTO `collection_centers` (`id`, `name`, `code`, `location`, `manager_id`, `capacity_kg`, `is_active`, `created_at`) VALUES
(1, 'Kinihira Center A', 'CC-A-001', 'Kinihira, Rulindo', NULL, '5000.00', 1, '2026-09-21 12:51:57'),
(2, 'Kinihira Center B', 'CC-B-002', 'Kinihira, Rulindo', NULL, '3000.00', 1, '2026-09-21 12:51:57'),
(3, 'Rulindo Center C', 'CC-C-003', 'Rulindo District', NULL, '4000.00', 1, '2026-09-21 12:51:57');

-- --------------------------------------------------------

--
-- Table structure for table `cooperatives`
--

CREATE TABLE `cooperatives` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_person` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cooperatives`
--

INSERT INTO `cooperatives` (`id`, `name`, `code`, `contact_person`, `phone`, `email`, `address`, `is_active`, `created_at`) VALUES
(1, 'ASSOPTHE Cooperative', 'COOP-001', 'Jean Mugisha', '+250788111111', NULL, NULL, 1, '2026-09-21 12:51:57'),
(2, 'Hillside Organic Tea Cooperative', 'COOP-002', 'Marie Uwase', '+250788222222', NULL, NULL, 1, '2026-09-21 12:51:57');

-- --------------------------------------------------------

--
-- Table structure for table `farmers`
--

CREATE TABLE `farmers` (
  `id` int(11) NOT NULL,
  `farmer_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `national_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cooperative_id` int(11) DEFAULT NULL,
  `collection_center_id` int(11) DEFAULT NULL,
  `bank_account` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile_money_number` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `registered_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `farmers`
--

INSERT INTO `farmers` (`id`, `farmer_code`, `national_id`, `first_name`, `last_name`, `phone`, `email`, `address`, `cooperative_id`, `collection_center_id`, `bank_account`, `mobile_money_number`, `is_active`, `registered_at`) VALUES
(1, 'FRM-20260921-0001', '1199980045567743', 'ELISABETH', 'MUHAWENIMANA', '0790841856', 'muheliza100@gmail.com', 'miyove', 1, 2, '09092787', '0790841856', 1, '2026-09-21 14:52:05'),
(3, 'FRM-20260921-0002', '1199970045567743', 'Gratien', 'IRADUKUNDA', '+250782679199', 'iradukunda@gmail.com', 'rulindo', 2, 2, '+250782679199', '+250782679199', 1, '2026-09-21 15:23:23');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `collection_center_id` int(11) NOT NULL,
  `current_stock_kg` decimal(10,2) DEFAULT 0.00,
  `threshold_kg` decimal(10,2) DEFAULT 1000.00,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `collection_center_id`, `current_stock_kg`, `threshold_kg`, `last_updated`) VALUES
(1, 1, '12.00', '1000.00', '2026-09-21 15:08:59'),
(2, 2, '30.00', '1000.00', '2026-09-21 15:24:11'),
(3, 3, '0.00', '1000.00', '2026-09-21 12:51:57');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `payment_code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `farmer_id` int(11) NOT NULL,
  `period_start` date NOT NULL,
  `period_end` date NOT NULL,
  `total_weight_kg` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `deductions` decimal(10,2) DEFAULT 0.00,
  `net_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `payment_method` enum('bank_transfer','mobile_money','cash') COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_status` enum('pending','processing','paid','failed') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `paid_at` datetime DEFAULT NULL,
  `processed_by` int(11) DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `payment_code`, `farmer_id`, `period_start`, `period_end`, `total_weight_kg`, `total_amount`, `deductions`, `net_amount`, `payment_method`, `payment_status`, `paid_at`, `processed_by`, `notes`, `created_at`) VALUES
(1, 'PAY-20260921-0001', 1, '2026-09-01', '2026-09-30', '12.00', '3800.00', '0.00', '3800.00', 'mobile_money', 'paid', '2026-09-21 17:19:37', 3, NULL, '2026-09-21 15:19:30'),
(2, 'PAY-20260921-0002', 3, '2026-09-01', '2026-09-30', '30.00', '10500.00', '0.00', '10500.00', 'bank_transfer', 'paid', '2026-09-21 17:25:43', 5, NULL, '2026-09-21 15:24:56');

-- --------------------------------------------------------

--
-- Table structure for table `price_configurations`
--

CREATE TABLE `price_configurations` (
  `id` int(11) NOT NULL,
  `quality_grade` enum('A','B','C','D') COLLATE utf8mb4_unicode_ci NOT NULL,
  `price_per_kg` decimal(10,2) NOT NULL,
  `effective_from` date NOT NULL,
  `effective_to` date DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `price_configurations`
--

INSERT INTO `price_configurations` (`id`, `quality_grade`, `price_per_kg`, `effective_from`, `effective_to`, `created_by`, `created_at`) VALUES
(1, 'A', '350.00', '2026-01-01', NULL, NULL, '2026-09-21 12:51:57'),
(2, 'B', '300.00', '2026-01-01', NULL, NULL, '2026-09-21 12:51:57'),
(3, 'C', '250.00', '2026-01-01', NULL, NULL, '2026-09-21 12:51:57'),
(4, 'D', '200.00', '2026-01-01', NULL, NULL, '2026-09-21 12:51:57');

-- --------------------------------------------------------

--
-- Table structure for table `tea_collections`
--

CREATE TABLE `tea_collections` (
  `id` int(11) NOT NULL,
  `transaction_code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `farmer_id` int(11) NOT NULL,
  `collector_id` int(11) NOT NULL,
  `collection_center_id` int(11) NOT NULL,
  `gross_weight_kg` decimal(10,2) NOT NULL,
  `net_weight_kg` decimal(10,2) NOT NULL,
  `quality_grade` enum('A','B','C','D') COLLATE utf8mb4_unicode_ci NOT NULL,
  `moisture_content` decimal(5,2) DEFAULT NULL,
  `collection_date` date NOT NULL,
  `collection_time` time NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','verified','processed') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tea_collections`
--

INSERT INTO `tea_collections` (`id`, `transaction_code`, `farmer_id`, `collector_id`, `collection_center_id`, `gross_weight_kg`, `net_weight_kg`, `quality_grade`, `moisture_content`, `collection_date`, `collection_time`, `notes`, `status`, `created_at`) VALUES
(1, 'TXN-20260921-0001', 1, 3, 1, '10.00', '8.00', 'B', '70.00', '2026-09-21', '16:56:00', '', 'processed', '2026-09-21 14:57:22'),
(2, 'TXN-20260921-0002', 1, 3, 1, '4.00', '4.00', 'A', '0.22', '2026-09-21', '17:08:00', '', 'processed', '2026-09-21 15:08:59'),
(3, 'TXN-20260921-0003', 3, 5, 2, '30.00', '30.00', 'A', '3.00', '2026-09-21', '17:23:00', '', 'processed', '2026-09-21 15:24:11');

-- --------------------------------------------------------

--
-- Table structure for table `transport_schedules`
--

CREATE TABLE `transport_schedules` (
  `id` int(11) NOT NULL,
  `schedule_code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `collection_center_id` int(11) NOT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `driver_id` int(11) DEFAULT NULL,
  `scheduled_date` date NOT NULL,
  `scheduled_time` time NOT NULL,
  `total_weight_kg` decimal(10,2) DEFAULT 0.00,
  `status` enum('scheduled','in_transit','delivered','cancelled') COLLATE utf8mb4_unicode_ci DEFAULT 'scheduled',
  `departure_time` datetime DEFAULT NULL,
  `arrival_time` datetime DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transport_schedules`
--

INSERT INTO `transport_schedules` (`id`, `schedule_code`, `collection_center_id`, `vehicle_id`, `driver_id`, `scheduled_date`, `scheduled_time`, `total_weight_kg`, `status`, `departure_time`, `arrival_time`, `notes`, `created_by`, `created_at`) VALUES
(1, 'TRP-20260921-0001', 1, 2, 4, '2026-09-21', '17:30:00', '50.00', 'scheduled', NULL, NULL, '', 4, '2026-09-21 15:30:43');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('admin','manager','collector','accountant','transport_officer','storekeeper') COLLATE utf8mb4_unicode_ci NOT NULL,
  `collection_center_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `first_name`, `last_name`, `phone`, `role`, `collection_center_id`, `is_active`, `last_login`, `created_at`, `updated_at`) VALUES
(2, 'Administrator', 'administrator@sorwathe.rw', 'Admin@123', 'Nazir', 'Keshwani', '0782679199', 'admin', 1, 1, '2026-09-21 18:09:38', '2026-09-21 13:39:37', '2026-09-21 16:09:38'),
(3, 'admin', 'admin@sorwathe.rw', 'admin123', 'System', 'Administrator', '+250788000000', 'admin', NULL, 1, '2026-09-21 16:49:02', '2026-09-21 13:51:30', '2026-09-21 14:49:02'),
(4, 'kamali', 'kamali@gmail.com', 'kamali', 'pierre', 'kamali', '0782679199', 'transport_officer', 1, 1, '2026-09-21 17:41:11', '2026-09-21 15:14:23', '2026-09-21 15:41:11'),
(5, 'manager', 'manager@gmail.com', 'manager123', 'alice', 'uwera', '0782679199', 'manager', 1, 1, '2026-09-21 17:41:56', '2026-09-21 15:16:12', '2026-09-21 15:41:56'),
(6, 'collector', 'collector@sorwathe.rw', 'collector', 'jean', 'kabalisa', '0782679199', 'collector', 2, 1, '2026-09-21 17:56:41', '2026-09-21 15:28:14', '2026-09-21 15:56:41'),
(7, 'accountant', 'accountant@sorwathe.rw', 'accountant', 'joy', 'abera', '0782679199', 'accountant', 2, 1, '2026-09-21 17:59:13', '2026-09-21 15:44:18', '2026-09-21 15:59:13');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL,
  `plate_number` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vehicle_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `capacity_kg` decimal(10,2) DEFAULT NULL,
  `is_available` tinyint(1) DEFAULT 1,
  `last_maintenance` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `plate_number`, `vehicle_type`, `capacity_kg`, `is_available`, `last_maintenance`, `created_at`) VALUES
(1, 'RAB 123 A', 'Truck', '5000.00', 1, NULL, '2026-09-21 12:51:57'),
(2, 'RAC 456 B', 'Truck', '3000.00', 1, NULL, '2026-09-21 12:51:57'),
(3, 'RAD 789 C', 'Pickup', '1500.00', 1, NULL, '2026-09-21 12:51:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_created` (`created_at`);

--
-- Indexes for table `collection_centers`
--
ALTER TABLE `collection_centers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `cooperatives`
--
ALTER TABLE `cooperatives`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `farmers`
--
ALTER TABLE `farmers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `farmer_code` (`farmer_code`),
  ADD UNIQUE KEY `national_id` (`national_id`),
  ADD KEY `cooperative_id` (`cooperative_id`),
  ADD KEY `collection_center_id` (`collection_center_id`),
  ADD KEY `idx_farmer_code` (`farmer_code`),
  ADD KEY `idx_national_id` (`national_id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `collection_center_id` (`collection_center_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_code` (`payment_code`),
  ADD KEY `farmer_id` (`farmer_id`),
  ADD KEY `idx_status` (`payment_status`);

--
-- Indexes for table `price_configurations`
--
ALTER TABLE `price_configurations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tea_collections`
--
ALTER TABLE `tea_collections`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transaction_code` (`transaction_code`),
  ADD KEY `farmer_id` (`farmer_id`),
  ADD KEY `collector_id` (`collector_id`),
  ADD KEY `collection_center_id` (`collection_center_id`),
  ADD KEY `idx_date` (`collection_date`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `transport_schedules`
--
ALTER TABLE `transport_schedules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `schedule_code` (`schedule_code`),
  ADD KEY `collection_center_id` (`collection_center_id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `idx_date` (`scheduled_date`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_role` (`role`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `plate_number` (`plate_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `collection_centers`
--
ALTER TABLE `collection_centers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cooperatives`
--
ALTER TABLE `cooperatives`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `farmers`
--
ALTER TABLE `farmers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `price_configurations`
--
ALTER TABLE `price_configurations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tea_collections`
--
ALTER TABLE `tea_collections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transport_schedules`
--
ALTER TABLE `transport_schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `farmers`
--
ALTER TABLE `farmers`
  ADD CONSTRAINT `farmers_ibfk_1` FOREIGN KEY (`cooperative_id`) REFERENCES `cooperatives` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `farmers_ibfk_2` FOREIGN KEY (`collection_center_id`) REFERENCES `collection_centers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`collection_center_id`) REFERENCES `collection_centers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`farmer_id`) REFERENCES `farmers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tea_collections`
--
ALTER TABLE `tea_collections`
  ADD CONSTRAINT `tea_collections_ibfk_1` FOREIGN KEY (`farmer_id`) REFERENCES `farmers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tea_collections_ibfk_2` FOREIGN KEY (`collector_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tea_collections_ibfk_3` FOREIGN KEY (`collection_center_id`) REFERENCES `collection_centers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transport_schedules`
--
ALTER TABLE `transport_schedules`
  ADD CONSTRAINT `transport_schedules_ibfk_1` FOREIGN KEY (`collection_center_id`) REFERENCES `collection_centers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transport_schedules_ibfk_2` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
