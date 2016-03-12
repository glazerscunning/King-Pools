-- phpMyAdmin SQL Dump
-- version 4.4.11
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 12, 2016 at 03:53 PM
-- Server version: 5.6.25
-- PHP Version: 5.5.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kingpools`
--

-- --------------------------------------------------------

--
-- Table structure for table `wp_king_vendors`
--

DROP TABLE IF EXISTS `wp_king_vendors`;
CREATE TABLE IF NOT EXISTS `wp_king_vendors` (
  `vendor_id` int(11) NOT NULL,
  `vendor_name` tinytext NOT NULL,
  `vendor_address` tinytext NOT NULL,
  `vendor_city` tinytext NOT NULL,
  `vendor_state` tinytext NOT NULL,
  `vendor_zip` mediumint(9) NOT NULL,
  `vendor_phone` tinytext NOT NULL,
  `vendor_fax` tinytext NOT NULL,
  `vendor_email` text NOT NULL,
  `vendor_type` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `wp_king_vendors`
--
ALTER TABLE `wp_king_vendors`
  ADD PRIMARY KEY (`vendor_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `wp_king_vendors`
--
ALTER TABLE `wp_king_vendors`
  MODIFY `vendor_id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
