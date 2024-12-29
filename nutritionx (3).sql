-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 29, 2024 at 06:17 PM
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
-- Database: `nutritionx`
--

-- --------------------------------------------------------

--
-- Table structure for table `daily_logs`
--

CREATE TABLE `daily_logs` (
  `LogID` int(11) NOT NULL,
  `Date` date NOT NULL,
  `MealType` varchar(50) NOT NULL,
  `FoodName` varchar(100) NOT NULL,
  `Grams` float NOT NULL,
  `Protein` float NOT NULL,
  `Carbohydrates` float NOT NULL,
  `Fats` float NOT NULL,
  `Calories` float NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `daily_logs`
--

INSERT INTO `daily_logs` (`LogID`, `Date`, `MealType`, `FoodName`, `Grams`, `Protein`, `Carbohydrates`, `Fats`, `Calories`, `user_id`) VALUES
(3, '2024-12-29', 'Breakfast', 'Apple Pie', 40, 0.8, 13.6, 4.4, 94.8, NULL),
(19, '2024-12-29', 'Lunch', 'Broccoli', 180, 6.66, 19.8, 1.08, 99, NULL),
(20, '2024-12-29', 'Breakfast', 'Broccoli', 200, 7.4, 22, 1.2, 110, 8),
(21, '2024-12-29', 'Snacks', 'Broccoli', 2500, 92.5, 275, 15, 1375, 8),
(27, '2024-12-29', 'Snacks', 'cheescake', 20000, 180, 0, 40, 0, 10),
(28, '2024-12-29', 'Dinner', 'Cucumber', 150, 1.05, 5.4, 0.15, 24, 10),
(29, '2024-12-29', 'Breakfast', 'Eggplant', 500, 5, 30, 1, 125, 10);

-- --------------------------------------------------------

--
-- Table structure for table `foods`
--

CREATE TABLE `foods` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `protein` decimal(5,2) NOT NULL,
  `carbs` decimal(5,2) NOT NULL,
  `fat` decimal(5,2) NOT NULL,
  `sodium` decimal(7,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `food_items`
--

CREATE TABLE `food_items` (
  `FoodID` int(11) NOT NULL,
  `FoodName` varchar(100) NOT NULL,
  `Category` varchar(50) DEFAULT NULL,
  `Calories` int(11) NOT NULL,
  `Protein` float NOT NULL,
  `Carbohydrates` float NOT NULL,
  `Fats` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `food_items`
--

INSERT INTO `food_items` (`FoodID`, `FoodName`, `Category`, `Calories`, `Protein`, `Carbohydrates`, `Fats`) VALUES
(1, 'Broccoli', 'Vegetable', 55, 3.7, 11, 0.6),
(2, 'Carrot', 'Vegetable', 41, 0.9, 10, 0.2),
(3, 'Spinach', 'Vegetable', 23, 2.9, 3.6, 0.4),
(4, 'Zucchini', 'Vegetable', 17, 1.2, 3.1, 0.3),
(5, 'Tomato', 'Vegetable', 18, 0.9, 3.9, 0.2),
(6, 'Cauliflower', 'Vegetable', 25, 1.9, 5, 0.3),
(7, 'Green Beans', 'Vegetable', 31, 2, 7, 0.1),
(8, 'Sweet Potato', 'Vegetable', 86, 1.6, 20, 0.1),
(9, 'Cucumber', 'Vegetable', 16, 0.7, 3.6, 0.1),
(10, 'Eggplant', 'Vegetable', 25, 1, 6, 0.2),
(11, 'Apple', 'Fruit', 52, 0.3, 14, 0.2),
(12, 'Banana', 'Fruit', 89, 1.1, 23, 0.3),
(13, 'Strawberry', 'Fruit', 32, 0.7, 7.7, 0.3),
(14, 'Orange', 'Fruit', 43, 0.9, 9, 0.1),
(15, 'Watermelon', 'Fruit', 30, 0.6, 8, 0.2),
(16, 'Pineapple', 'Fruit', 50, 0.5, 13, 0.1),
(17, 'Mango', 'Fruit', 60, 0.8, 15, 0.4),
(18, 'Avocado', 'Fruit', 160, 2, 9, 15),
(19, 'Pear', 'Fruit', 57, 0.4, 15, 0.1),
(20, 'Kiwi', 'Fruit', 61, 1.1, 15, 0.5),
(21, 'Brown Rice', 'Grain', 123, 2.7, 25.6, 0.9),
(22, 'Oats', 'Grain', 389, 16.9, 66.3, 6.9),
(23, 'Quinoa', 'Grain', 120, 4.1, 21.3, 1.9),
(24, 'White Rice', 'Grain', 130, 2.4, 28, 0.3),
(25, 'Barley', 'Grain', 354, 12.5, 73.5, 2.3),
(26, 'Chicken Breast', 'Meat', 165, 31, 0, 3.6),
(27, 'Ground Beef (85% lean)', 'Meat', 250, 26, 0, 17),
(28, 'Turkey', 'Meat', 189, 29, 0, 7.4),
(29, 'Pork Chop', 'Meat', 231, 23, 0, 15),
(30, 'Duck Breast', 'Meat', 337, 23.5, 0, 28.4),
(31, 'Salmon', 'Fish', 208, 20, 0, 13),
(32, 'Tuna', 'Fish', 132, 29, 0, 0.8),
(33, 'Shrimp', 'Fish', 99, 24, 0.2, 0.3),
(34, 'Crab', 'Fish', 97, 20, 0, 1.5),
(35, 'Tilapia', 'Fish', 96, 20, 0, 1.7),
(36, 'Milk', 'Dairy', 42, 3.4, 5, 1),
(37, 'Cheddar Cheese', 'Dairy', 403, 25, 1.3, 33),
(38, 'Greek Yogurt', 'Dairy', 59, 10, 3.6, 0.4),
(39, 'Egg', 'Dairy', 68, 6, 0.4, 4.8),
(40, 'Butter', 'Dairy', 717, 0.9, 0.1, 81),
(41, 'Almonds', 'Nuts', 579, 21, 22, 50),
(42, 'Peanuts', 'Nuts', 567, 25.8, 16.1, 49.2),
(43, 'Chia Seeds', 'Seeds', 486, 16, 42, 30),
(44, 'Sunflower Seeds', 'Seeds', 584, 20, 20, 51),
(45, 'Walnuts', 'Nuts', 654, 15, 14, 65),
(46, 'Asparagus', 'Vegetable', 20, 2.2, 3.9, 0.2),
(47, 'Kale', 'Vegetable', 49, 4.3, 8.8, 0.9),
(48, 'Red Bell Pepper', 'Vegetable', 31, 1, 6, 0.3),
(49, 'Brussels Sprouts', 'Vegetable', 43, 3.4, 9, 0.3),
(50, 'Beetroot', 'Vegetable', 43, 1.6, 10, 0.2),
(51, 'Papaya', 'Fruit', 43, 0.5, 11, 0.3),
(52, 'Blueberries', 'Fruit', 57, 0.7, 14.5, 0.3),
(53, 'Raspberries', 'Fruit', 52, 1.2, 12, 0.7),
(54, 'Grapes', 'Fruit', 69, 0.6, 18, 0.2),
(55, 'Cherries', 'Fruit', 50, 1, 12, 0.3),
(56, 'Farro', 'Grain', 170, 6, 35, 1.5),
(57, 'Bulgur', 'Grain', 151, 5.6, 33.8, 0.4),
(58, 'Teff', 'Grain', 367, 13.3, 73.1, 2.4),
(59, 'Cornmeal', 'Grain', 384, 7.2, 73, 1.8),
(60, 'Rice Noodles', 'Grain', 192, 3, 43, 0.4),
(61, 'Lamb', 'Meat', 294, 25, 0, 21),
(62, 'Ham', 'Meat', 145, 21, 0, 5.5),
(63, 'Venison', 'Meat', 158, 30.2, 0, 3.3),
(64, 'Goat Meat', 'Meat', 143, 27, 0, 3),
(65, 'Rabbit', 'Meat', 173, 29, 0, 6),
(66, 'Lobster', 'Fish', 89, 19, 0, 1),
(67, 'Octopus', 'Fish', 82, 14, 3, 1),
(68, 'Clams', 'Fish', 74, 12.8, 4.5, 0.6),
(69, 'Cod', 'Fish', 82, 18, 0, 0.7),
(70, 'Mussels', 'Fish', 86, 12, 3, 2),
(71, 'Swiss Cheese', 'Dairy', 380, 27, 1.4, 30),
(72, 'Feta Cheese', 'Dairy', 264, 14, 4, 21),
(73, 'Ricotta Cheese', 'Dairy', 174, 7.5, 7, 13),
(74, 'Heavy Whipping Cream', 'Dairy', 345, 2.8, 2.8, 37),
(75, 'Skim Milk', 'Dairy', 35, 3.4, 5, 0.1),
(76, 'Macadamia Nuts', 'Nuts', 718, 7.9, 13.8, 75.8),
(77, 'Pecans', 'Nuts', 691, 9.2, 13.9, 72),
(78, 'Hemp Seeds', 'Seeds', 553, 31.6, 8.7, 48.8),
(79, 'Sesame Butter (Tahini)', 'Seeds', 595, 17, 21, 53),
(80, 'Brazil Nuts', 'Nuts', 659, 14, 12, 67),
(81, 'Popcorn (Air-Popped)', 'Snack', 31, 1, 6.2, 0.4),
(82, 'Trail Mix', 'Snack', 462, 12, 49, 27),
(83, 'Potato Chips', 'Snack', 536, 7, 53, 36),
(84, 'Granola', 'Snack', 471, 10, 64, 20),
(85, 'Fruit Leather', 'Snack', 80, 0.5, 20, 0),
(86, 'Cheesecake', 'Dessert', 321, 5.5, 25, 23),
(87, 'Brownie', 'Dessert', 466, 5.9, 58, 23),
(88, 'Apple Pie', 'Dessert', 237, 2, 34, 11),
(89, 'Pancakes (with Syrup)', 'Dessert', 275, 6, 40, 9),
(90, 'Custard', 'Dessert', 122, 3.8, 17, 4.3),
(91, 'smk', 'Lunch', 0, 100, 0, 0),
(92, 'cheescake', 'Dessert', 0, 0.9, 0, 0.2),
(93, 'Tomato', 'Breakfast', 0, 0.9, 3.9, 0.2),
(94, 'Watermelon', 'Lunch', 0, 0.6, 8, 0.2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `weight` float NOT NULL,
  `height` float NOT NULL,
  `calories` int(11) NOT NULL,
  `protein` int(11) NOT NULL,
  `carbs` int(11) NOT NULL,
  `fat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `FirstName`, `LastName`, `Email`, `Password`, `weight`, `height`, `calories`, `protein`, `carbs`, `fat`) VALUES
(2, 'seif', 'tamer', 'seift188@gmail.com', '$2y$10$YkrJPZ2tO3oXqjLs25p3Oefb54L9Cn0dSxmDEsK4aiESIrZ7Ixkdm', 80, 175, 1773, 160, 221, 49),
(3, 'fkdsfkljds', 'fsddsdfsf', 'ayhaga@Gmail.com', '$2y$10$mGmyjKh90PmVNnRg0UbGXukl/ViD5yH50y5Hm8FIEM3ORNZho.TiO', 80, 170, 1742, 160, 217, 48),
(4, 'fkdsfkljdssss', 'ssss', 'ayhsaga@Gmail.com', '$2y$10$Xei3leHIqCdC2chrnH0lSuz7q0i6.80Np/tSk0aDL2PGLqzBrjfC.', 80, 170, 1742, 160, 217, 48),
(7, 'fkdsfkljdssssss', 'ssssss', 'ayshsaga@Gmail.com', '$2y$10$1wUtRxNnGwVpDg0wji2g5OvjburHkgJ.4nagrCfXih.BF/jN.AqZa', 80, 170, 1742, 160, 217, 48),
(8, 'seif', 'tamer', 'seift18833@gmail.com', '$2y$10$brtjWOoK0x8fwVCKj1A4zOYkvn3b0SISPrEgGd7/y7/xIod4V3qbG', 70, 176, 1680, 140, 210, 46),
(10, 'seif ', 'al gamed', 'ayhsssaga@Gmail.com', '$2y$10$jlDIugZNnwe.w06PQWxnWeIXbSrNh1v0uqos7y.eC/lpi/pmE/8yq', 72, 176, 1700, 144, 212, 47);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `daily_logs`
--
ALTER TABLE `daily_logs`
  ADD PRIMARY KEY (`LogID`);

--
-- Indexes for table `foods`
--
ALTER TABLE `foods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `food_items`
--
ALTER TABLE `food_items`
  ADD PRIMARY KEY (`FoodID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `daily_logs`
--
ALTER TABLE `daily_logs`
  MODIFY `LogID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `foods`
--
ALTER TABLE `foods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `food_items`
--
ALTER TABLE `food_items`
  MODIFY `FoodID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
