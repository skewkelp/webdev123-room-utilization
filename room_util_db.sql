-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 27, 2024 at 04:57 PM
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
-- Database: `room_util_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `account_id` varchar(10) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`account_id`, `first_name`, `last_name`, `username`, `password`) VALUES
('201001234', 'admin', 'admin', 'aa201001234', '$2y$10$v8G82MPHA9tVQ8MeA/Y3ueGwkU/fDuvrjaKbxGnUawZQWzCJYnJhG'),
('201201234', 'First', '1Teacher', 'ab201201234', '$2y$10$Mu6Slb4LvL3PFg2GFxQdIuN4G2Z7TTHzntou8UO9xy2JLKDJlC.Hi'),
('201201235', 'second', '2Teacher', 'ac201201235', '$2y$10$IPbH/v9f4BdQvUoIGFTQWO5VOgAlV0CiIbx6ivpnaoUi5ZIRjHrZe'),
('201201236', 'Three', '3Teacher', 'ac201201236', '$2y$10$AdlYsQkf7IEpl7m/PqdpMerMckMuOjKrhwejSGOQC/71l/MyGvsZ2'),
('201201237', 'Four', '4Teacher', 'ac201201237', '$2y$10$8AKq8IrzS43QSam8tgU3HOYdohuZKvVrQRVW0IGARhC9t3J/2g1Ly'),
('201201238', 'Five', '5Teacher', 'ac201201238', '$2y$10$UckU.gomnOEr5rGvRkK0YeyL6dqvpnVUqpLI9vg7oAT0w8kV.0u1K'),
('201201239', 'Six', '6Teacher', 'ac201201239', '$2y$10$N6ErDbEght/qAvn6YAjppukBTL3LyiK7gUtp8/.ztWeWzTpH.JoPm'),
('201201240', 'Seven', '7Teacher', 'ad201201240', '$2y$10$OYbo0dwLT.99s5TqMiJXduhWZADjedEr8d52yGv/yT.Roo0Q7o0ZG'),
('201201241', 'Eight', '8Teacher', 'ad201001241', '$2y$10$X0UXr2OgDXO5OGoQbnGg6OoOw8VgfJPTTRzMp5Sqn65Qq9JAWie66'),
('201201242', 'Nine', '9Teacher', 'ad201001242', '$2y$10$2KGHfNje9U2kiOIjyLj72u96eeSJHTKHkHPXxFdCCqUBztca5xo.W'),
('201201243', 'Ten', '10Teacher', 'ad201001243', '$2y$10$JxBMFSAAsGsVXqd7yl7A8uBab2w7MR2apSMOMaNa6jxwHNN/gHuaG'),
('202101234', 'student1', 'student1', 'qb202101234', '$2y$10$Af5IP72la6ehBOjvv9lZVuWGwiXZ.mX0bhS9tZAi6xFKku8Q3OC56'),
('202101235', 'student2', 'student2', 'qb202101235', '$2y$10$oR.dfekXyeBafr6YPYGgtuUy3fPiS359ubvmQ1htwgZLZgwOpkpVy');

-- --------------------------------------------------------

--
-- Table structure for table `class_details`
--

CREATE TABLE `class_details` (
  `class_id` varchar(10) NOT NULL,
  `subject_type` varchar(10) NOT NULL,
  `subject_id` varchar(10) DEFAULT NULL,
  `course_abbr` varchar(10) NOT NULL,
  `year_level` int(11) NOT NULL,
  `section` varchar(1) NOT NULL,
  `teacher_assigned` int(11) DEFAULT NULL,
  `time_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `semester` enum('1','2') NOT NULL,
  `school_year` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_details`
--

INSERT INTO `class_details` (`class_id`, `subject_type`, `subject_id`, `course_abbr`, `year_level`, `section`, `teacher_assigned`, `time_created`, `semester`, `school_year`) VALUES
('BSCS123003', 'LEC', 'CC100', 'ACT', 1, 'A', 1, '2024-12-23 10:34:37', '1', '2024-2025'),
('BSCS123212', 'LEC', 'SIPP125', 'CS', 2, 'C', 2, '2024-12-19 23:07:45', '1', '2024-2025'),
('BSCS123451', 'LAB', 'CC100', 'CS', 1, 'A', 2, '2024-12-15 22:33:20', '1', '2024-2025'),
('BSCS123451', 'LEC', 'CC100', 'CS', 1, 'A', 1, '2024-12-15 22:32:12', '1', '2024-2025'),
('BSCS123461', 'LAB', 'CC101', 'CS', 1, 'B', 4, '2024-12-15 22:41:21', '1', '2024-2025'),
('BSCS123461', 'LEC', 'CC101', 'CS', 1, 'B', 3, '2024-12-15 22:41:21', '1', '2024-2025'),
('BSCS124571', 'LAB', 'CC103', 'CS', 2, 'A', 8, '2024-12-15 22:41:21', '1', '2024-2025'),
('BSCS124571', 'LEC', 'CC103', 'CS', 2, 'A', 7, '2024-12-15 22:41:21', '1', '2024-2025'),
('BSCS124581', 'LAB', 'CC104', 'CS', 2, 'A', 9, '2024-12-15 22:41:21', '1', '2024-2025'),
('BSCS124581', 'LEC', 'CC104', 'CS', 2, 'A', 9, '2024-12-15 22:41:21', '1', '2024-2025'),
('BSCS124591', 'LEC', 'MAD121', 'CS', 2, 'A', 10, '2024-12-15 22:41:21', '1', '2024-2025'),
('BSCS124601', 'LEC', 'SIPP125', 'CS', 2, 'A', 5, '2024-12-15 22:41:21', '1', '2024-2025'),
('BSCS202401', 'LEC', 'SIPP125', 'CS', 1, 'A', 3, '2024-12-26 07:29:53', '1', '2024-2025'),
('BSCS202501', 'LEC', 'SAMPLE', 'CS', 1, 'A', 1, '2024-12-27 05:17:01', '1', '2024-2025');

-- --------------------------------------------------------

--
-- Table structure for table `class_logs`
--

CREATE TABLE `class_logs` (
  `log_id` int(11) NOT NULL,
  `class_id` varchar(10) NOT NULL,
  `subject_type` varchar(10) NOT NULL,
  `day` enum('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday') NOT NULL,
  `remarks` varchar(500) NOT NULL,
  `time_modified` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_logs`
--

INSERT INTO `class_logs` (`log_id`, `class_id`, `subject_type`, `day`, `time_modified`) VALUES
(1, 'BSCS123451', 'LEC', 'Wednesday', '2024-12-08 23:06:38'),
(2, 'BSCS123451', 'LAB', 'Wednesday', '2024-12-11 02:06:38'),
(3, 'BSCS123461', 'LEC', 'Monday', '2024-12-09 01:06:38'),
(4, 'BSCS123461', 'LAB', 'Wednesday', '2024-12-11 05:06:38'),
(6, 'BSCS124571', 'LAB', 'Friday', '2024-12-12 23:06:38'),
(7, 'BSCS124581', 'LEC', 'Wednesday', '2024-12-10 23:06:38'),
(8, 'BSCS124581', 'LAB', 'Wednesday', '2024-12-11 09:06:38'),
(9, 'BSCS124591', 'LEC', 'Monday', '2024-12-09 07:06:38'),
(10, 'BSCS124601', 'LEC', 'Thursday', '2024-12-12 03:36:38');

-- --------------------------------------------------------

--
-- Table structure for table `class_schedule`
--

CREATE TABLE `class_schedule` (
  `class_id` varchar(10) NOT NULL,
  `subject_type` varchar(10) NOT NULL,
  `day` enum('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday') NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `status` enum('OCCUPIED','AVAILABLE') NOT NULL,
  `remarks` varchar(500) NOT NULL,
  `room_code` varchar(10) NOT NULL,
  `room_no` int(11) NOT NULL,
  `time_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `semester` enum('1','2') NOT NULL,
  `school_year` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_schedule`
--

INSERT INTO `class_schedule` (`class_id`, `subject_type`, `day`, `start_time`, `end_time`, `status`, `remarks`, `room_code`, `room_no`, `time_created`, `semester`, `school_year`) VALUES
('BSCS123451', 'LAB', 'Wednesday', '10:00:00', '11:00:00', 'OCCUPIED', '', 'LR', 1, '2024-12-15 23:02:08', '1', '2024-2025'),
('BSCS123451', 'LEC', 'Wednesday', '08:00:00', '09:00:00', 'OCCUPIED', '', 'LR', 1, '2024-12-15 23:02:08', '1', '2024-2025'),
('BSCS123461', 'LAB', 'Wednesday', '13:00:00', '14:00:00', 'OCCUPIED', '', 'LAB', 1, '2024-12-15 23:02:08', '1', '2024-2025'),
('BSCS123461', 'LEC', 'Monday', '09:00:00', '12:00:00', 'OCCUPIED', '', 'LR', 1, '2024-12-15 23:02:08', '1', '2024-2025'),
('BSCS124571', 'LAB', 'Thursday', '06:00:00', '08:00:00', 'OCCUPIED', '', 'LR', 4, '2024-12-25 13:46:18', '1', '2024-2025'),
('BSCS124571', 'LAB', 'Friday', '07:00:00', '09:00:00', 'OCCUPIED', '', 'LAB', 1, '2024-12-15 23:02:08', '1', '2024-2025'),
('BSCS124571', 'LEC', 'Monday', '06:00:00', '07:00:00', 'OCCUPIED', '', 'LR', 4, '2024-12-25 13:46:18', '1', '2024-2025'),
('BSCS124571', 'LEC', 'Saturday', '11:30:00', '13:00:00', 'OCCUPIED', '', 'LR', 5, '2024-12-25 10:12:57', '1', '2024-2025'),
('BSCS124581', 'LAB', 'Wednesday', '17:00:00', '19:00:00', 'OCCUPIED', '', 'LAB', 1, '2024-12-15 23:02:08', '1', '2024-2025'),
('BSCS124581', 'LEC', 'Wednesday', '07:00:00', '10:00:00', 'OCCUPIED', '', 'LR', 3, '2024-12-15 23:02:08', '1', '2024-2025'),
('BSCS124591', 'LEC', 'Monday', '15:00:00', '17:00:00', 'OCCUPIED', '', 'LAB', 1, '2024-12-15 23:02:08', '1', '2024-2025'),
('BSCS124601', 'LEC', 'Thursday', '11:30:00', '13:00:00', 'OCCUPIED', '', 'LAB', 1, '2024-12-15 23:02:08', '1', '2024-2025');

-- --------------------------------------------------------

--
-- Table structure for table `course_details`
--

CREATE TABLE `course_details` (
  `course_abbr` varchar(10) NOT NULL,
  `course_name` varchar(20) NOT NULL,
  `course_description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_details`
--

INSERT INTO `course_details` (`course_abbr`, `course_name`, `course_description`) VALUES
('ACT', 'ACT', 'Associate in Computer Technology'),
('CS', 'BSCS', 'Bachelor of Science in Computer Science'),
('IT', 'BSIT', 'Bachelor of Science in Information Technology');

-- --------------------------------------------------------

--
-- Table structure for table `faculty_list`
--

CREATE TABLE `faculty_list` (
  `faculty_id` int(11) NOT NULL,
  `user_id` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty_list`
--

INSERT INTO `faculty_list` (`faculty_id`, `user_id`) VALUES
(1, '201201234'),
(2, '201201235'),
(3, '201201236'),
(4, '201201237'),
(5, '201201238'),
(6, '201201239'),
(7, '201201240'),
(8, '201201241'),
(9, '201201242'),
(10, '201201243');

-- --------------------------------------------------------

--
-- Table structure for table `room_list`
--

CREATE TABLE `room_list` (
  `room_code` varchar(10) NOT NULL,
  `room_no` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_list`
--

INSERT INTO `room_list` (`room_code`, `room_no`, `created_at`) VALUES
('LAB', 1, '2024-12-15 22:46:38'),
('LAB', 2, '2024-12-15 22:46:38'),
('LR', 1, '2024-12-15 22:46:38'),
('LR', 2, '2024-12-15 22:46:38'),
('LR', 3, '2024-12-15 22:46:38'),
('LR', 4, '2024-12-23 04:21:38'),
('LR', 5, '2024-12-18 02:30:28');

-- --------------------------------------------------------

--
-- Table structure for table `room_type`
--

CREATE TABLE `room_type` (
  `room_type_id` varchar(10) NOT NULL,
  `room_description` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_type`
--

INSERT INTO `room_type` (`room_type_id`, `room_description`) VALUES
('LAB', 'Laboratory'),
('LR', 'Lecture Room');

-- --------------------------------------------------------

--
-- Table structure for table `section_details`
--

CREATE TABLE `section_details` (
  `course_abbr` varchar(10) NOT NULL,
  `year_level` int(11) NOT NULL,
  `section` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `section_details`
--

INSERT INTO `section_details` (`course_abbr`, `year_level`, `section`) VALUES
('ACT', 1, 'A'),
('CS', 1, 'A'),
('CS', 1, 'B'),
('CS', 1, 'C'),
('CS', 2, 'A'),
('CS', 2, 'B'),
('CS', 2, 'C'),
('IT', 1, 'A'),
('IT', 1, 'B'),
('IT', 1, 'C');

-- --------------------------------------------------------

--
-- Table structure for table `semester`
--

CREATE TABLE `semester` (
  `semester` enum('1','2') NOT NULL,
  `school_year` varchar(10) NOT NULL,
  `description` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `semester`
--

INSERT INTO `semester` (`semester`, `school_year`, `description`) VALUES
('1', '2024-2025', '1st Sem | 2024-2025'),
('1', '2025-2026', '1st Sem | 2025-2026'),
('1', '2026-2027', '1st Sem | 2026-2027'),
('1', '2027-2028', '1st Sem | 2027-2028'),
('1', '2028-2029', '1st Sem | 2028-2029'),
('2', '2024-2025', '2nd Sem | 2024-2025'),
('2', '2025-2026', '2nd Sem | 2025-2026'),
('2', '2026-2027', '2nd Sem | 2026-2027'),
('2', '2027-2028', '2nd Sem | 2027-2028'),
('2', '2028-2029', '2nd Sem | 2028-2029');

-- --------------------------------------------------------

--
-- Table structure for table `subject_details`
--

CREATE TABLE `subject_details` (
  `subject_code` varchar(10) NOT NULL,
  `description` varchar(100) NOT NULL,
  `total_units` decimal(10,2) NOT NULL,
  `lec_units` decimal(10,2) NOT NULL,
  `lab_units` decimal(10,2) NOT NULL,
  `subject_prospectus_id` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject_details`
--

INSERT INTO `subject_details` (`subject_code`, `description`, `total_units`, `lec_units`, `lab_units`, `subject_prospectus_id`) VALUES
('CC100', 'Introduction to Computing', 3.00, 2.00, 1.00, '2023-2024'),
('CC101', 'Computer Programming 1 (Fundamentals of Programming)', 4.00, 3.00, 1.00, '2023-2024'),
('CC103', 'Data Structures and Algorithms', 3.00, 2.00, 1.00, '2023-2024'),
('CC104', 'Information Management', 3.00, 2.00, 1.00, '2023-2024'),
('CC105', 'Applications Development and Emerging Technologies', 3.00, 2.00, 1.00, '2023-2024'),
('MAD121', 'Mobile Application Development', 3.00, 2.00, 1.00, '2023-2024'),
('SAMPLE', 'Sample', 2.00, 1.00, 1.00, '2023-2024'),
('SIPP125', 'Social Issues and Professional Practice', 3.00, 3.00, 0.00, '2023-2024'),
('WD123', 'Web Development 2', 3.00, 2.00, 1.00, '2023-2024');

-- --------------------------------------------------------

--
-- Table structure for table `subject_prospectus`
--

CREATE TABLE `subject_prospectus` (
  `effective_school_year` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject_prospectus`
--

INSERT INTO `subject_prospectus` (`effective_school_year`) VALUES
('2023-2024'),
('2025-2026');

-- --------------------------------------------------------

--
-- Table structure for table `user_list`
--

CREATE TABLE `user_list` (
  `user_id` varchar(10) NOT NULL,
  `username` varchar(20) NOT NULL,
  `is_admin` tinyint(1) NOT NULL,
  `is_staff` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_list`
--

INSERT INTO `user_list` (`user_id`, `username`, `is_admin`, `is_staff`) VALUES
('201001234', 'aa201001234', 1, 1),
('201201234', 'ab201201234', 0, 1),
('201201235', 'ac201201235', 0, 1),
('201201236', 'ac201201236', 0, 1),
('201201237', 'ac201201237', 0, 1),
('201201238', 'ac201201238', 0, 1),
('201201239', 'ac201201239', 0, 1),
('201201240', 'ad201201240', 0, 1),
('201201241', 'ad201201241', 0, 1),
('201201242', 'ad201201242', 0, 1),
('201201243', 'ad201201243', 0, 1),
('202101234', 'qb202101234', 0, 0),
('202101235', 'qb202101235', 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `class_details`
--
ALTER TABLE `class_details`
  ADD PRIMARY KEY (`class_id`,`subject_type`) USING BTREE,
  ADD KEY `classdet_section_id_fk` (`course_abbr`,`year_level`,`section`),
  ADD KEY `classdet_fac_id_fk` (`teacher_assigned`),
  ADD KEY `classdet_sem_pk_fk` (`semester`,`school_year`),
  ADD KEY `classdet_sub_id_fk` (`subject_id`);

--
-- Indexes for table `class_logs`
--
ALTER TABLE `class_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `classlog_sched_pk_fk` (`class_id`,`subject_type`,`day`);

--
-- Indexes for table `class_schedule`
--
ALTER TABLE `class_schedule`
  ADD PRIMARY KEY (`class_id`,`subject_type`,`day`) USING BTREE,
  ADD KEY `classsched_room_id_fk` (`room_code`,`room_no`),
  ADD KEY `classsched_semester_id_fk` (`semester`,`school_year`);

--
-- Indexes for table `course_details`
--
ALTER TABLE `course_details`
  ADD PRIMARY KEY (`course_abbr`);

--
-- Indexes for table `faculty_list`
--
ALTER TABLE `faculty_list`
  ADD PRIMARY KEY (`faculty_id`),
  ADD KEY `fac_user_id_fk` (`user_id`);

--
-- Indexes for table `room_list`
--
ALTER TABLE `room_list`
  ADD PRIMARY KEY (`room_code`,`room_no`);

--
-- Indexes for table `room_type`
--
ALTER TABLE `room_type`
  ADD PRIMARY KEY (`room_type_id`);

--
-- Indexes for table `section_details`
--
ALTER TABLE `section_details`
  ADD PRIMARY KEY (`course_abbr`,`year_level`,`section`);

--
-- Indexes for table `semester`
--
ALTER TABLE `semester`
  ADD PRIMARY KEY (`semester`,`school_year`);

--
-- Indexes for table `subject_details`
--
ALTER TABLE `subject_details`
  ADD PRIMARY KEY (`subject_code`),
  ADD KEY `subdet_prosp_id_fk` (`subject_prospectus_id`);

--
-- Indexes for table `subject_prospectus`
--
ALTER TABLE `subject_prospectus`
  ADD PRIMARY KEY (`effective_school_year`);

--
-- Indexes for table `user_list`
--
ALTER TABLE `user_list`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `class_logs`
--
ALTER TABLE `class_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `faculty_list`
--
ALTER TABLE `faculty_list`
  MODIFY `faculty_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `account`
--
ALTER TABLE `account`
  ADD CONSTRAINT `accountid_fk` FOREIGN KEY (`account_id`) REFERENCES `user_list` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `class_details`
--
ALTER TABLE `class_details`
  ADD CONSTRAINT `classdet_fac_id_fk` FOREIGN KEY (`teacher_assigned`) REFERENCES `faculty_list` (`faculty_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `classdet_section_id_fk` FOREIGN KEY (`course_abbr`,`year_level`,`section`) REFERENCES `section_details` (`course_abbr`, `year_level`, `section`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `classdet_sem_pk_fk` FOREIGN KEY (`semester`,`school_year`) REFERENCES `semester` (`semester`, `school_year`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `classdet_sub_id_fk` FOREIGN KEY (`subject_id`) REFERENCES `subject_details` (`subject_code`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `class_logs`
--
ALTER TABLE `class_logs`
  ADD CONSTRAINT `classlog_sched_pk_fk` FOREIGN KEY (`class_id`,`subject_type`,`day`) REFERENCES `class_schedule` (`class_id`, `subject_type`, `day`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `class_schedule`
--
ALTER TABLE `class_schedule`
  ADD CONSTRAINT `classsched_class_id_fk` FOREIGN KEY (`class_id`,`subject_type`) REFERENCES `class_details` (`class_id`, `subject_type`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `classsched_room_id_fk` FOREIGN KEY (`room_code`,`room_no`) REFERENCES `room_list` (`room_code`, `room_no`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `classsched_semester_id_fk` FOREIGN KEY (`semester`,`school_year`) REFERENCES `semester` (`semester`, `school_year`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `faculty_list`
--
ALTER TABLE `faculty_list`
  ADD CONSTRAINT `fac_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `user_list` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `room_list`
--
ALTER TABLE `room_list`
  ADD CONSTRAINT `rlist_rtype_id_fk` FOREIGN KEY (`room_code`) REFERENCES `room_type` (`room_type_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `section_details`
--
ALTER TABLE `section_details`
  ADD CONSTRAINT `secdet_course_id_fk` FOREIGN KEY (`course_abbr`) REFERENCES `course_details` (`course_abbr`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `subject_details`
--
ALTER TABLE `subject_details`
  ADD CONSTRAINT `subdet_prosp_id_fk` FOREIGN KEY (`subject_prospectus_id`) REFERENCES `subject_prospectus` (`effective_school_year`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
