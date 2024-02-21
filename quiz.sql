-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 21, 2024 at 02:25 AM
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
-- Database: `quiz`
--

-- --------------------------------------------------------

--
-- Table structure for table `answer`
--

CREATE TABLE `answer` (
  `answerID` int(11) NOT NULL,
  `answerContent` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `answer`
--

INSERT INTO `answer` (`answerID`, `answerContent`) VALUES
(59, 'Starting fresh with a morning routing'),
(60, 'Conquering a daily workout'),
(61, 'Unwinding in your happy place'),
(62, 'Indulging in self-care pampering'),
(63, 'Quick and efficient, perfect for on-the-go'),
(64, 'Structured, because champions plan routines'),
(65, 'Indulgent, because skincare is a self-care ritual'),
(66, 'Flexible, because your routine changes with your mood'),
(67, 'Dehydrated — desperate for moisture'),
(68, 'Balanced — not too oily nor dry'),
(69, 'Prone to breakouts and oiliness'),
(70, 'Sensitive and requiring gentle care'),
(71, 'Fight off fine lines'),
(72, 'Reduce appearance of wrinkles'),
(73, 'Even skin tone and reduced dark spots'),
(82, 'Serum — the energizing booster'),
(83, 'Moisturizing Cream — the hydration hero'),
(84, 'Skincare Set — the ultimate champion\'s routine in one package'),
(85, 'Just me — I am my own laundry champion!'),
(86, '2 to 3 — We\'re a solid team doing laundry!'),
(87, '4 or more — I do laundry for everybody!'),
(88, 'Handwashing — I control how I wash my fabrics and linen.'),
(89, 'Washing machine for speed and convenience'),
(90, 'Easy cleaning and stain removal'),
(91, 'Fragrance boosters and fabric softeners'),
(92, 'Liquid detergent'),
(93, 'Powder detergent'),
(94, 'Fresh and floral scents'),
(95, 'Floral anti-bacteria'),
(96, 'Strong, luxurious fragrances'),
(97, 'Daily — for winning moments'),
(98, 'Every other day — finding balance in my hair game'),
(99, '2 - 3 times a week — letting natural oils work'),
(100, 'Less than once a week — I am a low-maintenance champion'),
(101, 'Straight, like a champion\'s determined path'),
(102, 'Wavy, reflecting my dynamic nature'),
(103, 'Curly, exuding resilience and flair'),
(104, 'Frizzy, showing off my rugged strength'),
(105, 'Dryness and brittleness'),
(106, 'Excessive oiliness'),
(107, 'Split ends and breakage'),
(108, 'Damaged strands'),
(109, 'Thin, lifeless hair'),
(110, 'An oily and itchy scalp'),
(111, 'Dry, frizzy strands'),
(113, 'Classic shampoo & conditioner'),
(114, 'Haircare Set — a champion\'s routine in one package'),
(115, 'Haircare made with innovative ingredients');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `categoryID` int(11) NOT NULL,
  `categoryName` text NOT NULL,
  `userClick` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`categoryID`, `categoryName`, `userClick`) VALUES
(4, 'Skin care', 0),
(5, 'Fabric care', 0),
(6, 'Hair care', 0);

-- --------------------------------------------------------

--
-- Table structure for table `child_question`
--

CREATE TABLE `child_question` (
  `cqID` int(11) NOT NULL,
  `pqID` int(11) NOT NULL,
  `cqContent` text NOT NULL,
  `cqNumOptions` tinyint(4) NOT NULL,
  `cqMinAnswer` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `parent_question`
--

CREATE TABLE `parent_question` (
  `pqID` int(11) NOT NULL,
  `pqContent` text NOT NULL,
  `pqNumOptions` tinyint(4) NOT NULL,
  `pqMinAnswer` tinyint(4) NOT NULL,
  `categoryID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parent_question`
--

INSERT INTO `parent_question` (`pqID`, `pqContent`, `pqNumOptions`, `pqMinAnswer`, `categoryID`) VALUES
(43, 'Which everyday ritual makes you feel like a champion?', 4, 0, 4),
(44, 'What is the ideal skincare routing that kickstarts your triumphant day?', 4, 1, 4),
(45, 'How would you describe your skin before and after your daily activities?', 4, 1, 4),
(46, 'What is the ultimate skincare goal for a winner like you?', 3, 1, 4),
(51, 'Which is your perfect skincare companion and your winning MVP (Most Valuable Product)?', 3, 1, 4),
(52, 'How many everyday champions do you do laundry for?', 3, 0, 5),
(53, 'Which laundry method would receive the gold medal?', 2, 1, 5),
(54, 'Which fabric care benefit would keep your clothes clean and ready to conquer the daily challenges with you?', 2, 1, 5),
(55, 'Which P&G fabric care product would complete your winning combination for cleaning, softening, and refreshing your fabrics and linen?', 2, 1, 5),
(56, 'If you had to pick a scent champion for your everyday life, which scent would it be?', 3, 0, 5),
(57, 'How frequently do you treat your hair?', 4, 1, 6),
(58, 'Which hair pattern perfectly describes your hair?', 4, 1, 6),
(59, 'How would you describe your skin before and after your daily activities', 3, 1, 6),
(60, 'Which daily hair challenge are you hoping to overcome?', 4, 1, 6),
(62, 'Which hair care product sounds like the perfect ticket to achieving gold-medal hair?', 3, 1, 6);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `prodID` int(11) NOT NULL,
  `prodName` text NOT NULL,
  `prodDescription` text NOT NULL,
  `prodImage` blob NOT NULL,
  `prodURL` text NOT NULL,
  `categoryID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`prodID`, `prodName`, `prodDescription`, `prodImage`, `prodURL`, `categoryID`) VALUES
(14, 'Nivea Expert Filler Cellular Hyaluronic Acid Redensifying Serum 30ml', 'Experience a transformative skincare routine with Nivea Expert Filler Cellular Hyaluronic Acid Redensifying Serum (30ml). This advanced formula, enriched with hyaluronic acid, penetrates deeply to hydrate and plump the skin, visibly reducing fine lines and wrinkles for a revitalized and youthful complexion.', 0x75706c6f6164732f6e697665612d736572756d2e6a7067, 'https://www.caretobeauty.com/ph/nivea-expert-filler-cellular-hyaluronic-acid-redensifying-serum-30ml/', 4),
(15, 'Acne Clear MicellAIR Cleanser', 'NIVEA Acne Clear MicellAIR Cleanser effectively cleanses dirt, oil and even waterproof makeup without stripping moisture.', 0x75706c6f6164732f4e495645412041636e6520436c656172204d6963656c6c414952203132356d6c2d363030783630302e6a7067, 'https://www.nivea.ph/products/acne-clear-micellair-cleanser-40059005554650264.html', 4),
(16, 'Extra White Repair Pore Minimiser Toner', 'Tones skin deeply to remove excess residues that cause dullness and skin damage. Contains Pearl Whitening Complex and 10 skin nutrients to give 10x better whitening effect: NIVEA EXTRA WHITE REPAIR PORE MINIMISER TONER.', 0x75706c6f6164732f657874726177686974652e6a7067, 'https://www.nivea.ph/products/extra-white-repair-pore-minimiser-toner-40058088672020264.html', 4),
(17, 'NIVEA Body Wash Shea Butter', 'Give your skin the care it deserves with NIVEA Shea Butter Body Wash with Nourishing Serum and its specially moisturizing formula that leaves skin nicely fragranced.', 0x75706c6f6164732f73686561206275747465722e6a7067, 'https://www.niveausa.com/products/shea-butter-721400261270079.html', 5),
(18, 'NIVEA Breathable Nourishing Body Lotion', 'This game-changing lotion is fast-absorbing and leaves little to no residue on skin. For intense, long-lasting nourishing moisture, use NIVEA Breathable Nourishing Body Lotion.', 0x75706c6f6164732f62726561746861626c652e6a7067, 'https://www.niveausa.com/products/nivea%c2%ae-breathable-nourishing-body-lotion-721400316190079.html', 5),
(19, 'Refreshing Wild Berries and Hibiscus with Nourishing Serum', 'Give your skin the care it deserves with the luscious scent of NIVEA Wild Berries & Hibiscus Body Wash with Nourishing Serum that creates a sweet bloom in your shower.', 0x75706c6f6164732f77696c64626572726965732e6a7067, 'https://www.niveausa.com/products/refreshing-wild-berries-and-hibiscus-with-nourishing-serum-721400261720079.html', 5);

-- --------------------------------------------------------

--
-- Table structure for table `product_answer`
--

CREATE TABLE `product_answer` (
  `paID` int(11) NOT NULL,
  `prodID` int(11) NOT NULL,
  `answerID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `question_answer`
--

CREATE TABLE `question_answer` (
  `qaID` int(11) NOT NULL,
  `pqID` int(11) DEFAULT NULL,
  `cqID` int(11) DEFAULT NULL,
  `answerID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `question_answer`
--

INSERT INTO `question_answer` (`qaID`, `pqID`, `cqID`, `answerID`) VALUES
(40, 43, NULL, 59),
(41, 43, NULL, 60),
(42, 43, NULL, 61),
(43, 43, NULL, 62),
(44, 44, NULL, 63),
(45, 44, NULL, 64),
(46, 44, NULL, 65),
(47, 44, NULL, 66),
(48, 45, NULL, 67),
(49, 45, NULL, 68),
(50, 45, NULL, 69),
(51, 45, NULL, 70),
(52, 46, NULL, 71),
(53, 46, NULL, 72),
(54, 46, NULL, 73),
(63, 51, NULL, 82),
(64, 51, NULL, 83),
(65, 51, NULL, 84),
(66, 52, NULL, 85),
(67, 52, NULL, 86),
(68, 52, NULL, 87),
(69, 53, NULL, 88),
(70, 53, NULL, 89),
(71, 54, NULL, 90),
(72, 54, NULL, 91),
(73, 55, NULL, 92),
(74, 55, NULL, 93),
(75, 56, NULL, 94),
(76, 56, NULL, 95),
(77, 56, NULL, 96),
(78, 57, NULL, 97),
(79, 57, NULL, 98),
(80, 57, NULL, 99),
(81, 57, NULL, 100),
(82, 58, NULL, 101),
(83, 58, NULL, 102),
(84, 58, NULL, 103),
(85, 58, NULL, 104),
(86, 59, NULL, 105),
(87, 59, NULL, 106),
(88, 59, NULL, 107),
(89, 60, NULL, 108),
(90, 60, NULL, 109),
(91, 60, NULL, 110),
(92, 60, NULL, 111),
(94, 62, NULL, 113),
(95, 62, NULL, 114),
(96, 62, NULL, 115);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userID` int(11) NOT NULL,
  `firstName` text NOT NULL,
  `lastName` text NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `firstName`, `lastName`, `username`, `password`) VALUES
(1, 'Ash', 'Ketchum', 'admin', 'admin'),
(2, 'Hello', 'World', 'test1', 'test1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answer`
--
ALTER TABLE `answer`
  ADD PRIMARY KEY (`answerID`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`categoryID`);

--
-- Indexes for table `child_question`
--
ALTER TABLE `child_question`
  ADD PRIMARY KEY (`cqID`),
  ADD KEY `cq_fk1` (`pqID`);

--
-- Indexes for table `parent_question`
--
ALTER TABLE `parent_question`
  ADD PRIMARY KEY (`pqID`),
  ADD KEY `pq_fk1` (`categoryID`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`prodID`),
  ADD KEY `product_fk1` (`categoryID`);

--
-- Indexes for table `product_answer`
--
ALTER TABLE `product_answer`
  ADD PRIMARY KEY (`paID`),
  ADD KEY `pa_fk1` (`prodID`),
  ADD KEY `pa_fk2` (`answerID`);

--
-- Indexes for table `question_answer`
--
ALTER TABLE `question_answer`
  ADD PRIMARY KEY (`qaID`),
  ADD KEY `qa_fk1` (`pqID`),
  ADD KEY `qa_fk2` (`cqID`),
  ADD KEY `qa_fk3` (`answerID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answer`
--
ALTER TABLE `answer`
  MODIFY `answerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `categoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `child_question`
--
ALTER TABLE `child_question`
  MODIFY `cqID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `parent_question`
--
ALTER TABLE `parent_question`
  MODIFY `pqID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `prodID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `product_answer`
--
ALTER TABLE `product_answer`
  MODIFY `paID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `question_answer`
--
ALTER TABLE `question_answer`
  MODIFY `qaID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `child_question`
--
ALTER TABLE `child_question`
  ADD CONSTRAINT `cq_fk1` FOREIGN KEY (`pqID`) REFERENCES `parent_question` (`pqID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `parent_question`
--
ALTER TABLE `parent_question`
  ADD CONSTRAINT `pq_fk1` FOREIGN KEY (`categoryID`) REFERENCES `category` (`categoryID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_fk1` FOREIGN KEY (`categoryID`) REFERENCES `category` (`categoryID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product_answer`
--
ALTER TABLE `product_answer`
  ADD CONSTRAINT `pa_fk1` FOREIGN KEY (`prodID`) REFERENCES `product` (`prodID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pa_fk2` FOREIGN KEY (`answerID`) REFERENCES `answer` (`answerID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `question_answer`
--
ALTER TABLE `question_answer`
  ADD CONSTRAINT `qa_fk1` FOREIGN KEY (`pqID`) REFERENCES `parent_question` (`pqID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `qa_fk2` FOREIGN KEY (`cqID`) REFERENCES `child_question` (`cqID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `qa_fk3` FOREIGN KEY (`answerID`) REFERENCES `answer` (`answerID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
