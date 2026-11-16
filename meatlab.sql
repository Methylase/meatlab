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

CREATE TABLE IF NOT EXISTS accounts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  role ENUM('admin','owner') NOT NULL DEFAULT 'owner',
  name VARCHAR(120) NOT NULL,
  email VARCHAR(160) NULL UNIQUE,
  phone VARCHAR(20) NULL UNIQUE,
  remember_token VARCHAR(64) NULL, 
  reset_token VARCHAR(64) NULL, 
  password_hash VARCHAR(255) NOT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS shops (
  id INT AUTO_INCREMENT PRIMARY KEY,
  account_id INT NOT NULL,
  shop_name VARCHAR(120) NOT NULL,
  slug VARCHAR(140) NOT NULL UNIQUE,
  address VARCHAR(255) NULL,
  city VARCHAR(80) NULL,
  description TEXT NULL,
  tagline VARCHAR(200), 
  rating DECIMAL(2,1) DEFAULT 4.8, 
  banner VARCHAR(80) NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  can_edit_banner TINYINT(1) NOT NULL DEFAULT 1,
  can_edit_price TINYINT(1) NOT NULL DEFAULT 1,
  can_add_products TINYINT(1) NOT NULL DEFAULT 1,
  max_products INT NOT NULL DEFAULT 50,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX (account_id),
  FOREIGN KEY (account_id) REFERENCES accounts(id)
);


CREATE TABLE categories (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(60), icon VARCHAR(40));

CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  shop_id INT NOT NULL,
  name VARCHAR(140) NOT NULL,
  category_id INT,
  description TEXT NULL,
  price_per_kg INT, 
  min_kg DECIMAL(4,1) DEFAULT 0.5, 
  is_top TINYINT DEFAULT 0,
  unit VARCHAR(20) NOT NULL DEFAULT 'kg',
  image VARCHAR(80) NULL,
  in_stock TINYINT(1) NOT NULL DEFAULT 1,
  status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'approved',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL,
  INDEX (shop_id),
  FOREIGN KEY (shop_id) REFERENCES shops(id)
);

CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  reference VARCHAR(40) NOT NULL UNIQUE,
  shop_id INT NOT NULL,
  customer_name VARCHAR(120) NOT NULL,
  customer_phone VARCHAR(20) NULL,
  customer_email VARCHAR(160) NULL,
  delivery_address VARCHAR(255) NULL,
  total DECIMAL(12,2) NOT NULL,
  customer_payment_reference VARCHAR(40) UNIQUE,
  subtotal INT, delivery INT, 
  customer_payment_status VARCHAR(20) DEFAULT 'pending',
  payment_status ENUM('unpaid','paid','failed') NOT NULL DEFAULT 'unpaid',
  status ENUM('pending','processing','on_the_way','delivered','declined') NOT NULL DEFAULT 'pending',
  accepted_at DATETIME NULL,
  dispatched_at DATETIME NULL,
  delivered_at DATETIME NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (shop_id) REFERENCES shops(id)
);

CREATE TABLE IF NOT EXISTS order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  product_id INT NULL,
  product_name VARCHAR(140) NOT NULL,
  unit_price DECIMAL(12,2) NOT NULL,
  kg DECIMAL(6,2),
  quantity DECIMAL(10,2) NOT NULL,
  line_total DECIMAL(12,2) NOT NULL,
  INDEX (order_id),
  FOREIGN KEY (order_id) REFERENCES orders(id)
);

CREATE TABLE IF NOT EXISTS order_notifications (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  channel ENUM('sms','email') NOT NULL,
  event VARCHAR(30) NOT NULL,
  success TINYINT(1) NOT NULL DEFAULT 0,
  response VARCHAR(255) NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX (order_id)
);

CREATE TABLE IF NOT EXISTS settings (
  name VARCHAR(60) PRIMARY KEY,
  value TEXT NULL
);

INSERT IGNORE INTO settings (name, value) VALUES
 ('auto_approve_products','1'),
 ('default_max_products','50'),
 ('default_can_edit_banner','1'),
 ('default_can_edit_price','1'),
 ('default_can_add_products','1'),
 ('max_image_mb','3'),
 ('categories','Beef,Goat,Chicken,Pork,Turkey,Ram,Fish,Offal,Other');

INSERT INTO categories (name,icon) VALUES ('Beef','fa-bone'),('Chicken','fa-drumstick-bite'),('Goat & Lamb','fa-paw'),('Fish','fa-fish'),('Processed','fa-bacon');

INSERT INTO accounts (role, name,phone,email,password_hash,remember_token,reset_token,is_active) VALUES
('admin','Admin','08188373898','methyl2007@yahoo.com','$2y$10$abcdefghijklmnopqrstuuJ3Wq3n3v1b1kQw5v1d0vG1h9yq0aE2a',NULL, NULL, 1),
('owner','Adekunle Ayoola','09045686782','adekunleayoola@yahoo.com','$2y$10$abcdefghijklmnopqrstuuJ3Wq3n3v1b1kQw5v1d0vG1h9yq0aE2a',NULL, NULL, 1),
('owner','Tade Bukola','07286373838','tadebukola@yahoo.com','$2y$10$abcdefghijklmnopqrstuuJ3Wq3n3v1b1kQw5v1d0vG1h9yq0aE2a',NULL, NULL, 1),
('owner','Edward Bright','08522374991','edward@yahoo.com','$2y$10$abcdefghijklmnopqrstuuJ3Wq3n3v1b1kQw5v1d0vG1h9yq0aE2a',NULL, NULL, 1),
('owner','Prince White','08148373591','pricewhite@gmail.com','$2y$10$abcdefghijklmnopqrstuuJ3Wq3n3v1b1kQw5v1d0vG1h9yq0aE2a',NULL, NULL, 1),
('owner','Shokoya Adedoyin','08186343899','shokoya@yahoo.com','$2y$10$abcdefghijklmnopqrstuuJ3Wq3n3v1b1kQw5v1d0vG1h9yq0aE2a',NULL, NULL, 1);


INSERT INTO shops (account_id,shop_name,slug,address,city,tagline,rating,banner,is_active,can_edit_banner,can_edit_price,can_add_products,max_products) VALUES
(2,'Barakah Meats','barakah-meats','12 Opebi Road, Ikeja','Lagos','Premium halal cuts, delivered fresh',4.9,NULL,1,1,1,1,50),
(3,'Prime Cuts Lagos','prime-cuts','5 Admiralty Way, Lekki','Lagos','Butcher-grade beef and goat',4.8,NULL,1,1,1,1,50),
(4,'Fresh Market Hub','fresh-market-hub','8 Allen Avenue, Ikeja','Lagos','Own production. Verified quality.',4.7,NULL,1,1,1,1,50),
(5,'Oga Butcher','oga-butcher','22 Bode Thomas, Surulere','Lagos','Your neighbourhood butcher',4.6,NULL,1,1,1,1,50),
(6,'Bollack Meat','bollack-meat','9 Obanla, Giwa','Ogun','Your clean and fresh cut',4.7,NULL,1,1,1,1,50);

INSERT INTO products (shop_id,category_id,name,description,price_per_kg,is_top,in_stock,status) SELECT s.id,1,'Prime Beef Ribeye','Marbled, tender, grill-ready',9500,1,1,'approved' FROM shops s;
INSERT INTO products (shop_id,category_id,name,description,price_per_kg,is_top,in_stock,status) SELECT s.id,1,'Freshly Cut Raw Beef','Cleaned and portioned',6500,1,1,'approved' FROM shops s;
INSERT INTO products (shop_id,category_id,name,description,price_per_kg,is_top,in_stock,status) SELECT s.id,2,'Whole Chicken','Farm fresh, cleaned',4800,1,1,'approved' FROM shops s;
INSERT INTO products (shop_id,category_id,name,description,price_per_kg,is_top,in_stock,status) SELECT s.id,3,'Goat Meat','Bone-in, soft and sweet',8200,1,1,'approved' FROM shops s;
INSERT INTO products (shop_id,category_id,name,description,price_per_kg,in_stock,status) SELECT s.id,4,'Catfish','Live-fresh, cleaned',5500,1,'approved' FROM shops s;
INSERT INTO products (shop_id,category_id,name,description,price_per_kg,in_stock,status) SELECT s.id,5,'Smoked Bacon','Hickory smoked',7800,1,'approved' FROM shops s;

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
(2, 'fe3772Yhy3fHF7ttZ2B4yc6Urzd5u8A3', 2);


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
(2, 'shop owner');

-- --------------------------------------------------------
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
-- Constraints for dumped tables
--

ALTER TABLE orders
  ADD COLUMN shop_paid_at DATETIME NULL AFTER delivered_at,
  ADD COLUMN shop_paid_amount DECIMAL(12,2) NULL AFTER shop_paid_at,
  ADD COLUMN shop_payout_ref VARCHAR(60) NULL AFTER shop_paid_amount,
  ADD COLUMN shop_paid_by INT NULL AFTER shop_payout_ref,
  ADD INDEX (shop_paid_at);


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- First admin: change the email, then paste a password hash.
-- Generate one with: php -r "echo password_hash('YourStrongPassword', PASSWORD_DEFAULT);"
-- INSERT INTO accounts (role,name,email,password_hash)
--   VALUES ('admin','Admin','admin@yourdomain.com','PASTE_HASH_HERE');
