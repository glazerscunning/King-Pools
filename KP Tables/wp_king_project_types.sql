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
-- Table structure for table `wp_king_project_types`
--

DROP TABLE IF EXISTS `wp_king_project_types`;
CREATE TABLE IF NOT EXISTS `wp_king_project_types` (
  `project_type_id` int(11) NOT NULL,
  `project_type_name` tinytext NOT NULL,
  `project_type` tinytext NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wp_king_project_types`
--

INSERT INTO `wp_king_project_types` (`project_type_id`, `project_type_name`, `project_type`) VALUES
(1, 'Construction/Remodel', 'construction-remodel'),
(2, 'Pool Cleaning', 'cleaning'),
(3, 'Service/Repair', 'service-repair');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `wp_king_project_types`
--
ALTER TABLE `wp_king_project_types`
  ADD PRIMARY KEY (`project_type_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `wp_king_project_types`
--
ALTER TABLE `wp_king_project_types`
  MODIFY `project_type_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
