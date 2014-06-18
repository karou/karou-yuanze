-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 05, 2013 at 04:22 AM
-- Server version: 5.5.24-log
-- PHP Version: 5.3.13

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `albatross_platform`
--

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

DROP TABLE IF EXISTS `status`;
CREATE TABLE IF NOT EXISTS `status` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `editable` tinyint(1) NOT NULL,
  `weight` bigint(20) DEFAULT NULL,
  `today` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `status_uniq` (`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=17 ;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`id`, `status`, `editable`, `weight`, `today`) VALUES
(1, 'Submitted surveys', 0, 1, 1),
(2, 'Assigned surveys', 0, 2, 1),
(3, 'Delayed surveys (> 2 days)', 0, 3, 1),
(4, 'Declined surveys', 0, 4, 1),
(5, 'Pending validation', 0, 13, 0),
(6, 'Pending validation by Submission Time (> 4 days)', 0, 14, 0),
(7, 'Pending validation by Visit Time (> 4 days)', 0, 15, 0),
(8, 'Pending QC', 0, 16, 0),
(9, 'RFA opened', 0, 5, 1),
(10, 'Open opportunity', 0, 6, 1),
(11, 'Survey Validated Number', 1, 7, 1),
(12, 'QC Done', 1, 8, 1),
(13, 'Data Integrity Check Done', 1, 9, 1),
(14, 'LS Translation Done', 1, 10, 1),
(15, 'Report Done', 1, 11, 1),
(16, 'Invalid Survey Number', 1, 12, 1);
SET FOREIGN_KEY_CHECKS=1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
