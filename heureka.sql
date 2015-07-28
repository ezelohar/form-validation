-- phpMyAdmin SQL Dump
-- version 4.4.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 28, 2015 at 02:20 PM
-- Server version: 5.6.24
-- PHP Version: 5.5.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `heureka`
--

-- --------------------------------------------------------

--
-- Table structure for table `delivery_method`
--

CREATE TABLE IF NOT EXISTS `delivery_method` (
  `id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL DEFAULT '1',
  `name` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `fixed_price` decimal(10,0) DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `delivery_method`
--

INSERT INTO `delivery_method` (`id`, `store_id`, `name`, `status`, `fixed_price`, `active`) VALUES
(1, 1, 'Osobni odbêr', 0, NULL, 1),
(2, 1, 'Osobni Odabêr s dobirkou', 0, NULL, 1),
(3, 1, 'Česka pošta', 0, NULL, 1),
(4, 1, 'Česka pošta - dobirka', 0, NULL, 1),
(5, 1, 'Prepravni služba (do 30kg)', 0, NULL, 1),
(6, 1, 'Prepravni služba (do 30kg) - dobirka', 0, NULL, 1),
(7, 1, 'Prepravni služba (nad 30kg)', 0, NULL, 1),
(8, 1, 'Prepravni služba (nad 30kg) - dobirka', 0, NULL, 1),
(9, 1, 'Kurÿr po Praze ten samÿ den', 0, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `delivery_method_options`
--

CREATE TABLE IF NOT EXISTS `delivery_method_options` (
  `id` int(11) NOT NULL,
  `delivery_method_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL DEFAULT '1',
  `url` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `weight_from` decimal(10,0) DEFAULT NULL,
  `weight_to` decimal(10,0) DEFAULT NULL,
  `notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_method_ranges`
--

CREATE TABLE IF NOT EXISTS `delivery_method_ranges` (
  `id` int(11) NOT NULL,
  `delivery_method_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL DEFAULT '1',
  `range_from` decimal(10,0) NOT NULL DEFAULT '0',
  `range_to` decimal(10,0) NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `delivery_method`
--
ALTER TABLE `delivery_method`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery_method_options`
--
ALTER TABLE `delivery_method_options`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `delivery_method_id_2` (`delivery_method_id`),
  ADD KEY `delivery_method_id` (`delivery_method_id`);

--
-- Indexes for table `delivery_method_ranges`
--
ALTER TABLE `delivery_method_ranges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `delivery_method_id` (`delivery_method_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `delivery_method`
--
ALTER TABLE `delivery_method`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `delivery_method_options`
--
ALTER TABLE `delivery_method_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `delivery_method_ranges`
--
ALTER TABLE `delivery_method_ranges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `delivery_method_options`
--
ALTER TABLE `delivery_method_options`
  ADD CONSTRAINT `method_options` FOREIGN KEY (`delivery_method_id`) REFERENCES `delivery_method` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `delivery_method_ranges`
--
ALTER TABLE `delivery_method_ranges`
  ADD CONSTRAINT `delivery_ranges` FOREIGN KEY (`delivery_method_id`) REFERENCES `delivery_method` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
