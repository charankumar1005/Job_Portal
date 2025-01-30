-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 04, 2024 at 11:54 AM
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
-- Database: `job_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `job_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `application_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `applied_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Pending','Reviewed','Rejected','Accepted') DEFAULT 'Pending',
  `resume` varchar(255) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `experience` varchar(100) DEFAULT NULL,
  `skills` text DEFAULT NULL,
  `expected_salary` decimal(10,2) DEFAULT NULL,
  `current_salary` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `job_id`, `user_id`, `application_date`, `applied_at`, `status`, `resume`, `name`, `dob`, `experience`, `skills`, `expected_salary`, `current_salary`) VALUES
(5, 4, 2, '2024-09-22 18:30:00', '2024-09-23 04:26:06', 'Reviewed', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 5, 2, '2024-09-23 18:30:00', '2024-09-24 14:33:49', 'Rejected', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 7, 7, '2024-10-27 18:30:00', '2024-10-28 05:40:50', 'Reviewed', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, 7, 8, '2024-10-27 18:30:00', '2024-10-28 05:53:00', 'Rejected', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 4, 8, '2024-10-27 18:30:00', '2024-10-28 06:15:14', 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 7, 8, '2024-10-27 18:30:00', '2024-10-28 06:15:20', 'Accepted', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(11, 4, 11, '2024-11-03 18:30:00', '2024-11-04 05:07:39', 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, 4, 11, '2024-11-03 18:30:00', '2024-11-04 05:31:13', 'Pending', 'file:///G:/My%20Drive/Charan_Kumar_.pdf', 'narersh', '2002-08-30', '0', 'java,python', 500000.00, 0.00),
(13, 4, 11, '2024-11-03 18:30:00', '2024-11-04 05:32:39', 'Pending', 'file:///G:/My%20Drive/Charan_Kumar_.pdf', 'narersh', '2002-08-30', '0', 'java,python', 500000.00, 0.00),
(14, 4, 11, '2024-11-03 18:30:00', '2024-11-04 05:32:43', 'Pending', 'file:///G:/My%20Drive/Charan_Kumar_.pdf', 'narersh', '2002-08-30', '0', 'java,python', 500000.00, 0.00),
(15, 4, 11, '2024-11-03 18:30:00', '2024-11-04 05:34:49', 'Pending', 'file:///G:/My%20Drive/Charan_Kumar_.pdf', 'narersh', '2002-08-30', '0', 'java,python', 500000.00, 0.00),
(16, 4, 11, '2024-11-03 18:30:00', '2024-11-04 05:34:54', 'Pending', 'file:///G:/My%20Drive/Charan_Kumar_.pdf', 'narersh', '2002-08-30', '0', 'java,python', 500000.00, 0.00),
(17, 4, 11, '2024-11-03 18:30:00', '2024-11-04 05:35:01', 'Pending', 'file:///G:/My%20Drive/Charan_Kumar_.pdf', 'narersh', '2002-08-30', '0', 'java,python', 500000.00, 0.00),
(18, 4, 11, '2024-11-03 18:30:00', '2024-11-04 05:37:25', 'Pending', 'file:///G:/My%20Drive/Charan_Kumar_.pdf', 'narersh', '2002-08-30', '0', 'java,python', 500000.00, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `employers`
--

CREATE TABLE `employers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `company_location` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `location` varchar(255) NOT NULL,
  `company` varchar(255) NOT NULL,
  `salary` decimal(10,2) NOT NULL,
  `posted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `title`, `description`, `location`, `company`, `salary`, `posted_by`) VALUES
(4, 'Full Stack Java Developer', 'must have springboot knowledge', 'Bangalore Urban', 'Infosys Pvt Ltd', 2400000.00, 4),
(5, 'Java Developer Intern', 'Need a successful student', 'Bangalore Urban', 'Concentrix', 15000.00, 3),
(6, 'Java developer', 'Good programming knowledge', 'Banglore', 'TCS', 50000.00, 5),
(7, 'Web developer', 'Having good website developing skills', 'Hyderabad', 'HCL', 500000.00, 6);

-- --------------------------------------------------------

--
-- Table structure for table `jobseekers`
--

CREATE TABLE `jobseekers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `skills` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('jobseeker','employer','admin') NOT NULL,
  `company_name` varchar(100) DEFAULT NULL,
  `resume` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `skills` varchar(255) DEFAULT NULL,
  `company_location` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`, `company_name`, `resume`, `created_at`, `skills`, `company_location`) VALUES
(1, 'admin', 'adminpassword', 'admin@example.com', 'admin', NULL, NULL, '2024-09-19 12:41:49', NULL, NULL),
(3, 'employer', '1234', '23352100@pondiuni.ac.in', 'employer', 'Pondicherry University', NULL, '2024-09-19 12:43:24', NULL, NULL),
(4, 'infosys', '1234', 'sams@pondiuni.ac.in', 'employer', 'Infosys Pvt Ltd', NULL, '2024-09-23 04:23:56', NULL, NULL),
(5, 'charan', '123', 'charan@gmail.com', 'employer', 'Tcs', NULL, '2024-10-28 04:28:12', NULL, NULL),
(6, 'tharun', '123', 'tharun@gmail.com', 'employer', 'Infosys', NULL, '2024-10-28 05:34:21', NULL, NULL),
(7, 'cherry', '123', 'cherry87@gmail.com', 'jobseeker', NULL, NULL, '2024-10-28 05:37:05', NULL, NULL),
(8, 'madhu', '123', 'madhu@gmail.com', 'jobseeker', NULL, 'uploads/1730094762_caste Certificate.jpg', '2024-10-28 05:52:42', NULL, NULL),
(10, 'charankumar', '123456', 'charan@12gmail.com', 'admin', NULL, NULL, '2024-11-04 05:04:00', NULL, NULL),
(11, 'naresh', '123', 'naresh@gmail.com', 'jobseeker', NULL, 'uploads/1730696822_Lighticon.png', '2024-11-04 05:07:02', NULL, NULL),
(12, 'Kirankumar', '123', 'kiran12@gmail.com', 'jobseeker', 'Tcs', NULL, '2024-11-04 05:44:35', 'Java', 'Mumbai');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_id` (`job_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `employers`
--
ALTER TABLE `employers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `posted_by` (`posted_by`);

--
-- Indexes for table `jobseekers`
--
ALTER TABLE `jobseekers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `employers`
--
ALTER TABLE `employers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `jobseekers`
--
ALTER TABLE `jobseekers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`);

--
-- Constraints for table `employers`
--
ALTER TABLE `employers`
  ADD CONSTRAINT `employers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `jobs_ibfk_1` FOREIGN KEY (`posted_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `jobseekers`
--
ALTER TABLE `jobseekers`
  ADD CONSTRAINT `jobseekers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
