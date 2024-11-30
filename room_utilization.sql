-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 30, 2024 at 10:33 AM
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
-- Database: `room_utilization`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','teacher','admin') NOT NULL,
  `is_staff` tinyint(1) NOT NULL DEFAULT 1,
  `is_admin` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`id`, `first_name`, `last_name`, `username`, `password`, `role`, `is_staff`, `is_admin`) VALUES
(1, 'Rhamirl', 'Jaafar', 'rham', '$2y$10$42jGvgsU9zoKdFwm18wqQe0nzO78jZvk1m1vMvx8BJYsLTHPuBaCa', 'admin', 1, 1),
(2, 'aziz', 'amin', 'amin123', '$2y$10$snPP2mhvYzbHhLYvQNqtcODt/ISqzoVkxK/THlbFFn26SFZ9yOT7u', 'student', 1, 1),
(5, 'first', 'teacher', 'teacher1', '$2y$10$mvrv6cbnYmTAwXO.lK6UxevwEWqjCdk5bSMi1EJVt.8P2fD0BGMXO', 'teacher', 1, 1),
(6, 'second', 'teacher', 'teacher2', '$2y$10$cInqcsJW1Wh6mqDji3OO1eXC6nEtUE0AF6D3Ha1XPn0qdYbRD..S.', 'teacher', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(1, 'Toys'),
(2, 'Gadget');

-- --------------------------------------------------------

--
-- Table structure for table `class_day`
--

CREATE TABLE `class_day` (
  `id` int(11) NOT NULL,
  `day_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_day`
--

INSERT INTO `class_day` (`id`, `day_id`, `class_id`) VALUES
(3, 1, 7),
(4, 1, 8),
(5, 2, 9);

-- --------------------------------------------------------

--
-- Table structure for table `class_details`
--

CREATE TABLE `class_details` (
  `id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `teacher_assigned` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_details`
--

INSERT INTO `class_details` (`id`, `section_id`, `room_id`, `subject_id`, `teacher_assigned`) VALUES
(10, 1, 1, 1, 1),
(11, 1, 2, 4, 2),
(13, 2, 1, 3, 1),
(21, 8, 3, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `class_time`
--

CREATE TABLE `class_time` (
  `id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `time_modified` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_time`
--

INSERT INTO `class_time` (`id`, `class_id`, `start_time`, `end_time`, `time_modified`) VALUES
(7, 10, '07:00:00', '09:00:00', '2024-11-27 10:57:00'),
(8, 11, '09:00:00', '12:00:00', '2024-11-27 10:57:00'),
(9, 13, '07:00:00', '09:00:00', '2024-11-29 04:29:18');

-- --------------------------------------------------------

--
-- Table structure for table `course_details`
--

CREATE TABLE `course_details` (
  `id` int(11) NOT NULL,
  `_name` varchar(20) NOT NULL,
  `_description` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_details`
--

INSERT INTO `course_details` (`id`, `_name`, `_description`) VALUES
(1, 'BSCS', 'Bachelor of Science in Computing Studies'),
(2, 'BSIT', 'Bachelor of Science in Information Technology'),
(3, 'ACT', 'Associate in Computer Technology');

-- --------------------------------------------------------

--
-- Table structure for table `faculty_list`
--

CREATE TABLE `faculty_list` (
  `id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty_list`
--

INSERT INTO `faculty_list` (`id`, `account_id`) VALUES
(1, 5),
(2, 6);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_ai` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `code`, `name`, `category_id`, `price`, `created_at`, `updated_ai`) VALUES
(1, 'A001', 'Tree', 1, 1.00, '2024-11-14 01:25:30', '2024-11-14 01:25:30'),
(2, 'A002', 'Tree', 1, 1.00, '2024-11-14 03:44:45', '2024-11-14 03:44:45');

-- --------------------------------------------------------

--
-- Table structure for table `room_list`
--

CREATE TABLE `room_list` (
  `id` int(11) NOT NULL,
  `room_name` varchar(50) NOT NULL,
  `type_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_list`
--

INSERT INTO `room_list` (`id`, `room_name`, `type_id`, `created_at`, `updated_at`) VALUES
(1, 'LR 1', 1, '2024-11-17 14:09:21', '2024-11-17 14:09:21'),
(2, 'LR 2', 1, '2024-11-17 14:09:56', '2024-11-17 14:09:56'),
(3, 'LAB 1', 2, '2024-11-27 14:29:55', '2024-11-27 14:29:55'),
(4, 'LR 3', 1, '2024-11-27 22:27:59', '2024-11-27 22:27:59'),
(5, 'LAB 2', 2, '2024-11-29 10:29:38', '2024-11-29 10:29:38');

-- --------------------------------------------------------

--
-- Table structure for table `room_type`
--

CREATE TABLE `room_type` (
  `id` int(11) NOT NULL,
  `room_description` varchar(255) NOT NULL,
  `room_code` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_type`
--

INSERT INTO `room_type` (`id`, `room_description`, `room_code`) VALUES
(1, 'Lecture Room', 'LR'),
(2, 'Laboratory Room', 'LAB');

-- --------------------------------------------------------

--
-- Table structure for table `section_details`
--

CREATE TABLE `section_details` (
  `id` int(11) NOT NULL,
  `section_name` varchar(255) NOT NULL,
  `course_id` int(11) NOT NULL,
  `year_level` enum('1','2','3','4','5') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `section_details`
--

INSERT INTO `section_details` (`id`, `section_name`, `course_id`, `year_level`) VALUES
(1, 'CS1A', 1, '1'),
(2, 'CS1B', 1, '1'),
(3, 'CS1C', 1, '1'),
(4, 'IT1A', 2, '1'),
(5, 'IT1B', 2, '1'),
(6, 'IT1C', 2, '1'),
(7, 'ACT1A', 3, '1'),
(8, 'CS2A', 1, '2'),
(9, 'CS2B', 1, '2'),
(10, 'CS2C', 1, '2'),
(11, 'IT2A', 2, '2'),
(12, 'IT2B', 2, '2'),
(13, 'IT2C', 2, '2'),
(14, 'ACT2A', 3, '2'),
(15, 'ACT2B', 3, '2');

-- --------------------------------------------------------

--
-- Table structure for table `status_description`
--

CREATE TABLE `status_description` (
  `id` int(11) NOT NULL,
  `description` enum('AVAILABLE','OCCUPIED') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `status_description`
--

INSERT INTO `status_description` (`id`, `description`) VALUES
(1, 'AVAILABLE'),
(2, 'OCCUPIED');

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

CREATE TABLE `stocks` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `status` varchar(100) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stocks`
--

INSERT INTO `stocks` (`id`, `product_id`, `quantity`, `status`, `reason`, `created_at`, `updated_at`) VALUES
(1, 1, 24, 'in', '', '2024-11-14 05:44:33', '2024-11-14 05:44:33'),
(2, 1, 24, 'out', 'Sold 24', '2024-11-14 05:57:57', '2024-11-14 05:57:57'),
(13, 1, 24, 'in', '', '2024-11-14 07:04:35', '2024-11-14 07:04:35');

-- --------------------------------------------------------

--
-- Table structure for table `subject_details`
--

CREATE TABLE `subject_details` (
  `id` int(11) NOT NULL,
  `subject_code` varchar(50) NOT NULL,
  `description` varchar(100) NOT NULL,
  `type_id` int(11) NOT NULL,
  `time_modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject_details`
--

INSERT INTO `subject_details` (`id`, `subject_code`, `description`, `type_id`, `time_modified`) VALUES
(1, 'CC103', 'Data Structures and Algorithm', 1, '2024-11-28 01:39:00'),
(2, 'CC103', 'Data Structures and Algorithm', 2, '2024-11-28 01:39:00'),
(3, 'CC100', 'Introduction to Computing Studies', 1, '2024-11-28 01:39:00'),
(4, 'CC100', 'Introduction to Computing Studies', 2, '2024-11-28 01:39:00');

-- --------------------------------------------------------

--
-- Table structure for table `subject_type_description`
--

CREATE TABLE `subject_type_description` (
  `id` int(11) NOT NULL,
  `type` enum('LEC','LAB','','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject_type_description`
--

INSERT INTO `subject_type_description` (`id`, `type`) VALUES
(1, 'LEC'),
(2, 'LAB');

-- --------------------------------------------------------

--
-- Table structure for table `_day`
--

CREATE TABLE `_day` (
  `id` int(11) NOT NULL,
  `day` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `_day`
--

INSERT INTO `_day` (`id`, `day`) VALUES
(1, 'Monday'),
(2, 'Tuesday'),
(3, 'Wednesday'),
(4, 'Thursday'),
(5, 'Friday'),
(6, 'Saturday');

-- --------------------------------------------------------

--
-- Table structure for table `_status`
--

CREATE TABLE `_status` (
  `class_day_id` int(11) NOT NULL,
  `status_desc_id` int(11) NOT NULL,
  `time_modified` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `_status`
--

INSERT INTO `_status` (`class_day_id`, `status_desc_id`, `time_modified`) VALUES
(3, 2, '2024-11-28 00:25:28'),
(4, 2, '2024-11-28 00:25:28'),
(5, 1, '2024-11-29 08:58:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `first_name` (`first_name`,`last_name`) USING BTREE;

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `class_day`
--
ALTER TABLE `class_day`
  ADD PRIMARY KEY (`id`),
  ADD KEY `classtimeid_fk` (`class_id`),
  ADD KEY `dayid_fk` (`day_id`);

--
-- Indexes for table `class_details`
--
ALTER TABLE `class_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `faculty_id_fk` (`teacher_assigned`),
  ADD KEY `room_id_fk` (`room_id`),
  ADD KEY `section_id_fk` (`section_id`),
  ADD KEY `subject_id_fk` (`subject_id`);

--
-- Indexes for table `class_time`
--
ALTER TABLE `class_time`
  ADD PRIMARY KEY (`id`),
  ADD KEY `classid_fk` (`class_id`);

--
-- Indexes for table `course_details`
--
ALTER TABLE `course_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faculty_list`
--
ALTER TABLE `faculty_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accountid_fk` (`account_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `cat_idfk` (`category_id`);

--
-- Indexes for table `room_list`
--
ALTER TABLE `room_list`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `room_name` (`room_name`),
  ADD KEY `typeid_fk` (`type_id`);

--
-- Indexes for table `room_type`
--
ALTER TABLE `room_type`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `room_type` (`room_code`);

--
-- Indexes for table `section_details`
--
ALTER TABLE `section_details`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `class_name` (`section_name`),
  ADD KEY `course_id_fk` (`course_id`);

--
-- Indexes for table `status_description`
--
ALTER TABLE `status_description`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `productid_fk` (`product_id`);

--
-- Indexes for table `subject_details`
--
ALTER TABLE `subject_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_details` (`subject_code`),
  ADD KEY `stypeid_fk` (`type_id`);

--
-- Indexes for table `subject_type_description`
--
ALTER TABLE `subject_type_description`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `_day`
--
ALTER TABLE `_day`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `_status`
--
ALTER TABLE `_status`
  ADD PRIMARY KEY (`class_day_id`),
  ADD KEY `statusdescid_fk` (`status_desc_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `class_day`
--
ALTER TABLE `class_day`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `class_details`
--
ALTER TABLE `class_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `class_time`
--
ALTER TABLE `class_time`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `course_details`
--
ALTER TABLE `course_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `faculty_list`
--
ALTER TABLE `faculty_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `room_list`
--
ALTER TABLE `room_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `room_type`
--
ALTER TABLE `room_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `section_details`
--
ALTER TABLE `section_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `status_description`
--
ALTER TABLE `status_description`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `subject_details`
--
ALTER TABLE `subject_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `subject_type_description`
--
ALTER TABLE `subject_type_description`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `_day`
--
ALTER TABLE `_day`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `class_day`
--
ALTER TABLE `class_day`
  ADD CONSTRAINT `classtimeid_fk` FOREIGN KEY (`class_id`) REFERENCES `class_time` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `dayid_fk` FOREIGN KEY (`day_id`) REFERENCES `_day` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `class_details`
--
ALTER TABLE `class_details`
  ADD CONSTRAINT `faculty_id_fk` FOREIGN KEY (`teacher_assigned`) REFERENCES `faculty_list` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `room_id_fk` FOREIGN KEY (`room_id`) REFERENCES `room_list` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `section_id_fk` FOREIGN KEY (`section_id`) REFERENCES `section_details` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `subject_id_fk` FOREIGN KEY (`subject_id`) REFERENCES `subject_details` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `class_time`
--
ALTER TABLE `class_time`
  ADD CONSTRAINT `classid_fk` FOREIGN KEY (`class_id`) REFERENCES `class_details` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `faculty_list`
--
ALTER TABLE `faculty_list`
  ADD CONSTRAINT `accountid_fk` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `cat_idfk` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`);

--
-- Constraints for table `room_list`
--
ALTER TABLE `room_list`
  ADD CONSTRAINT `typeid_fk` FOREIGN KEY (`type_id`) REFERENCES `room_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `section_details`
--
ALTER TABLE `section_details`
  ADD CONSTRAINT `course_id_fk` FOREIGN KEY (`course_id`) REFERENCES `course_details` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stocks`
--
ALTER TABLE `stocks`
  ADD CONSTRAINT `productid_fk` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Constraints for table `subject_details`
--
ALTER TABLE `subject_details`
  ADD CONSTRAINT `stypeid_fk` FOREIGN KEY (`type_id`) REFERENCES `subject_type_description` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `_status`
--
ALTER TABLE `_status`
  ADD CONSTRAINT `class_dayid_fk` FOREIGN KEY (`class_day_id`) REFERENCES `class_day` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `statusdescid_fk` FOREIGN KEY (`status_desc_id`) REFERENCES `status_description` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
