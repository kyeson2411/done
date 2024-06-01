-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 01, 2024 at 02:36 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `credit_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `credit_history`
--

CREATE TABLE `credit_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `credits` decimal(10,2) NOT NULL,
  `operation` varchar(50) NOT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `credit_history`
--

INSERT INTO `credit_history` (`id`, `user_id`, `credits`, `operation`, `timestamp`) VALUES
(1, 1, 5.00, 'deduct', '2024-06-01 06:54:51');

-- --------------------------------------------------------

--
-- Table structure for table `food_items`
--

CREATE TABLE `food_items` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `food_items`
--

INSERT INTO `food_items` (`id`, `name`, `price`) VALUES
(1, 'Sandwich', 5.00),
(2, 'Salad', 3.50),
(3, 'Pizza', 7.00),
(4, 'Burger', 6.50);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `food_item_id` int(11) NOT NULL,
  `order_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `food_item_id`, `order_date`) VALUES
(1, 1, 1, '2024-06-01 06:54:51');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `credits` decimal(10,2) DEFAULT 0.00,
  `expires_at` datetime DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `credits`, `expires_at`, `is_admin`) VALUES
(1, '123', '$2y$10$Oqanpo5kN359j7xijxfVTewyLqZDYaeDpo3HLDoSeulA6DLUd/sA6', 7.00, '2024-06-01 01:35:53', 0),
(2, 'admin', '$2y$10$VbcsEiSMofQK/rzPKy7EBOAZyuuvzVXD8cpGL..gTh.mP4mD2RNFu', 0.00, NULL, 1),
(3, '321', '$2y$10$MNdsqlYWoTq68AAdcArIqOLNbJBL.A22ZfCtpjobDFKliKuVCwrhu', 1.00, '2024-06-01 00:48:05', 0),
(4, 'user3', '$2y$10$IiKxiQbx87iGG278NkGVWeOJBBXGiSr/8.SCfcGzCBX7ah0d/Z9Tu', 0.00, NULL, 0),
(5, 'user', '$2y$10$dGyoAXE.jaUyUA3n9Xv7UOoRfWYhIvmNrcFxYK37tym/VNcW46t7C', 0.00, NULL, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `credit_history`
--
ALTER TABLE `credit_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `food_items`
--
ALTER TABLE `food_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `food_item_id` (`food_item_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `credit_history`
--
ALTER TABLE `credit_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `food_items`
--
ALTER TABLE `food_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `credit_history`
--
ALTER TABLE `credit_history`
  ADD CONSTRAINT `credit_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`food_item_id`) REFERENCES `food_items` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
