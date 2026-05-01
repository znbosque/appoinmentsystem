-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 01, 2026 at 08:50 AM
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
-- Database: `barangay_queue`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `birthdate` date DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `service` varchar(100) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `assigned_staff` varchar(100) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Pending',
  `queue_number` int(11) NOT NULL,
  `purpose` text DEFAULT NULL,
  `requirements` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `staff_assigned` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `username`, `fullname`, `birthdate`, `gender`, `contact`, `email`, `address`, `service`, `appointment_date`, `appointment_time`, `assigned_staff`, `status`, `queue_number`, `purpose`, `requirements`, `notes`, `created_at`, `staff_assigned`) VALUES
(43, '', 'kaycee', '0000-00-00', 'Female', '0987345289', 'kaycee@gmail.com', 'zone 3 balza', 'Barangay Service', '2026-03-27', '07:00:00', 'Juan Dela Cruz– Barangay Captain', 'canceled', 1, '', NULL, '', '2026-03-20 18:32:48', 'Juan Dela Cruz – Punong Barangay'),
(44, '', 'Jacl', '1998-08-30', 'Male', '099930214819', 'tuscano@gmail.com', 'Zzone 3', 'Barangay Service', '2026-03-31', '15:00:00', 'Maria Santos– Barangay Kagawad', 'Pending', 1, 'affidavit', NULL, 'request', '2026-03-27 17:35:09', 'Juan Dela Cruz – Punong Barangay'),
(45, '', 'Jask', '2025-07-23', 'Male', '096962323', 'tusca@gmail.com', 'Zone 2', 'Medical Checkup', '2026-03-27', '15:00:00', 'Dr. Juan Santos', 'Pending', 1, 'Check up', NULL, 'with med cert', '2026-03-27 17:36:49', 'Dr. Juan Santos');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'staff'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL,
  `profile` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `profile`, `email`, `contact`) VALUES
(1, 'joel', '$2y$10$6bM9VYLfOvFp1iMu/G5juuly2.x6KGiIZ0X2E8rmROZ0i/xItH91W', 'user', NULL, NULL, NULL),
(2, 'kaycee', '$2y$10$JFVHqYFIEqoi.WxYIIGRKOyM0VSZtlQToagyUxo72PVculoXPgSKS', 'user', 'kaycee_1773778025.jpg', NULL, NULL),
(3, 'admin', '$2y$10$lh97qVRPPH.VuIJGSle6NetU8IM2eX0OKqnogGTuuAWV/SwDTIZFi', 'admin', 'admin_1773775624.jpg', NULL, NULL),
(4, 'zandra', '$2y$10$Xh8DnApz4jlD./eb7rrVmuIVGQw5FUkIM1Nfhl71h3DDq0YLdwiXe', 'user', NULL, NULL, NULL),
(5, 'nicole', '$2y$10$.kVivTfEDoucXfL2/K.oiOR3ZSchE.Q1z0AGb.O.4OWu0jPg91pWe', '', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
