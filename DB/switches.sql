-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 11, 2023 at 04:09 AM
-- Server version: 8.0.31
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `switches`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL DEFAULT '0',
  `project_id` int NOT NULL DEFAULT '0',
  `log_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `project_id`, `log_type`, `remark`, `created_at`, `updated_at`) VALUES
(1, 4, 3, 'Create Task', '{\"title\":\"Development\"}', '2023-01-04 07:26:51', '2023-01-04 07:26:51'),
(2, 4, 1, 'Create Milestone', '{\"title\":\"M1\"}', '2023-01-04 07:50:23', '2023-01-04 07:50:23'),
(3, 4, 4, 'Create Task', '{\"title\":\"Development\"}', '2023-01-04 07:55:11', '2023-01-04 07:55:11'),
(4, 4, 4, 'Create Task', '{\"title\":\"Scrum Call\"}', '2023-01-04 07:58:34', '2023-01-04 07:58:34'),
(5, 4, 5, 'Create Task', '{\"title\":\"Migration\"}', '2023-02-16 14:44:31', '2023-02-16 14:44:31'),
(6, 4, 6, 'Create Task', '{\"title\":\"Scrum Meeting\"}', '2023-02-17 07:18:12', '2023-02-17 07:18:12'),
(7, 4, 6, 'Create Task', '{\"title\":\"Development\"}', '2023-02-17 07:18:58', '2023-02-17 07:18:58'),
(8, 4, 6, 'Create Task', '{\"title\":\"KT\"}', '2023-02-17 08:09:42', '2023-02-17 08:09:42'),
(9, 4, 6, 'Create Task', '{\"title\":\"Testing\"}', '2023-02-17 08:11:08', '2023-02-17 08:11:08');

-- --------------------------------------------------------

--
-- Table structure for table `admin_payment_settings`
--

DROP TABLE IF EXISTS `admin_payment_settings`;
CREATE TABLE IF NOT EXISTS `admin_payment_settings` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_payment_settings_name_created_by_unique` (`name`,`created_by`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_payment_settings`
--

INSERT INTO `admin_payment_settings` (`id`, `name`, `value`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'currency_symbol', '₹', 1, NULL, NULL),
(2, 'currency', 'INR', 1, NULL, NULL),
(3, 'is_stripe_enabled', 'off', 1, NULL, NULL),
(4, 'is_paypal_enabled', 'on', 1, NULL, NULL),
(5, 'paypal_mode', 'sandbox', 1, NULL, NULL),
(6, 'paypal_client_id', 'AVXKGiti3Za1oMSvHKxunaMckQ6zG966kFeSMBQIoRJMMu0o12tynMBugGVg-19wmP4x2Zu4ldifC9CE', 1, NULL, NULL),
(7, 'paypal_secret_key', 'EC38bG092PcxQIT_DfpRhe-2qF9C7g89fk-ybXQ9ldR5iKNLLOBw11zXr1SFEkD2iQV2bVSDMg0g6PNu', 1, NULL, NULL),
(8, 'is_paystack_enabled', 'off', 1, NULL, NULL),
(9, 'is_flutterwave_enabled', 'off', 1, NULL, NULL),
(10, 'is_razorpay_enabled', 'off', 1, NULL, NULL),
(11, 'is_mercado_enabled', 'off', 1, NULL, NULL),
(12, 'is_paytm_enabled', 'off', 1, NULL, NULL),
(13, 'is_mollie_enabled', 'off', 1, NULL, NULL),
(14, 'is_skrill_enabled', 'off', 1, NULL, NULL),
(15, 'is_coingate_enabled', 'off', 1, NULL, NULL),
(16, 'is_paymentwall_enabled', 'off', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `bugs`
--

DROP TABLE IF EXISTS `bugs`;
CREATE TABLE IF NOT EXISTS `bugs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `bug_id` int NOT NULL DEFAULT '0',
  `project_id` int NOT NULL DEFAULT '0',
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `priority` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `assign_to` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bug_comments`
--

DROP TABLE IF EXISTS `bug_comments`;
CREATE TABLE IF NOT EXISTS `bug_comments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bug_id` int NOT NULL DEFAULT '0',
  `user_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bug_files`
--

DROP TABLE IF EXISTS `bug_files`;
CREATE TABLE IF NOT EXISTS `bug_files` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `file` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `extension` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bug_id` int NOT NULL,
  `user_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bug_statuses`
--

DROP TABLE IF EXISTS `bug_statuses`;
CREATE TABLE IF NOT EXISTS `bug_statuses` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int NOT NULL DEFAULT '0',
  `order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bug_statuses`
--

INSERT INTO `bug_statuses` (`id`, `title`, `created_by`, `order`, `created_at`, `updated_at`) VALUES
(1, 'Confirmed', 2, 0, '2023-01-03 01:53:36', '2023-01-03 01:53:36'),
(2, 'Resolved', 2, 0, '2023-01-03 01:53:36', '2023-01-03 01:53:36'),
(3, 'Unconfirmed', 2, 0, '2023-01-03 01:53:36', '2023-01-03 01:53:36'),
(4, 'In Progress', 2, 0, '2023-01-03 01:53:36', '2023-01-03 01:53:36'),
(5, 'Verified', 2, 0, '2023-01-03 01:53:36', '2023-01-03 01:53:36'),
(6, 'Confirmed', 4, 0, '2023-01-03 02:49:19', '2023-01-03 02:49:19'),
(7, 'Resolved', 4, 0, '2023-01-03 02:49:19', '2023-01-03 02:49:19'),
(8, 'Unconfirmed', 4, 0, '2023-01-03 02:49:19', '2023-01-03 02:49:19'),
(9, 'In Progress', 4, 0, '2023-01-03 02:49:19', '2023-01-03 02:49:19'),
(10, 'Verified', 4, 0, '2023-01-03 02:49:19', '2023-01-03 02:49:19'),
(11, 'Confirmed', 8, 0, '2023-01-03 05:51:32', '2023-01-03 05:51:32'),
(12, 'Resolved', 8, 0, '2023-01-03 05:51:32', '2023-01-03 05:51:32'),
(13, 'Unconfirmed', 8, 0, '2023-01-03 05:51:32', '2023-01-03 05:51:32'),
(14, 'In Progress', 8, 0, '2023-01-03 05:51:32', '2023-01-03 05:51:32'),
(15, 'Verified', 8, 0, '2023-01-03 05:51:32', '2023-01-03 05:51:32'),
(16, 'Confirmed', 9, 0, '2023-01-03 06:14:42', '2023-01-03 06:14:42'),
(17, 'Resolved', 9, 0, '2023-01-03 06:14:42', '2023-01-03 06:14:42'),
(18, 'Unconfirmed', 9, 0, '2023-01-03 06:14:42', '2023-01-03 06:14:42'),
(19, 'In Progress', 9, 0, '2023-01-03 06:14:42', '2023-01-03 06:14:42'),
(20, 'Verified', 9, 0, '2023-01-03 06:14:42', '2023-01-03 06:14:42');

-- --------------------------------------------------------

--
-- Table structure for table `check_lists`
--

DROP TABLE IF EXISTS `check_lists`;
CREATE TABLE IF NOT EXISTS `check_lists` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `task_id` int NOT NULL DEFAULT '0',
  `created_by` int NOT NULL DEFAULT '0',
  `status` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ch_favorites`
--

DROP TABLE IF EXISTS `ch_favorites`;
CREATE TABLE IF NOT EXISTS `ch_favorites` (
  `id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `favorite_id` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ch_messages`
--

DROP TABLE IF EXISTS `ch_messages`;
CREATE TABLE IF NOT EXISTS `ch_messages` (
  `id` bigint NOT NULL,
  `type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_id` bigint NOT NULL,
  `to_id` bigint NOT NULL,
  `body` varchar(5000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachment` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seen` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_permissions`
--

DROP TABLE IF EXISTS `client_permissions`;
CREATE TABLE IF NOT EXISTS `client_permissions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `client_id` int NOT NULL,
  `project_id` int NOT NULL,
  `permissions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `client_permissions`
--

INSERT INTO `client_permissions` (`id`, `client_id`, `project_id`, `permissions`, `created_at`, `updated_at`) VALUES
(1, 5, 1, ',show activity,show milestone,create milestone,edit milestone,delete milestone,show task,create task,edit task,delete task,move task,create checklist,edit checklist,delete checklist,show checklist,show uploading,manage bug report,create bug report,edit bug report,delete bug report,move bug report,manage timesheet,create timesheet,edit timesheet,delete timesheet', '2023-01-03 05:36:07', '2023-01-03 05:36:07'),
(2, 5, 2, ',show activity,show milestone,create milestone,edit milestone,delete milestone,show task,create task,edit task,delete task,move task,create checklist,edit checklist,delete checklist,show checklist,show uploading,manage bug report,create bug report,edit bug report,delete bug report,move bug report,manage timesheet,create timesheet,edit timesheet,delete timesheet', '2023-01-04 05:32:19', '2023-01-04 05:32:19'),
(3, 5, 3, ',show activity,show milestone,create milestone,edit milestone,delete milestone,show task,create task,edit task,delete task,move task,create checklist,edit checklist,delete checklist,show checklist,show uploading,manage bug report,create bug report,edit bug report,delete bug report,move bug report,manage timesheet,create timesheet,edit timesheet,delete timesheet', '2023-01-04 07:25:08', '2023-01-04 07:25:08'),
(4, 5, 4, ',show activity,show milestone,create milestone,edit milestone,delete milestone,show task,create task,edit task,delete task,move task,create checklist,edit checklist,delete checklist,show checklist,show uploading,manage bug report,create bug report,edit bug report,delete bug report,move bug report,manage timesheet,create timesheet,edit timesheet,delete timesheet', '2023-01-04 07:53:12', '2023-01-04 07:53:12'),
(5, 5, 5, ',show activity,show milestone,create milestone,edit milestone,delete milestone,show task,create task,edit task,delete task,move task,create checklist,edit checklist,delete checklist,show checklist,show uploading,manage bug report,create bug report,edit bug report,delete bug report,move bug report,manage timesheet,create timesheet,edit timesheet,delete timesheet', '2023-02-16 14:36:30', '2023-02-16 14:36:30'),
(6, 5, 6, ',show activity,show milestone,create milestone,edit milestone,delete milestone,show task,create task,edit task,delete task,move task,create checklist,edit checklist,delete checklist,show checklist,show uploading,manage bug report,create bug report,edit bug report,delete bug report,move bug report,manage timesheet,create timesheet,edit timesheet,delete timesheet', '2023-02-17 06:21:27', '2023-02-17 06:21:27'),
(7, 5, 8, ',show activity,show milestone,create milestone,edit milestone,delete milestone,show task,create task,edit task,delete task,move task,create checklist,edit checklist,delete checklist,show checklist,show uploading,manage bug report,create bug report,edit bug report,delete bug report,move bug report,manage timesheet,create timesheet,edit timesheet,delete timesheet', '2023-02-17 06:42:55', '2023-02-17 06:42:55');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `task_id` int NOT NULL DEFAULT '0',
  `user_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contracts`
--

DROP TABLE IF EXISTS `contracts`;
CREATE TABLE IF NOT EXISTS `contracts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `client_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `project_id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` int DEFAULT NULL,
  `type` int NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `description` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `contract_description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `client_signature` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `company_signature` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contract_attechments`
--

DROP TABLE IF EXISTS `contract_attechments`;
CREATE TABLE IF NOT EXISTS `contract_attechments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `contract_id` int NOT NULL,
  `user_id` int NOT NULL,
  `files` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contract_comment`
--

DROP TABLE IF EXISTS `contract_comment`;
CREATE TABLE IF NOT EXISTS `contract_comment` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `contract_id` bigint UNSIGNED NOT NULL,
  `user_id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contract_note`
--

DROP TABLE IF EXISTS `contract_note`;
CREATE TABLE IF NOT EXISTS `contract_note` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `contract_id` bigint UNSIGNED NOT NULL,
  `user_id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contract_types`
--

DROP TABLE IF EXISTS `contract_types`;
CREATE TABLE IF NOT EXISTS `contract_types` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

DROP TABLE IF EXISTS `coupons`;
CREATE TABLE IF NOT EXISTS `coupons` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount` double(8,2) NOT NULL DEFAULT '0.00',
  `limit` int NOT NULL DEFAULT '0',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `name`, `code`, `discount`, `limit`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Ten Percent', 'IFREAV9WXS', 10.00, 50, NULL, 1, '2023-01-04 01:58:03', '2023-01-04 01:58:03');

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

DROP TABLE IF EXISTS `email_templates`;
CREATE TABLE IF NOT EXISTS `email_templates` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `from` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `email_templates`
--

INSERT INTO `email_templates` (`id`, `name`, `from`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'New User', 'AAPNA TeamWork', 1, '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(2, 'Project Assigned', 'AAPNA TeamWork', 1, '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(3, 'Task Created', 'AAPNA TeamWork', 1, '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(4, 'Task Moved', 'AAPNA TeamWork', 1, '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(5, 'Estimation Assigned', 'AAPNA TeamWork', 1, '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(6, 'New Contract', 'AAPNA TeamWork', 1, '2023-01-03 01:53:10', '2023-01-03 01:53:10');

-- --------------------------------------------------------

--
-- Table structure for table `email_template_langs`
--

DROP TABLE IF EXISTS `email_template_langs`;
CREATE TABLE IF NOT EXISTS `email_template_langs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` int NOT NULL,
  `lang` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `from` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `email_template_langs`
--

INSERT INTO `email_template_langs` (`id`, `parent_id`, `lang`, `subject`, `from`, `content`, `created_at`, `updated_at`) VALUES
(1, 1, 'ar', 'Login Detail', 'AAPNA TeamWork', '<p>مرحبا،&nbsp;<br>مرحبا بك في {app_name}.</p><p><b>البريد الإلكتروني </b>: {email}<br><b>كلمه السر</b> : {password}</p><p>{app_url}</p><p>شكر،<br>{app_name}</p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(2, 1, 'da', 'Login Detail', 'AAPNA TeamWork', '<p>Hej,&nbsp;<br>Velkommen til {app_name}.</p><p><b>E-mail </b>: {email}<br><b>Adgangskode</b> : {password}</p><p>{app_url}</p><p>Tak,<br>{app_name}</p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(3, 1, 'de', 'Login Detail', 'AAPNA TeamWork', '<p>Hallo,&nbsp;<br>Willkommen zu {app_name}.</p><p><b>Email </b>: {email}<br><b>Passwort</b> : {password}</p><p>{app_url}</p><p>Vielen Dank,<br>{app_name}</p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(4, 1, 'en', 'Login Detail', 'AAPNA TeamWork', '<p>Hello, <br>Welcome to {app_name}.</p><p><b>Email </b>: {email}<br><b>Password</b> : {password}</p><p>{app_url}</p><p>Thanks,<br>{app_name}</p>', '2023-01-03 01:53:10', '2023-01-04 02:12:23'),
(5, 1, 'es', 'Login Detail', 'AAPNA TeamWork', '<p>Hola,&nbsp;<br>Bienvenido a {app_name}.</p><p><b>Correo electrónico </b>: {email}<br><b>Contraseña</b> : {password}</p><p>{app_url}</p><p>Gracias,<br>{app_name}</p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(6, 1, 'fr', 'Login Detail', 'AAPNA TeamWork', '<p>Bonjour,&nbsp;<br>Bienvenue à {app_name}.</p><p><b>Email </b>: {email}<br><b>Mot de passe</b> : {password}</p><p>{app_url}</p><p>Merci,<br>{app_name}</p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(7, 1, 'it', 'Login Detail', 'AAPNA TeamWork', '<p>Ciao,&nbsp;<br>Benvenuto a {app_name}.</p><p><b>E-mail </b>: {email}<br><b>Parola d\'ordine</b> : {password}</p><p>{app_url}</p><p>Grazie,<br>{app_name}</p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(8, 1, 'ja', 'Login Detail', 'AAPNA TeamWork', '<p>こんにちは、&nbsp;<br>へようこそ {app_name}.</p><p><b>Eメール </b>: {email}<br><b>パスワード</b> : {password}</p><p>{app_url}</p><p>おかげで、<br>{app_name}</p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(9, 1, 'nl', 'Login Detail', 'AAPNA TeamWork', '<p>Hallo,&nbsp;<br>Welkom bij {app_name}.</p><p><b>E-mail </b>: {email}<br><b>Wachtwoord</b> : {password}</p><p>{app_url}</p><p>Bedankt,<br>{app_name}</p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(10, 1, 'pl', 'Login Detail', 'AAPNA TeamWork', '<p>Witaj,&nbsp;<br>Witamy w {app_name}.</p><p><b>E-mail </b>: {email}<br><b>Hasło</b> : {password}</p><p>{app_url}</p><p>Dzięki,<br>{app_name}</p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(11, 1, 'ru', 'Login Detail', 'AAPNA TeamWork', '<p>Привет,&nbsp;<br>Добро пожаловать в {app_name}.</p><p><b>Электронное письмо </b>: {email}<br><b>пароль</b> : {password}</p><p>{app_url}</p><p>Спасибо,<br>{app_name}</p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(12, 2, 'ar', 'New Project Assign', 'AAPNA TeamWork', '<p>مرحبا،<br>تم تعيين مشروع جديد لك.</p><p><b>اسم المشروع</b> : {project_name}<br><b>تسمية المشروع</b> :&nbsp; {project_label}<br><b>حالة المشروع </b>: {project_status}</p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(13, 2, 'da', 'New Project Assign', 'AAPNA TeamWork', '<p>Hej,<br>Der er tildelt nyt projekt til dig.</p><p><b>Projekt navn</b> : {project_name}<br><b>Projektetiket</b> :&nbsp; {project_label}<br><b>Projektstatus </b>: {project_status}</p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(14, 2, 'de', 'New Project Assign', 'AAPNA TeamWork', '<p>Hallo,<br>Ihnen wurde ein neues Projekt zugewiesen.</p><p><b>Projektname</b> : {project_name}<br><b>Projektbezeichnung</b> :&nbsp; {project_label}<br><b>Projekt-Status </b>: {project_status}</p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(15, 2, 'en', 'New Project Assign', 'AAPNA TeamWork', '<p>Hello,<br>New Project has been Assign to you.</p><p><b>Project Name</b> : {project_name}<br><b>Project Label</b> :&nbsp; {project_label}<br><b>Project Status </b>: {project_status}</p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(16, 2, 'es', 'New Project Assign', 'AAPNA TeamWork', '<p>Hola,<br>Se le ha asignado un nuevo proyecto.</p><p><b>Nombre del proyecto</b> : {project_name}<br><b>Etiqueta del proyecto</b> :&nbsp; {project_label}<br><b>Estado del proyecto </b>: {project_status}</p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(17, 2, 'fr', 'New Project Assign', 'AAPNA TeamWork', '<p>Bonjour,<br>Un nouveau projet vous a été attribué.</p><p><b>nom du projet</b> : {project_name}<br><b>Libellé du projet</b> :&nbsp; {project_label}<br><b>L\'état du projet </b>: {project_status}</p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(18, 2, 'it', 'New Project Assign', 'AAPNA TeamWork', '<p>Ciao,<br>Nuovo progetto è stato assegnato a te.</p><p><b>Nome del progetto</b> : {project_name}<br><b>Etichetta del progetto</b> :&nbsp; {project_label}<br><b>Stato del progetto </b>: {project_status}</p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(19, 2, 'ja', 'New Project Assign', 'AAPNA TeamWork', '<p>こんにちは、<br>新しいプロジェクトが割り当てられました。</p><p><b>プロジェクト名</b> : {project_name}<br><b>プロジェクトラベル</b> :&nbsp; {project_label}<br><b>プロジェクトの状況 </b>: {project_status}</p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(20, 2, 'nl', 'New Project Assign', 'AAPNA TeamWork', '<p>Hallo,<br>Nieuw project is aan u toegewezen.</p><p><b>Naam van het project</b> : {project_name}<br><b>Projectlabel</b> :&nbsp; {project_label}<br><b>Project status </b>: {project_status}</p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(21, 2, 'pl', 'New Project Assign', 'AAPNA TeamWork', '<p>Witaj,<br>Nowy projekt został Ci przypisany.</p><p><b>Nazwa Projektu</b> : {project_name}<br><b>Etykieta projektu</b> :&nbsp; {project_label}<br><b>Stan projektu </b>: {project_status}</p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(22, 2, 'ru', 'New Project Assign', 'AAPNA TeamWork', '<p>Привет,<br>Новый проект был назначен вам.</p><p><b>название проекта</b> : {project_name}<br><b>Метка проекта</b> :&nbsp; {project_label}<br><b>Статус проекта </b>: {project_status}</p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(23, 3, 'ar', 'New Task Assign', 'AAPNA TeamWork', '<p>مرحبا،<br>تم تعيين مهمة جديدة لك.</p><p><b>اسم المشروع</b> : {project_name}<br><b>تسمية المشروع</b> :&nbsp; {project_label}<br><b>حالة المشروع </b>: {project_status}</p><p><b>اسم المهمة </b>: {task_name}<br><b>أولوية المهمة </b>: {task_priority}<br><b>حالة المهمة </b>: {task_status}</p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(24, 3, 'da', 'New Task Assign', 'AAPNA TeamWork', '<p>Hej,<br>Ny opgave er blevet tildelt til dig.</p><p><b>Projekt navn</b> : {project_name}<br><b>Projektetiket</b> :&nbsp; {project_label}<br><b>Projektstatus </b>: {project_status}</p><p><b>Opgavens navn </b>: {task_name}<br><b>Opgaveprioritet </b>: {task_priority}<br><b>Opgavestatus </b>: {task_status}</p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(25, 3, 'de', 'New Task Assign', 'AAPNA TeamWork', '<p>Hallo,<br>Ihnen wurde eine neue Aufgabe zugewiesen.</p><p><b>Projektname</b> : {project_name}<br><b>Projektbezeichnung</b> :&nbsp; {project_label}<br><b>Projekt-Status </b>: {project_status}</p><p><b>Aufgabennname </b>: {task_name}<br><b>Aufgabenpriorität </b>: {task_priority}<br><b>Aufgabenstatus </b>: {task_status}</p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(26, 3, 'en', 'New Task Assign', 'AAPNA TeamWork', '<p>Hello,<br>New Task has been Assign to you.</p><p><b>Project Name</b> : {project_name}<br><b>Project Label</b> :&nbsp; {project_label}<br><b>Project Status </b>: {project_status}</p><p><b>Task Name </b>: {task_name}<br><b>Task Priority </b>: {task_priority}<br><b>Task Status </b>: {task_status}</p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(27, 3, 'es', 'New Task Assign', 'AAPNA TeamWork', '<p>Hola,<br>Se le ha asignado una nueva tarea.</p><p><b>Nombre del proyecto</b> : {project_name}<br><b>Etiqueta del proyecto</b> :&nbsp; {project_label}<br><b>Estado del proyecto </b>: {project_status}</p><p><b>Nombre de la tarea </b>: {task_name}<br><b>Prioridad de tarea </b>: {task_priority}<br><b>Estado de la tarea </b>: {task_status}</p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(28, 3, 'fr', 'New Task Assign', 'AAPNA TeamWork', '<p>Bonjour,<br>Une nouvelle tâche vous a été assignée.</p><p><b>nom du projet</b> : {project_name}<br><b>Libellé du projet</b> :&nbsp; {project_label}<br><b>L\'état du projet </b>: {project_status}</p><p><b>Nom de la tâche </b>: {task_name}<br><b>Priorité des tâches </b>: {task_priority}<br><b>Statut de la tâche </b>: {task_status}</p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(29, 3, 'it', 'New Task Assign', 'AAPNA TeamWork', '<p>Ciao,<br>La nuova attività è stata assegnata a te.</p><p><b>Nome del progetto</b> : {project_name}<br><b>Etichetta del progetto</b> :&nbsp; {project_label}<br><b>Stato del progetto </b>: {project_status}</p><p><b>Nome dell\'attività </b>: {task_name}<br><b>Priorità dell\'attività </b>: {task_priority}<br><b>Stato dell\'attività </b>: {task_status}</p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(30, 3, 'ja', 'New Task Assign', 'AAPNA TeamWork', '<p>こんにちは、<br>新しいタスクが割り当てられました。</p><p><b>プロジェクト名</b> : {project_name}<br><b>プロジェクトラベル</b> :&nbsp; {project_label}<br><b>プロジェクトの状況 </b>: {project_status}</p><p><b>タスク名 </b>: {task_name}<br><b>タスクの優先度 </b>: {task_priority}<br><b>タスクのステータス </b>: {task_status}</p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(31, 3, 'nl', 'New Task Assign', 'AAPNA TeamWork', '<p>Hallo,<br>Nieuwe taak is aan u toegewezen.</p><p><b>Naam van het project</b> : {project_name}<br><b>Projectlabel</b> :&nbsp; {project_label}<br><b>Project status </b>: {project_status}</p><p><b>Opdrachtnaam </b>: {task_name}<br><b>Taakprioriteit </b>: {task_priority}<br><b>Taakstatus </b>: {task_status}</p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(32, 3, 'pl', 'New Task Assign', 'AAPNA TeamWork', '<p>Witaj,<br>Nowe zadanie zostało Ci przypisane.</p><p><b>Nazwa Projektu</b> : {project_name}<br><b>Etykieta projektu</b> :&nbsp; {project_label}<br><b>Stan projektu </b>: {project_status}</p><p><b>Nazwa zadania </b>: {task_name}<br><b>Priorytet zadania </b>: {task_priority}<br><b>Status zadania </b>: {task_status}</p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(33, 3, 'ru', 'New Task Assign', 'AAPNA TeamWork', '<p>Привет,<br>Новая задача была назначена вам.</p><p><b>название проекта</b> : {project_name}<br><b>Метка проекта</b> :&nbsp; {project_label}<br><b>Статус проекта </b>: {project_status}</p><p><b>Название задачи </b>: {task_name}<br><b>Приоритет задачи </b>: {task_priority}<br><b>Состояние задачи </b>: {task_status}</p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(34, 4, 'ar', 'Task Move in Project', 'AAPNA TeamWork', '<p>مرحبا،<br>نقل المهمة {task_new_stage}.</p><p><span style=\"font-weight: bolder;\">اسم المهمة&nbsp;</span>: {task_name}<br><span style=\"font-weight: bolder;\">أولوية المهمة&nbsp;</span>: {task_priority}<br><span style=\"font-weight: bolder;\">حالة المهمة&nbsp;</span>: {task_status}<br></p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(35, 4, 'da', 'Task Move in Project', 'AAPNA TeamWork', '<p>Hej,<br>Opgave Flyt ind {task_new_stage}.</p><p><span style=\"font-weight: bolder;\">Opgavens navn&nbsp;</span>: {task_name}<br><span style=\"font-weight: bolder;\">Opgaveprioritet&nbsp;</span>: {task_priority}<br><span style=\"font-weight: bolder;\">Opgavestatus&nbsp;</span>: {task_status}<br></p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(36, 4, 'de', 'Task Move in Project', 'AAPNA TeamWork', '<p>Hallo,<br>Aufgabe Einzug {task_new_stage}.</p><p><span style=\"font-weight: bolder;\">Aufgabennname&nbsp;</span>: {task_name}<br><span style=\"font-weight: bolder;\">Aufgabenpriorität&nbsp;</span>: {task_priority}<br><span style=\"font-weight: bolder;\">Aufgabenstatus&nbsp;</span>: {task_status}<br></p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(37, 4, 'en', 'Task Move in Project', 'AAPNA TeamWork', '<p>Hello,<br>Task Move in {task_new_stage}.</p><p><span style=\"font-weight: bolder;\">Task Name&nbsp;</span>: {task_name}<br><span style=\"font-weight: bolder;\">Task Priority&nbsp;</span>: {task_priority}<br><span style=\"font-weight: bolder;\">Task Status&nbsp;</span>: {task_status}<br></p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(38, 4, 'es', 'Task Move in Project', 'AAPNA TeamWork', '<p>Hola,<br>Tarea Mover en {task_new_stage}.</p><p><span style=\"font-weight: bolder;\">Nombre de la tarea&nbsp;</span>: {task_name}<br><span style=\"font-weight: bolder;\">Prioridad de tarea&nbsp;</span>: {task_priority}<br><span style=\"font-weight: bolder;\">Estado de la tarea&nbsp;</span>: {task_status}<br></p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(39, 4, 'fr', 'Task Move in Project', 'AAPNA TeamWork', '<p>Bonjour,<br>Déplacer la tâche {task_new_stage}.</p><p><span style=\"font-weight: bolder;\">Nom de la tâche&nbsp;</span>: {task_name}<br><span style=\"font-weight: bolder;\">Priorité des tâches&nbsp;</span>: {task_priority}<br><span style=\"font-weight: bolder;\">Statut de la tâche&nbsp;</span>: {task_status}<br></p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(40, 4, 'it', 'Task Move in Project', 'AAPNA TeamWork', '<p>Ciao,<br>Attività Sposta in {task_new_stage}.</p><p><span style=\"font-weight: bolder;\">Nome dell\'attività&nbsp;</span>: {task_name}<br><span style=\"font-weight: bolder;\">Priorità dell\'attività&nbsp;</span>: {task_priority}<br><span style=\"font-weight: bolder;\">Stato dell\'attività&nbsp;</span>: {task_status}<br></p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(41, 4, 'ja', 'Task Move in Project', 'AAPNA TeamWork', '<p>こんにちは、<br>タスクの入居 {task_new_stage}.</p><p><span style=\"font-weight: bolder;\">タスク名&nbsp;</span>: {task_name}<br><span style=\"font-weight: bolder;\">タスクの優先度&nbsp;</span>: {task_priority}<br><span style=\"font-weight: bolder;\">タスクのステータス&nbsp;</span>: {task_status}<br></p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(42, 4, 'nl', 'Task Move in Project', 'AAPNA TeamWork', '<p>Hallo,<br>Taak Verplaatsen {task_new_stage}.</p><p><span style=\"font-weight: bolder;\">Opdrachtnaam&nbsp;</span>: {task_name}<br><span style=\"font-weight: bolder;\">Taakprioriteit&nbsp;</span>: {task_priority}<br><span style=\"font-weight: bolder;\">Taakstatus&nbsp;</span>: {task_status}<br></p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(43, 4, 'pl', 'Task Move in Project', 'AAPNA TeamWork', '<p>Witaj,<br>Zadanie Przenieś {task_new_stage}.</p><p><span style=\"font-weight: bolder;\">Nazwa zadania&nbsp;</span>: {task_name}<br><span style=\"font-weight: bolder;\">Priorytet zadania&nbsp;</span>: {task_priority}<br><span style=\"font-weight: bolder;\">Status zadania&nbsp;</span>: {task_status}<br></p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(44, 4, 'ru', 'Task Move in Project', 'AAPNA TeamWork', '<p>Привет,<br>Задача Переместить в {task_new_stage}.</p><p><span style=\"font-weight: bolder;\">Название задачи&nbsp;</span>: {task_name}<br><span style=\"font-weight: bolder;\">Приоритет задачи&nbsp;</span>: {task_priority}<br><span style=\"font-weight: bolder;\">Состояние задачи&nbsp;</span>: {task_status}<br></p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(45, 5, 'ar', 'New Estimation Assign', 'AAPNA TeamWork', '<p>مرحبا،<br>تم تعيين تقدير جديد لك.</p><p><b>معرف التقدير</b> : {estimation_name}<br><b>مرحلة التقدير</b> :&nbsp; {estimation_client}<br><span style=\"font-weight: bolder;\">تقدير&nbsp;</span><b>الحالة </b>: {estimation_status}</p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(46, 5, 'da', 'New Estimation Assign', 'AAPNA TeamWork', '<p>Hej,<br>Ny estimering er blevet tildelt til dig.</p><p><b>Estimations-id</b> : {estimation_name}<br><b>Estimeringsfase</b> :&nbsp; {estimation_client}<br><span style=\"font-weight: bolder;\">estimering&nbsp;</span><b>status </b>: {estimation_status}</p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(47, 5, 'de', 'New Estimation Assign', 'AAPNA TeamWork', '<p>Hallo,<br>Neue Schätzung wurde Ihnen zugewiesen.</p><p><b>Schätz-Id</b> : {estimation_name}<br><b>Schätzungsphase</b> :&nbsp; {estimation_client}<br><span style=\"font-weight: bolder;\">Einschätzung&nbsp;</span><b>Status </b>: {estimation_status}</p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(48, 5, 'en', 'New Estimation Assign', 'AAPNA TeamWork', '<p>Hello,<br>New Estimation has been Assign to you.</p><p><b>Estimation Id</b> : {estimation_name}<br><b>Estimation Stage</b> :&nbsp; {estimation_client}<br><span style=\"font-weight: bolder;\">Estimation&nbsp;</span><b>Status </b>: {estimation_status}</p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(49, 5, 'es', 'New Estimation Assign', 'AAPNA TeamWork', '<p>Hola,<br>Se le ha asignado una nueva estimación.</p><p><b>ID de estimación</b> : {estimation_name}<br><b>Etapa de estimación</b> :&nbsp; {estimation_client}<br><span style=\"font-weight: bolder;\">Estimacion&nbsp;</span><b>Estado </b>: {estimation_status}</p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(50, 5, 'fr', 'New Estimation Assign', 'AAPNA TeamWork', '<p>Bonjour,<br>Une nouvelle estimation vous a été attribuée.</p><p><b>Identifiant d\'estimation</b> : {estimation_name}<br><b>Étape d\'estimation</b> :&nbsp; {estimation_client}<br><span style=\"font-weight: bolder;\">Estimation&nbsp;</span><b>Statut </b>: {estimation_status}</p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(51, 5, 'it', 'New Estimation Assign', 'AAPNA TeamWork', '<p>Ciao,<br>La nuova stima è stata assegnata a te.</p><p><b>ID stima</b> : {estimation_name}<br><b>Fase di stima</b> :&nbsp; {estimation_client}<br><span style=\"font-weight: bolder;\">Stima&nbsp;</span><b>Stato </b>: {estimation_status}</p>', '2023-01-03 01:53:10', '2023-01-03 01:53:10'),
(52, 5, 'ja', 'New Estimation Assign', 'AAPNA TeamWork', '<p>こんにちは、<br>新しい見積もりが割り当てられました。</p><p><b>見積もりID</b> : {estimation_name}<br><b>見積もり段階</b> :&nbsp; {estimation_client}<br><span style=\"font-weight: bolder;\">推定&nbsp;</span><b>状態 </b>: {estimation_status}</p>', '2023-01-03 01:53:11', '2023-01-03 01:53:11'),
(53, 5, 'nl', 'New Estimation Assign', 'AAPNA TeamWork', '<p>Hallo,<br>Nieuwe schatting is aan u toegewezen.</p><p><b>Schattings-ID</b> : {estimation_name}<br><b>Schattingsfase</b> :&nbsp; {estimation_client}<br><span style=\"font-weight: bolder;\">Schatting&nbsp;</span><b>Toestand </b>: {estimation_status}</p>', '2023-01-03 01:53:11', '2023-01-03 01:53:11'),
(54, 5, 'pl', 'New Estimation Assign', 'AAPNA TeamWork', '<p>Witaj,<br>Nowe oszacowanie zostało Ci przypisane.</p><p><b>Identyfikator szacunku</b> : {estimation_name}<br><b>Etap szacowania</b> :&nbsp; {estimation_client}<br><span style=\"font-weight: bolder;\">Oszacowanie&nbsp;</span><b>Status </b>: {estimation_status}</p>', '2023-01-03 01:53:11', '2023-01-03 01:53:11'),
(55, 5, 'ru', 'New Estimation Assign', 'AAPNA TeamWork', '<p>Привет,<br>Новая оценка была назначена вам.</p><p><b>Идентификатор оценки</b> : {estimation_name}<br><b>Этап оценки</b> :&nbsp; {estimation_client}<br><span style=\"font-weight: bolder;\">Предварительный расчет&nbsp;</span><b>Положение дел </b>: {estimation_status}</p>', '2023-01-03 01:53:11', '2023-01-03 01:53:11'),
(56, 6, 'ar', 'Contract', 'AAPNA TeamWork', '<p>&nbsp;</p>\n                    <p><b>مرحبا</b> { contract_client }</p>\n                    <p><b>موضوع العقد</b> : { contract_subject }</p>\n                    <p><b>مشروع العقد </b>: { contract_project }</p>\n                    <p><b>تاريخ البدء</b> : { contract_start_date }</p>\n                    <p><b>تاريخ الانتهاء</b> : { contract_end_date }</p>\n                    <p>. أتطلع لسماع منك</p>\n                    <p><b>Regards نوع ،</b></p>\n                    <p>{ company_name }</p>', '2023-01-03 01:53:11', '2023-01-03 01:53:11'),
(57, 6, 'da', 'Contract', 'AAPNA TeamWork', '<p>&nbsp;</p>\n                    <p><b>Hej </b>{ contract_client }</p>\n                    <p><b>Kontraktemne :&nbsp;</b>{ contract_subject }</p>\n                    <p><b>Kontrakt-projekt :&nbsp;</b>{ contract_project }</p>\n                    <p><b>Startdato&nbsp;</b>: { contract_start_date }</p>\n                    <p><b>Slutdato&nbsp;</b>: { contract_end_date }</p>\n                    <p>Jeg glæder mig til at høre fra dig.</p>\n                    <p><b>Kind Hilds,</b></p>\n                    <p>{ company_name }</p><p></p>', '2023-01-03 01:53:11', '2023-01-03 01:53:11'),
(58, 6, 'de', 'Contract', 'AAPNA TeamWork', '<p>&nbsp;</p>\n                    <p><b>Hi</b> {contract_client}</p>\n                    <p>&nbsp;<b style=\"font-family: var(--bs-body-font-family); text-align: var(--bs-body-text-align);\">Vertragsgegenstand :</b><span style=\"font-family: var(--bs-body-font-family); font-weight: var(--bs-body-font-weight); text-align: var(--bs-body-text-align);\"> {contract_subject}</span></p>\n                    <p><b>Vertragsprojekt :&nbsp;</b>{contract_project}</p>\n                    <p><b>Startdatum&nbsp;</b>: {contract_start_date}</p>\n                    <p><b>Enddatum&nbsp;</b>: {contract_end_date}</p>\n                    <p>Freuen Sie sich auf das Hören von Ihnen.</p>\n                    <p><b>Gütige Grüße,</b></p>\n                    <p>{company_name}</p>', '2023-01-03 01:53:11', '2023-01-03 01:53:11'),
(59, 6, 'en', 'Contract', 'AAPNA TeamWork', '<p>&nbsp;</p>\n                    <p><strong>Hi</strong> {contract_client}</p>\n                    <p><b>Contract Subject</b>&nbsp;: {contract_subject}</p>\n                    <p><b>Contract Project</b>&nbsp;: {contract_project}</p>\n                    <p><b>Start Date&nbsp;</b>: {contract_start_date}</p>\n                    <p><b>End Date&nbsp;</b>: {contract_end_date}</p>\n                    <p>Looking forward to hear from you.</p>\n                    <p><strong>Kind Regards, </strong></p>\n                    <p>{company_name}</p>', '2023-01-03 01:53:11', '2023-01-03 01:53:11'),
(60, 6, 'es', 'Contract', 'AAPNA TeamWork', '<p><b>Hi </b>{contract_client} </p><p><span style=\"text-align: var(--bs-body-text-align);\"><b>asunto del contrato</b></span><b>&nbsp;:</b> {contract_subject}</p><p><b>contrato proyecto </b>: {<span style=\"font-family: var(--bs-body-font-family); font-size: var(--bs-body-font-size); font-weight: var(--bs-body-font-weight); text-align: var(--bs-body-text-align);\">contract_project</span><span style=\"font-family: var(--bs-body-font-family); font-weight: var(--bs-body-font-weight); text-align: var(--bs-body-text-align);\">}</span></p><p> </p><p><b>Start Date :</b> {contract_start_date} </p><p><b>Fecha de finalización :</b> {contract_end_date} </p><p>Con ganas de escuchar de usted. </p><p><b>Regards de tipo, </b></p><p>{contract_name}</p>', '2023-01-03 01:53:11', '2023-01-03 01:53:11'),
(61, 6, 'fr', 'Contract', 'AAPNA TeamWork', '<p><b>Bonjour</b> { contract_client }</p>\n                    <p><b>Objet du contrat :</b> { contract_subject } </p><p><span style=\"text-align: var(--bs-body-text-align);\"><b>contrat projet :</b></span>&nbsp;{ contract_project } </p><p><b>Date de début&nbsp;</b>: { contract_start_date } </p><p><b>Date de fin&nbsp;</b>: { contract_end_date } </p><p>Regard sur lavenir.</p>\n                    <p><b>Sincères amitiés,</b></p>\n                    <p>{ nom_entreprise }</p>', '2023-01-03 01:53:11', '2023-01-03 01:53:11'),
(62, 6, 'it', 'Contract', 'AAPNA TeamWork', '<p>&nbsp;</p>\n                    <p>Ciao {contract_client}</p>\n                    <p><b>Oggetto contratto :&nbsp;</b>{contract_subject} </p><p><b>Contract Project :</b> {contract_project} </p><p><b>Data di inizio</b>: {contract_start_date} </p><p><b>Data di fine</b>: {contract_end_date} </p><p>Non vedo lora di sentirti<br></p>\n                    <p><b>Kind Regards,</b></p>\n                    <p>{company_name}</p>', '2023-01-03 01:53:11', '2023-01-03 01:53:11'),
(63, 6, 'ja', 'Contract', 'AAPNA TeamWork', '<p><span style=\"font-family: var(--bs-body-font-family); font-weight: var(--bs-body-font-weight); text-align: var(--bs-body-text-align);\">こんにちは {contract_client}</span><br></p>\n                    <p><b>契約件名&nbsp;</b>: {contract subject}</p>\n                    <p><b>契約プロジェクト :</b> {contract_project}</p>\n                    <p><b>開始日</b>: {contract_start_date}</p>\n                    <p>&nbsp;<b style=\"font-family: var(--bs-body-font-family); text-align: var(--bs-body-text-align);\">終了日</b><span style=\"font-family: var(--bs-body-font-family); font-weight: var(--bs-body-font-weight); text-align: var(--bs-body-text-align);\">: {contract_end_date}</span></p><p><span style=\"text-align: var(--bs-body-text-align);\">あなたから聞いて楽しみにして</span></p><p><span style=\"text-align: var(--bs-body-text-align);\"><b>敬具、</b><br></span></p>\n                    <p>{ company_name}</p>', '2023-01-03 01:53:11', '2023-01-03 01:53:11'),
(64, 6, 'nl', 'Contract', 'AAPNA TeamWork', '<p>&nbsp;</p>\n                    <p><b>Hallo</b> { contract_client }</p>\n                    <p><b>Contractonderwerp</b> : { contract_subject } </p><p><b>Contractproject</b> : { contract_project } </p><p><b>Begindatum</b> : { contract_start_date } </p><p><b>Einddatum&nbsp;</b>: { contract_end_date } </p><p>Naar voren komen om van u te horen.</p><p><b>Met vriendelijke groeten</b>,<br></p>\n                    <p>{ bedrijfsnaam }</p>', '2023-01-03 01:53:11', '2023-01-03 01:53:11'),
(65, 6, 'pl', 'Contract', 'AAPNA TeamWork', '<p>&nbsp;</p>\n                    <p><b>Witaj</b> {contract_client }</p>\n                    <p><b>Temat umowy :&nbsp;</b>{contract_subject } </p><p><b>Projekt kontraktu</b>&nbsp;: {contract_project } </p><p><b>Data rozpoczęcia&nbsp;</b>: {contract_start_date } </p><p><b>Data zakończenia&nbsp;</b>: {contract_end_date } </p><p>Z niecierżną datą i z niecierżką na Ciebie.</p>\n                    <p><b>W Odniesieniu Do Rodzaju,</b></p>\n                    <p>{company_name }</p>', '2023-01-03 01:53:11', '2023-01-03 01:53:11'),
(66, 6, 'ru', 'Contract', 'AAPNA TeamWork', '<p></p>\n                    <p><b>Здравствуйте</b> { contract_client }</p>\n                    <p><b>Субъект договора :</b> { contract_subject } </p><p><b>Проект договора</b>: { contract_project } </p><p><b>Начальная дата </b>: { contract_start_date } </p><p><b>Конечная дата </b>: { contract_end_date } </p><p>нетерпением ожидаю услышать от вас.</p>\n                    <p><b>Привет.</b></p>\n                    <p>{ company_name }</p>', '2023-01-03 01:53:11', '2023-01-03 01:53:11'),
(67, 6, 'pt', 'Contract', 'AAPNA TeamWork', '<p>&nbsp;</p>\n                    <p><span style=\"text-align: var(--bs-body-text-align);\"><b>Olá</b></span>&nbsp;{contract_client}</p>\n                    <p><span style=\"text-align: var(--bs-body-text-align);\"><b>Assunto do Contrato</b></span>&nbsp;: {contract_subject}</p>\n                    <p><span style=\"text-align: var(--bs-body-text-align);\"><b>Projeto de contrato&nbsp;</b></span>: {contract_project}</p>\n                    <p><span style=\"text-align: var(--bs-body-text-align);\"><b>Data de início</b></span><b>&nbsp;</b>: {contract_start_date}</p>\n                    <p><span style=\"text-align: var(--bs-body-text-align);\"><b>Data final</b></span><b>&nbsp;</b>: {contract_end_date}</p>\n                    <p>Ansioso para ouvir de você.</p>\n                    <p><b>Atenciosamente,</b><br></p>\n                    <p>{company_name}</p>', '2023-01-03 01:53:11', '2023-01-03 01:53:11');

-- --------------------------------------------------------

--
-- Table structure for table `estimations`
--

DROP TABLE IF EXISTS `estimations`;
CREATE TABLE IF NOT EXISTS `estimations` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `estimation_id` bigint UNSIGNED NOT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `status` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `issue_date` date NOT NULL,
  `discount` double(8,2) NOT NULL,
  `tax_id` bigint UNSIGNED NOT NULL,
  `terms` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `estimation_products`
--

DROP TABLE IF EXISTS `estimation_products`;
CREATE TABLE IF NOT EXISTS `estimation_products` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `estimation_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` double NOT NULL,
  `quantity` int NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

DROP TABLE IF EXISTS `expenses`;
CREATE TABLE IF NOT EXISTS `expenses` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` int NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `amount` double DEFAULT NULL,
  `date` date DEFAULT NULL,
  `project` bigint UNSIGNED NOT NULL DEFAULT '0',
  `user_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `attachment` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenses_categories`
--

DROP TABLE IF EXISTS `expenses_categories`;
CREATE TABLE IF NOT EXISTS `expenses_categories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expenses_categories`
--

INSERT INTO `expenses_categories` (`id`, `name`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Snack', 2, '2023-01-03 01:53:36', '2023-01-03 01:53:36'),
(2, 'Server Charge', 2, '2023-01-03 01:53:36', '2023-01-03 01:53:36'),
(3, 'Bills', 2, '2023-01-03 01:53:36', '2023-01-03 01:53:36'),
(4, 'Office', 2, '2023-01-03 01:53:36', '2023-01-03 01:53:36'),
(5, 'Assests', 2, '2023-01-03 01:53:36', '2023-01-03 01:53:36'),
(6, 'Snack', 4, '2023-01-03 02:49:19', '2023-01-03 02:49:19'),
(7, 'Server Charge', 4, '2023-01-03 02:49:19', '2023-01-03 02:49:19'),
(8, 'Bills', 4, '2023-01-03 02:49:19', '2023-01-03 02:49:19'),
(9, 'Office', 4, '2023-01-03 02:49:19', '2023-01-03 02:49:19'),
(10, 'Assests', 4, '2023-01-03 02:49:19', '2023-01-03 02:49:19'),
(11, 'Snack', 8, '2023-01-03 05:51:32', '2023-01-03 05:51:32'),
(12, 'Server Charge', 8, '2023-01-03 05:51:32', '2023-01-03 05:51:32'),
(13, 'Bills', 8, '2023-01-03 05:51:32', '2023-01-03 05:51:32'),
(14, 'Office', 8, '2023-01-03 05:51:32', '2023-01-03 05:51:32'),
(15, 'Assests', 8, '2023-01-03 05:51:32', '2023-01-03 05:51:32'),
(16, 'Snack', 9, '2023-01-03 06:14:42', '2023-01-03 06:14:42'),
(17, 'Server Charge', 9, '2023-01-03 06:14:42', '2023-01-03 06:14:42'),
(18, 'Bills', 9, '2023-01-03 06:14:42', '2023-01-03 06:14:42'),
(19, 'Office', 9, '2023-01-03 06:14:42', '2023-01-03 06:14:42'),
(20, 'Assests', 9, '2023-01-03 06:14:42', '2023-01-03 06:14:42');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
CREATE TABLE IF NOT EXISTS `invoices` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `status` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `issue_date` date NOT NULL,
  `due_date` date NOT NULL,
  `discount` double(8,2) NOT NULL,
  `tax_id` bigint UNSIGNED DEFAULT NULL,
  `terms` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_payments`
--

DROP TABLE IF EXISTS `invoice_payments`;
CREATE TABLE IF NOT EXISTS `invoice_payments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `transaction_id` bigint UNSIGNED NOT NULL,
  `client_id` int NOT NULL DEFAULT '0',
  `invoice_id` bigint UNSIGNED NOT NULL,
  `amount` double NOT NULL,
  `date` date NOT NULL,
  `payment_id` bigint UNSIGNED NOT NULL,
  `payment_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_products`
--

DROP TABLE IF EXISTS `invoice_products`;
CREATE TABLE IF NOT EXISTS `invoice_products` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoice_id` int NOT NULL,
  `iteam` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` double NOT NULL DEFAULT '0',
  `type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `labels`
--

DROP TABLE IF EXISTS `labels`;
CREATE TABLE IF NOT EXISTS `labels` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `labels`
--

INSERT INTO `labels` (`id`, `name`, `color`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'On Hold', 'bg-red-thunderbird bg-font-red-thunderbird', 2, '2023-01-03 01:53:36', '2023-01-03 01:53:36'),
(2, 'New', 'bg-yellow-casablanca bg-font-yellow-casablanca', 2, '2023-01-03 01:53:36', '2023-01-03 01:53:36'),
(3, 'Pending', 'bg-purple-intense bg-font-purple-intense', 2, '2023-01-03 01:53:36', '2023-01-03 01:53:36'),
(4, 'Loss', 'bg-purple-medium bg-font-purple-medium', 2, '2023-01-03 01:53:36', '2023-01-03 01:53:36'),
(5, 'Win', 'bg-yellow-soft bg-font-yellow-soft', 2, '2023-01-03 01:53:36', '2023-01-03 01:53:36'),
(6, 'On Hold', 'bg-red-thunderbird bg-font-red-thunderbird', 4, '2023-01-03 02:49:19', '2023-01-03 02:49:19'),
(7, 'New', 'bg-yellow-casablanca bg-font-yellow-casablanca', 4, '2023-01-03 02:49:19', '2023-01-03 02:49:19'),
(8, 'Pending', 'bg-purple-intense bg-font-purple-intense', 4, '2023-01-03 02:49:19', '2023-01-03 02:49:19'),
(9, 'Loss', 'bg-purple-medium bg-font-purple-medium', 4, '2023-01-03 02:49:19', '2023-01-03 02:49:19'),
(10, 'Win', 'bg-yellow-soft bg-font-yellow-soft', 4, '2023-01-03 02:49:19', '2023-01-03 02:49:19'),
(11, 'On Hold', 'bg-red-thunderbird bg-font-red-thunderbird', 8, '2023-01-03 05:51:32', '2023-01-03 05:51:32'),
(12, 'New', 'bg-yellow-casablanca bg-font-yellow-casablanca', 8, '2023-01-03 05:51:32', '2023-01-03 05:51:32'),
(13, 'Pending', 'bg-purple-intense bg-font-purple-intense', 8, '2023-01-03 05:51:32', '2023-01-03 05:51:32'),
(14, 'Loss', 'bg-purple-medium bg-font-purple-medium', 8, '2023-01-03 05:51:32', '2023-01-03 05:51:32'),
(15, 'Win', 'bg-yellow-soft bg-font-yellow-soft', 8, '2023-01-03 05:51:32', '2023-01-03 05:51:32'),
(16, 'On Hold', 'bg-red-thunderbird bg-font-red-thunderbird', 9, '2023-01-03 06:14:42', '2023-01-03 06:14:42'),
(17, 'New', 'bg-yellow-casablanca bg-font-yellow-casablanca', 9, '2023-01-03 06:14:42', '2023-01-03 06:14:42'),
(18, 'Pending', 'bg-purple-intense bg-font-purple-intense', 9, '2023-01-03 06:14:42', '2023-01-03 06:14:42'),
(19, 'Loss', 'bg-purple-medium bg-font-purple-medium', 9, '2023-01-03 06:14:42', '2023-01-03 06:14:42'),
(20, 'Win', 'bg-yellow-soft bg-font-yellow-soft', 9, '2023-01-03 06:14:42', '2023-01-03 06:14:42');

-- --------------------------------------------------------

--
-- Table structure for table `leads`
--

DROP TABLE IF EXISTS `leads`;
CREATE TABLE IF NOT EXISTS `leads` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` double NOT NULL DEFAULT '0',
  `stage` int NOT NULL DEFAULT '0',
  `owner` int NOT NULL DEFAULT '0',
  `client` int NOT NULL DEFAULT '0',
  `source` int NOT NULL DEFAULT '0',
  `created_by` int NOT NULL DEFAULT '0',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_order` smallint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leads`
--

INSERT INTO `leads` (`id`, `name`, `price`, `stage`, `owner`, `client`, `source`, `created_by`, `notes`, `item_order`, `created_at`, `updated_at`) VALUES
(1, 'Teamwork Development', 100000, 5, 7, 5, 5, 4, 'Aapna teamwork', 0, '2023-01-03 02:56:42', '2023-01-03 02:56:42');

-- --------------------------------------------------------

--
-- Table structure for table `leadsources`
--

DROP TABLE IF EXISTS `leadsources`;
CREATE TABLE IF NOT EXISTS `leadsources` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leadsources`
--

INSERT INTO `leadsources` (`id`, `name`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Email', 2, '2023-01-03 01:53:36', '2023-01-03 01:53:36'),
(2, 'Facebook', 2, '2023-01-03 01:53:36', '2023-01-03 01:53:36'),
(3, 'Google', 2, '2023-01-03 01:53:36', '2023-01-03 01:53:36'),
(4, 'Phone', 2, '2023-01-03 01:53:36', '2023-01-03 01:53:36'),
(5, 'Email', 4, '2023-01-03 02:49:19', '2023-01-03 02:49:19'),
(6, 'Facebook', 4, '2023-01-03 02:49:19', '2023-01-03 02:49:19'),
(7, 'Google', 4, '2023-01-03 02:49:19', '2023-01-03 02:49:19'),
(8, 'Phone', 4, '2023-01-03 02:49:19', '2023-01-03 02:49:19'),
(9, 'Email', 8, '2023-01-03 05:51:32', '2023-01-03 05:51:32'),
(10, 'Facebook', 8, '2023-01-03 05:51:32', '2023-01-03 05:51:32'),
(11, 'Google', 8, '2023-01-03 05:51:32', '2023-01-03 05:51:32'),
(12, 'Phone', 8, '2023-01-03 05:51:32', '2023-01-03 05:51:32'),
(13, 'Email', 9, '2023-01-03 06:14:42', '2023-01-03 06:14:42'),
(14, 'Facebook', 9, '2023-01-03 06:14:42', '2023-01-03 06:14:42'),
(15, 'Google', 9, '2023-01-03 06:14:42', '2023-01-03 06:14:42'),
(16, 'Phone', 9, '2023-01-03 06:14:42', '2023-01-03 06:14:42');

-- --------------------------------------------------------

--
-- Table structure for table `leadstages`
--

DROP TABLE IF EXISTS `leadstages`;
CREATE TABLE IF NOT EXISTS `leadstages` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int NOT NULL DEFAULT '0',
  `order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leadstages`
--

INSERT INTO `leadstages` (`id`, `name`, `color`, `created_by`, `order`, `created_at`, `updated_at`) VALUES
(1, 'Initial Contact', '#e7505a', 2, 0, '2023-01-03 01:53:36', '2023-01-03 01:53:36'),
(2, 'Qualification', '#F4D03F', 2, 1, '2023-01-03 01:53:36', '2023-01-03 01:53:36'),
(3, 'Proposal', '#32c5d2', 2, 2, '2023-01-03 01:53:36', '2023-01-03 01:53:36'),
(4, 'Close', '#1BBC9B', 2, 3, '2023-01-03 01:53:36', '2023-01-03 01:53:36'),
(5, 'Initial Contact', '#e7505a', 4, 0, '2023-01-03 02:49:19', '2023-01-03 02:49:19'),
(6, 'Qualification', '#F4D03F', 4, 1, '2023-01-03 02:49:19', '2023-01-03 02:49:19'),
(7, 'Proposal', '#32c5d2', 4, 2, '2023-01-03 02:49:19', '2023-01-03 02:49:19'),
(8, 'Close', '#1BBC9B', 4, 3, '2023-01-03 02:49:19', '2023-01-03 02:49:19'),
(9, 'Initial Contact', '#e7505a', 8, 0, '2023-01-03 05:51:32', '2023-01-03 05:51:32'),
(10, 'Qualification', '#F4D03F', 8, 1, '2023-01-03 05:51:32', '2023-01-03 05:51:32'),
(11, 'Proposal', '#32c5d2', 8, 2, '2023-01-03 05:51:32', '2023-01-03 05:51:32'),
(12, 'Close', '#1BBC9B', 8, 3, '2023-01-03 05:51:32', '2023-01-03 05:51:32'),
(13, 'Initial Contact', '#e7505a', 9, 0, '2023-01-03 06:14:42', '2023-01-03 06:14:42'),
(14, 'Qualification', '#F4D03F', 9, 1, '2023-01-03 06:14:42', '2023-01-03 06:14:42'),
(15, 'Proposal', '#32c5d2', 9, 2, '2023-01-03 06:14:42', '2023-01-03 06:14:42'),
(16, 'Close', '#1BBC9B', 9, 3, '2023-01-03 06:14:42', '2023-01-03 06:14:42');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_08_19_100219_create_orders_table', 1),
(5, '2019_09_22_192348_create_messages_table', 1),
(6, '2019_09_28_102009_create_settings_table', 1),
(7, '2019_10_16_211433_create_favorites_table', 1),
(8, '2019_10_18_223259_add_avatar_to_users', 1),
(9, '2019_10_20_211056_add_messenger_color_to_users', 1),
(10, '2019_10_22_000539_add_dark_mode_to_users', 1),
(11, '2019_10_23_120558_create_leadstages_table', 1),
(12, '2019_10_24_060326_create_projectstages_table', 1),
(13, '2019_10_24_064028_create_leadsources_table', 1),
(14, '2019_10_24_071154_create_labels_table', 1),
(15, '2019_10_24_074519_create_productunits_table', 1),
(16, '2019_10_24_111452_create_expensescategories_table', 1),
(17, '2019_10_25_042508_create_leads_table', 1),
(18, '2019_10_25_214038_add_active_status_to_users', 1),
(19, '2019_11_06_053858_create_projects_table', 1),
(20, '2019_11_06_084310_create_products_table', 1),
(21, '2019_11_06_115449_create_userprojects_table', 1),
(22, '2019_11_07_074239_create_milestones_table', 1),
(23, '2019_11_07_105840_create_activity_log_table', 1),
(24, '2019_11_07_115130_create_project_files_table', 1),
(25, '2019_11_08_051517_create_tasks_table', 1),
(26, '2019_11_12_073012_create_bug_comments_table', 1),
(27, '2019_11_12_073012_create_comments_table', 1),
(28, '2019_11_12_100007_create_bug_files_table', 1),
(29, '2019_11_12_100007_create_task_files_table', 1),
(30, '2019_11_13_051828_create_taxes_table', 1),
(31, '2019_11_13_055026_create_invoices_table', 1),
(32, '2019_11_13_072623_create_expenses_table', 1),
(33, '2019_11_13_091357_create_payments_table', 1),
(34, '2019_11_13_111238_create_invoice_products_table', 1),
(35, '2019_11_13_120015_create_invoice_payments_table', 1),
(36, '2019_11_14_105120_create_check_list_table', 1),
(37, '2019_11_15_104222_create_client_permission_table', 1),
(38, '2019_11_21_090403_create_plans_table', 1),
(39, '2019_11_25_041305_create_notes_table', 1),
(40, '2019_12_18_071134_create_timesheets_table', 1),
(41, '2019_12_18_110230_create_bugs_table', 1),
(42, '2019_12_18_112007_create_bug_statuses_table', 1),
(43, '2020_03_14_041118_create_coupons_table', 1),
(44, '2020_03_14_041604_create_user_coupons_table', 1),
(45, '2020_03_23_161643_create_notifications_table', 1),
(46, '2020_03_25_101505_create_estimations_table', 1),
(47, '2020_03_25_113801_create_estimation_products_table', 1),
(48, '2020_04_18_063447_create_messageses_table', 1),
(49, '2020_04_23_124702_create_email_templates_table', 1),
(50, '2020_04_23_130249_create_email_template_langs_table', 1),
(51, '2020_04_27_093230_create_user_email_templates_table', 1),
(52, '2020_05_21_065337_create_permission_tables', 1),
(53, '2020_07_08_040251_add_payment_type_to_orders_table', 1),
(54, '2020_07_13_040251_add_payment_type_and_client_id_to_invoice_payments_table', 1),
(55, '2020_07_29_091541_update_price_amount', 1),
(56, '2021_07_14_052611_create_admin_payment_settings_table', 1),
(57, '2021_11_17_061858_create_plan_requests_table', 1),
(58, '2021_12_20_054906_create_time_trackers_table', 1),
(59, '2021_12_20_054951_create_track_photos_table', 1),
(60, '2021_12_27_055603_add_requested_plan_to_users_table', 1),
(61, '2021_12_27_062419_create_zoommeetings_table', 1),
(62, '2021_12_27_114842_create_user_defualt_views_table', 1),
(63, '2022_07_15_050915_add_from_to_email_template_langs_table', 1),
(64, '2022_08_01_035240_create_contract_types_table', 1),
(65, '2022_08_01_043658_create_contracts_table', 1),
(66, '2022_08_01_044335_create_contract_attechments_table', 1),
(67, '2022_08_01_045102_create_contract_comment_table', 1),
(68, '2022_08_01_045457_create_contract_note_table', 1),
(69, '2022_08_08_041735_create_project_reports_table', 1),
(70, '2022_08_12_043630_add_project_to_userprojects_table', 1),
(71, '2022_08_12_094547_add_milestones_to_progress_table', 1),
(72, '2022_08_12_122822_add_milestones_to_date_table', 1),
(73, '2022_08_16_034551_add_task_to_hours_table', 1),
(74, '2023_01_06_123249_add_parent_task_id_to_tasks', 2),
(75, '2019_12_14_000001_create_personal_access_tokens_table', 3),
(76, '2023_01_06_062924_add_is_billable_to_timesheets', 3),
(77, '2023_01_17_122608_create_task_groups_table', 3),
(78, '2023_01_17_131411_add_group_id_to_tasks_table', 3),
(79, '2023_01_25_113203_add_estimated_mins_to_tasks_table', 4),
(80, '2023_01_19_154427_drop_time_is_billable_from_timesheets_table', 5),
(81, '2023_01_19_154847_add_billable_start_time_end_time_to_timesheets_table', 5),
(82, '2023_01_25_134233_add_project_id_to_task_groups_table', 5),
(83, '2023_02_15_162945_add_parent_user_id_to_users_table', 6);

-- --------------------------------------------------------

--
-- Table structure for table `milestones`
--

DROP TABLE IF EXISTS `milestones`;
CREATE TABLE IF NOT EXISTS `milestones` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` int NOT NULL DEFAULT '0',
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `progress` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cost` double(15,2) NOT NULL DEFAULT '0.00',
  `start_date` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `due_date` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `milestones`
--

INSERT INTO `milestones` (`id`, `project_id`, `title`, `status`, `progress`, `cost`, `start_date`, `due_date`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 'M1', 'incomplete', NULL, 50.00, NULL, NULL, 'Test', '2023-01-04 07:50:23', '2023-01-04 07:50:23');

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 5),
(3, 'App\\Models\\User', 2),
(3, 'App\\Models\\User', 4),
(3, 'App\\Models\\User', 8),
(3, 'App\\Models\\User', 9),
(4, 'App\\Models\\User', 3),
(5, 'App\\Models\\User', 6),
(5, 'App\\Models\\User', 10),
(10, 'App\\Models\\User', 7),
(11, 'App\\Models\\User', 11);

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

DROP TABLE IF EXISTS `notes`;
CREATE TABLE IF NOT EXISTS `notes` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint NOT NULL,
  `type` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `type`, `data`, `is_read`, `created_at`, `updated_at`) VALUES
(1, 6, 'assign_project', '{\"project_id\":1,\"name\":\"DHI\",\"updated_by\":4}', 0, '2023-01-03 05:36:00', '2023-01-03 05:36:00'),
(2, 5, 'assign_project', '{\"project_id\":1,\"name\":\"DHI\",\"updated_by\":4}', 0, '2023-01-03 05:36:07', '2023-01-03 05:36:07'),
(3, 6, 'assign_project', '{\"project_id\":2,\"name\":\"Time Log\",\"updated_by\":7}', 0, '2023-01-04 05:32:15', '2023-01-04 05:32:15'),
(4, 5, 'assign_project', '{\"project_id\":2,\"name\":\"Time Log\",\"updated_by\":7}', 0, '2023-01-04 05:32:18', '2023-01-04 05:32:18'),
(5, 5, 'assign_project', '{\"project_id\":3,\"name\":\"Training\",\"updated_by\":7}', 0, '2023-01-04 07:25:04', '2023-01-04 07:25:04'),
(6, 4, 'create_task', '{\"project_id\":3,\"name\":\"Training\",\"updated_by\":7}', 0, '2023-01-04 07:26:51', '2023-01-04 07:26:51'),
(7, 5, 'create_task', '{\"project_id\":3,\"name\":\"Training\",\"updated_by\":7}', 0, '2023-01-04 07:27:15', '2023-01-04 07:27:15'),
(8, 4, 'move_task', '{\"project_id\":3,\"project_name\":\"Training\",\"task_id\":2,\"name\":\"Development\",\"updated_by\":7,\"old_status\":\"To Do\",\"new_status\":\"In Progress\"}', 0, '2023-01-04 07:27:28', '2023-01-04 07:27:28'),
(9, 4, 'move_task', '{\"project_id\":3,\"project_name\":\"Training\",\"task_id\":2,\"name\":\"Development\",\"updated_by\":7,\"old_status\":\"In Progress\",\"new_status\":\"To Do\"}', 0, '2023-01-04 07:27:30', '2023-01-04 07:27:30'),
(10, 5, 'move_task', '{\"project_id\":3,\"project_name\":\"Training\",\"task_id\":2,\"name\":\"Development\",\"updated_by\":7,\"old_status\":\"To Do\",\"new_status\":\"In Progress\"}', 0, '2023-01-04 07:27:52', '2023-01-04 07:27:52'),
(11, 5, 'move_task', '{\"project_id\":3,\"project_name\":\"Training\",\"task_id\":2,\"name\":\"Development\",\"updated_by\":7,\"old_status\":\"In Progress\",\"new_status\":\"To Do\"}', 0, '2023-01-04 07:27:54', '2023-01-04 07:27:54'),
(12, 4, 'create_milestone', '{\"project_id\":1,\"name\":\"DHI\",\"updated_by\":6}', 0, '2023-01-04 07:50:23', '2023-01-04 07:50:23'),
(13, 5, 'create_milestone', '{\"project_id\":1,\"name\":\"DHI\",\"updated_by\":6}', 0, '2023-01-04 07:50:23', '2023-01-04 07:50:23'),
(14, 6, 'assign_project', '{\"project_id\":4,\"name\":\"ZohoBook Invoice\",\"updated_by\":7}', 0, '2023-01-04 07:52:47', '2023-01-04 07:52:47'),
(15, 5, 'assign_project', '{\"project_id\":4,\"name\":\"ZohoBook Invoice\",\"updated_by\":7}', 0, '2023-01-04 07:53:11', '2023-01-04 07:53:11'),
(16, 6, 'create_task', '{\"project_id\":4,\"name\":\"ZohoBook Invoice\",\"updated_by\":4}', 0, '2023-01-04 07:55:11', '2023-01-04 07:55:11'),
(17, 5, 'create_task', '{\"project_id\":4,\"name\":\"ZohoBook Invoice\",\"updated_by\":4}', 0, '2023-01-04 07:55:56', '2023-01-04 07:55:56'),
(18, 6, 'create_task', '{\"project_id\":4,\"name\":\"ZohoBook Invoice\",\"updated_by\":4}', 0, '2023-01-04 07:58:34', '2023-01-04 07:58:34'),
(19, 5, 'create_task', '{\"project_id\":4,\"name\":\"ZohoBook Invoice\",\"updated_by\":4}', 0, '2023-01-04 07:58:58', '2023-01-04 07:58:58'),
(20, 7, 'assign_project', '{\"project_id\":5,\"name\":\"SAP\",\"updated_by\":11}', 0, '2023-02-16 14:36:24', '2023-02-16 14:36:24'),
(21, 5, 'assign_project', '{\"project_id\":5,\"name\":\"SAP\",\"updated_by\":11}', 0, '2023-02-16 14:36:27', '2023-02-16 14:36:27'),
(22, 4, 'create_task', '{\"project_id\":5,\"name\":\"SAP\",\"updated_by\":7}', 0, '2023-02-16 14:44:31', '2023-02-16 14:44:31'),
(23, 5, 'create_task', '{\"project_id\":5,\"name\":\"SAP\",\"updated_by\":7}', 0, '2023-02-16 14:44:34', '2023-02-16 14:44:34'),
(24, 10, 'assign_project', '{\"project_id\":6,\"name\":\"Litepics\",\"updated_by\":11}', 0, '2023-02-17 06:21:20', '2023-02-17 06:21:20'),
(25, 5, 'assign_project', '{\"project_id\":6,\"name\":\"Litepics\",\"updated_by\":11}', 0, '2023-02-17 06:21:24', '2023-02-17 06:21:24'),
(26, 10, 'assign_project', '{\"project_id\":7,\"name\":\"Litepics\",\"updated_by\":11}', 0, '2023-02-17 06:21:29', '2023-02-17 06:21:29'),
(27, 10, 'assign_project', '{\"project_id\":8,\"name\":\"test\",\"updated_by\":11}', 0, '2023-02-17 06:42:49', '2023-02-17 06:42:49'),
(28, 5, 'assign_project', '{\"project_id\":8,\"name\":\"test\",\"updated_by\":11}', 0, '2023-02-17 06:42:52', '2023-02-17 06:42:52'),
(29, 4, 'create_task', '{\"project_id\":6,\"name\":\"Litepics\",\"updated_by\":11}', 0, '2023-02-17 07:18:12', '2023-02-17 07:18:12'),
(30, 10, 'create_task', '{\"project_id\":6,\"name\":\"Litepics\",\"updated_by\":11}', 0, '2023-02-17 07:18:15', '2023-02-17 07:18:15'),
(31, 5, 'create_task', '{\"project_id\":6,\"name\":\"Litepics\",\"updated_by\":11}', 0, '2023-02-17 07:18:18', '2023-02-17 07:18:18'),
(32, 4, 'create_task', '{\"project_id\":6,\"name\":\"Litepics\",\"updated_by\":11}', 0, '2023-02-17 07:18:58', '2023-02-17 07:18:58'),
(33, 10, 'create_task', '{\"project_id\":6,\"name\":\"Litepics\",\"updated_by\":11}', 0, '2023-02-17 07:19:01', '2023-02-17 07:19:01'),
(34, 5, 'create_task', '{\"project_id\":6,\"name\":\"Litepics\",\"updated_by\":11}', 0, '2023-02-17 07:19:04', '2023-02-17 07:19:04'),
(35, 4, 'create_task', '{\"project_id\":6,\"name\":\"Litepics\",\"updated_by\":11}', 0, '2023-02-17 08:09:42', '2023-02-17 08:09:42'),
(36, 10, 'create_task', '{\"project_id\":6,\"name\":\"Litepics\",\"updated_by\":11}', 0, '2023-02-17 08:09:45', '2023-02-17 08:09:45'),
(37, 5, 'create_task', '{\"project_id\":6,\"name\":\"Litepics\",\"updated_by\":11}', 0, '2023-02-17 08:09:48', '2023-02-17 08:09:48'),
(38, 4, 'create_task', '{\"project_id\":6,\"name\":\"Litepics\",\"updated_by\":11}', 0, '2023-02-17 08:11:08', '2023-02-17 08:11:08'),
(39, 10, 'create_task', '{\"project_id\":6,\"name\":\"Litepics\",\"updated_by\":11}', 0, '2023-02-17 08:11:11', '2023-02-17 08:11:11'),
(40, 5, 'create_task', '{\"project_id\":6,\"name\":\"Litepics\",\"updated_by\":11}', 0, '2023-02-17 08:11:14', '2023-02-17 08:11:14');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_number` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_exp_month` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_exp_year` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `plan_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `plan_id` int NOT NULL,
  `price` double DEFAULT NULL,
  `price_currency` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `txn_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_status` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `receipt` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_id_unique` (`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE IF NOT EXISTS `payments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `name`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Cash', 2, '2023-01-03 01:53:36', '2023-01-03 01:53:36'),
(2, 'Bank', 2, '2023-01-03 01:53:36', '2023-01-03 01:53:36'),
(3, 'Cash', 4, '2023-01-03 02:49:19', '2023-01-03 02:49:19'),
(4, 'Bank', 4, '2023-01-03 02:49:19', '2023-01-03 02:49:19'),
(5, 'Cash', 8, '2023-01-03 05:51:32', '2023-01-03 05:51:32'),
(6, 'Bank', 8, '2023-01-03 05:51:32', '2023-01-03 05:51:32'),
(7, 'Cash', 9, '2023-01-03 06:14:42', '2023-01-03 06:14:42'),
(8, 'Bank', 9, '2023-01-03 06:14:42', '2023-01-03 06:14:42');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=152 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'manage user', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(2, 'create user', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(3, 'edit user', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(4, 'delete user', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(5, 'manage language', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(6, 'create language', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(7, 'manage account', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(8, 'edit account', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(9, 'change password account', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(10, 'manage system settings', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(11, 'manage role', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(12, 'create role', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(13, 'edit role', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(14, 'delete role', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(15, 'manage permission', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(16, 'create permission', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(17, 'edit permission', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(18, 'delete permission', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(19, 'manage company settings', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(20, 'manage stripe settings', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(21, 'manage lead stage', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(22, 'create lead stage', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(23, 'edit lead stage', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(24, 'delete lead stage', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(25, 'manage project stage', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(26, 'create project stage', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(27, 'edit project stage', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(28, 'delete project stage', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(29, 'manage lead source', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(30, 'create lead source', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(31, 'edit lead source', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(32, 'delete lead source', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(33, 'manage label', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(34, 'create label', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(35, 'edit label', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(36, 'delete label', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(37, 'manage product unit', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(38, 'create product unit', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(39, 'edit product unit', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(40, 'delete product unit', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(41, 'manage expense', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(42, 'create expense', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(43, 'edit expense', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(44, 'delete expense', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(45, 'manage client', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(46, 'create client', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(47, 'edit client', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(48, 'delete client', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(49, 'manage lead', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(50, 'create lead', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(51, 'edit lead', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(52, 'delete lead', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(53, 'manage project', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(54, 'create project', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(55, 'edit project', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(56, 'delete project', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(57, 'client permission project', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(58, 'invite user project', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(59, 'manage product', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(60, 'create product', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(61, 'edit product', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(62, 'delete product', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(63, 'show project', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(64, 'manage tax', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(65, 'create tax', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(66, 'edit tax', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(67, 'delete tax', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(68, 'manage invoice', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(69, 'create invoice', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(70, 'edit invoice', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(71, 'delete invoice', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(72, 'show invoice', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(73, 'manage expense category', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(74, 'create expense category', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(75, 'edit expense category', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(76, 'delete expense category', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(77, 'manage payment', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(78, 'create payment', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(79, 'edit payment', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(80, 'delete payment', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(81, 'manage invoice product', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(82, 'create invoice product', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(83, 'edit invoice product', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(84, 'delete invoice product', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(85, 'manage invoice payment', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(86, 'create invoice payment', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(87, 'manage task', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(88, 'create task', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(89, 'edit task', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(90, 'delete task', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(91, 'move task', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(92, 'show task', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(93, 'create checklist', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(94, 'edit checklist', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(95, 'create milestone', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(96, 'edit milestone', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(97, 'delete milestone', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(98, 'view milestone', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(99, 'manage change password', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(100, 'manage plan', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(101, 'create plan', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(102, 'edit plan', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(103, 'buy plan', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(104, 'manage note', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(105, 'create note', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(106, 'edit note', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(107, 'delete note', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(108, 'manage order', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(109, 'manage bug status', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(110, 'create bug status', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(111, 'edit bug status', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(112, 'delete bug status', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(113, 'move bug status', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(114, 'manage bug report', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(115, 'create bug report', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(116, 'edit bug report', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(117, 'delete bug report', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(118, 'move bug report', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(119, 'manage timesheet', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(120, 'edit bug report', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(121, 'create timesheet', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(122, 'edit timesheet', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(123, 'delete timesheet', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(124, 'manage coupon', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(125, 'create coupon', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(126, 'edit coupon', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(127, 'delete coupon', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(128, 'payment reminder invoice', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(129, 'send invoice', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(130, 'custom mail send invoice', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(131, 'manage business settings', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(132, 'manage estimations', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(133, 'create estimation', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(134, 'edit estimation', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(135, 'delete estimation', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(136, 'view estimation', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(137, 'estimation add product', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(138, 'estimation edit product', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(139, 'estimation delete product', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(140, 'manage email templates', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(141, 'create email template', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(142, 'edit email template', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(143, 'on-off email template', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(144, 'edit email template lang', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(145, 'manage contracts', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(146, 'edit contract', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(147, 'create contract', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(148, 'delete contract', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(149, 'create attachment', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(150, 'store comment', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(151, 'store note', 'web', '2023-01-03 01:53:09', '2023-01-03 01:53:09');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `plans`
--

DROP TABLE IF EXISTS `plans`;
CREATE TABLE IF NOT EXISTS `plans` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` double DEFAULT '0',
  `duration` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `max_users` int NOT NULL DEFAULT '0',
  `max_clients` int NOT NULL DEFAULT '0',
  `max_projects` int NOT NULL DEFAULT '0',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `image` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `plans_name_unique` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `plans`
--

INSERT INTO `plans` (`id`, `name`, `price`, `duration`, `max_users`, `max_clients`, `max_projects`, `description`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Free Plan', 0, 'unlimited', 250, 500, 500, '', 'free_plan.png', '2023-01-03 01:53:09', '2023-02-15 07:25:29'),
(2, 'Monthly', 100, 'month', 100, 50, 20, 'This is monthly based plan', NULL, '2023-01-04 02:31:22', '2023-01-04 02:31:22');

-- --------------------------------------------------------

--
-- Table structure for table `plan_requests`
--

DROP TABLE IF EXISTS `plan_requests`;
CREATE TABLE IF NOT EXISTS `plan_requests` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `plan_id` int NOT NULL,
  `duration` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'monthly',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` double NOT NULL DEFAULT '0',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit` int NOT NULL DEFAULT '0',
  `created_by` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `productunits`
--

DROP TABLE IF EXISTS `productunits`;
CREATE TABLE IF NOT EXISTS `productunits` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `productunits`
--

INSERT INTO `productunits` (`id`, `name`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Kilogram', 2, '2023-01-03 01:53:36', '2023-01-03 01:53:36'),
(2, 'Piece', 2, '2023-01-03 01:53:36', '2023-01-03 01:53:36'),
(3, 'Set', 2, '2023-01-03 01:53:36', '2023-01-03 01:53:36'),
(4, 'Item', 2, '2023-01-03 01:53:36', '2023-01-03 01:53:36'),
(5, 'Hour', 2, '2023-01-03 01:53:36', '2023-01-03 01:53:36'),
(6, 'Kilogram', 4, '2023-01-03 02:49:19', '2023-01-03 02:49:19'),
(7, 'Piece', 4, '2023-01-03 02:49:19', '2023-01-03 02:49:19'),
(8, 'Set', 4, '2023-01-03 02:49:19', '2023-01-03 02:49:19'),
(9, 'Item', 4, '2023-01-03 02:49:19', '2023-01-03 02:49:19'),
(10, 'Hour', 4, '2023-01-03 02:49:19', '2023-01-03 02:49:19'),
(11, 'Kilogram', 8, '2023-01-03 05:51:32', '2023-01-03 05:51:32'),
(12, 'Piece', 8, '2023-01-03 05:51:32', '2023-01-03 05:51:32'),
(13, 'Set', 8, '2023-01-03 05:51:32', '2023-01-03 05:51:32'),
(14, 'Item', 8, '2023-01-03 05:51:32', '2023-01-03 05:51:32'),
(15, 'Hour', 8, '2023-01-03 05:51:32', '2023-01-03 05:51:32'),
(16, 'Kilogram', 9, '2023-01-03 06:14:42', '2023-01-03 06:14:42'),
(17, 'Piece', 9, '2023-01-03 06:14:42', '2023-01-03 06:14:42'),
(18, 'Set', 9, '2023-01-03 06:14:42', '2023-01-03 06:14:42'),
(19, 'Item', 9, '2023-01-03 06:14:42', '2023-01-03 06:14:42'),
(20, 'Hour', 9, '2023-01-03 06:14:42', '2023-01-03 06:14:42');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
CREATE TABLE IF NOT EXISTS `projects` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` double NOT NULL DEFAULT '0',
  `start_date` date NOT NULL,
  `due_date` date NOT NULL,
  `client` int NOT NULL DEFAULT '0',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` int NOT NULL DEFAULT '0',
  `lead` int NOT NULL DEFAULT '0',
  `status` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'on_going',
  `is_active` int NOT NULL DEFAULT '1',
  `created_by` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `name`, `price`, `start_date`, `due_date`, `client`, `description`, `label`, `lead`, `status`, `is_active`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'DHI', 500000, '2023-01-03', '2023-01-31', 5, 'Testing', 7, 1, 'on_going', 1, 4, '2023-01-03 05:36:00', '2023-01-03 05:36:00'),
(2, 'Time Log', 100, '2023-01-02', '2023-03-31', 5, 'This is in house project', 7, 1, 'on_going', 1, 4, '2023-01-04 05:32:14', '2023-01-04 05:32:14'),
(3, 'Training', 0, '2023-01-01', '2023-12-31', 5, 'Training Testing', 7, 1, 'on_going', 1, 4, '2023-01-04 07:25:04', '2023-01-04 07:25:04'),
(4, 'ZohoBook Invoice', 50000, '2023-01-12', '2023-02-25', 5, 'ZohoBook Invoice', 7, 1, 'on_going', 1, 4, '2023-01-04 07:52:47', '2023-01-04 07:52:47'),
(5, 'SAP', 100, '2023-02-16', '2023-03-03', 5, 'test', 7, 1, 'on_going', 1, 4, '2023-02-16 14:36:24', '2023-02-16 14:36:24'),
(6, 'Litepics', 500, '2023-02-17', '2023-03-10', 5, 'Pics Upload and compressed', 7, 1, 'on_going', 1, 4, '2023-02-17 06:21:20', '2023-02-17 06:21:20');

-- --------------------------------------------------------

--
-- Table structure for table `projectstages`
--

DROP TABLE IF EXISTS `projectstages`;
CREATE TABLE IF NOT EXISTS `projectstages` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int NOT NULL DEFAULT '0',
  `order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projectstages`
--

INSERT INTO `projectstages` (`id`, `name`, `color`, `created_by`, `order`, `created_at`, `updated_at`) VALUES
(1, 'To Do', '#e7505a', 2, 0, '2023-01-03 01:53:36', '2023-01-03 01:53:36'),
(2, 'In Progress', '#F4D03F', 2, 1, '2023-01-03 01:53:36', '2023-01-03 01:53:36'),
(3, 'Bugs', '#32c5d2', 2, 2, '2023-01-03 01:53:36', '2023-01-03 01:53:36'),
(4, 'Done', '#1BBC9B', 2, 3, '2023-01-03 01:53:36', '2023-01-03 01:53:36'),
(5, 'To Do', '#e7505a', 4, 0, '2023-01-03 02:49:19', '2023-01-03 02:49:19'),
(6, 'In Progress', '#F4D03F', 4, 1, '2023-01-03 02:49:19', '2023-01-03 02:49:19'),
(7, 'Bugs', '#32c5d2', 4, 2, '2023-01-03 02:49:19', '2023-01-03 02:49:19'),
(8, 'Done', '#1BBC9B', 4, 3, '2023-01-03 02:49:19', '2023-01-03 02:49:19'),
(9, 'To Do', '#e7505a', 8, 0, '2023-01-03 05:51:32', '2023-01-03 05:51:32'),
(10, 'In Progress', '#F4D03F', 8, 1, '2023-01-03 05:51:32', '2023-01-03 05:51:32'),
(11, 'Bugs', '#32c5d2', 8, 2, '2023-01-03 05:51:32', '2023-01-03 05:51:32'),
(12, 'Done', '#1BBC9B', 8, 3, '2023-01-03 05:51:32', '2023-01-03 05:51:32'),
(13, 'To Do', '#e7505a', 9, 0, '2023-01-03 06:14:42', '2023-01-03 06:14:42'),
(14, 'In Progress', '#F4D03F', 9, 1, '2023-01-03 06:14:42', '2023-01-03 06:14:42'),
(15, 'Bugs', '#32c5d2', 9, 2, '2023-01-03 06:14:42', '2023-01-03 06:14:42'),
(16, 'Done', '#1BBC9B', 9, 3, '2023-01-03 06:14:42', '2023-01-03 06:14:42');

-- --------------------------------------------------------

--
-- Table structure for table `project_files`
--

DROP TABLE IF EXISTS `project_files`;
CREATE TABLE IF NOT EXISTS `project_files` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` int NOT NULL DEFAULT '0',
  `file_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(190) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_reports`
--

DROP TABLE IF EXISTS `project_reports`;
CREATE TABLE IF NOT EXISTS `project_reports` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'super admin', 'web', 0, '2023-01-03 01:53:09', '2023-01-03 01:53:09'),
(4, 'employee', 'web', 2, '2023-01-03 01:53:15', '2023-01-03 01:53:15'),
(10, 'Project Manager', 'web', 4, '2023-01-04 06:16:37', '2023-01-04 06:16:37'),
(11, 'PMO', 'web', 4, '2023-02-15 06:48:31', '2023-02-15 06:48:31');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 3),
(1, 11),
(2, 1),
(2, 3),
(2, 11),
(3, 1),
(3, 3),
(3, 11),
(4, 1),
(4, 3),
(4, 11),
(5, 1),
(5, 3),
(6, 1),
(7, 1),
(7, 2),
(7, 3),
(7, 4),
(7, 5),
(7, 6),
(7, 7),
(7, 8),
(7, 9),
(7, 11),
(8, 1),
(8, 2),
(8, 3),
(8, 4),
(8, 5),
(8, 6),
(8, 7),
(8, 8),
(8, 9),
(8, 10),
(8, 11),
(9, 1),
(9, 2),
(9, 3),
(9, 4),
(9, 5),
(9, 6),
(9, 7),
(9, 8),
(9, 9),
(9, 11),
(10, 1),
(11, 1),
(11, 3),
(11, 11),
(12, 1),
(12, 3),
(12, 11),
(13, 1),
(13, 3),
(13, 11),
(14, 1),
(14, 3),
(14, 11),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 3),
(19, 6),
(20, 1),
(21, 3),
(21, 6),
(22, 3),
(22, 6),
(23, 3),
(23, 6),
(24, 3),
(24, 6),
(25, 3),
(25, 6),
(25, 7),
(26, 3),
(26, 6),
(26, 7),
(27, 3),
(27, 6),
(27, 7),
(28, 3),
(28, 6),
(28, 7),
(29, 3),
(29, 6),
(30, 3),
(30, 6),
(31, 3),
(31, 6),
(32, 3),
(32, 6),
(33, 3),
(33, 6),
(33, 7),
(34, 3),
(34, 6),
(34, 7),
(35, 3),
(35, 6),
(35, 7),
(36, 3),
(36, 6),
(36, 7),
(37, 3),
(37, 6),
(37, 7),
(38, 3),
(38, 6),
(38, 7),
(39, 3),
(39, 6),
(39, 7),
(40, 3),
(40, 6),
(40, 7),
(41, 3),
(41, 6),
(42, 3),
(42, 6),
(43, 3),
(43, 6),
(44, 3),
(44, 6),
(45, 3),
(45, 11),
(46, 3),
(46, 11),
(47, 3),
(47, 11),
(48, 3),
(48, 11),
(49, 3),
(49, 4),
(49, 6),
(49, 7),
(49, 8),
(49, 9),
(50, 3),
(50, 6),
(50, 7),
(51, 3),
(51, 6),
(51, 7),
(52, 3),
(52, 6),
(52, 7),
(53, 2),
(53, 3),
(53, 4),
(53, 5),
(53, 6),
(53, 7),
(53, 8),
(53, 9),
(54, 3),
(54, 6),
(54, 7),
(55, 3),
(55, 6),
(55, 7),
(56, 3),
(56, 6),
(56, 7),
(57, 3),
(58, 3),
(59, 3),
(59, 6),
(59, 7),
(60, 3),
(60, 6),
(60, 7),
(61, 3),
(61, 6),
(61, 7),
(62, 3),
(62, 6),
(62, 7),
(63, 2),
(63, 3),
(63, 4),
(63, 5),
(63, 6),
(63, 7),
(63, 8),
(63, 9),
(64, 3),
(64, 6),
(65, 3),
(65, 6),
(66, 3),
(66, 6),
(67, 3),
(67, 6),
(68, 3),
(68, 6),
(69, 3),
(69, 6),
(70, 3),
(70, 6),
(71, 3),
(71, 6),
(72, 3),
(72, 6),
(73, 3),
(73, 6),
(74, 3),
(74, 6),
(75, 3),
(75, 6),
(76, 3),
(76, 6),
(77, 3),
(77, 6),
(78, 3),
(78, 6),
(79, 3),
(79, 6),
(80, 3),
(80, 6),
(81, 3),
(81, 6),
(82, 3),
(82, 6),
(83, 3),
(83, 6),
(84, 3),
(84, 6),
(85, 3),
(85, 6),
(86, 3),
(86, 6),
(87, 2),
(87, 3),
(87, 4),
(87, 5),
(87, 6),
(87, 7),
(87, 8),
(87, 9),
(88, 2),
(88, 3),
(88, 6),
(88, 7),
(89, 3),
(89, 6),
(89, 7),
(90, 3),
(90, 6),
(90, 7),
(91, 2),
(91, 3),
(91, 4),
(91, 5),
(91, 6),
(91, 7),
(91, 8),
(91, 9),
(92, 2),
(92, 3),
(92, 4),
(92, 5),
(92, 6),
(92, 7),
(92, 8),
(92, 9),
(93, 2),
(93, 3),
(93, 4),
(93, 6),
(93, 7),
(93, 8),
(93, 9),
(94, 2),
(94, 3),
(94, 6),
(94, 7),
(95, 2),
(95, 3),
(96, 2),
(96, 3),
(97, 2),
(97, 3),
(98, 2),
(98, 3),
(99, 1),
(99, 2),
(99, 3),
(100, 1),
(100, 3),
(100, 6),
(100, 7),
(101, 1),
(102, 1),
(103, 3),
(103, 6),
(104, 2),
(104, 3),
(104, 4),
(104, 6),
(104, 7),
(104, 8),
(104, 9),
(105, 2),
(105, 3),
(105, 4),
(105, 6),
(105, 7),
(105, 8),
(105, 9),
(106, 2),
(106, 3),
(106, 4),
(106, 6),
(106, 7),
(106, 8),
(106, 9),
(107, 2),
(107, 3),
(107, 4),
(107, 6),
(107, 7),
(107, 8),
(107, 9),
(108, 1),
(108, 3),
(109, 2),
(109, 3),
(110, 2),
(110, 3),
(111, 2),
(111, 3),
(112, 2),
(112, 3),
(113, 2),
(113, 3),
(114, 2),
(114, 3),
(114, 4),
(114, 6),
(114, 7),
(114, 8),
(114, 9),
(115, 2),
(115, 3),
(115, 4),
(115, 6),
(115, 7),
(115, 8),
(115, 9),
(116, 2),
(116, 3),
(116, 4),
(116, 6),
(116, 7),
(116, 8),
(116, 9),
(117, 2),
(117, 3),
(117, 4),
(117, 6),
(117, 7),
(117, 8),
(117, 9),
(118, 2),
(118, 3),
(118, 4),
(118, 6),
(118, 7),
(118, 8),
(118, 9),
(119, 2),
(119, 3),
(119, 4),
(119, 5),
(119, 6),
(119, 7),
(119, 8),
(119, 9),
(121, 2),
(121, 3),
(121, 4),
(121, 5),
(121, 6),
(121, 7),
(121, 8),
(121, 9),
(122, 2),
(122, 3),
(122, 4),
(122, 5),
(122, 6),
(122, 7),
(122, 8),
(122, 9),
(123, 2),
(123, 3),
(123, 4),
(123, 5),
(123, 6),
(123, 7),
(123, 8),
(123, 9),
(124, 1),
(125, 1),
(126, 1),
(127, 1),
(128, 3),
(129, 3),
(130, 2),
(131, 3),
(132, 2),
(132, 3),
(133, 3),
(134, 3),
(135, 3),
(136, 2),
(136, 3),
(137, 3),
(138, 3),
(139, 3),
(140, 1),
(140, 3),
(141, 1),
(142, 1),
(143, 3),
(144, 1),
(145, 2),
(145, 3),
(146, 3),
(147, 3),
(148, 3),
(149, 2),
(149, 3),
(150, 2),
(150, 3),
(151, 2),
(151, 3);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_name_created_by_unique` (`name`,`created_by`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `name`, `value`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'local_storage_validation', 'jpg,jpeg,png,xlsx,xls,csv,pdf', 1, '2023-01-03 01:53:36', '2023-01-03 01:53:36'),
(2, 'wasabi_storage_validation', 'jpg,jpeg,png,xlsx,xls,csv,pdf', 1, '2023-01-03 01:53:36', '2023-01-03 01:53:36'),
(3, 's3_storage_validation', 'jpg,jpeg,png,xlsx,xls,csv,pdf', 1, '2023-01-03 01:53:36', '2023-01-03 01:53:36'),
(4, 'local_storage_max_upload_size', '2048000', 1, '2023-01-03 01:53:36', '2023-01-03 01:53:36'),
(5, 'wasabi_max_upload_size', '2048000', 1, '2023-01-03 01:53:36', '2023-01-03 01:53:36'),
(6, 's3_max_upload_size', '2048000', 1, '2023-01-03 01:53:36', '2023-01-03 01:53:36'),
(7, 'gdpr_cookie', 'off', 1, '2023-01-04 02:18:58', '2023-01-04 02:20:26'),
(8, 'cookie_text', '', 1, '2023-01-04 02:18:58', '2023-01-04 02:20:26'),
(9, 'header_text', '', 1, '2023-01-04 02:18:58', '2023-01-04 02:20:26'),
(10, 'footer_text', '', 1, '2023-01-04 02:18:58', '2023-01-04 02:20:26'),
(11, 'default_language', 'en', 1, '2023-01-04 02:18:58', '2023-01-04 02:20:26'),
(12, 'enable_landing', 'yes', 1, '2023-01-04 02:18:58', '2023-01-04 02:20:26'),
(13, 'SITE_RTL', 'off', 1, '2023-01-04 02:18:58', '2023-01-04 02:20:26'),
(14, 'header_text', '', 4, '2023-01-04 09:04:28', '2023-01-18 07:57:57'),
(15, 'theme_color', 'theme-5', 4, '2023-01-04 09:04:28', '2023-01-18 07:57:57'),
(16, 'SITE_RTL', 'off', 4, '2023-01-04 09:04:28', '2023-01-18 07:57:57'),
(17, 'dark_mode', 'off', 4, '2023-01-04 09:04:28', '2023-01-18 07:57:57'),
(18, 'is_sidebar_transperent', 'on', 4, '2023-01-04 09:04:28', '2023-01-18 07:57:57'),
(19, 'company_logo_light', '4light_logo.png', 4, '2023-01-05 08:54:25', '2023-01-05 08:54:25'),
(20, 'company_favicon', '4_favicon.png', 4, '2023-01-18 07:52:08', '2023-01-18 07:52:08');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
CREATE TABLE IF NOT EXISTS `tasks` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_task_id` int NOT NULL,
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_id` int NOT NULL DEFAULT '0',
  `start_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `estimated_mins` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `hours` time(6) DEFAULT NULL,
  `due_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `assign_to` int NOT NULL,
  `project_id` int NOT NULL,
  `milestone_id` int DEFAULT NULL,
  `status` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'todo',
  `stage` int NOT NULL DEFAULT '0',
  `order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `parent_task_id`, `title`, `priority`, `description`, `group_id`, `start_date`, `estimated_mins`, `hours`, `due_date`, `assign_to`, `project_id`, `milestone_id`, `status`, `stage`, `order`, `created_at`, `updated_at`) VALUES
(1, 1, 'Scrum Call', 'High', 'Test', 1, '2023-01-03 11:23:53', '120', '12:53:53.000000', '2023-01-31 11:24:54', 4, 1, NULL, 'todo', 0, 0, NULL, NULL),
(2, 2, 'Development', 'low', 'Development Enhancement', 2, '2022-12-31 18:30:00', '540', '00:00:00.000000', '2023-01-30 18:30:00', 7, 3, NULL, 'todo', 5, 0, '2023-01-04 07:26:51', '2023-01-04 07:27:30'),
(3, 3, 'Development', 'medium', 'Development', 1, '2023-01-11 18:30:00', '380', '00:00:50.000000', '2023-02-24 18:30:00', 6, 4, NULL, 'todo', 5, 0, '2023-01-04 07:55:11', '2023-01-04 07:55:11'),
(4, 3, 'Scrum Call', 'low', 'Scrum Call', 2, '2023-01-03 18:30:00', '260', '00:00:00.000000', '2023-01-27 18:30:00', 6, 4, NULL, 'todo', 5, 0, '2023-01-04 07:58:34', '2023-01-04 07:58:34'),
(5, 5, 'Migration', 'medium', 'test', 0, '2023-02-15 18:30:00', '3000', '00:00:50.000000', '2023-03-02 18:30:00', 7, 5, NULL, 'todo', 5, 0, '2023-02-16 14:44:31', '2023-02-20 11:26:25'),
(6, 6, 'Scrum Meeting', 'low', 'Daily Scrum Call', 0, '2023-02-16 18:30:00', '1200', '00:00:20.000000', '2023-06-22 18:30:00', 10, 6, NULL, 'todo', 5, 0, '2023-02-17 07:18:12', '2023-02-20 11:42:36'),
(7, 7, 'Development', 'medium', 'Development', 0, '2023-02-16 18:30:00', '1800', '00:00:30.000000', '2023-08-10 18:30:00', 10, 6, NULL, 'todo', 5, 0, '2023-02-17 07:18:58', '2023-02-20 11:43:03'),
(8, 8, 'KT', 'low', 'Knowledghe Transfer', 0, '2023-02-16 18:30:00', '2400', '00:00:40.000000', '2023-07-13 18:30:00', 10, 6, NULL, 'todo', 5, 0, '2023-02-17 08:09:42', '2023-02-20 11:43:25'),
(9, 9, 'Testing', 'low', 'Testing the application', 0, '2023-02-16 18:30:00', '3000', '00:00:50.000000', '2023-03-03 18:30:00', 10, 6, NULL, 'todo', 5, 0, '2023-02-17 08:11:08', '2023-02-20 11:43:44');

-- --------------------------------------------------------

--
-- Table structure for table `task_files`
--

DROP TABLE IF EXISTS `task_files`;
CREATE TABLE IF NOT EXISTS `task_files` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `file` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `extension` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `task_id` int NOT NULL,
  `user_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_groups`
--

DROP TABLE IF EXISTS `task_groups`;
CREATE TABLE IF NOT EXISTS `task_groups` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `project_id` int NOT NULL DEFAULT '0',
  `created_by` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `task_groups`
--

INSERT INTO `task_groups` (`id`, `name`, `project_id`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'AAPNA TeamWork 2023', 0, 2, '2023-01-31 05:55:30', '2023-01-31 05:55:30'),
(2, 'Niswey 2023', 0, 1, '2023-01-31 06:45:53', '2023-01-31 06:45:53');

-- --------------------------------------------------------

--
-- Table structure for table `taxes`
--

DROP TABLE IF EXISTS `taxes`;
CREATE TABLE IF NOT EXISTS `taxes` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate` double(8,2) NOT NULL DEFAULT '0.00',
  `created_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `timesheets`
--

DROP TABLE IF EXISTS `timesheets`;
CREATE TABLE IF NOT EXISTS `timesheets` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` int NOT NULL DEFAULT '0',
  `user_id` int NOT NULL DEFAULT '0',
  `task_id` int NOT NULL DEFAULT '0',
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `billable` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_mins` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `timesheets`
--

INSERT INTO `timesheets` (`id`, `project_id`, `user_id`, `task_id`, `date`, `start_time`, `end_time`, `remark`, `billable`, `total_mins`, `created_at`, `updated_at`) VALUES
(20, 1, 6, 1, '2022-12-29', '17:52:00', '19:52:00', 'test', 'No', 120, '2023-01-18 12:23:20', '2023-01-18 12:23:20'),
(6, 1, 6, 1, '2023-02-01', '18:01:00', '21:01:00', 'testing', 'Yes', 180, '2023-01-12 11:34:03', '2023-01-12 11:34:03'),
(5, 1, 6, 1, '2023-02-26', '16:57:00', '18:57:00', 'test', 'No', 120, '2023-01-12 11:34:03', '2023-01-12 11:34:03'),
(7, 4, 6, 3, '2023-01-02', '18:13:00', '20:11:00', 'feedback', 'Yes', 118, '2023-01-12 11:40:43', '2023-01-12 11:40:43'),
(8, 4, 6, 4, '2023-02-27', '22:18:00', '23:12:00', 'feedback', 'Yes', 54, '2023-01-12 11:40:43', '2023-01-12 11:40:43'),
(9, 1, 6, 1, '2023-01-04', '10:00:00', '11:00:00', 'asdf', 'Yes', 60, '2023-01-12 11:42:09', '2023-01-12 11:42:09'),
(10, 1, 6, 1, '2023-02-21', '10:00:00', '11:00:00', 'lkj', 'Yes', 60, '2023-01-12 11:42:09', '2023-01-12 11:42:09'),
(11, 1, 6, 1, '2023-01-19', '17:16:00', '19:16:00', 'Rahul Kumar', 'Yes', 120, '2023-01-12 11:46:55', '2023-01-12 11:46:55'),
(12, 4, 6, 4, '2023-01-20', '17:18:00', '19:18:00', 'worked on feedback shared by Amit', 'Yes', 120, '2023-01-12 11:49:10', '2023-01-12 11:49:10'),
(13, 1, 6, 1, '2023-01-06', '17:52:00', '19:52:00', 'worked on teamwork timesheet module', 'No', 120, '2023-01-12 12:23:03', '2023-01-12 12:23:03'),
(14, 1, 6, 1, '2023-01-12', '21:25:00', '23:26:00', 'rahul', 'Yes', 121, '2023-01-12 14:53:44', '2023-01-12 14:53:44'),
(15, 6, 10, 1, '2023-01-12', '21:25:00', '23:26:00', 'rahul', 'Yes', 121, '2023-01-12 14:53:44', '2023-01-12 14:53:44'),
(16, 4, 6, 3, '2023-02-03', '16:08:00', '18:08:00', 'asdf', 'Yes', 125, '2023-01-13 10:39:03', '2023-01-13 10:39:03'),
(17, 4, 6, 3, '2023-01-10', '17:09:00', '20:09:00', 'Meeting Room Booking Project', 'Yes', 180, '2023-01-18 11:54:42', '2023-01-18 11:54:42'),
(18, 4, 6, 3, '2023-01-13', '20:09:00', '22:09:00', 'Meeting Room Booking Project', 'No', 120, '2023-01-18 11:54:42', '2023-01-18 11:54:42'),
(25, 1, 6, 1, '2023-01-10', '18:28:00', '20:28:00', 'asdf', 'Yes', 120, '2023-01-31 12:59:44', '2023-01-31 12:59:44'),
(21, 1, 6, 1, '2023-01-04', '17:52:00', '19:52:00', 'test', 'Yes', 120, '2023-01-18 12:23:20', '2023-01-18 12:23:20'),
(22, 5, 7, 1, '2023-03-02', '18:29:00', '20:29:00', 'asf', 'Yes', 120, '2023-01-18 12:59:34', '2023-01-18 12:59:34'),
(23, 1, 6, 1, '2023-01-11', '18:30:00', '20:38:00', 'Testing Remarks', 'No', 128, '2023-01-18 12:59:34', '2023-01-30 13:29:44'),
(24, 1, 6, 1, '2023-02-28', '18:31:00', '20:31:00', 'Meeting Room Booking', 'Yes', 120, '2023-01-18 13:06:38', '2023-01-18 13:06:38'),
(26, 1, 6, 1, '2023-02-22', '20:32:00', '22:34:00', 'testing', 'No', 122, '2023-01-31 12:59:44', '2023-02-23 12:16:33'),
(28, 5, 7, 5, '2023-02-24', '20:15:00', '22:15:00', 'Created HS App Installation page.', 'Yes', 120, '2023-02-16 14:45:24', '2023-02-23 12:17:06'),
(29, 1, 10, 6, '2023-03-01', '14:02:00', '15:02:00', 'Call with client for demo', 'Yes', 60, '2023-02-17 08:33:56', '2023-02-17 08:33:56'),
(30, 1, 10, 7, '2023-02-17', '15:02:00', '18:02:00', 'Workon client feedback', 'Yes', 180, '2023-02-17 08:33:56', '2023-02-17 08:33:56');

-- --------------------------------------------------------

--
-- Table structure for table `time_trackers`
--

DROP TABLE IF EXISTS `time_trackers`;
CREATE TABLE IF NOT EXISTS `time_trackers` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` int DEFAULT NULL,
  `task_id` int DEFAULT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_billable` int NOT NULL DEFAULT '0',
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `total_time` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `is_active` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `created_by` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `track_photos`
--

DROP TABLE IF EXISTS `track_photos`;
CREATE TABLE IF NOT EXISTS `track_photos` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `track_id` int NOT NULL DEFAULT '0',
  `user_id` int NOT NULL DEFAULT '0',
  `img_path` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  `status` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `userprojects`
--

DROP TABLE IF EXISTS `userprojects`;
CREATE TABLE IF NOT EXISTS `userprojects` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL DEFAULT '0',
  `project_id` int NOT NULL DEFAULT '0',
  `is_active` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `permission` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `userprojects`
--

INSERT INTO `userprojects` (`id`, `user_id`, `project_id`, `is_active`, `permission`, `created_at`, `updated_at`) VALUES
(1, 4, 1, NULL, NULL, '2023-01-03 05:36:00', '2023-01-03 05:36:00'),
(2, 6, 1, NULL, NULL, '2023-01-03 05:36:00', '2023-01-03 05:36:00'),
(3, 4, 2, NULL, NULL, '2023-01-04 05:32:15', '2023-01-04 05:32:15'),
(4, 6, 2, NULL, NULL, '2023-01-04 05:32:15', '2023-01-04 05:32:15'),
(5, 4, 3, NULL, NULL, '2023-01-04 07:25:04', '2023-01-04 07:25:04'),
(6, 7, 3, NULL, NULL, '2023-01-04 07:25:04', '2023-01-04 07:25:04'),
(7, 4, 4, NULL, NULL, '2023-01-04 07:52:47', '2023-01-04 07:52:47'),
(8, 6, 4, NULL, NULL, '2023-01-04 07:52:47', '2023-01-04 07:52:47'),
(9, 4, 5, NULL, NULL, '2023-02-16 14:36:24', '2023-02-16 14:36:24'),
(10, 7, 5, NULL, NULL, '2023-02-16 14:36:24', '2023-02-16 14:36:24'),
(11, 4, 6, NULL, NULL, '2023-02-17 06:21:20', '2023-02-17 06:21:20'),
(12, 10, 1, NULL, NULL, '2023-02-17 06:21:20', '2023-02-17 06:21:20');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_parent_id` int NOT NULL DEFAULT '0',
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `lang` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int NOT NULL DEFAULT '0',
  `plan` int DEFAULT NULL,
  `requested_plan` int NOT NULL DEFAULT '0',
  `delete_status` int NOT NULL DEFAULT '1',
  `plan_expire_date` date DEFAULT NULL,
  `is_active` int NOT NULL DEFAULT '1',
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `messenger_color` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#2180f3',
  `dark_mode` tinyint(1) NOT NULL DEFAULT '0',
  `active_status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_parent_id`, `name`, `email`, `email_verified_at`, `password`, `type`, `avatar`, `lang`, `created_by`, `plan`, `requested_plan`, `delete_status`, `plan_expire_date`, `is_active`, `remember_token`, `created_at`, `updated_at`, `messenger_color`, `dark_mode`, `active_status`) VALUES
(10, 7, 'Rahul Kumar', 'rahul@switches.com', NULL, '$2y$10$rkvENAksjOFjJ5JtC2pdnOaFmf/E0WHXFmcbLKFQyHzbX5WUt/9e2', 'employee', '', 'en', 4, NULL, 0, 1, NULL, 1, NULL, '2023-02-13 09:02:05', '2023-02-17 05:09:11', '#2180f3', 0, 0),
(11, 0, 'Admin', 'admin@switches.com', NULL, '$2y$10$g.zOJSImsVuSRkU1Yu0lDuZaXc9tzldwtY4joBDBAcUZ3WRXTZ15O', 'PMO', '', 'en', 4, NULL, 0, 1, NULL, 1, NULL, '2023-02-15 07:07:30', '2023-02-15 07:07:30', '#2180f3', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_coupons`
--

DROP TABLE IF EXISTS `user_coupons`;
CREATE TABLE IF NOT EXISTS `user_coupons` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user` int NOT NULL,
  `coupon` int NOT NULL,
  `order` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_defualt_views`
--

DROP TABLE IF EXISTS `user_defualt_views`;
CREATE TABLE IF NOT EXISTS `user_defualt_views` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `module` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `route` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `view` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_email_templates`
--

DROP TABLE IF EXISTS `user_email_templates`;
CREATE TABLE IF NOT EXISTS `user_email_templates` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `template_id` int NOT NULL,
  `user_id` int NOT NULL,
  `is_active` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_email_templates`
--

INSERT INTO `user_email_templates` (`id`, `template_id`, `user_id`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 1, '2023-01-03 01:53:36', '2023-01-03 01:53:36'),
(2, 2, 2, 1, '2023-01-03 01:53:36', '2023-01-03 01:53:36'),
(3, 3, 2, 1, '2023-01-03 01:53:36', '2023-01-03 01:53:36'),
(4, 4, 2, 1, '2023-01-03 01:53:36', '2023-01-03 01:53:36'),
(5, 5, 2, 1, '2023-01-03 01:53:36', '2023-01-03 01:53:36'),
(6, 6, 2, 1, '2023-01-03 01:53:36', '2023-01-03 01:53:36'),
(7, 1, 4, 1, '2023-01-03 02:49:19', '2023-01-03 02:49:19'),
(8, 2, 4, 1, '2023-01-03 02:49:19', '2023-01-03 02:49:19'),
(9, 3, 4, 1, '2023-01-03 02:49:19', '2023-01-03 02:49:19'),
(10, 4, 4, 1, '2023-01-03 02:49:19', '2023-01-03 02:49:19'),
(11, 5, 4, 1, '2023-01-03 02:49:19', '2023-01-03 02:49:19'),
(12, 6, 4, 1, '2023-01-03 02:49:19', '2023-01-03 02:49:19'),
(13, 1, 8, 1, '2023-01-03 05:51:32', '2023-01-03 05:51:32'),
(14, 2, 8, 1, '2023-01-03 05:51:32', '2023-01-03 05:51:32'),
(15, 3, 8, 1, '2023-01-03 05:51:32', '2023-01-03 05:51:32'),
(16, 4, 8, 1, '2023-01-03 05:51:32', '2023-01-03 05:51:32'),
(17, 5, 8, 1, '2023-01-03 05:51:32', '2023-01-03 05:51:32'),
(18, 6, 8, 1, '2023-01-03 05:51:32', '2023-01-03 05:51:32'),
(19, 1, 9, 1, '2023-01-03 06:14:42', '2023-01-03 06:14:42'),
(20, 2, 9, 1, '2023-01-03 06:14:42', '2023-01-03 06:14:42'),
(21, 3, 9, 1, '2023-01-03 06:14:42', '2023-01-03 06:14:42'),
(22, 4, 9, 1, '2023-01-03 06:14:42', '2023-01-03 06:14:42'),
(23, 5, 9, 1, '2023-01-03 06:14:42', '2023-01-03 06:14:42'),
(24, 6, 9, 1, '2023-01-03 06:14:42', '2023-01-03 06:14:42');

-- --------------------------------------------------------

--
-- Table structure for table `zoommeetings`
--

DROP TABLE IF EXISTS `zoommeetings`;
CREATE TABLE IF NOT EXISTS `zoommeetings` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meeting_id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_id` int NOT NULL DEFAULT '0',
  `project_id` int NOT NULL DEFAULT '0',
  `employee` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `duration` int NOT NULL DEFAULT '0',
  `start_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `join_url` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'waiting',
  `created_by` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
