-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 12, 2016 at 01:22 PM
-- Server version: 5.7.9
-- PHP Version: 7.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `instr_div`
--

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

DROP TABLE IF EXISTS `rooms`;
CREATE TABLE IF NOT EXISTS `rooms` (
  `room_id` int(11) NOT NULL AUTO_INCREMENT,
  `room_num` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `room_cap_class` int(11) NOT NULL,
  `room_cap_test` int(11) NOT NULL,
  `room_audio` tinyint(1) NOT NULL,
  `room_status` tinyint(1) NOT NULL,
  PRIMARY KEY (`room_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`room_id`, `room_num`, `room_cap_class`, `room_cap_test`, `room_audio`, `room_status`) VALUES
(1, 'B307', 56, 28, 0, 0),
(5, 'F102', 400, 93, 1, 0),
(6, 'F103', 200, 42, 1, 0),
(7, 'F104', 200, 42, 1, 0),
(8, 'F105', 400, 93, 1, 0),
(9, 'F106', 200, 48, 1, 0),
(10, 'F107', 106, 53, 0, 0),
(11, 'F108', 108, 54, 0, 0),
(12, 'F109', 72, 36, 0, 0),
(13, 'F201', 54, 27, 0, 0),
(14, 'F202', 56, 28, 0, 0),
(17, 'G101', 66, 40, 0, 0),
(18, 'G102', 74, 37, 0, 0),
(19, 'G103', 74, 37, 0, 0),
(20, 'G104', 74, 37, 0, 0),
(21, 'G105', 58, 37, 0, 0),
(22, 'G106', 58, 37, 0, 0),
(23, 'G107', 58, 37, 0, 0),
(24, 'G108', 84, 42, 0, 0),
(25, 'G201', 64, 40, 0, 0),
(26, 'G202', 74, 37, 0, 0),
(27, 'G203', 68, 37, 0, 0),
(28, 'G204', 74, 37, 0, 0),
(29, 'G205', 60, 37, 0, 0),
(30, 'G206', 60, 37, 0, 0),
(31, 'G207', 60, 37, 0, 0),
(32, 'G208', 84, 42, 0, 0),
(2, 'B308', 56, 28, 0, 0),
(3, 'B309', 76, 98, 0, 0),
(4, 'B310', 98, 49, 0, 0),
(15, 'F203', 56, 28, 0, 0),
(16, 'F204', 56, 28, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `room_requests`
--

DROP TABLE IF EXISTS `room_requests`;
CREATE TABLE IF NOT EXISTS `room_requests` (
  `req_id` int(11) NOT NULL AUTO_INCREMENT,
  `room_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `req_date` date NOT NULL,
  `req_time_in` time NOT NULL,
  `req_time_out` time NOT NULL,
  `req_reason` text COLLATE utf8_unicode_ci NOT NULL,
  `req_num_students` int(11) NOT NULL,
  `req_esd` tinyint(1) NOT NULL,
  `fac_authorized` tinyint(1) NOT NULL,
  `id_authorized` tinyint(1) NOT NULL,
  `requested_on` int(11) NOT NULL,
  PRIMARY KEY (`req_id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) NOT NULL,
  `user_email` varchar(60) NOT NULL,
  `user_pass` varchar(255) NOT NULL,
  `user_type` tinyint(1) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name` (`user_name`),
  UNIQUE KEY `user_email` (`user_email`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `user_email`, `user_pass`, `user_type`) VALUES
(1, 'Fathaah Ansar', 'f2015565', '$2y$10$htVLqWFt3IQMY4BOISXYceq0f6Bmow.ZxkBUJeHbGf1rVtcjbPVhS', 2),
(10, 'idadmin', 'id', '$2y$10$g8AXS7JEvKl/Xagrj/Ye4uh3Pbk9l//YYb6hKH9u8Er9bJtsFMbaO', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
