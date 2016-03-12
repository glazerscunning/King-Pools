SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


DROP TABLE IF EXISTS `wp_king_customers`;
CREATE TABLE IF NOT EXISTS `wp_king_customers` (
  `customer_id` int(11) NOT NULL,
  `customer_firstname` tinytext NOT NULL,
  `customer_lastname` tinytext NOT NULL,
  `customer_address` tinytext NOT NULL,
  `customer_city` tinytext NOT NULL,
  `customer_state` tinytext NOT NULL,
  `customer_zip` mediumint(9) NOT NULL,
  `customer_phone` tinytext NOT NULL,
  `customer_email` text NOT NULL,
  `customer_status` varchar(10) NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `wp_king_customers`
  ADD PRIMARY KEY (`customer_id`);


ALTER TABLE `wp_king_customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
