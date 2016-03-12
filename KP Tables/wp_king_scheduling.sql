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
-- Table structure for table `wp_king_scheduling`
--

DROP TABLE IF EXISTS `wp_king_scheduling`;
CREATE TABLE IF NOT EXISTS `wp_king_scheduling` (
  `schedule_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `schedule_date` date NOT NULL,
  `last_updated` datetime NOT NULL,
  `project_id` int(11) NOT NULL,
  `phase_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `wp_king_scheduling`
--
ALTER TABLE `wp_king_scheduling`
  ADD PRIMARY KEY (`schedule_id`),
  ADD UNIQUE KEY `vendor_id` (`vendor_id`,`project_id`,`phase_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `wp_king_scheduling`
--
ALTER TABLE `wp_king_scheduling`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
