-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 26, 2013 at 10:04 AM
-- Server version: 5.5.24-log
-- PHP Version: 5.3.13

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
-- Table structure for table `date`
--

CREATE TABLE IF NOT EXISTS `date` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `daily_date` date NOT NULL,
  `bu_id` bigint(20) DEFAULT NULL,
  `forecast` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `daily_date_bu_id_uniq` (`daily_date`,`bu_id`),
  KEY `IDX_AA9E377AE0319FBC` (`bu_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=37 ;

--
-- Dumping data for table `date`
--

INSERT INTO `date` (`id`, `daily_date`, `bu_id`, `forecast`) VALUES
(1, '2013-07-15', 1, 0),
(2, '2013-07-16', 2, 0),
(3, '2013-07-17', 3, 100),
(4, '2013-07-18', 4, 0),
(5, '2013-07-19', 5, 0),
(6, '2013-07-20', 6, 0),
(7, '2013-07-21', 3, 40),
(8, '2013-07-22', 8, 0),
(9, '2013-07-26', 10, 0),
(10, '2013-07-23', 9, 0),
(11, '2013-07-19', 2, 0),
(12, '2013-07-20', 2, 0),
(13, '2013-07-20', 5, 0),
(14, '2013-07-25', 4, NULL),
(15, '2013-07-25', 1, NULL),
(16, '2013-07-25', NULL, NULL),
(22, '2013-07-25', 2, NULL),
(23, '2013-07-17', NULL, 123),
(24, '2013-07-26', NULL, NULL),
(25, '2013-07-17', 1, NULL),
(26, '2013-07-18', NULL, 100),
(27, '2013-07-22', 3, NULL),
(28, '2013-07-22', NULL, 22),
(29, '2013-07-26', 3, NULL),
(30, '2013-07-18', 3, 100),
(31, '2013-07-15', 3, 100),
(32, '2013-07-16', 3, 100),
(33, '2013-07-19', 3, 10),
(34, '2013-07-20', 3, 100),
(35, '2013-07-21', NULL, NULL),
(36, '2013-07-16', NULL, 11);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `date`
--
ALTER TABLE `date`
  ADD CONSTRAINT `FK_AA9E377AE0319FBC` FOREIGN KEY (`bu_id`) REFERENCES `bu` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
