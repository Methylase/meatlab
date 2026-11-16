-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 21, 2026 at 12:05 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `meatlab`
--

-- --------------------------------------------------------


CREATE TABLE shops (id INT AUTO_INCREMENT PRIMARY KEY, owner_name VARCHAR(120), shop_name VARCHAR(120), slug VARCHAR(140) UNIQUE, phone VARCHAR(30), email VARCHAR(160), password VARCHAR(255), address VARCHAR(255), city VARCHAR(80), tagline VARCHAR(200), rating DECIMAL(2,1) DEFAULT 4.8, remember_token VARCHAR(64), reset_token VARCHAR(64), created_at DATETIME DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE categories (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(60), icon VARCHAR(40));
CREATE TABLE products (id INT AUTO_INCREMENT PRIMARY KEY, shop_id INT, category_id INT, name VARCHAR(120), description VARCHAR(255), price_per_kg INT, min_kg DECIMAL(4,1) DEFAULT 0.5, is_top TINYINT DEFAULT 0);
CREATE TABLE orders (id INT AUTO_INCREMENT PRIMARY KEY, reference VARCHAR(40) UNIQUE, shop_id INT, customer_name VARCHAR(120), phone VARCHAR(30), email VARCHAR(160), address VARCHAR(255), subtotal INT, delivery INT, total INT, status VARCHAR(20) DEFAULT 'pending', created_at DATETIME DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE order_items (id INT AUTO_INCREMENT PRIMARY KEY, order_id INT, product_id INT, name VARCHAR(120), kg DECIMAL(6,2), price_per_kg INT);
INSERT INTO categories (name,icon) VALUES ('Beef','fa-bone'),('Chicken','fa-drumstick-bite'),('Goat & Lamb','fa-paw'),('Fish','fa-fish'),('Processed','fa-bacon');
INSERT INTO shops (owner_name,shop_name,slug,phone,email,password,address,city,tagline,rating) VALUES
('Demo','Barakah Meats','barakah-meats','08011111111','barakah@example.com','$2y$10$abcdefghijklmnopqrstuuJ3Wq3n3v1b1kQw5v1d0vG1h9yq0aE2a','12 Opebi Road, Ikeja','Lagos','Premium halal cuts, delivered fresh',4.9),
('Demo','Prime Cuts Lagos','prime-cuts','08022222222','prime@example.com','x','5 Admiralty Way, Lekki','Lagos','Butcher-grade beef and goat',4.8),
('Demo','Fresh Market Hub','fresh-market-hub','08033333333','hub@example.com','x','8 Allen Avenue, Ikeja','Lagos','Own production. Verified quality.',4.7),
('Demo','Oga Butcher','oga-butcher','08044444444','oga@example.com','x','22 Bode Thomas, Surulere','Lagos','Your neighbourhood butcher',4.6);
INSERT INTO products (shop_id,category_id,name,description,price_per_kg,is_top) SELECT s.id,1,'Prime Beef Ribeye','Marbled, tender, grill-ready',9500,1 FROM shops s;
INSERT INTO products (shop_id,category_id,name,description,price_per_kg,is_top) SELECT s.id,1,'Freshly Cut Raw Beef','Cleaned and portioned',6500,1 FROM shops s;
INSERT INTO products (shop_id,category_id,name,description,price_per_kg,is_top) SELECT s.id,2,'Whole Chicken','Farm fresh, cleaned',4800,1 FROM shops s;
INSERT INTO products (shop_id,category_id,name,description,price_per_kg,is_top) SELECT s.id,3,'Goat Meat','Bone-in, soft and sweet',8200,1 FROM shops s;
INSERT INTO products (shop_id,category_id,name,description,price_per_kg) SELECT s.id,4,'Catfish','Live-fresh, cleaned',5500 FROM shops s;
INSERT INTO products (shop_id,category_id,name,description,price_per_kg) SELECT s.id,5,'Smoked Bacon','Hickory smoked',7800 FROM shops s;


--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `access` int(11) NOT NULL DEFAULT 0,
  `user_type` varchar(200) DEFAULT NULL,
  `state_id` varchar(50) DEFAULT NULL,
  `lga_id` int(1) NOT NULL,
  `flag` varchar(20) DEFAULT NULL,
  `trongate_user_id` int(11) NOT NULL,
  `pw_token` varchar(200) DEFAULT NULL,
  `code` varchar(100) NOT NULL,
  `expires_at` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `last_active` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_login` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`id`, `name`, `phone`, `email`, `password`, `access`, `user_type`, `state_id`, `lga_id`, `flag`, `trongate_user_id`, `pw_token`, `code`, `expires_at`, `status`, `last_active`, `last_login`, `date_created`, `date_updated`) VALUES
(1, 'Mutiu', NULL, 'methyl2007@gmail.com', '$2y$11$wBOysoT/tqZAHJ9utg6drunIMIEnrDeH79sJdrHtEKg45bmQbj8LW', 1, 'admin', NULL, 0, NULL, 1, NULL, '', NULL, 1, '2026-10-16 23:20:27', '2026-10-16 23:20:27', '2026-07-07 17:22:12', '2026-10-16 23:20:27'),
(2, 'Yahya Muhammed', '08188373898', 'muhammed@yahoo.com', '$2y$11$wBOysoT/tqZAHJ9utg6drunIMIEnrDeH79sJdrHtEKg45bmQbj8LW', 2, 'founder', NULL, 0, NULL, 2, NULL, '', NULL, 1, '2026-10-16 23:20:58', '2026-10-16 23:20:58', '2026-09-18 14:53:11', '2026-10-16 23:20:58'),
(3, 'Dayo', '08188373898', 'methyl2008@gmail.com', '$2y$11$wBOysoT/tqZAHJ9utg6drunIMIEnrDeH79sJdrHtEKg45bmQbj8LW', 3, 'national team', NULL, 0, NULL, 3, NULL, '', NULL, 1, '2026-10-16 23:21:07', '2026-10-16 23:21:07', '2026-07-07 17:22:12', '2026-10-16 23:21:07'),
(4, 'Olakunle', '08188373898', 'methyl2008@yahoo.com', '$2y$11$wBOysoT/tqZAHJ9utg6drunIMIEnrDeH79sJdrHtEKg45bmQbj8LW', 4, 'manager', '1', 0, NULL, 4, NULL, 'QyC2gFEe', NULL, 1, '2026-10-16 23:21:22', '2026-10-16 23:21:22', '2026-07-07 17:22:12', '2026-10-16 23:21:22'),
(5, 'Dele', '08188373898', 'methyl2007@yahoo.com', '$2y$11$wBOysoT/tqZAHJ9utg6drunIMIEnrDeH79sJdrHtEKg45bmQbj8LW', 5, 'coordinator', '1', 1, NULL, 5, NULL, 'NBa9yAcg', NULL, 1, '2026-10-16 23:19:20', '2026-10-16 23:19:20', '2026-07-07 17:22:12', '2026-10-16 23:19:20'),
(6, 'Adesuyi Abimbola', '08188343898', 'methyl2009@yahoo.com', '$2y$11$wBOysoT/tqZAHJ9utg6drunIMIEnrDeH79sJdrHtEKg45bmQbj8LW', 6, 'agent', '1', 1, NULL, 6, NULL, '', NULL, 1, '2026-10-16 23:21:33', '2026-10-16 23:21:33', '2026-07-07 17:40:11', '2026-10-16 23:21:33'),
(16, 'Kunle Adeyemo', '08188373898', 'kunle@yahoo.com', '$2y$11$wBOysoT/tqZAHJ9utg6drunIMIEnrDeH79sJdrHtEKg45bmQbj8LW', 6, 'agent', '1', 2, NULL, 120, NULL, '', NULL, 1, '2026-10-16 23:21:42', '2026-10-16 23:21:42', '2026-10-13 19:53:37', '2026-10-16 23:21:42'),
(17, 'Fagbenro Ayeni', '08147873474', 'fagbenro@yahoo.com', '$2y$11$wBOysoT/tqZAHJ9utg6drunIMIEnrDeH79sJdrHtEKg45bmQbj8LW', 5, 'coordinator', '2', 4, NULL, 128, NULL, '', NULL, 1, '2026-10-16 23:21:50', '2026-10-16 23:21:50', '2026-10-15 02:18:25', '2026-10-16 23:21:50');

-- --------------------------------------------------------

--
-- Table structure for table `contact_logs`
--

CREATE TABLE `contact_logs` (
  `id` int(11) NOT NULL,
  `lead_id` int(11) DEFAULT NULL,
  `agent_id` int(11) DEFAULT NULL,
  `outcome` varchar(60) DEFAULT NULL,
  `reason` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_logs`
--

INSERT INTO `contact_logs` (`id`, `lead_id`, `agent_id`, `outcome`, `reason`, `created_at`) VALUES
(1, 2, 6, '  Interested', NULL, '2026-09-15 16:31:31'),
(2, 1, 6, '  Interested', NULL, '2026-09-16 07:37:35'),
(10, 15, 6, '  Interested', NULL, '2026-09-17 17:12:44'),
(11, 16, 6, '  Interested', NULL, '2026-09-18 16:36:57'),
(12, NULL, 6, ' Not Interested', 'No funds or financia', '2026-09-19 00:48:19'),
(13, 18, 6, '  Interested', NULL, '2026-09-19 00:52:12'),
(14, 21, 6, '  Interested', NULL, '2026-09-20 08:50:54'),
(32, 27, 6, ' Not Interested', 'Already has a saving', '2026-09-22 00:16:35'),
(33, 31, 6, '  Interested', NULL, '2026-09-22 01:03:15'),
(34, 31, 6, '  Interested', NULL, '2026-09-22 01:07:34'),
(35, 31, 6, '  Interested', NULL, '2026-09-22 01:12:25'),
(36, 30, 6, '  Interested', NULL, '2026-09-22 01:31:29'),
(37, 29, 6, '  Interested', NULL, '2026-09-22 01:40:13'),
(38, 28, 6, '  Interested', NULL, '2026-09-30 23:22:27'),
(39, 45, 6, '  Interested', NULL, '2026-10-04 23:07:26'),
(40, 32, 6, '  Interested', NULL, '2026-10-04 23:17:44'),
(41, 46, 6, ' Not Interested', 'Wrong contact or unr', '2026-10-05 18:28:09');

-- --------------------------------------------------------

--
-- Table structure for table `contributions`
--

CREATE TABLE `contributions` (
  `id` int(11) NOT NULL,
  `lead_id` int(11) DEFAULT NULL,
  `amount` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `earnings`
--

CREATE TABLE `earnings` (
  `id` int(11) NOT NULL,
  `agent_id` int(11) DEFAULT NULL,
  `basic_salary` int(11) DEFAULT 0,
  `commission_per_group_onboarded` varchar(20) DEFAULT NULL,
  `total_onboarded` varchar(20) DEFAULT NULL,
  `total_salary` varchar(20) DEFAULT NULL,
  `month` varchar(20) DEFAULT NULL,
  `year` varchar(20) DEFAULT NULL,
  `bonus` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `earnings`
--

INSERT INTO `earnings` (`id`, `agent_id`, `basic_salary`, `commission_per_group_onboarded`, `total_onboarded`, `total_salary`, `month`, `year`, `bonus`, `created_at`, `updated_at`) VALUES
(1, 6, 30000, '5000', '4', '50000', '10', '2026', 0, '2026-09-15 16:23:12', '2026-10-04 23:33:37'),
(5, 16, 50000, '1500', NULL, NULL, NULL, NULL, 0, '2026-10-13 19:53:37', '2026-10-13 19:53:37');

-- --------------------------------------------------------

--
-- Table structure for table `field_visits`
--

CREATE TABLE `field_visits` (
  `id` int(11) NOT NULL,
  `lead_id` int(11) DEFAULT NULL,
  `agent_id` int(11) DEFAULT NULL,
  `lga_id` int(11) DEFAULT NULL,
  `visit_purpose` varchar(100) NOT NULL,
  `visit_outcome` varchar(100) NOT NULL,
  `latitude` decimal(10,7) NOT NULL,
  `longitude` decimal(10,7) NOT NULL,
  `note` text NOT NULL,
  `visit_status` enum('visited','not_found','rescheduled') NOT NULL,
  `visit_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `visit_time` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `field_visits`
--

INSERT INTO `field_visits` (`id`, `lead_id`, `agent_id`, `lga_id`, `visit_purpose`, `visit_outcome`, `latitude`, `longitude`, `note`, `visit_status`, `visit_date`, `visit_time`, `created_at`) VALUES
(1, 2, 6, 1, 'for onboarding', 'onboarding went well', 0.0000000, 0.0000000, 'nice', 'visited', '2026-09-29 02:05:11', '4:30', '2026-09-29 02:05:11');

-- --------------------------------------------------------

--
-- Table structure for table `invited_links`
--

CREATE TABLE `invited_links` (
  `id` int(11) NOT NULL,
  `link` varchar(100) DEFAULT NULL,
  `lead_id` int(11) DEFAULT NULL,
  `agent_id` int(11) DEFAULT NULL,
  `send_via` varchar(50) DEFAULT NULL,
  `meeting_type` varchar(50) NOT NULL,
  `meeting_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `meeting_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `meeting_location` varchar(100) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invited_links`
--

INSERT INTO `invited_links` (`id`, `link`, `lead_id`, `agent_id`, `send_via`, `meeting_type`, `meeting_time`, `meeting_date`, `meeting_location`, `message`, `created_at`) VALUES
(1, '', 2, 6, NULL, 'physical', '0000-00-00 00:00:00', '2026-09-14 23:00:00', 'Sabon Gari', 'Hello Fatimah Ibrahim I\'d like to meet with you and Mutiu Adepoju to walk through how KoloSquare can support your group\'s savings goals. Looking forward to seeing you.\n                                                        ', '2026-09-15 16:50:37'),
(2, '', 1, 6, NULL, 'physical', '0000-00-00 00:00:00', '2026-09-15 23:00:00', 'Kano Municipal', 'Hello Fatimah Ibrahim I\'d like to meet with you and Sabon Gari Women Cooperative to walk through how KoloSquare can support your group\'s savings goals. Looking forward to seeing you.\n                                                        ', '2026-09-16 07:38:39'),
(4, '', 15, 6, NULL, 'physical', '0000-00-00 00:00:00', '2026-09-16 23:00:00', 'Group leader house', 'Hello Sule Gambi I\'d like to meet with you and Teachers Trade Union to walk through how KoloSquare can support your group\'s savings goals. Looking forward to seeing you.\n                                                        ', '2026-09-17 17:15:35'),
(5, '', 16, 6, NULL, 'physical', '0000-00-00 00:00:00', '2026-09-17 23:00:00', 'Kasali house', 'Hello Kasali Danladi I\'d like to meet with you and Kasali Danladi to walk through how KoloSquare can support your group\'s savings goals. Looking forward to seeing you.\n                                                        ', '2026-09-18 16:40:58'),
(6, '', 18, 6, NULL, 'physical', '0000-00-00 00:00:00', '2026-09-18 23:00:00', 'Group Leaders House', 'Hello Fatai Sheu I\'d like to meet with you and Farmers Cooperative to walk through how KoloSquare can support your group\'s savings goals. Looking forward to seeing you.\n                                                        ', '2026-09-19 01:20:48'),
(7, 'https://google.meet.com/vur-luv-you', 21, 6, 'whatsapp', 'virtual', '0000-00-00 00:00:00', '2026-09-26 23:00:00', NULL, 'Hello Miss Margret I\'d like to meet with you and Our Ladies Cooperative to walk through how KoloSquare can support your group\'s savings goals. Looking forward to seeing you.\n                                                        ', '2026-09-20 17:22:11'),
(8, 'https://google.meet.com/vur-luv-you', 31, 6, 'whatsapp', 'virtual', '0000-00-00 00:00:00', '2026-09-21 23:00:00', NULL, 'Hello Ben Amadi I\'d like to meet with you and Miner Cooperative to walk through how KoloSquare can support your group\'s savings goals. Looking forward to seeing you.\n                                                        ', '2026-09-22 01:13:57'),
(9, NULL, 30, 6, NULL, 'physical', '0000-00-00 00:00:00', '2026-09-21 23:00:00', 'Group Leader\'s House', 'Hello Oyadele Fashakin I\'d like to meet with you and Union Of Public Worker to walk through how KoloSquare can support your group\'s savings goals. Looking forward to seeing you.\n                                                        ', '2026-09-22 01:32:28'),
(10, NULL, 29, 6, NULL, 'physical', '0000-00-00 00:00:00', '2026-09-21 23:00:00', 'Mr Bayo\'s House', 'Hello Mr Bayo Onanuga I\'d like to meet with you and NUT Cooperative to walk through how KoloSquare can support your group\'s savings goals. Looking forward to seeing you.\n                                                        ', '2026-09-22 01:41:19'),
(11, NULL, 28, 6, NULL, 'physical', '0000-00-00 00:00:00', '2026-09-30 23:00:00', 'Leader house', 'Hello Mrs Adebola Jayeola I\'d like to meet with you and Our Ladies Cooperative to walk through how KoloSquare can support your group\'s savings goals. Looking forward to seeing you.\n                                                        ', '2026-09-30 23:22:58'),
(12, NULL, 45, 6, NULL, 'physical', '0000-00-00 00:00:00', '2026-10-04 23:00:00', 'Leader House', 'Hello Danladi Mohammed I\'d like to meet with you and Association Of Rural Farmer to walk through how KoloSquare can support your group\'s savings goals. Looking forward to seeing you.\n                                                        ', '2026-10-04 23:10:09'),
(13, NULL, 45, 6, NULL, 'physical', '0000-00-00 00:00:00', '2026-10-05 23:00:00', 'Leader House', 'Hello Danladi Mohammed I\'d like to meet with you and Association Of Rural Farmer to walk through how KoloSquare can support your group\'s savings goals. Looking forward to seeing you.\n                                                        ', '2026-10-04 23:10:22'),
(14, NULL, 45, 6, NULL, 'physical', '0000-00-00 00:00:00', '2026-10-05 23:00:00', 'Leader House', 'Hello Danladi Mohammed I\'d like to meet with you and Association Of Rural Farmer to walk through how KoloSquare can support your group\'s savings goals. Looking forward to seeing you.\n                                                        ', '2026-10-04 23:10:45'),
(15, NULL, 32, 6, NULL, 'physical', '0000-00-00 00:00:00', '2026-10-05 23:00:00', 'Leaders House', 'Hello Dele Agbede I\'d like to meet with you and Welder Cooperative to walk through how KoloSquare can support your group\'s savings goals. Looking forward to seeing you.\n                                                        ', '2026-10-04 23:32:36');

-- --------------------------------------------------------

--
-- Table structure for table `invite_logs`
--

CREATE TABLE `invite_logs` (
  `id` int(11) NOT NULL,
  `link_id` int(11) DEFAULT NULL,
  `lead_id` int(11) DEFAULT NULL,
  `agent_id` int(11) DEFAULT NULL,
  `action` enum('sent','not sent') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leads`
--

CREATE TABLE `leads` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `leader_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `whatsapp` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `group_type` varchar(20) NOT NULL,
  `location` varchar(50) NOT NULL,
  `lead_task_id` varchar(20) NOT NULL,
  `latitude` decimal(10,7) NOT NULL,
  `longitude` decimal(10,7) NOT NULL,
  `location_verified` tinyint(1) DEFAULT 0,
  `stage_id` enum('1','2','3','4','5','6') DEFAULT '1',
  `stage_name` varchar(60) DEFAULT NULL,
  `agent_id` int(11) DEFAULT NULL,
  `state_id` int(11) DEFAULT NULL,
  `lga_id` int(11) DEFAULT NULL,
  `onboarding_status` varchar(50) DEFAULT NULL,
  `reason_not_onboarding` varchar(200) DEFAULT NULL,
  `enrolled_members` varchar(20) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_action_at` timestamp NULL DEFAULT NULL,
  `contacted_at` timestamp NULL DEFAULT NULL,
  `last_visit_at` timestamp NULL DEFAULT NULL,
  `meeting_at` timestamp NULL DEFAULT NULL,
  `demo_done_at` timestamp NULL DEFAULT NULL,
  `onboarding_at` timestamp NULL DEFAULT NULL,
  `retention_at` timestamp NULL DEFAULT NULL,
  `member_joined_at` timestamp NULL DEFAULT NULL,
  `first_contribution_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `reason_mark_as_lost` text NOT NULL,
  `active_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leads`
--

INSERT INTO `leads` (`id`, `name`, `leader_name`, `address`, `phone`, `whatsapp`, `email`, `group_type`, `location`, `lead_task_id`, `latitude`, `longitude`, `location_verified`, `stage_id`, `stage_name`, `agent_id`, `state_id`, `lga_id`, `onboarding_status`, `reason_not_onboarding`, `enrolled_members`, `note`, `created_at`, `last_action_at`, `contacted_at`, `last_visit_at`, `meeting_at`, `demo_done_at`, `onboarding_at`, `retention_at`, `member_joined_at`, `first_contribution_at`, `completed_at`, `status`, `reason_mark_as_lost`, `active_at`, `updated_at`) VALUES
(1, 'Sabon Gari Women Cooperative', 'Fatimah Ibrahim', '', '+2348188373898', '+2348188373898', '', 'cooperative', 'Kano Municipal, Sabon Gari', 'L-3629', 0.0000000, 0.0000000, 0, '6', 'completed', 6, 1, 1, 'Onboarded', NULL, '11', '', '2026-09-15 15:06:27', '2026-09-16 08:39:41', '2026-09-16 08:37:35', NULL, '2026-09-16 08:38:39', '2026-09-16 08:39:04', '2026-09-16 08:39:22', NULL, NULL, NULL, '2026-09-16 08:39:41', NULL, '', NULL, '2026-10-02 19:16:51'),
(2, 'Mutiu Adepoju', 'Fatimah Ibrahim', '', '+2348188373894', '+2348188373898', '', 'cooperative', 'Kano Municipal, Sabon Gari', 'L-7193', 0.0000000, 0.0000000, 0, '6', 'completed', 6, 1, 1, 'Onboarded', NULL, '11', '', '2026-09-15 15:16:24', '2026-09-15 18:30:24', '2026-09-15 17:31:31', NULL, '2026-09-15 17:50:38', '2026-09-15 17:58:50', '2026-09-15 18:08:48', '2026-09-15 18:15:06', '2026-09-01 17:36:33', '2026-09-15 18:15:06', '2026-09-15 09:15:00', NULL, '', NULL, '2026-10-02 19:16:58'),
(15, 'Teachers Trade Union', 'Sule Gambi', '', '+2349036561101', '+2348188373898', '', 'trade union', 'Kano Municipal, Sabon Gari', 'L-3887', 0.0000000, 0.0000000, 0, '6', 'completed', 6, 1, 1, 'Onboarded', NULL, '11', '', '2026-09-17 16:37:20', '2026-09-17 18:21:54', '2026-09-17 18:12:44', NULL, '2026-09-17 18:15:35', '2026-09-17 18:18:52', '2026-09-17 18:19:21', NULL, '2026-09-22 23:17:18', '2026-09-22 23:34:03', '2026-09-17 18:21:54', NULL, '', NULL, '2026-10-02 19:17:04'),
(16, 'Kasali Danladi', 'Kasali Danladi', '', '+2349036561102', '+2348188373898', '', 'market', 'Kano Municipal, Sabon Gari', 'L-5087', 0.0000000, 0.0000000, 0, '6', 'completed', 6, 1, 1, 'Onboarded', NULL, '11', '', '2026-09-18 16:35:32', '2026-09-19 00:08:57', '2026-09-18 17:36:57', NULL, '2026-09-18 17:40:58', '2026-09-18 17:43:01', '2026-09-18 17:47:15', NULL, NULL, NULL, '2026-09-19 00:08:57', NULL, '', NULL, '2026-10-02 19:17:13'),
(18, 'Farmers Cooperative', 'Fatai Sheu', '', '+2349036561105', '+2348188373898', '', 'cooperative', 'Aderingbe Street Abeokuta Road Ogun', 'L-4138', 0.0000000, 0.0000000, 0, '6', 'completed', 6, 1, 1, 'Onboarded', NULL, '11', '', '2026-09-19 00:52:00', '2026-09-19 03:30:12', '2026-09-19 01:52:12', NULL, '2026-09-19 02:20:48', '2026-09-19 03:06:02', '2026-09-19 03:29:07', NULL, NULL, NULL, '2026-09-18 23:30:02', NULL, '', NULL, '2026-10-02 19:17:32'),
(21, 'Our Ladies Cooperative', 'Miss Margret', '', '+2348188373877', '+2348188373898', '', 'cooperative', 'Adeola Odeku Street Lekki Lagos', 'L-3548', 0.0000000, 0.0000000, 0, '6', 'completed', 6, 1, 1, 'Onboarded', NULL, '11', NULL, '2026-09-19 10:53:40', '2026-09-20 18:22:11', '2026-09-20 09:50:54', NULL, '2026-09-20 18:22:11', '2026-10-05 00:42:12', '2026-10-05 00:42:19', NULL, NULL, NULL, '2026-10-05 00:43:38', 'lost', 'Leader of the group is not showing interest anymore.', NULL, '2026-10-05 00:43:41'),
(27, 'Teachers Trade Union', 'Mr Glady Okaka', '', '+2349036591109', '+2348188373898', '', 'trade union', 'Adeola Odeku Street Lekki Lagos', 'L-3567', 0.0000000, 0.0000000, 0, '6', 'completed', 6, 1, 1, 'Onboarded', NULL, '11', NULL, '2026-09-22 00:09:30', '2026-09-22 01:16:35', '2026-09-22 01:16:35', NULL, '2026-10-05 00:42:24', '2026-10-05 00:42:30', '2026-10-05 00:42:43', NULL, NULL, NULL, '2026-10-05 00:43:51', 'lost', 'Already has a saving system', NULL, '2026-10-05 00:43:55'),
(28, 'Our Ladies Cooperative', 'Mrs Adebola Jayeola', '', '+2348147373874', '+2348188373898', '', 'cooperative', 'Bayo Street Lekki Lagos', 'L-9432', 0.0000000, 0.0000000, 0, '6', 'completed', 6, 1, 1, 'Onboarded', NULL, '11', '', '2026-09-22 00:18:17', '2026-10-01 00:31:31', '2026-10-01 00:22:27', NULL, '2026-10-01 00:22:58', '2026-10-01 00:23:16', '2026-10-01 00:23:36', NULL, NULL, NULL, '2026-10-01 00:31:31', NULL, '', NULL, '2026-10-02 19:15:47'),
(29, 'NUT Cooperative', 'Mr Bayo Onanuga', '', '+2349036562101', '+2348188373898', '', 'cooperative', 'Trade Complex Lagos', 'L-8217', 0.0000000, 0.0000000, 0, '6', 'completed', 6, 1, 1, ' Onboarding', 'member not present at the meeting', '11', '', '2026-09-22 00:19:56', '2026-09-22 04:00:03', '2026-09-22 02:40:13', NULL, '2026-09-22 02:41:19', '2026-09-22 03:44:13', '2026-09-22 04:00:03', NULL, NULL, NULL, '2026-10-05 00:43:56', NULL, '', NULL, '2026-10-05 00:44:01'),
(30, 'Union Of Public Worker', 'Oyadele Fashakin', '', '+2348036589101', '+2348188373898', '', 'trade union', 'Ogundimu Street Ikeja Lagos', 'L-2266', 0.0000000, 0.0000000, 0, '6', 'completed', 6, 1, 1, 'Onboarded', NULL, '11', '', '2026-09-22 00:25:40', '2026-09-22 02:32:45', '2026-09-22 02:31:29', NULL, '2026-09-22 02:32:28', '2026-09-22 02:32:45', '2026-10-05 00:36:10', NULL, NULL, NULL, '2026-10-05 00:36:38', NULL, '', NULL, '2026-10-05 00:36:41'),
(31, 'Miner Cooperative', 'Ben Amadi', '', '+234900011101', '+2348188373898', '', 'cooperative', 'Garki Abuja', 'L-2603', 0.0000000, 0.0000000, 0, '6', 'completed', 6, 1, 1, 'Onboarded', 'wants to rechedule', '11', '', '2026-09-22 00:38:28', '2026-10-01 00:32:33', '2026-09-22 02:12:24', NULL, '2026-09-22 02:13:57', '2026-09-22 02:28:37', '2026-09-22 03:43:39', NULL, NULL, NULL, '2026-10-01 00:32:33', NULL, '', NULL, '2026-10-02 19:14:34'),
(32, 'Welder Cooperative', 'Dele Agbede', '', '+2349024561101', '+2348188373898', '', 'cooperative', 'Adeola Odeku Street Lekki Lagos', 'L-5272', 0.0000000, 0.0000000, 0, '6', 'completed', 6, 1, 1, 'Onboarded', NULL, '11', '', '2026-10-02 11:43:40', '2026-10-02 12:43:40', '2026-10-04 23:17:44', NULL, '2026-10-05 00:42:58', '2026-10-05 00:43:03', '2026-10-05 00:43:09', NULL, NULL, NULL, '2026-10-05 00:44:05', NULL, '', NULL, '2026-10-05 00:44:09'),
(45, 'Association Of Rural Farmer', 'Danladi Mohammed', '', '+2349036599101', '+2348188373898', '', 'cooperative', 'Dairu Street Kastina State', 'L-2273', 0.0000000, 0.0000000, 0, '6', 'completed', 6, 1, 1, 'Onboarded', NULL, '11', NULL, '2026-10-04 22:55:30', '2026-10-04 23:55:30', '2026-10-04 23:07:26', NULL, '2026-10-04 23:10:45', '2026-10-05 00:43:14', '2026-10-05 00:43:25', NULL, NULL, NULL, '2026-10-05 00:44:10', NULL, '', NULL, '2026-10-05 08:12:10'),
(46, 'Association Of Small Ruminant Women', 'Mr Salisu Gbadamosi', '', '+2348147873422', '+2348188373898', '', 'cooperative', 'Darki Way Wuse 2', 'L-4698', 0.0000000, 0.0000000, 0, '2', 'contacted', 6, 1, 1, NULL, NULL, NULL, NULL, '2026-10-05 18:25:34', '2026-10-05 19:28:09', '2026-10-05 19:28:09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'lost', 'Wrong contact or unreachable', NULL, '2026-10-05 18:28:09');

-- --------------------------------------------------------

--
-- Table structure for table `lead_funnel`
--

CREATE TABLE `lead_funnel` (
  `id` int(11) NOT NULL,
  `lead_id` int(11) DEFAULT NULL,
  `from_stage` int(11) DEFAULT 0,
  `to_stage` int(11) DEFAULT 0,
  `agent_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lead_funnel`
--

INSERT INTO `lead_funnel` (`id`, `lead_id`, `from_stage`, `to_stage`, `agent_id`, `created_at`) VALUES
(1, 2, 1, 2, 6, '2026-09-15 16:31:31'),
(2, 2, 2, 3, 6, '2026-09-15 16:50:38'),
(3, 2, 3, 4, 6, '2026-09-15 16:58:50'),
(4, 2, 4, 5, 6, '2026-09-15 17:08:48'),
(5, 2, 5, 6, 6, '2026-09-15 17:30:24'),
(6, 1, 1, 2, 6, '2026-09-16 07:37:35'),
(7, 1, 2, 3, 6, '2026-09-16 07:38:39'),
(8, 1, 3, 4, 6, '2026-09-16 07:39:04'),
(9, 1, 4, 5, 5, '2026-09-16 07:39:22'),
(10, 1, 5, 6, 6, '2026-09-16 07:39:41'),
(25, 15, 1, 2, 6, '2026-09-17 17:12:44'),
(26, 15, 2, 3, 6, '2026-09-17 17:15:35'),
(27, 15, 3, 4, 6, '2026-09-17 17:18:52'),
(28, 15, 4, 5, 6, '2026-09-17 17:19:21'),
(29, 15, 5, 6, 6, '2026-09-17 17:21:54'),
(30, 16, 1, 2, 6, '2026-09-18 16:36:57'),
(31, 16, 2, 3, 6, '2026-09-18 16:40:58'),
(32, 16, 3, 4, 6, '2026-09-18 16:43:01'),
(33, 16, 4, 5, 6, '2026-09-18 16:47:15'),
(34, 16, 5, 6, 6, '2026-09-18 23:08:57'),
(35, 18, 1, 2, 6, '2026-09-19 00:52:12'),
(36, 18, 2, 3, 6, '2026-09-19 01:20:48'),
(37, 18, 3, 4, 6, '2026-09-19 02:06:02'),
(38, 18, 4, 5, 6, '2026-09-19 02:29:07'),
(39, 18, 5, 6, 6, '2026-09-19 02:30:12'),
(40, 21, 1, 2, 6, '2026-09-20 08:50:54'),
(41, 21, 2, 3, 6, '2026-09-20 17:22:11'),
(42, 31, 1, 2, 6, '2026-09-22 01:12:25'),
(43, 31, 2, 3, 6, '2026-09-22 01:13:57'),
(44, 31, 3, 4, 6, '2026-09-22 01:28:37'),
(45, 30, 1, 2, 6, '2026-09-22 01:31:29'),
(46, 30, 2, 3, 6, '2026-09-22 01:32:28'),
(47, 30, 3, 4, 6, '2026-09-22 01:32:45'),
(48, 29, 1, 2, 6, '2026-09-22 01:40:13'),
(49, 29, 2, 3, 6, '2026-09-22 01:41:19'),
(50, 31, 4, 5, 6, '2026-09-22 02:43:39'),
(51, 29, 3, 4, 6, '2026-09-22 02:44:13'),
(52, 29, 4, 5, 6, '2026-09-22 03:00:03'),
(53, 28, 1, 2, 6, '2026-09-30 23:22:27'),
(54, 28, 2, 3, 6, '2026-09-30 23:22:58'),
(55, 28, 3, 4, 6, '2026-09-30 23:23:16'),
(56, 28, 4, 5, 6, '2026-09-30 23:23:36'),
(57, 28, 5, 6, 6, '2026-09-30 23:31:31'),
(58, 31, 5, 6, 6, '2026-09-30 23:32:33'),
(59, 32, 2, 3, 6, '2026-10-04 23:32:36'),
(60, 32, 3, 4, 6, '2026-10-04 23:32:55'),
(61, 32, 4, 5, 6, '2026-10-04 23:33:26'),
(62, 32, 5, 6, 6, '2026-10-04 23:33:37');

-- --------------------------------------------------------

--
-- Table structure for table `lead_stage_scripts`
--

CREATE TABLE `lead_stage_scripts` (
  `id` int(11) NOT NULL,
  `lead_id` int(11) NOT NULL,
  `stage_id` tinyint(4) NOT NULL,
  `script_id` int(11) NOT NULL,
  `used_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lead_stage_scripts`
--

INSERT INTO `lead_stage_scripts` (`id`, `lead_id`, `stage_id`, `script_id`, `used_at`) VALUES
(1, 1, 1, 1, '2026-10-02 20:49:55'),
(2, 1, 1, 2, '2026-10-02 20:49:55'),
(3, 1, 2, 3, '2026-10-02 20:49:55'),
(4, 1, 3, 4, '2026-10-02 20:52:21'),
(5, 1, 4, 5, '2026-10-02 20:52:21'),
(6, 1, 5, 6, '2026-10-02 20:53:06'),
(7, 2, 1, 1, '2026-10-02 20:57:14'),
(8, 2, 1, 2, '2026-10-02 20:57:14'),
(9, 2, 2, 3, '2026-10-02 20:57:14'),
(10, 2, 3, 4, '2026-10-02 21:00:38'),
(11, 2, 4, 5, '2026-10-02 21:00:38'),
(12, 2, 5, 6, '2026-10-02 21:03:20'),
(13, 15, 1, 1, '2026-10-02 21:06:07'),
(14, 15, 1, 2, '2026-10-02 21:06:07'),
(15, 16, 2, 3, '2026-10-02 21:06:07'),
(16, 15, 3, 4, '2026-10-02 21:07:50'),
(17, 15, 4, 5, '2026-10-02 21:07:50'),
(18, 15, 5, 6, '2026-10-02 21:08:46'),
(19, 16, 1, 1, '2026-10-02 21:10:25'),
(20, 16, 1, 2, '2026-10-02 21:10:25'),
(21, 16, 2, 3, '2026-10-02 21:10:25'),
(22, 16, 3, 4, '2026-10-02 21:12:30'),
(23, 16, 4, 5, '2026-10-02 21:12:30'),
(24, 16, 5, 6, '2026-10-02 21:13:09'),
(25, 18, 1, 1, '2026-10-02 21:14:47'),
(26, 18, 1, 2, '2026-10-02 21:14:47'),
(27, 18, 2, 3, '2026-10-02 21:14:47'),
(28, 18, 3, 4, '2026-10-02 21:16:09'),
(29, 18, 4, 5, '2026-10-02 21:16:09'),
(30, 18, 5, 6, '2026-10-02 21:16:52'),
(31, 21, 1, 1, '2026-10-02 21:20:35'),
(32, 21, 1, 2, '2026-10-02 21:20:35'),
(33, 21, 2, 3, '2026-10-02 21:20:35'),
(34, 27, 1, 1, '2026-10-02 21:21:02'),
(35, 27, 1, 2, '2026-10-02 21:21:02'),
(36, 28, 1, 1, '2026-10-02 21:25:58'),
(37, 28, 1, 2, '2026-10-02 21:25:58'),
(38, 28, 2, 3, '2026-10-02 21:25:58'),
(39, 28, 3, 4, '2026-10-02 21:27:31'),
(40, 28, 4, 5, '2026-10-02 21:27:31'),
(41, 28, 5, 6, '2026-10-02 21:28:01'),
(42, 29, 1, 1, '2026-10-02 21:31:02'),
(43, 29, 1, 2, '2026-10-02 21:31:02'),
(44, 29, 2, 3, '2026-10-02 21:31:02'),
(45, 29, 3, 4, '2026-10-02 21:32:08'),
(46, 29, 4, 5, '2026-10-02 21:32:08'),
(47, 30, 1, 1, '2026-10-02 21:33:37'),
(48, 30, 1, 2, '2026-10-02 21:33:37'),
(49, 30, 2, 3, '2026-10-02 21:33:37'),
(50, 30, 3, 4, '2026-10-02 21:34:32'),
(51, 31, 1, 1, '2026-10-02 21:36:48'),
(52, 31, 1, 2, '2026-10-02 21:36:48'),
(53, 31, 2, 3, '2026-10-02 21:36:48'),
(54, 31, 3, 4, '2026-10-02 21:37:54'),
(55, 31, 4, 5, '2026-10-02 21:37:54'),
(56, 31, 5, 6, '2026-10-02 21:38:24'),
(57, 32, 1, 1, '2026-10-02 21:38:24'),
(202, 45, 1, 3, '2026-10-04 22:55:30'),
(203, 30, 4, 5, '2026-10-04 23:05:24'),
(204, 30, 5, 6, '2026-10-04 23:05:45'),
(205, 45, 1, 2, '2026-10-04 23:07:26'),
(206, 45, 2, 3, '2026-10-04 23:10:09'),
(207, 45, 3, 3, '2026-10-04 23:10:22'),
(208, 45, 4, 3, '2026-10-04 23:10:45'),
(209, 32, 1, 2, '2026-10-04 23:17:44'),
(210, 32, 2, 3, '2026-10-04 23:32:36'),
(211, 32, 3, 4, '2026-10-04 23:32:55'),
(212, 32, 4, 5, '2026-10-04 23:33:26'),
(213, 32, 5, 6, '2026-10-04 23:33:37'),
(214, 46, 1, 3, '2026-10-05 18:25:34');

-- --------------------------------------------------------

--
-- Table structure for table `local_govts`
--

CREATE TABLE `local_govts` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `state_id` int(11) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `local_govts`
--

INSERT INTO `local_govts` (`id`, `name`, `state_id`, `date_created`) VALUES
(1, 'Ikeja', 1, '2026-07-07 17:22:12'),
(2, 'Agege', 1, '2026-07-07 17:22:12'),
(3, 'Abeokuta', 2, '2026-07-07 17:22:12'),
(4, 'Ifo', 2, '2026-07-07 17:22:12'),
(5, 'Abuja Municipal', 3, '2026-07-07 17:22:12'),
(6, 'Gwagbalada', 3, '2026-07-07 17:22:12'),
(7, 'Birnin Gwari', 4, '2026-10-12 18:59:28'),
(8, 'Chikun', 4, '2026-10-12 19:15:09');

-- --------------------------------------------------------

--
-- Table structure for table `meeting_logs`
--

CREATE TABLE `meeting_logs` (
  `id` int(11) NOT NULL,
  `lead_id` int(11) DEFAULT NULL,
  `agent_id` int(11) DEFAULT NULL,
  `note` text NOT NULL,
  `purpose` varchar(50) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `demo_members` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meeting_logs`
--

INSERT INTO `meeting_logs` (`id`, `lead_id`, `agent_id`, `note`, `purpose`, `reason`, `demo_members`, `created_at`) VALUES
(1, 2, 6, '', 'Demo done', NULL, '5', '2026-09-15 16:58:49'),
(2, 1, 6, '', 'Demo done', NULL, '7', '2026-09-16 07:39:04'),
(3, 1, 6, '', 'Demo done', NULL, '7', '2026-09-17 11:35:56'),
(4, 15, 6, '', 'Demo done', NULL, '11', '2026-09-17 17:18:52'),
(5, 16, 6, '', 'Demo done', NULL, '11', '2026-09-18 16:43:01'),
(6, 18, 6, '', 'Demo done', NULL, '11', '2026-09-19 02:06:02'),
(7, 31, 6, 'I needs more time', 'Demo not done', 'wants to rechedule', NULL, '2026-09-22 01:23:12'),
(8, 31, 6, 'The five team members ask question and i gave a proper explanation.', 'Demo done', NULL, '5', '2026-09-22 01:28:37'),
(9, 30, 6, '', 'Demo done', NULL, '7', '2026-09-22 01:32:45'),
(10, 29, 6, 'Members not available at the meeting', 'Demo not done', 'member not present at the meeting', NULL, '2026-09-22 01:59:02'),
(11, 29, 6, 'They need more time', 'Demo not done', 'wants to rechedule', NULL, '2026-09-22 02:07:17'),
(12, 29, 6, 'More time is needed', 'Demo not done', 'wants to rechedule', NULL, '2026-09-22 02:09:11'),
(13, 29, 6, 'More time is needed', 'Demo not done', 'wants to rechedule', NULL, '2026-09-22 02:09:25'),
(14, 29, 6, 'more time', 'Demo not done', 'wants to rechedule', NULL, '2026-09-22 02:10:59'),
(15, 29, 6, 'Needs more time', 'Demo not done', 'wants to rechedule', NULL, '2026-09-22 02:14:26'),
(16, 29, 6, '', 'Demo done', NULL, '10', '2026-09-22 02:44:13'),
(17, 28, 6, '', 'Demo done', NULL, '11', '2026-09-30 23:23:16'),
(18, 32, 6, '', 'Demo done', NULL, '11', '2026-10-04 23:32:55');

-- --------------------------------------------------------

--
-- Table structure for table `objection_logs`
--

CREATE TABLE `objection_logs` (
  `id` int(11) NOT NULL,
  `lead_id` int(11) DEFAULT NULL,
  `agent_id` int(11) DEFAULT NULL,
  `from_stage_encountered` int(11) DEFAULT 0,
  `contact_method` varchar(50) DEFAULT NULL,
  `objection_category` varchar(50) NOT NULL,
  `objection_sub_category` varchar(50) NOT NULL,
  `verbatim_notes` text DEFAULT NULL,
  `is_overcome` int(11) DEFAULT 0,
  `playbook_resolution_strategy_id` int(11) DEFAULT NULL,
  `resolution_notes` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `objection_logs`
--

INSERT INTO `objection_logs` (`id`, `lead_id`, `agent_id`, `from_stage_encountered`, `contact_method`, `objection_category`, `objection_sub_category`, `verbatim_notes`, `is_overcome`, `playbook_resolution_strategy_id`, `resolution_notes`, `created_at`, `updated_at`) VALUES
(34, 21, 6, 3, 'sms', 'POST_MEETING_STALL', 'DECISION_LAG', 'Leader of the group is not showing interest anymore.', 0, 5, '', '2026-09-21 11:20:59', '2026-09-21 11:20:59'),
(35, NULL, 6, 1, NULL, 'MANUAL_TERMINATION', 'EXPLICIT_REJECTION', 'Already has a saving system', 0, 1, '', '2026-09-21 17:49:54', '2026-09-21 17:49:54'),
(36, 27, 6, 1, NULL, 'MANUAL_TERMINATION', 'EXPLICIT_REJECTION', 'Already has a saving system', 0, 1, '', '2026-09-22 00:16:35', '2026-09-22 00:16:35'),
(37, 46, 6, 1, NULL, 'MANUAL_TERMINATION', 'EXPLICIT_REJECTION', 'Wrong contact or unreachable', 0, 1, '', '2026-10-05 18:28:09', '2026-10-05 18:28:09');

-- --------------------------------------------------------

--
-- Table structure for table `objection_playbook`
--

CREATE TABLE `objection_playbook` (
  `id` int(11) NOT NULL,
  `valid_funnel_stage` int(11) DEFAULT 0,
  `objection_category` varchar(200) NOT NULL,
  `objection_sub_category` varchar(200) NOT NULL,
  `script_title` text NOT NULL,
  `script_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `objection_playbook`
--

INSERT INTO `objection_playbook` (`id`, `valid_funnel_stage`, `objection_category`, `objection_sub_category`, `script_title`, `script_text`, `created_at`) VALUES
(1, 2, 'MANUAL_TERMINATION', 'EXPLICIT_REJECTION', 'Immediate Rejection Audit & Lost Lead Verification', ' Lead manually flagged as lost. Agent must verify if the account went to a competitor or had budget constraints. Audit the final interaction log for compliance. Log competitor data if applicable and queue for long-term re-evaluation in 180 days.', '2026-07-07 16:22:12'),
(2, 2, 'POST_ADVANCEMENT_STALL', 'VERBAL_INTEREST_GHOST', 'Interested Lead Stagnation & Pipeline Ghosting Review', 'The lead explicitly stated they were interested and was moved forward, but has completely stalled in the new stage. This indicates a high likelihood of a \"polite brush-off\" or an immediate internal hurdle. Agent must review the transition notes. Call the lead directly within 24 hours to re-verify commitment, or drop them back to a nurturing track if no contact is made within 5 business days.', '2026-07-07 16:22:12'),
(3, 2, 'OPERATIONAL_FAILURE_SENERIO', 'VIRTUAL_MEETING_MISSING_LINK', 'Operational Lapse: Missing Virtual Meeting Link', 'System flag: Operational bottleneck detected. A milestone meeting was intended, but no virtual link was dispatched. Internal manager must audit agent activity, reassign the account if necessary, and force-issue a priority meeting link.', '2026-09-18 13:55:23'),
(4, 2, 'POST_MEETING_DEADLOCK SCENERIO', 'PHYSICAL_MEETING_ABORTED', 'Operational Lapse: Missed On-Site Agent Dispatch', 'System flag: Operational bottleneck detected. A milestone meeting was intended, but no physical agent dispatch was logged. Internal manager must audit agent activity, reassign the account if necessary, and force-issue a priority meeting link.', '2026-09-18 13:55:23'),
(5, 3, 'POST_MEETING_STALL', 'DECISION_LAG', 'Meeting was completed successfully, but the lead sits completely stagnant in that post-meeting stage.', 'Meeting was successfully logged, but the account has exceeded the 7-day post-meeting stagnation threshold. System has auto-routed the lead here. Agent must reach out with a direct \"Next Steps\" action item or proposal summary to break the decision inertia.', '2026-09-18 13:55:23'),
(6, 3, 'MILESTONE_FAILURE', 'DEMO_BYPASSED_OR_DEMO_NOT_DONE', 'Product Demo was bypassed or failed to take place (\"Demo Not Done\").', 'Pipeline progression failed due to a missing core milestone. A demo was required but never completed. System blocks further pipeline advancement. Agent must schedule an emergency 15-minute alignment call or upload a recorded product brief to fulfill compliance.', '2026-09-18 13:55:23'),
(7, 4, 'ONBOARDING_GATEWAY', 'IMPLEMENTATION_DELAY', 'Demo completed successfully, but the lead remains paralyzed in the \"Onboarding Begin\" phase.', 'Presentation was successful, but user adoption has stalled at the starting gate. This pattern usually indicates high technical friction or data compliance delays. Trigger a system alert to the Customer Success engineering team to offer a guided setup session.', '2026-09-18 13:55:23'),
(8, 4, 'ONBOARDING_STALL', 'ADOPTION_FAILURE', 'Lead is in the active Onboarding state but fails to cross the finish line to be marked as \"Onboarded\".', 'High-risk churn warning. The client account is mid-onboarding but has stopped completing setup milestones. Extract software utilization logs immediately. Identify the exact configuration page where user activity ceased and initiate a direct intervention call.', '2026-09-18 13:55:23'),
(9, 4, 'POST_ADVANCEMENT_STALL', 'ONBOARDING_REJECTION', 'Lead is in the demo stage, but onboarding was reject\".', 'Pipeline progression failed due to a missing core milestone. An onboarding phase was truncated. Sytem has auto-routed the lead back here. Agent must come of with better strategy to figure out why and how the onboarding must be done.', '2026-09-18 13:55:23');

-- --------------------------------------------------------

--
-- Table structure for table `salaries`
--

CREATE TABLE `salaries` (
  `id` int(11) NOT NULL,
  `agent_id` int(11) DEFAULT NULL,
  `basic_salary` varchar(20) NOT NULL,
  `commission_per_group_onboarded` varchar(20) NOT NULL,
  `total_onboarded` varchar(20) NOT NULL,
  `total_commission` varchar(20) NOT NULL,
  `total_salary` varchar(20) NOT NULL,
  `salary_month` varchar(20) NOT NULL,
  `salary_year` varchar(20) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `salaries`
--

INSERT INTO `salaries` (`id`, `agent_id`, `basic_salary`, `commission_per_group_onboarded`, `total_onboarded`, `total_commission`, `total_salary`, `salary_month`, `salary_year`, `created_date`) VALUES
(10, 6, '30000', '5000', '2', '10000', '40000', '10', '2026', '2026-10-01 15:12:01'),
(11, 6, '30000', '5000', '2', '10000', '40000', '10', '2026', '2026-10-01 15:17:01');

-- --------------------------------------------------------

--
-- Table structure for table `stage_scripts`
--

CREATE TABLE `stage_scripts` (
  `id` int(11) NOT NULL,
  `stage_name` varchar(50) NOT NULL,
  `stage_id` tinyint(4) NOT NULL,
  `title` varchar(150) NOT NULL,
  `body` text NOT NULL,
  `success_rate` decimal(5,2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) DEFAULT 1,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stage_scripts`
--

INSERT INTO `stage_scripts` (`id`, `stage_name`, `stage_id`, `title`, `body`, `success_rate`, `is_active`, `updated_at`) VALUES
(1, 'new lead', 1, 'Warm Opener', 'I am an agent working with KoloSquare. I noticed your group was assigned to me — we help groups like yours access shared savings and group onboarding benefits. Do you have 2 minutes to hear how it works?', 100.00, 1, '2026-10-05 07:38:21'),
(2, 'contacted', 2, 'Fast Follow-up', 'Thanks for speaking with me earlier. I would like to book a short meeting this week so your group can see how the program works. Would Tuesday or Thursday work for you?', 100.00, 1, '2026-10-03 09:53:55'),
(3, 'meeting scheduled', 3, 'Discovery Questions', 'Before we get into the program, tell me about your group — how many members, how do you currently save or contribute, and what has been the biggest challenge so far?', 100.00, 1, '2026-10-05 07:38:21'),
(4, 'demo done', 4, 'Savings-first Demo', 'Open with a real savings example (e.g. \"Groups like yours have saved up to X in 3 months\"), walk through signup, then ask: \"Based on what you have seen, is this something your group would like to start this month?\"', 100.00, 1, '2026-10-03 09:53:55'),
(5, 'onboarding started', 5, 'Same-week Incentive', 'If your group completes signup by this Friday, I can apply our first-month onboarding support at no extra cost. Would that work for your group?', 100.00, 1, '2026-10-03 09:53:55'),
(6, 'completed', 6, 'Referral Ask', 'Welcome aboard! Now that you are fully set up, do you know any neighboring groups who might benefit from the same program? A referral from you carries a lot of weight.', 11.11, 1, '2026-10-05 07:38:21');

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `name`, `date_created`) VALUES
(1, 'Lagos', '2026-07-07 17:22:12'),
(2, 'Ogun', '2026-07-07 17:22:12'),
(3, 'FCT (Abuja)', '2026-07-07 17:22:12'),
(4, 'Kaduna', '2026-10-12 18:59:28');

-- --------------------------------------------------------

--
-- Table structure for table `targets`
--

CREATE TABLE `targets` (
  `id` int(11) NOT NULL,
  `agent_id` int(11) DEFAULT NULL,
  `type` enum('daily','weekly','monthly') NOT NULL,
  `to_onboard` int(11) DEFAULT 0,
  `status` varchar(60) DEFAULT NULL,
  `start_date` timestamp NULL DEFAULT current_timestamp(),
  `end_date` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `targets`
--

INSERT INTO `targets` (`id`, `agent_id`, `type`, `to_onboard`, `status`, `start_date`, `end_date`, `updated_at`) VALUES
(1, 6, 'daily', 1, 'active', '2026-09-09 17:48:42', '2026-09-09 17:49:31', '2026-10-13 17:49:40'),
(2, 6, 'weekly', 5, 'active', '2026-09-09 17:49:42', '2026-10-09 17:49:49', '2026-10-13 17:49:59'),
(3, 6, 'monthly', 20, 'active', '2026-09-09 17:50:00', '2026-09-09 17:50:39', '2026-10-13 17:50:45'),
(21, 16, 'daily', 1, 'active', '2026-10-13 19:53:37', '2026-10-13 19:53:37', '2026-10-13 19:53:37'),
(22, 16, 'weekly', 5, 'active', '2026-10-13 19:53:37', '2026-10-13 19:53:37', '2026-10-13 19:53:37'),
(23, 16, 'monthly', 20, 'active', '2026-10-13 19:53:37', '2026-10-13 19:53:37', '2026-10-13 19:53:37');

-- --------------------------------------------------------

--
-- Table structure for table `trongate_administrators`
--

CREATE TABLE `trongate_administrators` (
  `id` int(11) NOT NULL,
  `username` varchar(65) DEFAULT NULL,
  `password` varchar(60) DEFAULT NULL,
  `trongate_user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trongate_administrators`
--

INSERT INTO `trongate_administrators` (`id`, `username`, `password`, `trongate_user_id`) VALUES
(1, 'admin', '$2y$11$SoHZDvbfLSRHAi3WiKIBiu.tAoi/GCBBO4HRxVX1I3qQkq3wCWfXi', 1),
(2, 'ayomide', '$2y$11$WrLThOuS1tG9knWlABA/veIimEtc9Q93vf0M4Zo9AMmtMkz6WM4Zi', 2);

-- --------------------------------------------------------

--
-- Table structure for table `trongate_comments`
--

CREATE TABLE `trongate_comments` (
  `id` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `date_created` int(11) DEFAULT 0,
  `user_id` int(11) DEFAULT NULL,
  `target_table` varchar(125) DEFAULT NULL,
  `update_id` int(11) DEFAULT NULL,
  `code` varchar(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trongate_pages`
--

CREATE TABLE `trongate_pages` (
  `id` int(11) NOT NULL,
  `url_string` varchar(255) DEFAULT NULL,
  `page_title` varchar(255) DEFAULT NULL,
  `meta_keywords` text DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `page_body` text DEFAULT NULL,
  `date_created` int(11) DEFAULT NULL,
  `last_updated` int(11) DEFAULT NULL,
  `published` tinyint(1) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trongate_pages`
--

INSERT INTO `trongate_pages` (`id`, `url_string`, `page_title`, `meta_keywords`, `meta_description`, `page_body`, `date_created`, `last_updated`, `published`, `created_by`) VALUES
(1, 'policy', 'policy', '', '', '<h1>policy</h1>', 1714488887, 1714488887, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `trongate_tokens`
--

CREATE TABLE `trongate_tokens` (
  `id` int(11) NOT NULL,
  `token` varchar(125) DEFAULT NULL,
  `user_id` int(11) DEFAULT 0,
  `expiry_date` int(11) DEFAULT NULL,
  `code` varchar(3) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trongate_tokens`
--

INSERT INTO `trongate_tokens` (`id`, `token`, `user_id`, `expiry_date`, `code`) VALUES
(915, 'Gww2MfFJNMjgwxeZQSUhup58k88fNkN3', 1, 1792279669, '0'),
(916, 'aX4JEkCbnfSVrwpFYVyNhbWdJ2rm47A3', 1, 1792364212, '0');

-- --------------------------------------------------------

--
-- Table structure for table `trongate_users`
--

CREATE TABLE `trongate_users` (
  `id` int(11) NOT NULL,
  `code` varchar(32) DEFAULT NULL,
  `user_level_id` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trongate_users`
--

INSERT INTO `trongate_users` (`id`, `code`, `user_level_id`) VALUES
(1, 'Tz8tehsWsTPUHEtzfbYjXzaKNqLmfAUz', 1),
(2, 'fe3772Yhy3fHF7ttZ2B4yc6Urzd5u8A3', 2),
(3, 'Eg9AanCrVJDanv9Ev8mVXZCKue9L9XEr', 3),
(4, 'xTNCfeEQXmLJ34kxfCsgePqcaxHHFxcM', 4),
(5, 'tTNCfeEQXmLJ34kxfCsgePqcaxHHFxcK', 5),
(6, 'xTNCfeEQXmLJ34kxfCsgePqcaxHHFxcM', 6),
(120, 'hENjx7KNdy8TC9NxnDWZyRXQDmJrMQgC', 6),
(121, 'j4uprFr3cPb37nsqkgwzxsFyfBYM9QWq', 5),
(122, 'ka8npQEcrkFh44wjzuLqjCHD3UhFwGMD', 5),
(123, 's4bw37y3VmVZ6hRdNsv5tNsvbGWg3bs6', 5),
(124, '33BRufzUetvnr78mqVxsrAxCDbkmv7vV', 5),
(125, 'gHWtbydPhs8px3mNTXN42rFNAGB4hxKx', 5),
(126, 'bMbWy2DBPpdr52yQuNy2Bc5yjYjw9h62', 5),
(127, 'CRFJZqfJH7LrKzHup3BTsBrmHqbpxGvy', 5),
(128, 'jpuVSd6fGNDh6LRQwHmfPtTx5EY84Tq3', 5),
(129, 'ahHs5MpgyTjxE7CL5ryj6gxRdangag92', 5),
(130, 'ZYgyCwkxQgeu7fEAA7gdDqvnQDcR6hEn', 5),
(131, 'Q7yD2Ygw8vy3AuqMxTqtxRZvK5ReAUsd', 5);

-- --------------------------------------------------------

--
-- Table structure for table `trongate_user_levels`
--

CREATE TABLE `trongate_user_levels` (
  `id` int(11) NOT NULL,
  `level_title` varchar(125) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trongate_user_levels`
--

INSERT INTO `trongate_user_levels` (`id`, `level_title`) VALUES
(1, 'admin'),
(2, 'founder'),
(3, 'national team'),
(4, 'manager'),
(5, 'coordinator'),
(6, 'agent');

-- --------------------------------------------------------

--
-- Table structure for table `visit_logs`
--

CREATE TABLE `visit_logs` (
  `id` int(11) NOT NULL,
  `visit_id` int(11) DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_logs`
--
ALTER TABLE `contact_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_contact_logs_account` (`agent_id`),
  ADD KEY `fk_objection_log_leads` (`lead_id`);

--
-- Indexes for table `contributions`
--
ALTER TABLE `contributions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_contributions_leads` (`lead_id`);

--
-- Indexes for table `earnings`
--
ALTER TABLE `earnings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_earnings_account` (`agent_id`);

--
-- Indexes for table `field_visits`
--
ALTER TABLE `field_visits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_field_visits_account` (`agent_id`),
  ADD KEY `fk_field_visit_leads` (`lead_id`),
  ADD KEY `fk_field_visit_local_govt` (`lga_id`);

--
-- Indexes for table `invited_links`
--
ALTER TABLE `invited_links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_invited_links_account` (`agent_id`),
  ADD KEY `fk_invited_links_leads` (`lead_id`);

--
-- Indexes for table `invite_logs`
--
ALTER TABLE `invite_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_invite_logs_account` (`agent_id`),
  ADD KEY `fk_invite_logs_leads` (`lead_id`),
  ADD KEY `fk_invite_logs_invited_links` (`link_id`);

--
-- Indexes for table `leads`
--
ALTER TABLE `leads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_leads_account` (`agent_id`),
  ADD KEY `fk_leads_states` (`state_id`),
  ADD KEY `fk_leads_local_govts` (`lga_id`);

--
-- Indexes for table `lead_funnel`
--
ALTER TABLE `lead_funnel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_lead_funnel_account` (`agent_id`),
  ADD KEY `fk_lead_funnel_leads` (`lead_id`);

--
-- Indexes for table `lead_stage_scripts`
--
ALTER TABLE `lead_stage_scripts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lead_id` (`lead_id`),
  ADD KEY `script_id` (`script_id`);

--
-- Indexes for table `local_govts`
--
ALTER TABLE `local_govts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_local_govts_state` (`state_id`);

--
-- Indexes for table `meeting_logs`
--
ALTER TABLE `meeting_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_meeting_logs_account` (`agent_id`),
  ADD KEY `fk_meeting_logs_leads` (`lead_id`);

--
-- Indexes for table `objection_logs`
--
ALTER TABLE `objection_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_objection_logs_account` (`agent_id`),
  ADD KEY `fk_objection_logs_leads` (`lead_id`),
  ADD KEY `fk_objection_logs_objection_playbook` (`playbook_resolution_strategy_id`);

--
-- Indexes for table `objection_playbook`
--
ALTER TABLE `objection_playbook`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `salaries`
--
ALTER TABLE `salaries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_salaries_account` (`agent_id`);

--
-- Indexes for table `stage_scripts`
--
ALTER TABLE `stage_scripts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `targets`
--
ALTER TABLE `targets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_targets_account` (`agent_id`);

--
-- Indexes for table `trongate_administrators`
--
ALTER TABLE `trongate_administrators`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trongate_comments`
--
ALTER TABLE `trongate_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trongate_pages`
--
ALTER TABLE `trongate_pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trongate_tokens`
--
ALTER TABLE `trongate_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trongate_users`
--
ALTER TABLE `trongate_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trongate_user_levels`
--
ALTER TABLE `trongate_user_levels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `visit_logs`
--
ALTER TABLE `visit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_visit_logs_leads` (`visit_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `contact_logs`
--
ALTER TABLE `contact_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `contributions`
--
ALTER TABLE `contributions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `earnings`
--
ALTER TABLE `earnings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `field_visits`
--
ALTER TABLE `field_visits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `invited_links`
--
ALTER TABLE `invited_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `invite_logs`
--
ALTER TABLE `invite_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leads`
--
ALTER TABLE `leads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `lead_funnel`
--
ALTER TABLE `lead_funnel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `lead_stage_scripts`
--
ALTER TABLE `lead_stage_scripts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=215;

--
-- AUTO_INCREMENT for table `local_govts`
--
ALTER TABLE `local_govts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `meeting_logs`
--
ALTER TABLE `meeting_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `objection_logs`
--
ALTER TABLE `objection_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `objection_playbook`
--
ALTER TABLE `objection_playbook`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `salaries`
--
ALTER TABLE `salaries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `stage_scripts`
--
ALTER TABLE `stage_scripts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `targets`
--
ALTER TABLE `targets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `trongate_administrators`
--
ALTER TABLE `trongate_administrators`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `trongate_comments`
--
ALTER TABLE `trongate_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trongate_pages`
--
ALTER TABLE `trongate_pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `trongate_tokens`
--
ALTER TABLE `trongate_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=917;

--
-- AUTO_INCREMENT for table `trongate_users`
--
ALTER TABLE `trongate_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

--
-- AUTO_INCREMENT for table `trongate_user_levels`
--
ALTER TABLE `trongate_user_levels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `visit_logs`
--
ALTER TABLE `visit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contact_logs`
--
ALTER TABLE `contact_logs`
  ADD CONSTRAINT `fk_contact_logs_account` FOREIGN KEY (`agent_id`) REFERENCES `account` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_objection_log_leads` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `contributions`
--
ALTER TABLE `contributions`
  ADD CONSTRAINT `fk_contributions_leads` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `earnings`
--
ALTER TABLE `earnings`
  ADD CONSTRAINT `fk_earnings_account` FOREIGN KEY (`agent_id`) REFERENCES `account` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `field_visits`
--
ALTER TABLE `field_visits`
  ADD CONSTRAINT `fk_field_visit_leads` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_field_visit_local_govt` FOREIGN KEY (`lga_id`) REFERENCES `local_govts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_field_visits_account` FOREIGN KEY (`agent_id`) REFERENCES `account` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `invited_links`
--
ALTER TABLE `invited_links`
  ADD CONSTRAINT `fk_invited_links_account` FOREIGN KEY (`agent_id`) REFERENCES `account` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_invited_links_leads` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `invite_logs`
--
ALTER TABLE `invite_logs`
  ADD CONSTRAINT `fk_invite_logs_account` FOREIGN KEY (`agent_id`) REFERENCES `account` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_invite_logs_invited_links` FOREIGN KEY (`link_id`) REFERENCES `invited_links` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_invite_logs_leads` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `leads`
--
ALTER TABLE `leads`
  ADD CONSTRAINT `fk_leads_account` FOREIGN KEY (`agent_id`) REFERENCES `account` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_leads_local_govts` FOREIGN KEY (`lga_id`) REFERENCES `local_govts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_leads_states` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `lead_funnel`
--
ALTER TABLE `lead_funnel`
  ADD CONSTRAINT `fk_lead_funnel_account` FOREIGN KEY (`agent_id`) REFERENCES `account` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_lead_funnel_leads` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `lead_stage_scripts`
--
ALTER TABLE `lead_stage_scripts`
  ADD CONSTRAINT `lead_stage_scripts_ibfk_1` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`),
  ADD CONSTRAINT `lead_stage_scripts_ibfk_2` FOREIGN KEY (`script_id`) REFERENCES `stage_scripts` (`id`);

--
-- Constraints for table `local_govts`
--
ALTER TABLE `local_govts`
  ADD CONSTRAINT `fk_local_govts_state` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `meeting_logs`
--
ALTER TABLE `meeting_logs`
  ADD CONSTRAINT `fk_meeting_logs_account` FOREIGN KEY (`agent_id`) REFERENCES `account` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_meeting_logs_leads` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `objection_logs`
--
ALTER TABLE `objection_logs`
  ADD CONSTRAINT `fk_objection_logs_account` FOREIGN KEY (`agent_id`) REFERENCES `account` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_objection_logs_leads` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_objection_logs_objection_playbook` FOREIGN KEY (`playbook_resolution_strategy_id`) REFERENCES `objection_playbook` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `salaries`
--
ALTER TABLE `salaries`
  ADD CONSTRAINT `fk_salaries_account` FOREIGN KEY (`agent_id`) REFERENCES `account` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `targets`
--
ALTER TABLE `targets`
  ADD CONSTRAINT `fk_targets_account` FOREIGN KEY (`agent_id`) REFERENCES `account` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `visit_logs`
--
ALTER TABLE `visit_logs`
  ADD CONSTRAINT `fk_visit_logs_leads` FOREIGN KEY (`visit_id`) REFERENCES `field_visits` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
