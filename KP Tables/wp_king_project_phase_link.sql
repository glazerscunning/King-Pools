-- phpMyAdmin SQL Dump
-- version 4.4.11
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 12, 2016 at 03:52 PM
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
-- Table structure for table `wp_king_project_phase_link`
--

DROP TABLE IF EXISTS `wp_king_project_phase_link`;
CREATE TABLE IF NOT EXISTS `wp_king_project_phase_link` (
  `project_id` int(11) NOT NULL,
  `phase_id` int(11) NOT NULL,
  `status` varchar(25) NOT NULL DEFAULT 'incomplete',
  `complete_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `wp_king_project_phase_link`
--
ALTER TABLE `wp_king_project_phase_link`
  ADD PRIMARY KEY (`project_id`,`phase_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
