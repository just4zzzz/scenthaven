-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 16, 2025 at 07:39 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Create and use the scenthaven database
DROP DATABASE IF EXISTS `scenthaven`;
CREATE DATABASE IF NOT EXISTS `scenthaven`;
USE `scenthaven`;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `scenthaven`
--

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `createdAt` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `description`, `createdAt`) VALUES
(1, 'Chanel', 'Luxury French fashion house', '2025-05-15 21:00:10'),
(2, 'Dior', 'French luxury goods company', '2025-05-15 21:00:10'),
(3, 'Tom Ford', 'American luxury fashion house', '2025-05-15 21:00:10'),
(4, 'Jo Malone', 'British perfume and scented candle brand', '2025-05-15 21:00:10'),
(5, 'Guerlain', 'French perfume, cosmetics and skincare house', '2025-05-15 21:00:10');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `createdAt` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `createdAt`) VALUES
(1, 'Floral', 'Fragrances with prominent flower notes', '2025-05-15 21:00:10'),
(2, 'Oriental', 'Rich, warm and exotic fragrances', '2025-05-15 21:00:10'),
(3, 'Woody', 'Fragrances with prominent wood notes', '2025-05-15 21:00:10'),
(4, 'Fresh', 'Light and citrusy fragrances', '2025-05-15 21:00:10'),
(5, 'Fougère', 'Fern-like fragrances with lavender notes', '2025-05-15 21:00:10');

-- --------------------------------------------------------

--
-- Table structure for table `orderitems`
--

CREATE TABLE `orderitems` (
  `id` int(11) NOT NULL,
  `orderId` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `pricePerUnit` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `createdAt` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `totalAmount` decimal(10,2) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `createdAt` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stockQuantity` int(11) NOT NULL DEFAULT 0,
  `brandId` int(11) DEFAULT NULL,
  `categoryId` int(11) DEFAULT NULL,
  `imageUrl` varchar(255) DEFAULT NULL,
  `volumeMl` int(11) DEFAULT NULL,
  `concentration` varchar(50) DEFAULT NULL,
  `createdAt` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `stockQuantity`, `brandId`, `categoryId`, `imageUrl`, `volumeMl`, `concentration`, `createdAt`) VALUES
(1, 'Chanel N°5', 'Iconic aldehydic floral fragrance launched in 1921. Features notes of aldehydes, jasmine, rose, and sandalwood.', 135.00, 50, 1, 1, 'images/product1.jpg', 50, 'Eau de Parfum', '2025-05-15 21:00:10'),
(2, 'Miss Dior', 'Elegant and feminine fragrance with notes of rose, jasmine and citrus.', 120.00, 60, 2, 1, 'images/product2.jpg', 50, 'Eau de Parfum', '2025-05-15 21:00:10'),
(3, 'Black Orchid', 'Luxurious and sensual with top notes of jasmine, black truffle, and black orchid.', 150.00, 30, 3, 2, 'images/product3.jpg', 50, 'Eau de Parfum', '2025-05-15 21:00:10'),
(4, 'Sauvage', 'A powerful fresh and spicy fragrance with notes of Bergamot and Ambroxan.', 155.00, 40, 2, 4, 'images/product4.jpg', 100, 'Eau de Toilette', '2025-05-15 21:00:10'),
(5, 'Bleu de Chanel', 'An elegant woody aromatic fragrance with citrus and amber notes.', 165.00, 35, 1, 3, 'images/product5.jpg', 100, 'Eau de Toilette', '2025-05-15 21:00:10'),
(6, 'Neroli Portofino', 'A fresh citrus aromatic blend capturing the essence of the Italian Riviera.', 295.00, 25, 3, 4, 'images/product6.jpg', 50, 'Eau de Parfum', '2025-05-15 21:00:10');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `createdAt` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `createdAt`) VALUES
(1, 'just4zzzz', 'just4z@gmail.com', '$2y$10$/mXKsX/JgUgcL6cSyADxd.mNbuMp6uJDfGQm8uwz2wdkfVo5XmAwS', '2025-05-17 00:49:21'),
(2, 'kharyzzz', 'kharyz@gmail', '$2y$10$02kc2jHmqfPIT/N51ElxPOxSfqe0pKe9iVXCPzd1qkCbyHtUdBwUS', '2025-05-17 00:55:11'),
(3, 'wwww', 'wwww@gmail', '$2y$10$wmEjLSoWWUvobBwWrGuYmeHSgwxLfyHNn5JEcyvCq0WfKieSb6bRK', '2025-05-17 00:58:26'),
(4, 'Mistytyty', 'zzz@gmail', '$2y$10$PQOgWF8FoeIfvYi.aolIHOsjI/nVt4rC5MmSTQzORGM4giDfucP1O', '2025-05-17 01:03:08'),
(5, 'aaaaaaaa', 'kharyzz@gmail', '12345678', '2025-05-17 01:07:02');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orderId` (`orderId`),
  ADD KEY `productId` (`productId`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `brandId` (`brandId`),
  ADD KEY `categoryId` (`categoryId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`),
  ADD KEY `productId` (`productId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orderitems`
--
ALTER TABLE `orderitems`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD CONSTRAINT `orderitems_ibfk_1` FOREIGN KEY (`orderId`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `orderitems_ibfk_2` FOREIGN KEY (`productId`) REFERENCES `products` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`brandId`) REFERENCES `brands` (`id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`categoryId`) REFERENCES `categories` (`id`);

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`productId`) REFERENCES `products` (`id`);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
