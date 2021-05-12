-- phpMyAdmin SQL Dump
-- version OVH
-- https://www.phpmyadmin.net/
--
-- Host: eslaekomedidas.mysql.db
-- Generation Time: May 11, 2021 at 10:08 PM
-- Server version: 5.6.50-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eslaekomedidas`
--

-- --------------------------------------------------------

--
-- Table structure for table `luzparaeleko_inverter_data`
--

CREATE TABLE `luzparaeleko_inverter_data` (
  `id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ac_output_voltage` float(5,2) NOT NULL,
  `ac_output_frequency` float(4,2) NOT NULL,
  `ac_output_apparent_power` smallint(6) NOT NULL,
  `ac_output_active_power` smallint(6) NOT NULL,
  `output_load_percent` tinyint(4) NOT NULL,
  `bus_voltage` smallint(6) NOT NULL,
  `battery_voltage` tinyint(4) NOT NULL,
  `battery_charging_current` smallint(6) NOT NULL,
  `battery_capacity` tinyint(4) NOT NULL,
  `inverter_heat_sink_temperature` smallint(6) NOT NULL,
  `network_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_spanish_ci COMMENT='Inverter data for LuzParaElEko project';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `luzparaeleko_inverter_data`
--
ALTER TABLE `luzparaeleko_inverter_data`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `luzparaeleko_inverter_data`
--
ALTER TABLE `luzparaeleko_inverter_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
