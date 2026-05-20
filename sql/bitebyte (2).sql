-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 20, 2026 at 01:34 PM
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
-- Database: `bitebyte`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `CategoriesID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`CategoriesID`, `Name`) VALUES
(5, 'Breakfast'),
(4, 'Dessert'),
(6, 'Dinner'),
(1, 'Lunch'),
(3, 'Meal'),
(2, 'Snack');

-- --------------------------------------------------------

--
-- Table structure for table `recipes`
--

CREATE TABLE `recipes` (
  `RecipesID` int(11) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Description` text DEFAULT NULL,
  `Instructions` text DEFAULT NULL,
  `ImageURL` varchar(255) DEFAULT NULL,
  `TimeNeeded` int(11) NOT NULL,
  `Difficulty` enum('1','2','3','4','5') DEFAULT '1',
  `PotsNum` int(11) DEFAULT 1,
  `Rating` decimal(3,2) DEFAULT 0.00,
  `UsersID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recipes`
--

INSERT INTO `recipes` (`RecipesID`, `Title`, `Description`, `Instructions`, `ImageURL`, `TimeNeeded`, `Difficulty`, `PotsNum`, `Rating`, `UsersID`) VALUES
(1, 'Salad Sandwich', 'Now THIS is a veggie sandwich worth making!', 'Step 1: This is step 1|Step 2: This is step 2|Step 3: This is step 3|Step 4: This is step 4', 'salad_sandwich.jpg', 20, '1', 2, 4.00, NULL),
(2, 'Biscotti', 'A classic almond version of the famous Italian biscuit.', 'Step 1: This is step 1|Step 2: This is step 2|Step 3: This is step 3|Step 4: This is step 4', 'biscotti.jpg', 90, '3', 5, 5.00, NULL),
(3, 'Zucchini Slice', 'Zucchini Slice is an Australian classic, a cross between a frittata and a savoury muffin.', 'Step 1: This is step 1|Step 2: This is step 2|Step 3: This is step 3|Step 4: This is step 4', 'zucchini_slice.jpg', 25, '3', 4, 3.00, NULL),
(4, 'Tzatziki', 'Greek masterpiece', 'Step 1: This is step 1|Step 2: This is step 2|Step 3: This is step 3|Step 4: This is step 4', 'tzatziki.jpg', 8, '1', 1, 5.00, NULL),
(5, 'Chocolate Frosting', 'Delicious', 'Step 1: This is step 1|Step 2: This is step 2|Step 3: This is step 3|Step 4: This is step 4', 'choc_frosting.jpg', 15, '1', 2, 5.00, NULL),
(6, 'Lemon Curd', 'My recipe for a simple homemade lemon curd.', 'Step 1: This is step 1|Step 2: This is step 2|Step 3: This is step 3|Step 4: This is step 4', 'lemon_curd.jpg', 20, '2', 1, 4.00, NULL),
(7, 'Prawn Burgers', 'Prawn burgers (shrimp burgers) are bizarrely hard to find – but you can make them at home in under 30 minutes!', 'Step 1: This is step 1|Step 2: This is step 2|Step 3: This is step 3|Step 4: This is step 4', 'prawn_burgers.jpg', 25, '3', 2, 5.00, NULL),
(8, 'Crepes', 'Crepes are one of the easiest and most loved French dishes.', 'Step 1: This is step 1|Step 2: This is step 2|Step 3: This is step 3|Step 4: This is step 4', 'crepes.jpg', 20, '1', 2, 4.00, NULL),
(9, 'Vodka Pasta', 'This Vodka Pasta, or Penne alla Vodka, tastes like it came straight from a cosy Italian trattoria.', 'Step 1: This is step 1|Step 2: This is step 2|Step 3: This is step 3|Step 4: This is step 4', 'vodka_pasta.jpg', 30, '4', 4, 3.00, NULL),
(10, 'Peanut Butter Brownies', 'Peanut Butter Brownies are great.', 'Step 1: This is step 1|Step 2: This is step 2|Step 3: This is step 3|Step 4: This is step 4', 'brownies.jpg', 90, '4', 6, 5.00, NULL),
(11, 'Charcoal Chicken Skewers', 'So heavily flavoured they’re practically crusted with seasoning', 'Step 1: This is step 1| Step 2: This is step 2| Step 3: This is step 3| Step 4: This is step 4', 'chicken_skewers.jpg', 45, '2', 1, 5.00, NULL),
(12, 'Chimichurri Butter', 'Chimichurri Butter is an enriched butter version of classic Argentinian Chimichurri Sauce', 'Step 1: This is step 1| Step 2: This is step 2| Step 3: This is step 3| Step 4: This is step 4', 'cimicuri_butter.jpg', 20, '1', 2, 2.00, NULL),
(13, 'Baked Sausage Breakfast', 'This is a big, hearty breakfast hash loaded with sausages, bacon, eggs and potatoes', 'Step 1: This is step 1| Step 2: This is step 2| Step 3: This is step 3| Step 4: This is step 4', 'sausages_eggs.jpg', 80, '3', 5, 5.00, NULL),
(14, 'Lebanese Fattoush Salad', 'Fattoush is a fresh Middle Eastern salad made with crispy toasted or fried pieces of pita bread jumbled with lettuce, cucumbers, tomatoes, radishes, and capsicum (bell peppers), tossed with a zesty lemon dressing.', 'Step 1: This is step 1| Step 2: This is step 2| Step 3: This is step 3| Step 4: This is step 4', 'fattoush.jpg', 15, '2', 1, 4.00, NULL),
(15, 'Beef Tataki', 'Here’s a Beef Tataki recipe, a modern Japanese favorite with thinly sliced beef in a citrusy dressing.', 'Step 1: This is step 1| Step 2: This is step 2| Step 3: This is step 3| Step 4: This is step 4', 'beef_tataki.jpg', 100, '4', 3, 4.00, NULL),
(16, 'Oven-baked Quesadillas', 'This is a handy recipe for crispy quesadillas baked in the oven.', 'Step 1: This is step 1| Step 2: This is step 2| Step 3: This is step 3| Step 4: This is step 4', 'quasadillas.jpg', 30, '2', 2, 5.00, NULL),
(17, 'Chicken Noodle Soup', 'This creamy chicken noodle soup is a homemade version of instant cup-a-soup.', 'Step 1: This is step 1| Step 2: This is step 2| Step 3: This is step 3| Step 4: This is step 4', 'chicken_noodle_soup.jpg', 140, '5', 6, 4.00, NULL),
(18, 'Vietnamese pickled carrots', 'Pickles are an essential part of Vietnamese food!', 'Step 1: This is step 1| Step 2: This is step 2| Step 3: This is step 3| Step 4: This is step 4', 'pickled_carrot.jpg', 8, '1', 1, 3.00, NULL),
(19, 'Chocolate Cream Pie', 'A magnificent Chocolate Cream Pie with a chocolate biscuit base, a creamy smooth chocolate custard filling, topped with clouds of cream.', 'Step 1: This is step 1| Step 2: This is step 2| Step 3: This is step 3| Step 4: This is step 4', 'cream_pie.jpg', 100, '5', 10, 3.00, NULL),
(20, 'Tuna sandwich', 'Here’s my classic tuna sandwich. Tuna in oil, not water.', 'Step 1: This is step 1| Step 2: This is step 2| Step 3: This is step 3| Step 4: This is step 4', 'tuna_sandwich.jpg', 12, '2', 2, 2.00, NULL),
(21, 'Waffles', 'Waffles are easy – same ingredients as pancakes, just different ratios so they’re crispy outside, soft and fluffy inside.', 'Step 1: This is step 1| Step 2: This is step 2| Step 3: This is step 3| Step 4: This is step 4', 'waffles.jpg', 35, '2', 5, 4.00, NULL),
(22, 'Chicken Marsala', 'hicken Marsala – Golden crispy cutlets, rich creamy Marsala wine sauce.', 'Step 1: This is step 1| Step 2: This is step 2| Step 3: This is step 3| Step 4: This is step 4', 'marsala.jpg', 40, '3', 4, 5.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `recipescat`
--

CREATE TABLE `recipescat` (
  `RecipesID` int(11) NOT NULL,
  `CategoriesID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recipescat`
--

INSERT INTO `recipescat` (`RecipesID`, `CategoriesID`) VALUES
(1, 1),
(1, 5),
(2, 2),
(2, 4),
(3, 1),
(3, 3),
(3, 6),
(4, 2),
(4, 5),
(5, 4),
(6, 4),
(7, 1),
(7, 3),
(7, 6),
(8, 4),
(9, 1),
(9, 3),
(10, 2),
(10, 4),
(11, 1),
(11, 2),
(11, 3),
(12, 2),
(13, 3),
(13, 5),
(14, 1),
(14, 3),
(14, 6),
(15, 1),
(15, 3),
(16, 2),
(16, 3),
(16, 6),
(17, 1),
(17, 3),
(18, 2),
(19, 4),
(20, 3),
(20, 5),
(21, 2),
(21, 4),
(22, 1),
(22, 3);

-- --------------------------------------------------------

--
-- Table structure for table `recipestag`
--

CREATE TABLE `recipestag` (
  `RecipesID` int(11) NOT NULL,
  `TagsID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recipestag`
--

INSERT INTO `recipestag` (`RecipesID`, `TagsID`) VALUES
(1, 1),
(1, 3),
(1, 5),
(2, 4),
(2, 10),
(3, 6),
(4, 1),
(4, 3),
(4, 4),
(4, 5),
(4, 8),
(4, 11),
(5, 1),
(5, 3),
(6, 1),
(6, 3),
(7, 7),
(8, 4),
(8, 7),
(9, 9),
(10, 4),
(10, 8),
(11, 2),
(11, 4),
(11, 8),
(12, 4),
(12, 6),
(12, 8),
(13, 7),
(14, 1),
(14, 3),
(14, 5),
(14, 7),
(14, 8),
(15, 7),
(16, 4),
(17, 9),
(18, 2),
(18, 4),
(18, 8),
(19, 7),
(19, 8),
(20, 1),
(20, 3),
(20, 8),
(21, 4);

-- --------------------------------------------------------

--
-- Table structure for table `support`
--

CREATE TABLE `support` (
  `SupportID` int(11) NOT NULL,
  `Type` varchar(100) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Email` varchar(150) NOT NULL,
  `Subject` varchar(255) NOT NULL,
  `Problem` text NOT NULL,
  `Status` enum('Open','Solved') DEFAULT 'Open',
  `SubmitTime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `TagsID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`TagsID`, `Name`) VALUES
(4, 'Cheap'),
(2, 'Gluten free'),
(11, 'Greek'),
(7, 'High-Protein'),
(10, 'Italian'),
(8, 'Low-Fat'),
(9, 'Pasta'),
(5, 'Raw'),
(6, 'Sugar-Free'),
(1, 'Vegan'),
(3, 'Vegetarian');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UsersID` int(11) NOT NULL,
  `Username` varchar(100) NOT NULL,
  `Email` varchar(150) NOT NULL,
  `Pass` varchar(255) NOT NULL,
  `RegDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UsersID`, `Username`, `Email`, `Pass`, `RegDate`) VALUES
(1, 'milutin1234.', 'milutinmilankovic@gmail.com', '$2y$10$wfyzGHp6NDiT4DwMa17CP.YjD3glXJy1Scs.V7WSx.R7Lz4zGHlJ6', '2026-05-19 19:26:03'),
(2, 'karadzicazbuka', 'vukkaradzic@gmail.com', '$2y$10$cewRsAO3xkEmAOnHrgrY0eYp1dh6Nk65FMY13HirheJwsJPZEbYpW', '2026-05-19 19:33:59'),
(3, 'teslastrujavau', 'nikolatesla@gmail.com', '$2y$10$yXWaVFBt/oZa7YLathfcbeI43iE/R7DFE3rz6JkhsxLaQDEhNijr6', '2026-05-19 19:41:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`CategoriesID`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Indexes for table `recipes`
--
ALTER TABLE `recipes`
  ADD PRIMARY KEY (`RecipesID`),
  ADD KEY `fk_recipe_user` (`UsersID`);

--
-- Indexes for table `recipescat`
--
ALTER TABLE `recipescat`
  ADD PRIMARY KEY (`RecipesID`,`CategoriesID`),
  ADD KEY `fk_pivot_cat` (`CategoriesID`);

--
-- Indexes for table `recipestag`
--
ALTER TABLE `recipestag`
  ADD PRIMARY KEY (`RecipesID`,`TagsID`),
  ADD KEY `fk_pivot_tag` (`TagsID`);

--
-- Indexes for table `support`
--
ALTER TABLE `support`
  ADD PRIMARY KEY (`SupportID`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`TagsID`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UsersID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `CategoriesID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `recipes`
--
ALTER TABLE `recipes`
  MODIFY `RecipesID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `support`
--
ALTER TABLE `support`
  MODIFY `SupportID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `TagsID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UsersID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `recipes`
--
ALTER TABLE `recipes`
  ADD CONSTRAINT `fk_recipe_user` FOREIGN KEY (`UsersID`) REFERENCES `users` (`UsersID`) ON DELETE SET NULL;

--
-- Constraints for table `recipescat`
--
ALTER TABLE `recipescat`
  ADD CONSTRAINT `fk_pivot_cat` FOREIGN KEY (`CategoriesID`) REFERENCES `categories` (`CategoriesID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_pivot_recipe_cat` FOREIGN KEY (`RecipesID`) REFERENCES `recipes` (`RecipesID`) ON DELETE CASCADE;

--
-- Constraints for table `recipestag`
--
ALTER TABLE `recipestag`
  ADD CONSTRAINT `fk_pivot_recipes_tag` FOREIGN KEY (`RecipesID`) REFERENCES `recipes` (`RecipesID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_pivot_tag` FOREIGN KEY (`TagsID`) REFERENCES `tags` (`TagsID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
