-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql300.infinityfree.com
-- Generation Time: Aug 05, 2025 at 09:41 AM
-- Server version: 11.4.7-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_39578464_Ideal_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `PASSWORD` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `email`, `PASSWORD`) VALUES
(1, 'admin@example.com', 'admin123'),
(2, 'idealpublicschool@gmail.com', 'ideal@1234');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `submitted_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `email`, `message`, `created_at`, `submitted_at`) VALUES
(3, 'Umar Mumtaz Ahmad', 'khan@gmail.com', 'Hi my name is Khan I want to take admission in 5th class', '2025-07-29 10:10:44', '2025-07-29 10:32:43');

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE `results` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `marks_obtained` int(11) DEFAULT NULL,
  `total_marks` int(11) DEFAULT NULL,
  `exam_type` varchar(50) DEFAULT NULL,
  `date` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`id`, `student_id`, `subject`, `marks_obtained`, `total_marks`, `exam_type`, `date`) VALUES
(1, 1, 'Math', 45, 50, 'Midterm', '2025-07-20'),
(2, 1, 'Science', 40, 50, 'Midterm', '2025-07-20'),
(3, 1, 'English', 43, 50, 'Midterm', '2025-07-20'),
(4, 2, 'Math', 44, 50, 'Midterm', '2025-07-20'),
(5, 2, 'Science', 47, 50, 'Midterm', '2025-07-20'),
(6, 2, 'English', 43, 50, 'Midterm', '2025-07-20'),
(7, 10, 'Math', 80, 100, '0', '2025-07-31'),
(8, 10, 'Science ', 78, 100, '0', '2025-07-31'),
(9, 10, 'Hindi', 95, 100, '0', '2025-07-31'),
(10, 10, 'English', 75, 100, '0', '2025-07-31'),
(11, 10, 'Social Science ', 82, 100, 'Unit Test', '2025-07-31'),
(12, 1, 'Math', 90, 100, 'Half Yearly', '2025-07-30'),
(13, 1, 'Science', 95, 100, 'Half Yearly', '2025-07-30'),
(14, 1, 'Social Science ', 91, 100, 'Half Yearly', '2025-07-30'),
(15, 11, 'Math', 98, 100, 'Unit Test', '2025-08-05'),
(16, 11, 'science', 35, 100, 'Unit Test', '2025-08-05'),
(17, 11, 'S Science', 36, 100, 'Unit Test', '2025-08-05'),
(18, 11, 'Math S', 40, 100, 'Unit Test', '2025-08-05');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `gender` enum('Male','Female','Other') DEFAULT 'Male',
  `dob` date DEFAULT NULL,
  `roll_no` varchar(50) DEFAULT NULL,
  `class` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `email`, `password`, `gender`, `dob`, `roll_no`, `class`) VALUES
(1, 'Umar Mumtaz Ahmad', 'umar1@gmail.com', '', 'Male', '2017-06-13', '1', 'LKG'),
(2, 'Atif Khan', 'umar1@gmail.com', '', 'Male', '2008-02-29', '2', 'LKG'),
(7, 'Aslam Khan', 'umar1@gmail.com', '', 'Male', '2025-07-30', '4', 'LKG'),
(8, 'aaaaa', 'adminaa@example.com', '$2y$10$ENCRYPTED_PASSWORD_HASH', 'Male', '2025-07-30', '5', 'LKG'),
(9, 'abcd', 'abcd@gmail.com', '$2y$10$ENCRYPTED_PASSWORD_HASH', 'Male', '2025-07-07', '5', 'LKG'),
(10, 'Aslam Khan', 'umar11@gmail.com', '$2y$10$Zf0.OjjgOKswpZgTbUrjC.gUapHscDrKTGUJMaog1Ro6uIoMp0T6i', 'Male', '2025-06-29', '6', 'Class 1'),
(11, 'Ashif Khan', 'asif@gmail.com', '$2y$10$8Lgi22Oakrdx8z/4Qoq.ueixwOH05WKxsRKKuX9.2oRl7BZixHFpm', 'Male', '2025-07-27', '8', 'LKG');

-- --------------------------------------------------------

--
-- Table structure for table `student_attendance`
--

CREATE TABLE `student_attendance` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `class` varchar(50) DEFAULT NULL,
  `date` date NOT NULL,
  `status` enum('Present','Absent') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `student_attendance`
--

INSERT INTO `student_attendance` (`id`, `student_id`, `class`, `date`, `status`) VALUES
(1, 1, 'LKG', '2025-07-30', 'Present'),
(2, 4, 'LKG', '2025-07-30', 'Present'),
(3, 8, 'LKG', '2025-07-31', 'Present'),
(4, 7, 'LKG', '2025-07-31', 'Present'),
(5, 2, 'LKG', '2025-07-31', 'Absent'),
(6, 1, 'LKG', '2025-07-31', 'Absent'),
(7, 10, 'Class 1', '2025-07-31', 'Present'),
(8, 8, 'LKG', '2025-08-02', 'Present'),
(9, 9, 'LKG', '2025-08-02', 'Present'),
(10, 7, 'LKG', '2025-08-02', 'Present'),
(11, 2, 'LKG', '2025-08-02', 'Absent'),
(12, 1, 'LKG', '2025-08-02', 'Present'),
(13, 10, 'Class 1', '2025-08-02', 'Present'),
(14, 8, 'LKG', '2025-08-05', 'Present'),
(15, 9, 'LKG', '2025-08-05', 'Present'),
(16, 11, 'LKG', '2025-08-05', 'Present'),
(17, 7, 'LKG', '2025-08-05', 'Absent'),
(18, 2, 'LKG', '2025-08-05', 'Present'),
(19, 1, 'LKG', '2025-08-05', 'Present');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `joined_date` date DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `name`, `email`, `phone`, `subject`, `joined_date`, `password`) VALUES
(1, 'Aslam Khan', 'aslam@gmail.com', NULL, 'Math', NULL, ''),
(3, 'Hamza', 'hamza@gmail.com', NULL, 'Science', NULL, ''),
(4, 'Umair Ahmad', 'umair@gmail.com', NULL, 'Math', NULL, ''),
(5, 'Mohsin Khan', 'mohsin@gmail.com', NULL, 'Math', NULL, ''),
(6, 'xyz', 'xyz@gmail.com', '7858965845', 'science', '2025-07-31', '$2y$10$W5R19NiYY7q4xtvCLm9l9ejAwIgqWvWEkgPtYOqVeEtKMq8C17m9q');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_attendance`
--

CREATE TABLE `teacher_attendance` (
  `id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `status` enum('Present','Absent','Leave') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `teacher_attendance`
--

INSERT INTO `teacher_attendance` (`id`, `teacher_id`, `date`, `status`) VALUES
(1, 1, '2025-07-29', 'Present'),
(10, 4, '2025-07-30', 'Present'),
(3, 3, '2025-07-29', 'Present'),
(4, 4, '2025-07-29', 'Present'),
(5, 4, '2025-07-28', 'Present'),
(6, 4, '2025-07-27', 'Present'),
(7, 4, '2025-07-26', 'Present'),
(8, 4, '2025-07-25', 'Present'),
(9, 4, '2025-07-24', 'Absent'),
(11, 3, '2025-07-30', 'Present'),
(12, 1, '2025-07-30', 'Present'),
(13, 6, '2025-07-31', 'Present'),
(14, 6, '2025-08-02', 'Present'),
(15, 5, '2025-08-02', 'Present'),
(16, 4, '2025-08-02', 'Present'),
(17, 1, '2025-08-02', 'Present'),
(18, 3, '2025-08-02', 'Present'),
(19, 6, '2025-08-05', 'Present');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_attendance`
--
ALTER TABLE `student_attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teacher_attendance`
--
ALTER TABLE `teacher_attendance`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `teacher_id` (`teacher_id`,`date`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `student_attendance`
--
ALTER TABLE `student_attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `teacher_attendance`
--
ALTER TABLE `teacher_attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
