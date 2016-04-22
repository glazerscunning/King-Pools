-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 23, 2016 at 11:44 AM
-- Server version: 5.5.47-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `kingpoolsinc`
--

-- --------------------------------------------------------

--
-- Table structure for table `ykpahz_king_estimates`
--

CREATE TABLE IF NOT EXISTS `ykpahz_king_estimates` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `lead_id` int(10) NOT NULL,
  `value` varchar(200) NOT NULL,
  `status` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=280 ;

--
-- Dumping data for table `ykpahz_king_estimates`
--

INSERT INTO `ykpahz_king_estimates` (`id`, `lead_id`, `value`, `status`) VALUES
(3, 5, '32200', 'unconverted'),
(4, 6, '44800', 'unconverted'),
(5, 7, '43800', 'converted'),
(6, 0, '0', 'unconverted'),
(7, 0, '0', 'unconverted'),
(8, 0, '0', 'unconverted'),
(9, 8, '53850', 'converted'),
(10, 1365, '22900', 'unconverted'),
(11, 0, '0', 'unconverted'),
(12, 0, '0', 'unconverted'),
(13, 0, '0', 'unconverted'),
(14, 1365, '25600', 'unconverted'),
(15, 0, '0', 'unconverted'),
(16, 1365, '25600', 'unconverted'),
(17, 1365, '25600', 'unconverted'),
(18, 1365, '25600', 'unconverted'),
(19, 1365, '25600', 'unconverted'),
(20, 0, '0', 'unconverted'),
(21, 0, '0', 'unconverted'),
(22, 0, '0', 'unconverted'),
(23, 0, '0', 'unconverted'),
(24, 0, '0', 'unconverted'),
(25, 0, '0', 'unconverted'),
(26, 0, '0', 'unconverted'),
(27, 0, '0', 'unconverted'),
(28, 0, '0', 'unconverted'),
(29, 0, '0', 'unconverted'),
(30, 0, '0', 'unconverted'),
(31, 0, '0', 'unconverted'),
(32, 0, '0', 'unconverted'),
(33, 0, '0', 'unconverted'),
(34, 0, '0', 'unconverted'),
(35, 0, '0', 'unconverted'),
(36, 1365, '25600', 'unconverted'),
(37, 1426, '37500', 'unconverted'),
(38, 1426, '37500', 'unconverted'),
(39, 1426, '37500', 'unconverted'),
(40, 1426, '37500', 'unconverted'),
(41, 1428, '36900', 'unconverted'),
(42, 1429, '25600', 'unconverted'),
(43, 1429, '25600', 'unconverted'),
(44, 1431, '33200', 'unconverted'),
(45, 1432, '25600', 'unconverted'),
(46, 0, '0', 'unconverted'),
(47, 1431, '33200', 'unconverted'),
(48, 1431, '33200', 'unconverted'),
(49, 1433, '46800', 'unconverted'),
(50, 1434, '34100', 'unconverted'),
(51, 1435, '41000', 'unconverted'),
(52, 1436, '39800', 'unconverted'),
(53, 1437, '37300', 'unconverted'),
(54, 0, '0', 'unconverted'),
(55, 0, '0', 'unconverted'),
(56, 0, '0', 'unconverted'),
(57, 0, '0', 'unconverted'),
(58, 0, '0', 'unconverted'),
(59, 0, '0', 'unconverted'),
(60, 0, '0', 'unconverted'),
(61, 0, '0', 'unconverted'),
(62, 0, '0', 'unconverted'),
(63, 0, '0', 'unconverted'),
(64, 1438, '29300', 'unconverted'),
(65, 1439, '29300', 'unconverted'),
(66, 0, '0', 'unconverted'),
(67, 0, '0', 'unconverted'),
(68, 0, '0', 'unconverted'),
(69, 1440, '46250', 'unconverted'),
(70, 1441, '46200', 'unconverted'),
(71, 0, '0', 'unconverted'),
(72, 1442, '34100', 'unconverted'),
(73, 1443, '37300', 'unconverted'),
(74, 0, '0', 'unconverted'),
(75, 1444, '35200', 'unconverted'),
(76, 1444, '35200', 'unconverted'),
(77, 0, '0', 'unconverted'),
(78, 1446, '102825', 'unconverted'),
(79, 1446, '102825', 'unconverted'),
(80, 1448, '58650', 'unconverted'),
(81, 0, '0', 'unconverted'),
(82, 0, '0', 'unconverted'),
(83, 0, '0', 'unconverted'),
(84, 0, '0', 'unconverted'),
(85, 1449, '40200', 'unconverted'),
(86, 1450, '45900', 'unconverted'),
(87, 1451, '41400', 'unconverted'),
(88, 1452, '37700', 'unconverted'),
(89, 0, '0', 'unconverted'),
(90, 0, '0', 'unconverted'),
(91, 1452, '37700', 'unconverted'),
(92, 0, '0', 'unconverted'),
(93, 1452, '37700', 'unconverted'),
(94, 0, '0', 'unconverted'),
(95, 1453, '67050', 'unconverted'),
(96, 1453, '67050', 'unconverted'),
(97, 0, '0', 'unconverted'),
(98, 0, '0', 'unconverted'),
(99, 0, '0', 'unconverted'),
(100, 1455, '43900', 'unconverted'),
(101, 1456, '42700', 'unconverted'),
(102, 1457, '37500', 'unconverted'),
(103, 1458, '44600', 'unconverted'),
(104, 1459, '57300', 'unconverted'),
(105, 1460, '56200', 'unconverted'),
(106, 1460, '56200', 'unconverted'),
(107, 1462, '60100', 'unconverted'),
(108, 1462, '60100', 'unconverted'),
(109, 1464, '72800', 'unconverted'),
(110, 1465, '25600', 'unconverted'),
(111, 0, '0', 'unconverted'),
(112, 0, '0', 'unconverted'),
(113, 0, '0', 'unconverted'),
(114, 0, '0', 'unconverted'),
(115, 0, '0', 'unconverted'),
(116, 1466, '39700', 'unconverted'),
(117, 1467, '45550', 'unconverted'),
(118, 1468, '39000', 'unconverted'),
(119, 1469, '45500', 'unconverted'),
(120, 1470, '44900', 'unconverted'),
(121, 1471, '37500', 'unconverted'),
(122, 1472, '36900', 'unconverted'),
(123, 1473, '29300', 'unconverted'),
(124, 1474, '37300', 'unconverted'),
(125, 1475, '44700', 'unconverted'),
(126, 1476, '38900', 'unconverted'),
(127, 1477, '46900', 'unconverted'),
(128, 0, '0', 'unconverted'),
(129, 0, '0', 'unconverted'),
(130, 0, '0', 'unconverted'),
(131, 0, '0', 'unconverted'),
(132, 1478, '49400', 'unconverted'),
(133, 1479, '47550', 'unconverted'),
(134, 1480, '53250', 'unconverted'),
(135, 1480, '53250', 'unconverted'),
(136, 1481, '55000', 'unconverted'),
(137, 1482, '30500', 'unconverted'),
(138, 1483, '37500', 'unconverted'),
(139, 1484, '37100', 'unconverted'),
(140, 1485, '52050', 'unconverted'),
(141, 1485, '52050', 'unconverted'),
(142, 0, '0', 'unconverted'),
(143, 0, '0', 'unconverted'),
(144, 0, '0', 'unconverted'),
(145, 0, '0', 'unconverted'),
(146, 0, '0', 'unconverted'),
(147, 1487, '45650', 'unconverted'),
(148, 0, '0', 'unconverted'),
(149, 0, '0', 'unconverted'),
(150, 1487, '45650', 'unconverted'),
(151, 0, '0', 'unconverted'),
(152, 1488, '48600', 'unconverted'),
(153, 1489, '47600', 'unconverted'),
(154, 1490, '44900', 'unconverted'),
(155, 0, '0', 'unconverted'),
(156, 1487, '45650', 'unconverted'),
(157, 0, '0', 'unconverted'),
(158, 0, '0', 'unconverted'),
(159, 0, '0', 'unconverted'),
(160, 1365, '25600', 'unconverted'),
(161, 1495, '27000', 'unconverted'),
(162, 1496, '45550', 'unconverted'),
(163, 1496, '45550', 'unconverted'),
(164, 1498, '53600', 'unconverted'),
(165, 1499, '54100', 'unconverted'),
(166, 1499, '54100', 'unconverted'),
(167, 1499, '54100', 'unconverted'),
(168, 1499, '54100', 'unconverted'),
(169, 1499, '54100', 'unconverted'),
(170, 1500, '43700', 'unconverted'),
(171, 1501, '54300', 'unconverted'),
(172, 1502, '37350', 'unconverted'),
(173, 1503, '37300', 'unconverted'),
(174, 1504, '25600', 'unconverted'),
(175, 1505, '33800', 'unconverted'),
(176, 1505, '33800', 'unconverted'),
(177, 1505, '33800', 'unconverted'),
(178, 1508, '39600', 'unconverted'),
(179, 1509, '33800', 'unconverted'),
(180, 1510, '37900', 'unconverted'),
(181, 1510, '37900', 'unconverted'),
(182, 1512, '33300', 'unconverted'),
(183, 1512, '33300', 'unconverted'),
(184, 1512, '33300', 'unconverted'),
(185, 1508, '39600', 'unconverted'),
(186, 1512, '33300', 'unconverted'),
(187, 1510, '37900', 'unconverted'),
(188, 1509, '33800', 'unconverted'),
(189, 1487, '45650', 'unconverted'),
(190, 1499, '54100', 'unconverted'),
(191, 1514, '38000', 'unconverted'),
(192, 1514, '38000', 'unconverted'),
(193, 1516, '60300', 'unconverted'),
(194, 1517, '50400', 'unconverted'),
(195, 1518, '46300', 'unconverted'),
(196, 1514, '38000', 'unconverted'),
(197, 1514, '38000', 'unconverted'),
(198, 1519, '48000', 'unconverted'),
(199, 1520, '25700', 'unconverted'),
(200, 1521, '48400', 'unconverted'),
(201, 1522, '39800', 'unconverted'),
(202, 1522, '39800', 'unconverted'),
(203, 1524, '49700', 'unconverted'),
(204, 1525, '64850', 'unconverted'),
(205, 1526, '57450', 'unconverted'),
(206, 1527, '36900', 'unconverted'),
(207, 1528, '29300', 'unconverted'),
(208, 1529, '34100', 'unconverted'),
(209, 1530, '48850', 'unconverted'),
(210, 1531, '37500', 'unconverted'),
(211, 1532, '25600', 'unconverted'),
(212, 1533, '62100', 'unconverted'),
(213, 1533, '62100', 'unconverted'),
(214, 1535, '67800', 'unconverted'),
(215, 1536, '62000', 'unconverted'),
(216, 1537, '49100', 'unconverted'),
(217, 1537, '49100', 'unconverted'),
(218, 1539, '26800', 'unconverted'),
(219, 1540, '33800', 'unconverted'),
(220, 1541, '25600', 'unconverted'),
(221, 1542, '29300', 'unconverted'),
(222, 1545, '52400', 'unconverted'),
(223, 1546, '45500', 'unconverted'),
(224, 1547, '42900', 'unconverted'),
(225, 1548, '42900', 'unconverted'),
(226, 1549, '54500', 'unconverted'),
(227, 1550, '29300', 'unconverted'),
(228, 1550, '29300', 'unconverted'),
(229, 1552, '34100', 'unconverted'),
(230, 1553, '29300', 'unconverted'),
(231, 1546, '45500', 'unconverted'),
(232, 1554, '30800', 'unconverted'),
(233, 1555, '32000', 'unconverted'),
(234, 1556, '39500', 'unconverted'),
(235, 1556, '39500', 'unconverted'),
(236, 1558, '39500', 'unconverted'),
(237, 1559, '34700', 'unconverted'),
(238, 1560, '39500', 'unconverted'),
(239, 1561, '36800', 'unconverted'),
(240, 1562, '46200', 'unconverted'),
(241, 1563, '41800', 'unconverted'),
(242, 1564, '37300', 'unconverted'),
(243, 1565, '31300', 'unconverted'),
(244, 1566, '25600', 'unconverted'),
(245, 1567, '32100', 'unconverted'),
(246, 1567, '32100', 'unconverted'),
(247, 1569, '32000', 'unconverted'),
(248, 1570, '32000', 'unconverted'),
(249, 1571, '32000', 'unconverted'),
(250, 1572, '36800', 'unconverted'),
(251, 1573, '32000', 'unconverted'),
(252, 1574, '32000', 'unconverted'),
(253, 1575, '32000', 'unconverted'),
(254, 1575, '32000', 'unconverted'),
(255, 1576, '33800', 'unconverted'),
(256, 1577, '39500', 'unconverted'),
(257, 1578, '46300', 'unconverted'),
(258, 1578, '46300', 'unconverted'),
(259, 1580, '33800', 'unconverted'),
(260, 1581, '37400', 'unconverted'),
(261, 1581, '37400', 'unconverted'),
(262, 1583, '41300', 'unconverted'),
(263, 1584, '41300', 'unconverted'),
(264, 1585, '41300', 'unconverted'),
(265, 1586, '39900', 'unconverted'),
(266, 1587, '37400', 'unconverted'),
(267, 1588, '29900', 'unconverted'),
(268, 1588, '29900', 'unconverted'),
(269, 1590, '46300', 'unconverted'),
(270, 1591, '37500', 'unconverted'),
(271, 1591, '37500', 'unconverted'),
(272, 1592, '42750', 'unconverted'),
(273, 1593, '53250', 'unconverted'),
(274, 1594, '43600', 'unconverted'),
(275, 1594, '43600', 'unconverted'),
(276, 1596, '37900', 'unconverted'),
(277, 1596, '37900', 'unconverted'),
(278, 1598, '36400', 'unconverted'),
(279, 1599, '45500', 'unconverted');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;