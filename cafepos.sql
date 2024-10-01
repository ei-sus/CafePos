-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 01, 2024 at 06:24 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cafepos`
--

-- --------------------------------------------------------

--
-- Table structure for table `drinks_ingredients`
--

CREATE TABLE `drinks_ingredients` (
  `id` tinyint(5) NOT NULL,
  `item_d` varchar(100) NOT NULL,
  `quantity_d` smallint(10) NOT NULL,
  `unit_d` text NOT NULL,
  `price_d` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `drinks_ingredients`
--

INSERT INTO `drinks_ingredients` (`id`, `item_d`, `quantity_d`, `unit_d`, `price_d`) VALUES
(7, 'Coffee Bean', 9, 'kg', 120);

-- --------------------------------------------------------

--
-- Table structure for table `pastries_ingredients`
--

CREATE TABLE `pastries_ingredients` (
  `id` tinyint(10) NOT NULL,
  `item_p` varchar(100) NOT NULL,
  `quantity_p` tinyint(5) NOT NULL,
  `price_p` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pastries_ingredients`
--

INSERT INTO `pastries_ingredients` (`id`, `item_p`, `quantity_p`, `price_p`) VALUES
(1, 'Bagel', 10, 40);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `drinks_ingredients`
--
ALTER TABLE `drinks_ingredients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ID` (`id`),
  ADD KEY `ID_2` (`id`);

--
-- Indexes for table `pastries_ingredients`
--
ALTER TABLE `pastries_ingredients`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `drinks_ingredients`
--
ALTER TABLE `drinks_ingredients`
  MODIFY `id` tinyint(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `pastries_ingredients`
--
ALTER TABLE `pastries_ingredients`
  MODIFY `id` tinyint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
