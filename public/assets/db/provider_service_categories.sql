-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 20, 2019 at 02:59 PM
-- Server version: 5.7.28
-- PHP Version: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rxclaim_live`
--

-- --------------------------------------------------------

--
-- Table structure for table `provider_service_categories`
--

CREATE TABLE `provider_service_categories` (
  `id` int(11) NOT NULL,
  `provider_id` varchar(20) NOT NULL,
  `cartegory_name` varchar(255) DEFAULT NULL,
  `cartegory_code` varchar(255) DEFAULT NULL,
  `general_cartegory_code` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `provider_service_categories`
--

INSERT INTO `provider_service_categories` (`id`, `provider_id`, `cartegory_name`, `cartegory_code`, `general_cartegory_code`) VALUES
(1, '', 'Drugs', 'Drugs', 'Drugs'),
(2, '', 'Diagnostic', 'Laboratory', 'Laboratory'),
(3, '', 'Medical or Surgical', 'Medical', 'Medical'),
(4, '', 'Optical', 'Optical', 'Optical'),
(5, '', 'Dental', 'Dental', 'Dental'),
(6, '', 'Administrative', 'Administrative', 'Administrative'),
(7, '', 'Baby Products', 'Other', 'Baby Products'),
(8, '', 'Books', 'Other', 'Books'),
(9, '', 'Confectionery', 'Other', 'Confectionery'),
(10, '', 'Cosmetics', 'Other', 'Cosmetics'),
(11, '', 'Gifts', 'Other', 'Gifts'),
(12, '', 'Personal Care', 'Other', 'Personal Care'),
(13, '', 'Hardware', 'Other', 'Hardware'),
(14, '', 'Sex Products', 'Other', 'Sex Products'),
(15, '', 'Sundries', 'Other', 'Sundries'),
(16, '', 'Medical Consumables', 'Other', 'Medical Consumables'),
(17, '', 'ENT', 'ENT', 'ENT'),
(18, '', 'Radiology', 'Radiology', 'Radiology');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `provider_service_categories`
--
ALTER TABLE `provider_service_categories`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `provider_service_categories`
--
ALTER TABLE `provider_service_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
