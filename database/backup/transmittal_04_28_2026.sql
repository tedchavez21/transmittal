-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 28, 2026 at 07:25 AM
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
-- Database: `transmittal`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`, `updated_at`) VALUES
(1, 'UZZIEL', '$2y$12$LNpFXLlidn8MATYjf6wsh.DJHkG.aAzcR07cWRqCQzIWCLg/bQ.tO', '2026-04-14 23:26:22', '2026-04-21 21:40:43');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_handlers`
--

CREATE TABLE `email_handlers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT 0,
  `approved_at` timestamp NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `email_handlers`
--

INSERT INTO `email_handlers` (`id`, `name`, `approved`, `approved_at`, `active`, `created_at`, `updated_at`) VALUES
(1, 'uzziel', 1, '2026-04-22 22:14:15', 1, '2026-04-22 16:54:32', '2026-04-22 22:14:15'),
(2, 'teddy', 0, NULL, 0, '2026-04-22 17:17:18', '2026-04-26 22:58:42'),
(3, 'Juvielyn Fiesta', 1, '2026-04-22 17:29:24', 1, '2026-04-22 17:29:11', '2026-04-22 17:29:24'),
(4, 'Hanna Marie Lorica', 1, '2026-04-28 01:28:06', 1, '2026-04-22 22:13:28', '2026-04-28 01:28:06'),
(5, 'Julie Ann Espejo', 1, '2026-04-26 22:01:39', 1, '2026-04-26 19:35:01', '2026-04-26 22:01:39'),
(6, 'Teddyboi', 1, '2026-04-26 21:20:24', 1, '2026-04-26 21:13:42', '2026-04-26 21:20:24'),
(7, 'uzziel martinez', 0, NULL, 0, '2026-04-26 22:57:33', '2026-04-28 00:27:25'),
(8, 'tED', 0, NULL, 0, '2026-04-27 00:48:27', '2026-04-27 00:50:36'),
(9, 'teddyt', 0, NULL, 0, '2026-04-27 15:04:43', '2026-04-27 15:05:31');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2026_04_13_060450_create_records_table', 1),
(2, '2026_04_13_073529_create_sessions_table', 1),
(3, '2026_04_15_000000_add_encoder_and_approval_to_records_table', 1),
(4, '2026_04_15_010000_create_officers_table', 1),
(5, '2026_04_15_020000_add_active_to_officers_table', 2),
(6, '2026_04_15_030000_create_admins_table', 3),
(7, '2026_04_16_000000_add_location_fields_to_records_table', 4),
(8, '2026_04_17_000000_add_source_and_transmittal_to_records_table', 5),
(9, '2026_04_20_000000_add_admin_transmittal_number_to_records_table', 5),
(10, '2026_04_20_052119_create_cache_table', 5),
(11, '2026_04_22_000001_add_accounts_to_records_table', 6),
(12, '2026_04_22_000002_add_date_occurrence_to_records_table', 7),
(13, '2026_04_23_000000_add_facebook_page_url_to_records_table', 7),
(14, '2026_04_23_100000_create_email_handlers_table', 8),
(15, '2026_04_23_100001_change_date_occurrence_to_string_on_records_table', 8),
(17, '2026_04_23_052624_add_date_occurrence_to_records_table', 9),
(18, '2026_04_27_000000_change_date_occurrence_to_date_on_records_table', 9),
(19, '2026_04_27_000001_change_date_occurrence_to_string_on_records_table', 10),
(20, '2026_04_27_025011_add_date_received_to_records_table', 11);

-- --------------------------------------------------------

--
-- Table structure for table `officers`
--

CREATE TABLE `officers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `officers`
--

INSERT INTO `officers` (`id`, `name`, `approved`, `active`, `approved_at`, `created_at`, `updated_at`) VALUES
(1, 'Ted Eiden Chavez', 0, 0, NULL, '2026-04-14 21:19:41', '2026-04-28 01:47:02'),
(2, 'Uzziel Martinez', 1, 1, '2026-04-27 23:36:43', '2026-04-14 21:31:09', '2026-04-27 23:36:43'),
(3, 'Gemmary Eiden Chavez', 0, 0, NULL, '2026-04-14 23:20:44', '2026-04-14 23:46:18'),
(4, 'Hanna Marie Lorica', 0, 0, NULL, '2026-04-15 00:54:05', '2026-04-15 00:55:56'),
(5, 'Jessica Rose Flores', 0, 0, NULL, '2026-04-20 17:59:09', '2026-04-21 20:46:16'),
(6, 'Glen Bondoc', 1, 1, '2026-04-26 18:26:58', '2026-04-26 18:26:14', '2026-04-26 18:26:58');

-- --------------------------------------------------------

--
-- Table structure for table `records`
--

CREATE TABLE `records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `farmerName` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `province` varchar(255) DEFAULT NULL,
  `municipality` varchar(255) DEFAULT NULL,
  `barangay` varchar(255) DEFAULT NULL,
  `line` varchar(255) NOT NULL,
  `program` varchar(255) NOT NULL,
  `causeOfDamage` varchar(255) NOT NULL,
  `modeOfPayment` varchar(255) NOT NULL,
  `accounts` varchar(255) DEFAULT NULL,
  `facebook_page_url` text DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `source` enum('OD','Email','Facebook') NOT NULL DEFAULT 'OD',
  `transmittal_number` varchar(255) DEFAULT NULL,
  `admin_transmittal_number` varchar(255) DEFAULT NULL,
  `admin_transmittal_assigned_at` timestamp NULL DEFAULT NULL,
  `encoderName` varchar(255) DEFAULT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT 0,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `date_occurrence` varchar(500) DEFAULT NULL,
  `date_received` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `records`
--

INSERT INTO `records` (`id`, `farmerName`, `address`, `province`, `municipality`, `barangay`, `line`, `program`, `causeOfDamage`, `modeOfPayment`, `accounts`, `facebook_page_url`, `remarks`, `source`, `transmittal_number`, `admin_transmittal_number`, `admin_transmittal_assigned_at`, `encoderName`, `approved`, `approved_at`, `created_at`, `updated_at`, `date_occurrence`, `date_received`) VALUES
(145, 'NELSON PAGATPATAN', 'Lamorito, Guimba, Nueva Ecija', 'Nueva Ecija', 'Guimba', 'Lamorito', 'rice', 'RSBSA', 'PEST', 'palawan', 'Jastinepagatpatan82@Gmail.Com', NULL, NULL, 'Email', NULL, '1', '2026-04-26 23:00:15', 'Hanna Marie Lorica', 1, '2026-04-26 18:32:49', '2026-04-26 18:32:49', '2026-04-26 23:00:15', '4/18/2026', '2026-04-23'),
(146, 'WILFREDO F LLANA', 'San Antonio, Cuyapo, Nueva Ecija', 'Nueva Ecija', 'Cuyapo', 'San Antonio', 'rice', 'RSBSA', 'DINAGA AT HINDI NAMUNGA', 'not_indicated', 'Emily Sapon Llana', 'https://www.facebook.com/emily.llana.5', NULL, 'Facebook', NULL, '1', '2026-04-26 21:45:34', 'Facebook', 1, '2026-04-26 18:34:20', '2026-04-26 18:34:20', '2026-04-26 21:45:34', '2/20/2026', '2026-04-24'),
(147, 'CHRIZEL C. BUTCHAYO', 'Bianoan, Casiguran, Aurora', 'Aurora', 'Casiguran', 'Bianoan', 'rice', 'RSBSA', 'STEMBORER/ NANINILAW', 'not_indicated', 'ahllenbutchayo314@gmail.com', NULL, NULL, 'Email', NULL, '1', '2026-04-26 21:45:34', 'Hanna Marie Lorica', 1, '2026-04-26 18:37:35', '2026-04-26 18:37:35', '2026-04-26 21:45:34', NULL, '2026-04-25'),
(148, 'WELISSA S. ADRIANO', 'Diaat, Maria Aurora, Aurora', 'Aurora', 'Maria Aurora', 'Diaat', 'livestock', 'RSBSA', 'SAMRID', 'not_indicated', 'pcicaurora@yahoo.com', NULL, NULL, 'Email', NULL, '1', '2026-04-26 21:45:34', 'Hanna Marie Lorica', 1, '2026-04-26 18:39:15', '2026-04-26 18:39:15', '2026-04-26 21:45:34', '4/18/2026', '2026-04-27'),
(149, 'ROWELL Y. SERADOY', 'Borlongan, Dipaculao, Aurora', 'Aurora', 'Dipaculao', 'Borlongan', 'rice', 'RSBSA', 'LACKOF WATER (DRY)', 'palawan', 'pcicaurora@yahoo.com', NULL, NULL, 'Email', NULL, '1', '2026-04-26 21:45:34', 'Hanna Marie Lorica', 1, '2026-04-26 18:40:57', '2026-04-26 18:40:57', '2026-04-26 21:45:34', '3/30/2026', '2026-04-27'),
(150, 'LOURDES L. DAYAG', 'Cadayacan, Maria Aurora, Aurora', 'Aurora', 'Maria Aurora', 'Cadayacan', 'high-value', 'RSBSA', 'TUYOT', 'palawan', 'pcicaurora@yahoo.com', NULL, NULL, 'Email', NULL, '1', '2026-04-26 21:45:34', 'Hanna Marie Lorica', 1, '2026-04-26 18:42:48', '2026-04-26 18:42:48', '2026-04-26 21:45:34', '4/20/2026', '2026-04-27'),
(151, 'CHERRY LOUISE B. MAMARIL', 'Bayanihan, Maria Aurora, Aurora', 'Aurora', 'Maria Aurora', 'Bayanihan', 'high-value', 'CFITF', 'PEST', 'palawan', 'pcicaurora@yahoo.com', NULL, NULL, 'Email', NULL, '1', '2026-04-26 21:45:34', 'Hanna Marie Lorica', 1, '2026-04-26 18:45:14', '2026-04-26 18:45:14', '2026-04-26 21:45:34', '4/21/2026', '2026-04-27'),
(152, 'ADELAIDA S. DELA CRUZ', 'Calabalabaan, Science City of Muñoz, Nueva Ecija', 'Nueva Ecija', 'Science City of Muñoz', 'Calabalabaan', 'rice', 'RSBSA', 'BLB / DEADHEARTS', 'palawan', NULL, NULL, 'SIR ROLANDO', 'OD', NULL, '1', '2026-04-26 21:45:34', 'Glen Bondoc', 1, '2026-04-26 18:45:28', '2026-04-26 18:45:28', '2026-04-26 21:45:34', 'N/A', NULL),
(153, 'VIRGILIO M. HIPOLITO SR.', 'Calabalabaan, Science City of Muñoz, Nueva Ecija', 'Nueva Ecija', 'Science City of Muñoz', 'Calabalabaan', 'rice', 'RSBSA', 'TUNGRO', 'palawan', NULL, NULL, 'SIR ROLANDO', 'OD', NULL, '1', '2026-04-26 21:45:34', 'Glen Bondoc', 1, '2026-04-26 18:46:34', '2026-04-26 18:46:34', '2026-04-26 21:45:34', 'N/A', NULL),
(154, 'ZENAIDA DC TABADERO', 'Calabalabaan, Science City of Muñoz, Nueva Ecija', 'Nueva Ecija', 'Science City of Muñoz', 'Calabalabaan', 'rice', 'RSBSA', 'TUNGRO', 'palawan', NULL, NULL, 'SIR ROLANDO', 'OD', NULL, '1', '2026-04-26 22:43:22', 'Glen Bondoc', 1, '2026-04-26 18:47:24', '2026-04-26 18:47:24', '2026-04-26 22:43:22', 'N/A', NULL),
(155, 'RICO S. MORAL', 'Marikit, Casiguran, Aurora', 'Aurora', 'Casiguran', 'Marikit', 'rice', 'RSBSA', 'RECONSIDERATION LETTER', 'not_indicated', 'pcicaurora@yahoo.com', NULL, NULL, 'Email', NULL, '1', '2026-04-26 21:45:34', 'Hanna Marie Lorica', 1, '2026-04-26 18:48:05', '2026-04-26 18:48:05', '2026-04-26 21:45:34', NULL, '2026-04-27'),
(157, 'ERNESTO DS. DELA CRUZ', 'Calabalabaan, Science City of Muñoz, Nueva Ecija', 'Nueva Ecija', 'Science City of Muñoz', 'Calabalabaan', 'rice', 'RSBSA', 'TUNGRO', 'palawan', NULL, NULL, 'SIR ROLANDO', 'OD', NULL, '1', '2026-04-26 21:45:34', 'Glen Bondoc', 1, '2026-04-26 18:48:23', '2026-04-26 18:48:23', '2026-04-26 21:45:34', 'N/A', NULL),
(158, 'MARY JOY T. CASTRO', 'Calantas, Casiguran, Aurora', 'Aurora', 'Casiguran', 'Calantas', 'rice', 'RSBSA', 'STEMBORER DAMAGED AND WHITE HEAD', 'not_indicated', 'pciccasiguran@gmail.com', NULL, NULL, 'Email', NULL, '1', '2026-04-26 21:45:34', 'Hanna Marie Lorica', 1, '2026-04-26 18:50:15', '2026-04-26 18:50:15', '2026-04-26 21:45:34', '4/27/2026', '2026-04-27'),
(161, 'MARVIN R. BUENA', 'Tinib, Casiguran, Aurora', 'Aurora', 'Casiguran', 'Tinib', 'corn', 'RSBSA', 'N/A', 'not_indicated', 'pciccasiguran@gmail.com', NULL, NULL, 'Email', NULL, '1', '2026-04-26 21:45:34', 'Hanna Marie Lorica', 1, '2026-04-26 19:10:19', '2026-04-26 19:10:19', '2026-04-26 21:45:34', NULL, '2026-04-27'),
(162, 'RUFINO L. MARQUEZ', 'Calantas, Casiguran, Aurora', 'Aurora', 'Casiguran', 'Calantas', 'rice', 'RSBSA', 'STEMBORER', 'not_indicated', 'pciccasiguran@gmail.com', NULL, NULL, 'Email', NULL, '1', '2026-04-26 21:45:34', 'Hanna Marie Lorica', 1, '2026-04-26 19:12:07', '2026-04-26 19:12:07', '2026-04-26 21:45:34', '4/27/2026', '2026-04-27'),
(163, 'MOLINA, CEFERINO PORTERA', 'Tinib, Casiguran, Aurora', 'Aurora', 'Casiguran', 'Tinib', 'rice', 'RSBSA', 'STEMBORER', 'not_indicated', 'pciccasiguran@gmail.com', NULL, NULL, 'Email', NULL, '1', '2026-04-26 21:45:34', 'Hanna Marie Lorica', 1, '2026-04-26 19:14:24', '2026-04-26 19:14:24', '2026-04-26 21:45:34', '4/27/2026', '2026-04-27'),
(164, 'RONALD D. HIZON', 'Calangcuasan, Casiguran, Aurora', 'Aurora', 'Casiguran', 'Calangcuasan', 'rice', 'RSBSA', 'DROUGHT', 'not_indicated', 'pciccasiguran@gmail.com', NULL, NULL, 'Email', NULL, '1', '2026-04-26 21:45:34', 'Hanna Marie Lorica', 1, '2026-04-26 19:17:12', '2026-04-26 19:17:12', '2026-04-26 21:45:34', '4/27/2026', '2026-04-27'),
(165, 'ANGELICA C. BUENA', 'Tinib, Casiguran, Aurora', 'Aurora', 'Casiguran', 'Tinib', 'corn', 'RSBSA', 'FALL ARMY WORM', 'not_indicated', 'pciccasiguran@gmail.com', NULL, NULL, 'Email', NULL, '1', '2026-04-26 21:45:34', 'Hanna Marie Lorica', 1, '2026-04-26 19:25:10', '2026-04-26 19:25:10', '2026-04-26 21:45:34', '4/27/2026', '2026-04-27'),
(166, 'JOCELYN D. PLATA', 'Esperanza, Casiguran, Aurora', 'Aurora', 'Casiguran', 'Esperanza', 'rice', 'RSBSA', 'DROUGHT', 'not_indicated', 'Pciccasiguran@Gmail.Com', NULL, NULL, 'Email', NULL, '1', '2026-04-26 21:45:34', 'Hanna Marie Lorica', 1, '2026-04-26 19:28:16', '2026-04-26 19:28:16', '2026-04-26 21:45:34', '4/27/2026', '2026-04-27'),
(167, 'MARVIN R. BUENA', 'Tinib, Casiguran, Aurora', 'Aurora', 'Casiguran', 'Tinib', 'corn', 'RSBSA', 'FALL ARMY WORM', 'not_indicated', 'Pciccasiguran@Gmail.Com', NULL, 'WAITING IF SAME PARCEL LANG SA NAUNA', 'Email', NULL, '1', '2026-04-26 21:45:34', 'Hanna Marie Lorica', 1, '2026-04-26 19:30:31', '2026-04-26 19:30:31', '2026-04-26 21:45:34', '4/22/2026', '2026-04-27'),
(168, 'MERVIN  R. FABRIGAS', 'Ditale, Dipaculao, Aurora', 'Aurora', 'Dipaculao', 'Ditale', 'corn', 'RSBSA', 'TUYOT', 'not_indicated', 'Lgu.Maodipaculao@Gmail.Com', NULL, NULL, 'Email', NULL, '1', '2026-04-26 21:45:34', 'Hanna Marie Lorica', 1, '2026-04-26 19:39:57', '2026-04-26 19:39:57', '2026-04-26 21:45:34', 'MARCH', '2026-04-27'),
(169, 'Albert G. Torre', 'Dibet, Casiguran, Aurora', 'Aurora', 'Casiguran', 'Dibet', 'rice', 'RSBSA', 'Stemborer', 'not_indicated', 'Cresielfriginal882@Gmail.Com', NULL, 'Via Yahoo', 'Email', NULL, '1', '2026-04-26 21:45:34', 'Julie Ann Espejo', 1, '2026-04-26 19:45:49', '2026-04-26 19:45:49', '2026-04-26 21:45:34', '4/23/2026', '2026-04-23'),
(170, 'Apolonio A. Aguilar Jr.', 'Esteves, Casiguran, Aurora', 'Aurora', 'Casiguran', 'Esteves', 'rice', 'RSBSA', 'Stemborer', 'not_indicated', 'Cresielfriginal882@Gmail.Com', NULL, 'Via Yahoo', 'Email', NULL, '1', '2026-04-26 21:45:34', 'Julie Ann Espejo', 1, '2026-04-26 19:48:05', '2026-04-26 19:48:05', '2026-04-26 21:45:34', '4/23/2026', '2026-04-23'),
(171, 'FLORENTINO OLETE JR', 'Dibet, Casiguran, Aurora', 'Aurora', 'Casiguran', 'Dibet', 'rice', 'RSBSA', 'STEMBORER', 'not_indicated', 'Pciccasiguran@Gmail.Com', NULL, 'WAITING IF SAME PARCEL LANG. 2 ANG SINEND', 'Email', NULL, '1', '2026-04-26 21:45:34', 'Hanna Marie Lorica', 1, '2026-04-26 19:49:37', '2026-04-26 19:49:37', '2026-04-26 21:45:34', '4/27/2026', '2026-04-27'),
(172, 'Herminiano Flores Marcos Jr.', 'Esperanza, Casiguran, Aurora', 'Aurora', 'Casiguran', 'Esperanza', 'rice', 'RSBSA', 'Drought', 'not_indicated', 'Cresielfriginal882@Gmail.Com', NULL, 'Via Yahoo', 'Email', NULL, '1', '2026-04-26 21:45:34', 'Julie Ann Espejo', 1, '2026-04-26 19:49:40', '2026-04-26 19:49:40', '2026-04-26 21:45:34', '4/23/2026', '2026-04-23'),
(173, 'Willy PeñA Soriano', 'Calangcuasan, Casiguran, Aurora', 'Aurora', 'Casiguran', 'Calangcuasan', 'rice', 'RSBSA', 'Stemborer', 'not_indicated', 'Cresielfriginal882@Gmail.Com', NULL, 'Via Yahoo', 'Email', NULL, '1', '2026-04-26 21:45:34', 'Julie Ann Espejo', 1, '2026-04-26 19:50:37', '2026-04-26 19:50:37', '2026-04-26 21:45:34', '4/23/2026', '2026-04-23'),
(174, 'FLORENTINO OLETE JR', 'Dibet, Casiguran, Aurora', 'Aurora', 'Casiguran', 'Dibet', 'rice', 'RSBSA', 'STEMBORER', 'not_indicated', 'Pciccasiguran@Gmail.Com', NULL, 'WAITING IF SAME PARCEL LANG. 2 ANG SINEND', 'Email', NULL, '1', '2026-04-26 21:45:34', 'Hanna Marie Lorica', 1, '2026-04-26 19:52:55', '2026-04-26 19:52:55', '2026-04-26 21:45:34', '4/27/2026', '2026-04-27'),
(175, 'Eufemia T. Turzar', 'Bianoan, Casiguran, Aurora', 'Aurora', 'Casiguran', 'Bianoan', 'rice', 'RSBSA', 'Stemborer/Rice Grain Bug/Rice Bin/Drought', 'not_indicated', 'Cherrydelavega15@Gmail.Com', NULL, 'Via Yahoo', 'Email', NULL, '1', '2026-04-26 21:45:34', 'Julie Ann Espejo', 1, '2026-04-26 19:55:07', '2026-04-26 19:55:07', '2026-04-26 21:45:34', '3/31/2026', '2026-04-27'),
(180, 'RESTITUTO M LIWAG', 'San Cristobal, Licab, Nueva Ecija', 'Nueva Ecija', 'Licab', 'San Cristobal', 'rice', 'RSBSA', 'BAHA DULOT NG MALAKAS ULAN', 'palawan', 'SIR GLEYMOR', NULL, NULL, 'Facebook', NULL, '1', '2026-04-26 21:45:34', 'Facebook', 1, '2026-04-26 21:22:49', '2026-04-26 21:22:49', '2026-04-26 21:45:34', NULL, '2026-04-24'),
(181, 'FELINO C PABLO', 'Mallorca, San Leonardo, Nueva Ecija', 'Nueva Ecija', 'San Leonardo', 'Mallorca', 'clti', 'RSBSA', 'LEPTOSPIROS', 'not_indicated', 'Maria Luisa Nepomuceno Mendoza', 'https://www.facebook.com/marialuisa.nepomuceno.56#', 'FORWARDED TO SIR GLEN', 'Facebook', NULL, '1', '2026-04-26 21:45:34', 'Facebook', 1, '2026-04-26 21:28:25', '2026-04-26 21:28:25', '2026-04-26 21:45:34', '3/28/2026', '2026-04-24'),
(182, 'CARMELITA QUIMING', 'Labney, Science City of Muñoz, Nueva Ecija', 'Nueva Ecija', 'Science City of Muñoz', 'Labney', 'rice', 'RSBSA', 'TUNGRO/NAMUMULA', 'not_indicated', 'Marivic Quiming', 'https://www.facebook.com/marivic.quiming.1#', NULL, 'Facebook', NULL, '1', '2026-04-26 21:45:34', 'Facebook', 1, '2026-04-26 21:31:10', '2026-04-26 21:31:10', '2026-04-26 21:45:34', 'APRIL', '2026-04-25'),
(183, 'LITO F CARIDAD SR', 'Lawang, Dilasag, Aurora', 'Aurora', 'Dilasag', 'Lawang', 'corn', 'RSBSA', 'DROUGHT', 'palawan', 'DA DILASAG', 'https://www.facebook.com/omag.dilasag', NULL, 'Facebook', NULL, '1', '2026-04-26 21:45:34', 'Facebook', 1, '2026-04-26 21:32:49', '2026-04-26 21:32:49', '2026-04-26 21:45:34', 'DROUGHT', '2026-04-23'),
(184, 'FELIPE G ESTRIBER', 'Dimaseset, Dilasag, Aurora', 'Aurora', 'Dilasag', 'Dimaseset', 'rice', 'RSBSA', 'RAT PEST/BPH', 'palawan', 'DA DILASAG', 'https://www.facebook.com/omag.dilasag', NULL, 'Facebook', NULL, '1', '2026-04-26 21:45:34', 'Facebook', 1, '2026-04-26 21:34:18', '2026-04-26 21:34:18', '2026-04-26 21:45:34', '4/13/2026', '2026-04-23'),
(185, 'FERNANDO ANTONIO LOPEZ', 'Aglipay, Rizal, Nueva Ecija', 'Nueva Ecija', 'Rizal', 'Aglipay', 'rice', 'RSBSA', 'LEAF HOPPER, UBAN', 'not_indicated', 'Fe Marie Lopez', 'https://www.facebook.com/femarie.lopez#', NULL, 'Facebook', NULL, '1', '2026-04-26 21:45:34', 'Facebook', 1, '2026-04-26 21:36:01', '2026-04-26 21:36:01', '2026-04-26 21:45:34', 'SUMASAPAW', '2026-04-23'),
(186, 'YOLANDA P ESTEBAN', 'San Antonio, Cuyapo, Nueva Ecija', 'Nueva Ecija', 'Cuyapo', 'San Antonio', 'rice', 'RSBSA', '.', 'not_indicated', 'Armando Esteban', 'https://www.facebook.com/armando.esteban.142240#', NULL, 'Facebook', NULL, '1', '2026-04-26 21:45:34', 'Facebook', 1, '2026-04-26 21:37:43', '2026-04-26 21:37:43', '2026-04-26 21:45:34', '3/20/2026', '2026-04-26'),
(187, 'MARIO S CASTILLO', 'Dimaseset, Dilasag, Aurora', 'Aurora', 'Dilasag', 'Dimaseset', 'rice', 'RSBSA', 'RAT PEST, ULMOG', 'palawan', 'DA DILASAG', 'https://www.facebook.com/omag.dilasag', NULL, 'Facebook', NULL, '1', '2026-04-26 21:45:34', 'Facebook', 1, '2026-04-26 21:38:40', '2026-04-26 21:38:40', '2026-04-26 21:45:34', '4/13/2026', '2026-04-27'),
(188, 'JOSSIE R PEDRONIO', 'Dimaseset, Dilasag, Aurora', 'Aurora', 'Dilasag', 'Dimaseset', 'rice', 'RSBSA', 'RAT PEST, ULMOG, ARMYWORM', 'palawan', 'DA DILASAG', 'https://www.facebook.com/omag.dilasag', NULL, 'Facebook', NULL, '1', '2026-04-26 21:45:34', 'Facebook', 1, '2026-04-26 21:39:28', '2026-04-26 21:39:28', '2026-04-26 21:45:34', '4/10/2026', '2026-04-27'),
(189, 'CARLITO D SANCHEZ', 'Columbitin, Cuyapo, Nueva Ecija', 'Nueva Ecija', 'Cuyapo', 'Columbitin', 'rice', 'RSBSA', 'RAT DAMAGE/INSECT (STEMBORER, BLB)', 'not_indicated', 'CARL SANCHEZ', 'https://www.facebook.com/carl.sanchez.892053#', NULL, 'Facebook', NULL, '1', '2026-04-26 21:45:34', 'Facebook', 1, '2026-04-26 21:40:44', '2026-04-26 21:40:44', '2026-04-26 21:45:34', 'FEB TO MARCH', '2026-04-27'),
(190, 'GERARDO BUNAG BALMEO', 'Bakal II, Talavera, Nueva Ecija', 'Nueva Ecija', 'Talavera', 'Bakal II', 'rice', 'RSBSA', 'STEMBORER', 'not_indicated', 'GERARDO BALMEO', 'https://www.facebook.com/gerardo.balmeo#', NULL, 'Facebook', NULL, '1', '2026-04-26 21:45:34', 'Facebook', 1, '2026-04-26 21:42:22', '2026-04-26 21:42:22', '2026-04-26 21:45:34', '3/30/2026', '2026-04-27'),
(191, 'FRANCIS ADRIAN E. RODOLFO', 'Bantug, Science City of Muñoz, Nueva Ecija', 'Nueva Ecija', 'Science City of Muñoz', 'Bantug', 'rice', 'RSBSA', 'STUNTED GROWTH', 'not_indicated', 'NJ DELA CRUZ', 'https://www.facebook.com/noel.d.cruz.3#', NULL, 'Facebook', NULL, '1', '2026-04-26 21:45:34', 'Facebook', 1, '2026-04-26 21:43:46', '2026-04-26 21:43:46', '2026-04-26 21:45:34', '4/20/2026', '2026-04-27'),
(192, 'HONORIO S. MARIANO', 'San Roque, San Leonardo, Nueva Ecija', 'Nueva Ecija', 'San Leonardo', 'San Roque', 'rice', 'RSBSA', 'BATOK', 'not_indicated', 'Jayarminadelacruz10@Gmail.Com', NULL, NULL, 'Email', NULL, '2', '2026-04-27 01:20:27', 'Hanna Marie Lorica', 1, '2026-04-26 21:44:55', '2026-04-26 21:44:55', '2026-04-27 01:20:27', '4/27/2026', '2026-04-27'),
(194, 'ROLANDO C AGAT', 'Calisitan, Science City of Muñoz, Nueva Ecija', 'Nueva Ecija', 'Science City of Muñoz', 'Calisitan', 'rice', 'RSBSA', 'STEMBORER', 'not_indicated', 'N/A', NULL, 'C/O Sir Ian', 'Facebook', NULL, '2', '2026-04-27 01:20:27', 'Facebook', 1, '2026-04-26 22:13:46', '2026-04-26 22:13:46', '2026-04-27 01:20:27', '4/20/2026', '2026-04-27'),
(195, 'CARLITO F DONATO', 'San Juan, Laur, Nueva Ecija', 'Nueva Ecija', 'Laur', 'San Juan', 'livestock', 'RSBSA', 'PROLAPSE', 'not_indicated', 'CARLITO FRAGATA DONATO', 'https://www.facebook.com/carlitofragata.donato#', 'FORWARDED TO MAAM REIGN', 'Facebook', NULL, '2', '2026-04-27 01:20:27', 'Facebook', 1, '2026-04-26 22:23:45', '2026-04-26 22:23:45', '2026-04-27 01:20:27', '4/23/2026', '2026-04-25'),
(196, 'ARLENE B PEREZ', 'Bakal II, Talavera, Nueva Ecija', 'Nueva Ecija', 'Talavera', 'Bakal II', 'rice', 'RSBSA', 'STEMBORER, PLANT DISEASE', 'not_indicated', 'Arlene Perez', 'https://www.facebook.com/milove.arlene#', NULL, 'Facebook', NULL, '2', '2026-04-27 01:20:27', 'Facebook', 1, '2026-04-26 22:32:12', '2026-04-26 22:32:12', '2026-04-27 01:20:27', '04/02/2026', '2026-04-27'),
(197, 'JAMES RAMOS', 'General Luna, Carranglan, Nueva Ecija', 'Nueva Ecija', 'Carranglan', 'General Luna', 'livestock', 'RSBSA', 'PHOTO OF DEAD GOAT', 'not_indicated', 'Emily Rosario Baltazar', 'https://www.facebook.com/profile.php?id=61578144235357#', 'FORWARDED TO MAAM REIGN', 'Facebook', NULL, '2', '2026-04-27 01:20:27', 'Facebook', 1, '2026-04-26 23:08:11', '2026-04-26 23:08:11', '2026-04-27 01:20:27', NULL, '2026-04-27'),
(198, 'ROLANDE TENCE FRANCISCO', 'Conversion, Pantabangan, Nueva Ecija', 'Nueva Ecija', 'Pantabangan', 'Conversion', 'livestock', 'RSBSA', 'FOLLOW UP DOCS', 'not_indicated', 'Rechelle Villaluna', 'https://www.facebook.com/rechelle.villaluna.395#', 'FORWARDED TO MAAM REIGN', 'Facebook', NULL, '2', '2026-04-27 01:20:27', 'Facebook', 1, '2026-04-26 23:13:36', '2026-04-26 23:13:36', '2026-04-27 01:20:27', NULL, '2026-04-27'),
(199, 'LEONARD B PEREZ', 'Bakal II, Talavera, Nueva Ecija', 'Nueva Ecija', 'Talavera', 'Bakal II', 'rice', 'REGULAR', 'stemborer', 'not_indicated', 'Leonard B Perez', 'https://www.facebook.com/dranoelzerep2186#', NULL, 'Facebook', NULL, '2', '2026-04-27 01:20:27', 'Facebook', 1, '2026-04-26 23:25:44', '2026-04-26 23:25:44', '2026-04-27 01:20:27', '4/2/2026', '2026-04-27'),
(200, 'RAFEL S PEREZ', 'Bakal II, Talavera, Nueva Ecija', 'Nueva Ecija', 'Talavera', 'Bakal II', 'rice', 'REGULAR', 'STEMBORER/UNKNOWN DISEASE', 'not_indicated', 'Rafael Perez', 'https://www.facebook.com/rafael.perez.414876', NULL, 'Facebook', NULL, '2', '2026-04-27 01:20:27', 'Facebook', 1, '2026-04-26 23:28:25', '2026-04-26 23:28:25', '2026-04-27 01:20:27', '3/30/2026', '2026-04-27'),
(201, 'MIKE TORALBA', 'Conversion, Pantabangan, Nueva Ecija', 'Nueva Ecija', 'Pantabangan', 'Conversion', 'livestock', 'RSBSA', 'SAMBRED', 'not_indicated', 'Mike Femol Toralba', 'https://www.facebook.com/mike.femol.toralba', 'FORWARDED TO MAAM REIGN', 'Facebook', NULL, '2', '2026-04-27 01:20:27', 'Facebook', 1, '2026-04-26 23:31:00', '2026-04-26 23:31:00', '2026-04-27 01:20:27', '4/22/2026', '2026-04-27'),
(202, 'NORILYN P. AGUSTIN', 'Bakal II, Talavera, Nueva Ecija', 'Nueva Ecija', 'Talavera', 'Bakal II', 'rice', 'REGULAR', 'STEMBORER/PLANT DISEASE', 'not_indicated', 'Norilyn Perez Agustin', 'https://www.facebook.com/npagustin', NULL, 'Facebook', NULL, '2', '2026-04-27 01:20:27', 'Facebook', 1, '2026-04-26 23:37:13', '2026-04-26 23:37:13', '2026-04-27 01:20:27', '4/1/2026', '2026-04-27'),
(203, 'VERNIE AGUSTIN', 'Bakal II, Talavera, Nueva Ecija', 'Nueva Ecija', 'Talavera', 'Bakal II', 'rice', 'REGULAR', 'STEMBORER', 'not_indicated', 'Agustin Vernie', 'https://www.facebook.com/vernie.f.agustin', NULL, 'Facebook', NULL, '2', '2026-04-27 01:20:27', 'Facebook', 1, '2026-04-26 23:40:26', '2026-04-26 23:40:26', '2026-04-27 01:20:27', '4/1/2026', '2026-04-27'),
(204, 'ROLANDO B BALMEO', 'Bakal II, Talavera, Nueva Ecija', 'Nueva Ecija', 'Talavera', 'Bakal II', 'rice', 'REGULAR', 'STEMBORER', 'not_indicated', 'Vita F. Balmeo', 'https://www.facebook.com/vita.f.balmeo', NULL, 'Facebook', NULL, '2', '2026-04-27 01:20:27', 'Facebook', 1, '2026-04-26 23:45:19', '2026-04-26 23:45:19', '2026-04-27 01:20:27', '4/1/2026', '2026-04-27'),
(207, 'ALBERTO C. JOSUE', 'Diniog, Dilasag, Aurora', 'Aurora', 'Dilasag', 'Diniog', 'rice', 'RSBSA', 'BPH', 'palawan', 'DA DILASAG', 'https://www.facebook.com/omag.dilasag', NULL, 'Facebook', NULL, '3', '2026-04-28 03:48:41', 'Facebook', 1, '2026-04-27 01:28:59', '2026-04-27 01:28:59', '2026-04-28 03:48:41', '4/18/2026', '2026-04-27'),
(208, 'PETER B EUGENIO', 'Esperanza, Dilasag, Aurora', 'Aurora', 'Dilasag', 'Esperanza', 'rice', 'RSBSA', 'ARMY WORM, BPH, DROUGHT', 'palawan', 'DA DILASAG', 'https://www.facebook.com/omag.dilasag', NULL, 'Facebook', NULL, '3', '2026-04-28 03:48:41', 'Facebook', 1, '2026-04-27 01:29:50', '2026-04-27 01:29:50', '2026-04-28 03:48:41', '4/23/2026', '2026-04-27'),
(209, 'SONRY D SORIA', 'Esperanza, Dilasag, Aurora', 'Aurora', 'Dilasag', 'Esperanza', 'rice', 'RSBSA', 'DROUGHT', 'palawan', 'DA DILASAG', 'https://www.facebook.com/omag.dilasag', NULL, 'Facebook', NULL, '3', '2026-04-28 03:48:41', 'Facebook', 1, '2026-04-27 01:30:39', '2026-04-27 01:30:39', '2026-04-28 03:48:41', '4/23/2026', '2026-04-27'),
(210, 'EDDIE DC LAMBINICIO', 'Masagana, Dilasag, Aurora', 'Aurora', 'Dilasag', 'Masagana', 'rice', 'RSBSA', 'DROUGHT', 'palawan', 'DA DILASAG', 'https://www.facebook.com/omag.dilasag', NULL, 'Facebook', NULL, '3', '2026-04-28 03:48:41', 'Facebook', 1, '2026-04-27 01:31:42', '2026-04-27 01:31:42', '2026-04-28 03:48:41', '4/22/2026', '2026-04-27'),
(211, 'FREDERICK M. PINEDA', 'Dimaseset, Dilasag, Aurora', 'Aurora', 'Dilasag', 'Dimaseset', 'rice', 'RSBSA', 'BPH', 'palawan', 'DA DILASAG', 'https://www.facebook.com/omag.dilasag', NULL, 'Facebook', NULL, '3', '2026-04-28 03:48:41', 'Facebook', 1, '2026-04-27 01:33:09', '2026-04-27 01:33:09', '2026-04-28 03:48:41', '4/20/2026', '2026-04-27'),
(212, 'SAMUEL B RAMAC', 'Dimaseset, Dilasag, Aurora', 'Aurora', 'Dilasag', 'Dimaseset', 'rice', 'RSBSA', 'BPH', 'palawan', 'DA DILASAG', 'https://www.facebook.com/omag.dilasag', NULL, 'Facebook', NULL, '3', '2026-04-28 03:48:41', 'Facebook', 1, '2026-04-27 01:33:58', '2026-04-27 01:33:58', '2026-04-28 03:48:41', '4/20/2026', '2026-04-27'),
(213, 'RAUL L PORTERA', 'Dimaseset, Dilasag, Aurora', 'Aurora', 'Dilasag', 'Dimaseset', 'rice', 'RSBSA', '.', 'palawan', 'DA DILASAG', 'https://www.facebook.com/omag.dilasag', NULL, 'Facebook', NULL, '3', '2026-04-28 03:48:41', 'Facebook', 1, '2026-04-27 01:34:59', '2026-04-27 01:34:59', '2026-04-28 03:48:41', NULL, '2026-04-27'),
(221, 'DOMINGO O FLORES', 'District VI, Cuyapo, Nueva Ecija', 'Nueva Ecija', 'Cuyapo', 'District VI', 'livestock', 'RSBSA', 'DYSTOCIA', 'not_indicated', 'Donald Flores', 'https://www.facebook.com/donald.flores.819830#', 'FORWARDED TO MAAM REIGN', 'Facebook', NULL, '3', '2026-04-28 03:48:41', 'Facebook', 1, '2026-04-27 01:54:57', '2026-04-27 01:54:57', '2026-04-28 03:48:41', '4/21/2026', '2026-04-27'),
(230, 'DIEGO C. PAGHUBASAN', 'Esteves, Casiguran, Aurora', 'Aurora', 'Casiguran', 'Esteves', 'rice', 'RSBSA', 'STEMBORER', 'not_indicated', 'Pciccasiguran@Gmail.Com', NULL, NULL, 'Email', NULL, '3', '2026-04-28 03:48:41', 'Hanna Marie Lorica', 1, '2026-04-28 01:30:06', '2026-04-28 01:30:06', '2026-04-28 03:48:41', '4/27/2026', '2026-04-27'),
(231, 'ROLANDO A. FERNANDEZ', 'Dibacong, Casiguran, Aurora', 'Aurora', 'Casiguran', 'Dibacong', 'rice', 'RSBSA', 'DROUGHT', 'not_indicated', 'Pciccasiguran@Gmail.Com', NULL, NULL, 'Email', NULL, '3', '2026-04-28 03:48:41', 'Hanna Marie Lorica', 1, '2026-04-28 01:31:27', '2026-04-28 01:31:27', '2026-04-28 03:48:41', '4/24/2026', '2026-04-27'),
(232, 'JAYSON JOHN B. ALVAREZ', 'Barangay 1, Casiguran, Aurora', 'Aurora', 'Casiguran', 'Barangay 1', 'livestock', 'RSBSA', 'SAMRID', 'not_indicated', 'Pciccasiguran@Gmail.Com', NULL, NULL, 'Email', NULL, '3', '2026-04-28 03:48:41', 'Hanna Marie Lorica', 1, '2026-04-28 01:38:20', '2026-04-28 01:38:20', '2026-04-28 03:48:41', '4/26/2026', '2026-04-27'),
(233, 'MADILYN N. RAMOS', 'Santa Isabel, Cabiao, Nueva Ecija', 'Nueva Ecija', 'Cabiao', 'Santa Isabel', 'rice', 'REGULAR', 'STEMBORER AT NECKROT', 'not_indicated', 'Sunshineagricoop@Gmail.Com', NULL, NULL, 'Email', NULL, '3', '2026-04-28 03:48:41', 'Hanna Marie Lorica', 1, '2026-04-28 01:43:58', '2026-04-28 01:43:58', '2026-04-28 03:48:41', '4/20/2026', '2026-04-28'),
(234, 'ROLLY R. MARQUEZ', 'Calantas, Casiguran, Aurora', 'Aurora', 'Casiguran', 'Calantas', 'rice', 'RSBSA', 'STEMBORER', 'not_indicated', 'Pciccasiguran@Gmail.Com', NULL, NULL, 'Email', NULL, '3', '2026-04-28 03:48:41', 'Hanna Marie Lorica', 1, '2026-04-28 01:46:34', '2026-04-28 01:46:34', '2026-04-28 03:48:41', '4/24/2026', '2026-04-28'),
(235, 'ANALYN ESPORNA', 'Pias, General Tinio, Nueva Ecija', 'Nueva Ecija', 'General Tinio', 'Pias', 'rice', 'RSBSA', 'DROUGHT', 'not_indicated', 'Dakristel28@Gmail.Com', NULL, '1st parcel', 'Email', NULL, '3', '2026-04-28 03:48:41', 'Hanna Marie Lorica', 1, '2026-04-28 02:17:20', '2026-04-28 02:17:20', '2026-04-28 03:48:41', '4/15/2026', '2026-04-28'),
(236, 'ANALYN ESPORNA', 'Pias, General Tinio, Nueva Ecija', 'Nueva Ecija', 'General Tinio', 'Pias', 'rice', 'RSBSA', 'DROUGHT', 'not_indicated', 'Dakristel28@Gmail.Com', NULL, '2nd PARCEL', 'Email', NULL, '3', '2026-04-28 03:48:41', 'Hanna Marie Lorica', 1, '2026-04-28 02:21:44', '2026-04-28 02:21:44', '2026-04-28 03:48:41', '4/10/2026', '2026-04-28'),
(237, 'EDWIN A. BERNAS', 'Calantas, Casiguran, Aurora', 'Aurora', 'Casiguran', 'Calantas', 'rice', 'RSBSA', 'STEMBORER', 'not_indicated', 'Pciccasiguran@Gmail.Com', NULL, NULL, 'Email', NULL, '3', '2026-04-28 03:48:41', 'Hanna Marie Lorica', 1, '2026-04-28 02:44:00', '2026-04-28 02:44:00', '2026-04-28 03:48:41', '4/25/2026', '2026-04-28'),
(238, 'ROSITA S. TAPEL', 'Esteves, Casiguran, Aurora', 'Aurora', 'Casiguran', 'Esteves', 'rice', 'REGULAR', 'DROUGHT', 'not_indicated', 'Pciccasiguran@Gmail.Com', NULL, NULL, 'Email', NULL, '3', '2026-04-28 03:48:41', 'Hanna Marie Lorica', 1, '2026-04-28 02:44:59', '2026-04-28 02:44:59', '2026-04-28 03:48:41', '4/28/2026', '2026-04-28'),
(239, 'JAYSON B. DAMASO', 'Colosboa, Cuyapo, Nueva Ecija', 'Nueva Ecija', 'Cuyapo', 'Colosboa', 'livestock', 'RSBSA', 'SNAKE BITE', 'not_indicated', 'Jayson Damaso', 'https://www.facebook.com/jayson.damaso.5', 'C/O MAAM REIGN', 'Facebook', NULL, '3', '2026-04-28 04:31:44', 'Facebook', 1, '2026-04-28 03:20:38', '2026-04-28 03:20:38', '2026-04-28 04:31:44', '4/22/2026', '2026-04-28'),
(240, 'JOEL P. VELASCO TEST', 'Calem, Guimba, Nueva Ecija', 'Nueva Ecija', 'Guimba', 'Calem', 'rice', 'RSBSA', 'TUNGRO', 'check', 'SIR GLEYMOR', '', '', 'Facebook', NULL, '3', '2026-04-28 04:34:59', 'Facebook', 1, '2026-04-28 03:22:30', '2026-04-28 03:22:30', '2026-04-28 04:34:59', '3/30/2026', '2026-04-27'),
(241, 'FREDDIE F. PRIMO', 'Calem, Guimba, Nueva Ecija', 'Nueva Ecija', 'Guimba', 'Calem', 'rice', 'RSBSA', 'TUNGRO', 'check', 'SIR GLEYMOR', NULL, NULL, 'Facebook', NULL, '3', '2026-04-28 04:37:59', 'Facebook', 1, '2026-04-28 03:23:21', '2026-04-28 03:23:21', '2026-04-28 04:37:59', '3/30/2026', '2026-04-28'),
(242, 'PACIFICO PINEDA CANILLO JR', 'Mapalad, Dinalungan, Aurora', 'Aurora', 'Dinalungan', 'Mapalad', 'clti', 'REGULAR', 'HEMORRHAGIC STROKE', 'not_indicated', 'FROM SIR OTEP', NULL, 'C/O SIR GLEN', 'Facebook', NULL, '3', '2026-04-28 05:09:02', 'Facebook', 1, '2026-04-28 03:30:59', '2026-04-28 03:30:59', '2026-04-28 05:09:02', '3/28/2026', '2026-04-28'),
(243, 'JOYCE Q. DAMASO', 'Decoliat, Maria Aurora, Aurora', 'Aurora', 'Maria Aurora', 'Decoliat', 'corn', 'RSBSA', 'TUYOT', 'not_indicated', 'Pcicaurora@Yahoo.Com', NULL, 'LOT NO.1', 'Email', NULL, '3', '2026-04-28 03:48:40', 'Hanna Marie Lorica', 1, '2026-04-28 03:32:34', '2026-04-28 03:32:34', '2026-04-28 03:48:40', '03/15 - 4/27/2026', '2026-04-28'),
(244, 'JOYCE Q. DAMASO', 'Decoliat, Maria Aurora, Aurora', 'Aurora', 'Maria Aurora', 'Decoliat', 'rice', 'RSBSA', 'TUYOT', 'not_indicated', 'Pcicaurora@Yahoo.Com', NULL, 'LOT NO.2', 'Email', NULL, '3', '2026-04-28 03:48:40', 'Hanna Marie Lorica', 1, '2026-04-28 03:33:45', '2026-04-28 03:33:45', '2026-04-28 03:48:40', 'MARCH 15- APRIL 27, 2026', '2026-04-28'),
(245, 'GINA A. GULENG', 'Bannawag, Maria Aurora, Aurora', 'Aurora', 'Maria Aurora', 'Bannawag', 'high-value', 'CFITF', 'PEST', 'palawan', 'Pcicaurora@Yahoo.Com', NULL, NULL, 'Email', NULL, '3', '2026-04-28 03:48:40', 'Hanna Marie Lorica', 1, '2026-04-28 03:40:10', '2026-04-28 03:40:10', '2026-04-28 03:48:40', '4/24/2026', '2026-04-28'),
(246, 'FB SAMPLE TEDDY', 'Betes, Aliaga, Nueva Ecija', 'Nueva Ecija', 'Aliaga', 'Betes', 'rice', 'RSBSA', 'SAMPLE', 'palawan', 'NONE', NULL, 'SAMPLE', 'Facebook', NULL, NULL, NULL, 'Facebook', 1, '2026-04-28 04:33:52', '2026-04-28 04:33:52', '2026-04-28 04:36:20', 'SAMPLE', '2026-04-28');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` text NOT NULL,
  `last_activity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`, `created_at`, `updated_at`) VALUES
('bNbbHaq19PLfeZ8LICky4fdCXWKM197CDyQNaSFM', NULL, '192.168.30.51', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiOFhFYUpJNDRza2JnM3ljTmdUOTN2UXFLZVltSVJramtXY0o4V2xHZCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly8xOTIuMTY4LjMwLjkyL2VtYWlsLWhhbmRsZXIiO3M6NToicm91dGUiO3M6MTM6ImVtYWlsLWhhbmRsZXIiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjE1OiJlbWFpbF91c2VyX25hbWUiO3M6MTY6Ikp1bGllIEFubiBFc3Blam8iO3M6MTU6ImVtYWlsX2xvZ2dlZF9pbiI7YjoxO30=', 1777262107, NULL, NULL),
('EQ3ddJZBjUBWuy8OO88w3LE4zyijHTnuAldI9hfQ', NULL, '192.168.30.69', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiU0dnNEFzMTVidDYzbHRMVVhlS0FwU2w2cHpWdlBoVWFzUTFZd2hoQyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDg6Imh0dHA6Ly8xOTIuMTY4LjMwLjkyL2FkbWluL2FwaS9wZW5kaW5nLWFwcHJvdmFscyI7czo1OiJyb3V0ZSI7czoyNzoiYWRtaW4uYXBpLnBlbmRpbmctYXBwcm92YWxzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1777281924, NULL, NULL),
('jYsH8kdUAGH4Kod36Z3iFyXKB7BVuzU3caC3Ed9V', NULL, '192.168.30.67', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTzMzVmNXeWpLVjNoR0RXMXpvRDhOdUZTY0kxZHFSSnV6N0pVT0J1eCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjA6Imh0dHA6Ly8xOTIuMTY4LjMwLjkyIjtzOjU6InJvdXRlIjtzOjc6IndlbGNvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1777265838, NULL, NULL),
('pY5WheuHHZQHRzhCaUDIcBnk02id1BKpnIGfZSx7', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiTGNpUloyYTc4UWV4QW5pQTVVeTlURWJMOGZJNFYybU81SklPUm04dCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1777283867, NULL, NULL),
('vyt8pq3hmJNgTx7PN0DsAlnZ78Msyxky75dZDhFY', NULL, '192.168.30.244', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiOGZXbVVUZGJDd2x0cGUzY08xaXcwd0NvVnJpdTBVZnBqTnZ2YWU3RSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly8xOTIuMTY4LjMwLjkyL2VtYWlsLWhhbmRsZXIiO3M6NToicm91dGUiO3M6MTM6ImVtYWlsLWhhbmRsZXIiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjE1OiJlbWFpbF91c2VyX25hbWUiO3M6MTg6Ikhhbm5hIE1hcmllIExvcmljYSI7czoxNToiZW1haWxfbG9nZ2VkX2luIjtiOjE7fQ==', 1777268969, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_username_unique` (`username`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `email_handlers`
--
ALTER TABLE `email_handlers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_handlers_name_unique` (`name`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `officers`
--
ALTER TABLE `officers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `officers_name_unique` (`name`);

--
-- Indexes for table `records`
--
ALTER TABLE `records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `email_handlers`
--
ALTER TABLE `email_handlers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `officers`
--
ALTER TABLE `officers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `records`
--
ALTER TABLE `records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=247;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
