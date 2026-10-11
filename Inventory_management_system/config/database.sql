-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Oct 11, 2026 at 12:40 PM
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
-- Database: `inventory_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `created_at`) VALUES
(1, 'Electronics', '2026-08-07 11:38:39'),
(2, 'Furniture', '2026-08-07 11:38:39'),
(3, 'Office Supplies', '2026-08-07 11:38:39'),
(4, 'Computer Peripherals', '2026-08-07 11:38:39'),
(5, 'Stationery', '2026-08-07 11:38:39'),
(6, 'Stationary', '2026-08-07 11:39:54');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `stock_qty` int(11) NOT NULL DEFAULT 0,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `low_stock_threshold` int(11) NOT NULL DEFAULT 10,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `category_id`, `supplier_id`, `stock_qty`, `price`, `low_stock_threshold`, `created_at`, `updated_at`) VALUES
(1, 'Laptop Pro X1', 1, 1, 15, 75000.00, 5, '2026-08-07 11:38:39', '2026-08-07 11:38:39'),
(2, 'Wireless Mouse', 4, 1, 50, 1500.00, 10, '2026-08-07 11:38:39', '2026-08-07 11:38:39'),
(3, 'Ergonomic Desk Chair', 2, 2, 8, 8500.00, 5, '2026-08-07 11:38:39', '2026-08-07 11:38:39'),
(4, 'USB-C Hub 7-in-1', 4, 3, 25, 2500.00, 8, '2026-08-07 11:38:39', '2026-08-07 11:38:39'),
(5, 'Premium Paper Ream', 5, 2, 100, 350.00, 20, '2026-08-07 11:38:39', '2026-08-07 11:38:39'),
(6, 'Monitor 27-inch 4K', 1, 4, 12, 35000.00, 5, '2026-08-07 11:38:39', '2026-08-07 11:38:39'),
(7, 'Standing Desk', 2, 2, 6, 28000.00, 3, '2026-08-07 11:38:39', '2026-08-07 11:38:39'),
(8, 'Wireless Keyboard', 4, 1, 35, 2800.00, 10, '2026-08-07 11:38:39', '2026-08-07 11:38:39'),
(9, 'Stapler Heavy Duty', 5, 3, 60, 450.00, 15, '2026-08-07 11:38:39', '2026-08-07 11:38:39'),
(10, 'Desktop PC Workstation', 1, 4, 10, 55000.00, 4, '2026-08-07 11:38:39', '2026-08-07 11:38:39'),
(11, 'Pen Pineapple Pen', 6, 5, 9, 110.00, 10, '2026-08-07 11:39:54', '2026-08-17 00:56:40'),
(12, 'Kawaii', 6, 6, 10, 1000.00, 5, '2026-08-07 12:21:57', '2026-08-17 01:31:59'),
(13, 'Ballpoint Pen (Box)', 3, 7, 50, 10.00, 10, '2026-08-16 16:55:00', '2026-08-16 16:55:00'),
(14, 'ApplePen', 6, 5, 11, 120.00, 10, '2026-08-16 23:44:15', '2026-08-16 23:45:00'),
(15, 'Office chair', 3, 7, 100, 2500.00, 10, '2026-08-17 00:36:55', '2026-08-17 00:36:55'),
(16, 'Office table', 3, 7, 100, 3000.00, 10, '2026-08-17 00:38:46', '2026-08-17 00:38:46');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `supplier_id` int(11) NOT NULL,
  `supplier_name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`supplier_id`, `supplier_name`, `created_at`) VALUES
(1, 'TechSupply Inc.', '2026-08-07 11:38:39'),
(2, 'OfficePlus', '2026-08-07 11:38:39'),
(3, 'Global Distributors', '2026-08-07 11:38:39'),
(4, 'Prime Electronics', '2026-08-07 11:38:39'),
(5, 'Shupeh.ph', '2026-08-07 11:39:54'),
(6, 'Lazado.ph', '2026-08-07 12:21:57'),
(7, 'Office Inc.', '2026-08-16 16:55:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','employee') NOT NULL DEFAULT 'employee',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'admin', '$2b$10$yUdaWWcASOkM4DpEQ.W9g.aMkjTQGvG6jiI.gCmSiblfT.KjfQFcm', 'admin', '2026-08-17 00:08:26'),
(2, 'user', '$2y$10$WjFyt5ZvUPHdHrrH8OK5NujozSDd98TmzLdXg.NJza8LnJgpHeVgO', 'employee', '2026-08-17 00:16:25'),
(3, 'New', '$2y$10$ylrFzUgRdRLGetzl9Il0O.adVCa5CVijmtz5iY6N.TT0fUKD0EkTe', 'employee', '2026-08-17 00:39:32');

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_inventory_summary`
-- (See below for the actual view)
--
CREATE TABLE `vw_inventory_summary` (
`product_id` int(11)
,`product_name` varchar(255)
,`category_name` varchar(100)
,`supplier_name` varchar(100)
,`stock_qty` int(11)
,`low_stock_threshold` int(11)
,`price` decimal(10,2)
,`total_value` decimal(20,2)
,`stock_status` varchar(12)
,`created_at` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_low_stock_products`
-- (See below for the actual view)
--
CREATE TABLE `vw_low_stock_products` (
`product_id` int(11)
,`product_name` varchar(255)
,`category_name` varchar(100)
,`supplier_name` varchar(100)
,`stock_qty` int(11)
,`low_stock_threshold` int(11)
,`price` decimal(10,2)
,`total_value` decimal(20,2)
,`stock_status` varchar(12)
,`created_at` timestamp
);

-- --------------------------------------------------------

--
-- Structure for view `vw_inventory_summary`
--
DROP TABLE IF EXISTS `vw_inventory_summary`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_inventory_summary`  AS SELECT `p`.`product_id` AS `product_id`, `p`.`product_name` AS `product_name`, `c`.`category_name` AS `category_name`, `s`.`supplier_name` AS `supplier_name`, `p`.`stock_qty` AS `stock_qty`, `p`.`low_stock_threshold` AS `low_stock_threshold`, `p`.`price` AS `price`, `p`.`stock_qty`* `p`.`price` AS `total_value`, CASE WHEN `p`.`stock_qty` = 0 THEN 'OUT OF STOCK' WHEN `p`.`stock_qty` <= `p`.`low_stock_threshold` THEN 'LOW STOCK' ELSE 'OK' END AS `stock_status`, `p`.`created_at` AS `created_at` FROM ((`products` `p` left join `categories` `c` on(`p`.`category_id` = `c`.`category_id`)) left join `suppliers` `s` on(`p`.`supplier_id` = `s`.`supplier_id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `vw_low_stock_products`
--
DROP TABLE IF EXISTS `vw_low_stock_products`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_low_stock_products`  AS SELECT `vw_inventory_summary`.`product_id` AS `product_id`, `vw_inventory_summary`.`product_name` AS `product_name`, `vw_inventory_summary`.`category_name` AS `category_name`, `vw_inventory_summary`.`supplier_name` AS `supplier_name`, `vw_inventory_summary`.`stock_qty` AS `stock_qty`, `vw_inventory_summary`.`low_stock_threshold` AS `low_stock_threshold`, `vw_inventory_summary`.`price` AS `price`, `vw_inventory_summary`.`total_value` AS `total_value`, `vw_inventory_summary`.`stock_status` AS `stock_status`, `vw_inventory_summary`.`created_at` AS `created_at` FROM `vw_inventory_summary` WHERE `vw_inventory_summary`.`stock_status` in ('LOW STOCK','OUT OF STOCK') ORDER BY `vw_inventory_summary`.`stock_qty` ASC ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `idx_category` (`category_id`),
  ADD KEY `idx_supplier` (`supplier_id`),
  ADD KEY `idx_stock` (`stock_qty`),
  ADD KEY `idx_threshold` (`low_stock_threshold`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`supplier_id`),
  ADD UNIQUE KEY `supplier_name` (`supplier_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`supplier_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
