-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 12, 2025 at 01:40 AM
-- Server version: 10.11.10-MariaDB
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u349700752_solidarity`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `schoolName` varchar(150) DEFAULT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `name`, `email`, `password`, `schoolName`, `role`) VALUES
(3, 'Admin', 'Admin@gmail.com', '$2y$10$OLC64H9LaTzGiT/0dTbBQuKzlBBJpLjKlUTZRMFSK5lC9o3xFuBb2', '', 'admin'),
(4, 'Josie C. Ochoa', 'ochoajosefina088@gmail.com', '$2y$10$MUwmCY.CX5JXlKjZv.HpA.NAhVDuCok6chL749QYFfKtzkgBc16cq', '', 'user'),
(6, 'Jayson Angelo Batoon', 'jayson.batoon@bulsu.edu.ph', '$2y$10$3Tsd.CveF4RhozmSEpLyKOUVU/YT6ymS.aFyERuBn2TZuIr9EG6vO', '', 'user'),
(7, 'Josefina C. Ochoa', 'josefina.ochoa@bulsu.edu.ph', '$2y$10$H8DFxBDZrg3GhBlyRCZzVOAVLZQf5PBvkZG64.h6uFUFolBn6dezK', '', 'user'),
(8, 'Butch Stephen Duay', 'Butchstephen.duay@bulsu.edu.ph', '$2y$10$P7PguzmRy/85nrWmbak9lOv6h1oJXnxW6J0Zafx/q/mI7Hxv//YXi', 'B', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(8, 'Affectual Solidarity'),
(9, 'Associational Solidarity'),
(10, 'Functional Solidarity');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `question_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `status` enum('Active','Archived') DEFAULT 'Active',
  `date_created` date DEFAULT curdate(),
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`question_id`, `question`, `category`, `status`, `date_created`, `category_id`) VALUES
(22, 'Concerned about the welfare of humanity', 'Affectual Solidarity', 'Active', '2025-04-28', 8),
(23, 'Establish deep and satisfying interpersonal relationships', 'Affectual Solidarity', 'Active', '2025-04-28', 8),
(24, 'Accept others as they are', 'Affectual Solidarity', 'Active', '2025-04-28', 8),
(25, 'Give the space that others asks from you', 'Affectual Solidarity', 'Active', '2025-04-28', 8),
(26, 'Takes full responsibility for tasks assigned to your team', 'Affectual Solidarity', 'Active', '2025-04-28', 8),
(27, 'Identify and admit your defenses', 'Affectual Solidarity', 'Active', '2025-04-28', 8),
(28, 'Maintain strong moral and ethical standard', 'Affectual Solidarity', 'Active', '2025-04-28', 8),
(29, 'Sees the need for privacy', 'Affectual Solidarity', 'Active', '2025-04-28', 8),
(30, 'Provides time to those who need you', 'Affectual Solidarity', 'Active', '2025-04-28', 8),
(31, 'Would not expect returns for favors granted', 'Affectual Solidarity', 'Active', '2025-04-28', 8),
(32, 'Does not seek popularity', 'Affectual Solidarity', 'Active', '2025-04-28', 8),
(33, 'Maintains democratic views', 'Affectual Solidarity', 'Active', '2025-04-28', 8),
(34, 'Listens to self-talk', 'Affectual Solidarity', 'Active', '2025-04-28', 8),
(35, 'Maintains honesty at all cost', 'Affectual Solidarity', 'Active', '2025-04-28', 8),
(36, 'Play and find time for recreation', 'Associational Solidarity', 'Active', '2025-04-28', 9),
(37, 'Perform based on set standards', 'Associational Solidarity', 'Active', '2025-04-28', 9),
(38, 'Share feelings with others', 'Associational Solidarity', 'Active', '2025-04-28', 9),
(39, 'Do your tasks competently', 'Associational Solidarity', 'Active', '2025-05-07', 9),
(40, 'Feels close with all of your family circle', 'Associational Solidarity', 'Active', '2025-05-07', 9),
(41, 'Provides money to your dependents', 'Associational Solidarity', 'Active', '2025-05-07', 9),
(42, 'Are connected with your entire family', 'Associational Solidarity', 'Active', '2025-05-07', 9),
(43, 'Work hard based on your workplace’ expectations and standards', 'Associational Solidarity', 'Active', '2025-05-07', 9),
(44, 'Works as productively as you can', 'Associational Solidarity', 'Active', '2025-05-07', 9),
(45, 'Gives presents or gifts on all occasions to everyone within your circle', 'Associational Solidarity', 'Active', '2025-05-07', 9),
(46, 'Are spontaneous in expressing your thoughts and feelings', 'Associational Solidarity', 'Active', '2025-05-07', 9),
(47, 'Try new things to avoid routines', 'Associational Solidarity', 'Active', '2025-05-07', 9),
(48, 'Would accept donations from sponsors and givers', 'Functional Solidarity', 'Active', '2025-05-07', 10),
(49, 'Are capable of tolerating uncertainty', 'Functional Solidarity', 'Active', '2025-05-07', 10),
(50, 'Adhere more to problem-centered coping ways', 'Functional Solidarity', 'Active', '2025-05-07', 10),
(51, 'Maintain unusual sense of humor', 'Functional Solidarity', 'Active', '2025-05-07', 10),
(52, 'Have experienced life in all its ups and downs', 'Functional Solidarity', 'Active', '2025-05-07', 10),
(53, 'Would manage to do tasks willingly and independently', 'Functional Solidarity', 'Active', '2025-05-07', 10),
(54, 'Perform tasks maintaining creative standards', 'Functional Solidarity', 'Active', '2025-05-07', 10),
(55, 'Maintain good terms with everyone on the job', 'Functional Solidarity', 'Active', '2025-05-07', 10),
(56, 'Can accept criticisms positively', 'Functional Solidarity', 'Active', '2025-05-07', 10),
(57, 'test', 'Affectual Solidarity', 'Active', '2025-06-09', 8);

-- --------------------------------------------------------

--
-- Table structure for table `responses`
--

CREATE TABLE `responses` (
  `id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `response_data` int(11) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `responses`
--

INSERT INTO `responses` (`id`, `account_id`, `question_id`, `response_data`, `date_created`) VALUES
(18, 7, 22, 4, '2025-05-15 11:16:51'),
(19, 7, 23, 4, '2025-05-15 11:16:51'),
(20, 7, 24, 4, '2025-05-15 11:16:51'),
(21, 7, 25, 4, '2025-05-15 11:16:51'),
(22, 7, 26, 4, '2025-05-15 11:16:51'),
(23, 7, 27, 4, '2025-05-15 11:16:51'),
(24, 7, 28, 4, '2025-05-15 11:16:51'),
(25, 7, 29, 4, '2025-05-15 11:16:51'),
(26, 7, 30, 4, '2025-05-15 11:16:51'),
(27, 7, 31, 4, '2025-05-15 11:16:51'),
(28, 7, 32, 4, '2025-05-15 11:16:51'),
(29, 7, 33, 4, '2025-05-15 11:16:51'),
(30, 7, 34, 4, '2025-05-15 11:16:51'),
(31, 7, 35, 4, '2025-05-15 11:16:51'),
(32, 7, 36, 4, '2025-05-15 11:16:51'),
(33, 7, 37, 4, '2025-05-15 11:16:51'),
(34, 7, 38, 4, '2025-05-15 11:16:51'),
(35, 7, 39, 4, '2025-05-15 11:16:51'),
(36, 7, 40, 4, '2025-05-15 11:16:51'),
(37, 7, 41, 4, '2025-05-15 11:16:51'),
(38, 7, 42, 4, '2025-05-15 11:16:51'),
(39, 7, 43, 4, '2025-05-15 11:16:51'),
(40, 7, 44, 4, '2025-05-15 11:16:51'),
(41, 7, 45, 4, '2025-05-15 11:16:51'),
(42, 7, 46, 4, '2025-05-15 11:16:51'),
(43, 7, 47, 4, '2025-05-15 11:16:51'),
(44, 7, 48, 3, '2025-05-15 11:16:51'),
(45, 7, 49, 3, '2025-05-15 11:16:51'),
(46, 7, 50, 3, '2025-05-15 11:16:51'),
(47, 7, 51, 4, '2025-05-15 11:16:51'),
(48, 7, 52, 4, '2025-05-15 11:16:51'),
(49, 7, 53, 4, '2025-05-15 11:16:51'),
(50, 7, 54, 4, '2025-05-15 11:16:51'),
(51, 7, 55, 4, '2025-05-15 11:16:51'),
(52, 7, 56, 3, '2025-05-15 11:16:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`question_id`),
  ADD KEY `fk_category` (`category_id`);

--
-- Indexes for table `responses`
--
ALTER TABLE `responses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `question_id` (`question_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `responses`
--
ALTER TABLE `responses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `fk_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `responses`
--
ALTER TABLE `responses`
  ADD CONSTRAINT `responses_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `responses_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`question_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
