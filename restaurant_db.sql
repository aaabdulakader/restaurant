-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 09, 2023 at 03:12 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `restaurant_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `reservation_id` int(11) DEFAULT NULL,
  `rating` int(11) NOT NULL,
  `comment` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `customer_id`, `reservation_id`, `rating`, `comment`) VALUES
(1, 6, 1, 4, 'Great experience!'),
(2, 7, 2, 5, 'Excellent service and food'),
(3, 8, 3, 3, 'Average experience'),
(4, 9, 4, 4, 'Friendly staff'),
(5, 10, 5, 2, 'Disappointing quality');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `menu_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`menu_id`, `name`, `description`) VALUES
(1, 'Breakfast Menu', 'Delicious breakfast options'),
(2, 'Lunch Menu', 'Variety of lunch items'),
(3, 'Dinner Menu', 'Elegant dinner choices');

-- --------------------------------------------------------

--
-- Table structure for table `menu_item`
--

CREATE TABLE `menu_item` (
  `item_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_item`
--

INSERT INTO `menu_item` (`item_id`, `menu_id`, `name`, `price`, `description`) VALUES
(2, 1, 'Eggs Benedict', 12.90, 'Classic Brunch Dish'),
(3, 2, 'Caesar Sal', 9.99, 'Fresh Greens and Dressing'),
(4, 2, 'Club Sandwich', 11.99, 'Layered Deli Goodness'),
(5, 3, 'Grilled Salmon', 18.99, 'Succulent Seafood'),
(6, 3, 'Filet Mignon', 29.99, 'Premium Steak Cut'),
(10, 2, 'Rice', 20.00, 'Flavorful Grain Side');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_date` datetime NOT NULL,
  `total_cost` decimal(10,2) NOT NULL,
  `delivery_option` enum('pickup','delivery') NOT NULL,
  `pickup_time` time DEFAULT NULL,
  `delivery_address` text DEFAULT NULL,
  `payment_info` varchar(100) NOT NULL,
  `order_status` enum('pending','processing','completed','cancelled') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `rate` enum('1','2','3','4','5') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `order_date`, `total_cost`, `delivery_option`, `pickup_time`, `delivery_address`, `payment_info`, `order_status`, `created_at`, `updated_at`, `rate`) VALUES
(7, 11, '2023-08-02 03:02:06', 50.96, 'pickup', '04:01:48', '', '', 'completed', '2023-08-02 01:02:06', '2023-08-08 04:00:19', '3'),
(8, 1, '2023-08-02 03:03:25', 21.98, 'pickup', '03:03:23', '', '', 'completed', '2023-08-02 01:03:25', '2023-08-07 22:48:31', '1'),
(9, 1, '2023-08-06 23:36:40', 52.97, 'pickup', '23:36:35', '', '', 'processing', '2023-08-06 21:36:40', '2023-08-08 00:03:19', NULL),
(10, 11, '2023-08-08 06:55:24', 48.96, 'pickup', '06:55:03', '', '', 'processing', '2023-08-08 04:55:24', '2023-08-09 00:38:43', '1'),
(11, 11, '2023-08-09 02:36:24', 22.89, 'pickup', '03:36:13', '', '', 'pending', '2023-08-09 00:36:24', '2023-08-09 00:36:24', '1');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `reservation_id` int(11) NOT NULL,
  `amount` decimal(8,2) NOT NULL,
  `payment_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `customer_id`, `reservation_id`, `amount`, `payment_date`) VALUES
(1, 6, 1, 50.00, '2023-06-10 19:00:00'),
(2, 7, 2, 30.00, '2023-06-11 20:00:00'),
(3, 8, 3, 90.00, '2023-06-12 21:00:00'),
(4, 9, 4, 40.00, '2023-06-13 18:00:00'),
(5, 10, 5, 60.00, '2023-06-14 17:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `reservation_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reservation_date` varchar(255) DEFAULT NULL,
  `num_guests` int(11) NOT NULL,
  `time` varchar(255) DEFAULT NULL,
  `reservation_status` enum('Pending','Confirmed','Cancelled') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`reservation_id`, `user_id`, `reservation_date`, `num_guests`, `time`, `reservation_status`) VALUES
(11, 11, '2023-08-12', 4, '12:23', 'Confirmed'),
(12, 11, '2023-08-17', 3, '12:23', 'Pending'),
(13, 1, '2023-08-11', 9, '14:30', 'Cancelled'),
(14, 11, '2023-08-09', 4, '16:09', 'Pending'),
(15, 11, '2023-08-11', 3, '19:36', 'Confirmed'),
(16, 1, '2023-08-10', 10, '19:39', 'Cancelled');

-- --------------------------------------------------------

--
-- Table structure for table `restaurant`
--

CREATE TABLE `restaurant` (
  `restaurant_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `opening_hours` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `restaurant`
--

INSERT INTO `restaurant` (`restaurant_id`, `name`, `address`, `phone_number`, `opening_hours`) VALUES
(1, 'Delicious Eats', '123 Main St', '5551234567', '9:00 AM - 10:00 PM');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `position` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`user_id`, `full_name`, `position`) VALUES
(2, 'Sarah Anderson', 'Waiter'),
(3, 'Robert Brown', 'Chef'),
(4, 'Amy Davis', 'Bartender');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `role` enum('customer','staff','manager') NOT NULL,
  `phone_number` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `username`, `password`, `email`, `address`, `role`, `phone_number`) VALUES
(1, 'Test Test', 'manager', 'test', 'test@example.com', '123 Main St i', 'manager', '5551234567'),
(2, 'Sarah Johnson', 'sarahjohnson', 'password456', 'sarahjohnson@example.com', '456 Elm St', 'manager', '5559876543'),
(3, 'Michael Williams', 'michaelwilliams', 'password789', 'michaelwilliams@example.com', '789 Oak St', 'customer', '5554567890'),
(4, 'Emily Davis', 'emilydavis', 'passwordabc', 'emilydavis@example.com', '321 Pine St', 'customer', '7778889999'),
(5, 'David Wilson', 'davidwilson', 'passwordxyz', 'davidwilson@example.com', '654 Maple St', 'customer', '4445556666'),
(6, 'Sarah Anderson', 'sarahanderson', 'password789', 'sarahanderson@example.com', '123 Main St', 'staff', '5551234567'),
(7, 'Robert Brown', 'robertbrown', 'passworddef', 'robertbrown@example.com', '456 Elm St', 'staff', '5551234567'),
(8, 'Amy Davis', 'amydavis', 'passwordghi', 'amydavis@example.com', '789 Oak St', 'staff', '5551234567'),
(9, 'Jennifer Adams', 'jenniferadams', 'passwordjkl', 'jenniferadams@example.com', '321 Pine St', 'manager', '5551234567'),
(10, 'Test User', 'testuser', 'passwordtest', 'testuser@example.com', '654 Maple St', 'customer', '5551234567'),
(11, 'Abdulkader Abdi', 'user', 'test', 'aabdulakader@gmail.com', '375 Mackubin St', 'customer', '6122076727'),
(12, 'aa', 'aa', 'test', 'aa@gmail.com', '123 adress', 'customer', '1234567890');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `reservation_id` (`reservation_id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`menu_id`);

--
-- Indexes for table `menu_item`
--
ALTER TABLE `menu_item`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `reservation_id` (`reservation_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservation_id`),
  ADD KEY `customer_id` (`user_id`);

--
-- Indexes for table `restaurant`
--
ALTER TABLE `restaurant`
  ADD PRIMARY KEY (`restaurant_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `menu_item`
--
ALTER TABLE `menu_item`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `restaurant`
--
ALTER TABLE `restaurant`
  MODIFY `restaurant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `menu_item` (`item_id`);

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`reservation_id`);

--
-- Constraints for table `menu_item`
--
ALTER TABLE `menu_item`
  ADD CONSTRAINT `menu_item_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`menu_id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`reservation_id`);

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `staff_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
