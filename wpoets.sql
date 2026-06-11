-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Generation Time: Jun 11, 2026 at 12:00 PM
-- Server version: 8.4.7
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wpoets`
--

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

DROP TABLE IF EXISTS `sections`;
CREATE TABLE IF NOT EXISTS `sections` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `sort_order` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_sections_sort` (`sort_order`),
  KEY `idx_sections_active` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `slug`, `label`, `icon_path`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'learning', 'Learning', 'assets/svg/DL-learning.svg', 1, 1, '2026-06-11 12:59:04', '2026-06-11 12:59:04'),
(2, 'technology', 'Technology', 'assets/svg/DL-technology.svg', 2, 1, '2026-06-11 12:59:04', '2026-06-11 12:59:04'),
(3, 'communication', 'Communication', 'assets/svg/DL-communication.svg', 3, 1, '2026-06-11 12:59:04', '2026-06-11 14:20:19');

-- --------------------------------------------------------

--
-- Table structure for table `slides`
--

DROP TABLE IF EXISTS `slides`;
CREATE TABLE IF NOT EXISTS `slides` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `section_id` int UNSIGNED NOT NULL,
  `category` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(400) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link_url` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#',
  `image_path` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `sort_order` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_slides_section` (`section_id`),
  KEY `idx_slides_sort` (`sort_order`),
  KEY `idx_slides_active` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `slides`
--

INSERT INTO `slides` (`id`, `section_id`, `category`, `title`, `link_url`, `image_path`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'DIGITAL LEARNING INFRASTRUCTURE', 'Usability enhancement and Training for Transaction Portal for Customers', '#', 'assets/images/DL-Learning-1.jpg', 1, 1, '2026-06-11 12:59:05', '2026-06-11 12:59:05'),
(2, 2, 'TECHNOLOGY SOLUTIONS', 'Leveraging Cutting-Edge Technology for Business Transformation', '#', 'assets/images/DL-Technology.jpg', 2, 1, '2026-06-11 12:59:05', '2026-06-11 12:59:05'),
(3, 3, 'COMMUNICATION STRATEGIES', 'Building Bridges Through Effective Communication Frameworks', '#', 'assets/images/DL-Communication.jpg', 3, 1, '2026-06-11 12:59:05', '2026-06-11 12:59:05');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `slides`
--
ALTER TABLE `slides`
  ADD CONSTRAINT `fk_slides_section` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
