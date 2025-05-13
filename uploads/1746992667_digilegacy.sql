-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 11, 2025 at 09:37 PM
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
-- Database: `digilegacy`
--

-- --------------------------------------------------------

--
-- Table structure for table `beneficiaries`
--

CREATE TABLE `beneficiaries` (
  `id` int(11) NOT NULL,
  `nic` varchar(20) DEFAULT NULL,
  `personal_code` varchar(50) DEFAULT NULL,
  `relationship` varchar(50) DEFAULT NULL,
  `shared_count` int(11) DEFAULT 1,
  `added_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `beneficiaries`
--

INSERT INTO `beneficiaries` (`id`, `nic`, `personal_code`, `relationship`, `shared_count`, `added_date`) VALUES
(1, '200112346012', 'YHMOQA00', 'sis', 1, '2025-05-08'),
(2, '200211300317', 'YHMOQA00', 'sis', 1, '2025-05-08');

-- --------------------------------------------------------

--
-- Table structure for table `file_boxes`
--

CREATE TABLE `file_boxes` (
  `id` int(11) NOT NULL,
  `box_id` varchar(50) NOT NULL,
  `uploaded_by` varchar(10) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `visible_to` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `file_boxes`
--

INSERT INTO `file_boxes` (`id`, `box_id`, `uploaded_by`, `title`, `content`, `visible_to`, `created_at`) VALUES
(1, 'box_1746986125543', 'U003', NULL, NULL, NULL, '2025-05-11 17:55:25'),
(2, 'box_1746986125774', 'U003', NULL, NULL, NULL, '2025-05-11 17:55:25'),
(3, 'box_1746986126095', 'U003', NULL, NULL, NULL, '2025-05-11 17:55:26'),
(4, 'box_1746986126238', 'U003', NULL, NULL, NULL, '2025-05-11 17:55:26'),
(5, 'box_1746986126390', 'U003', NULL, NULL, NULL, '2025-05-11 17:55:26');

-- --------------------------------------------------------

--
-- Table structure for table `file_box_files`
--

CREATE TABLE `file_box_files` (
  `id` int(11) NOT NULL,
  `box_id` varchar(50) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` text NOT NULL,
  `uploaded_by` varchar(10) NOT NULL,
  `visible_to` text NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `file_box_files`
--

INSERT INTO `file_box_files` (`id`, `box_id`, `file_name`, `file_path`, `uploaded_by`, `visible_to`, `title`, `content`, `uploaded_at`) VALUES
(1, 'box_1746986125543', 'MY Stuffs.xlsx', 'uploads/1746990746_MY Stuffs.xlsx', 'U001', '\"[\\\"U002\\\",\\\"U005\\\"]\"', '', '', '2025-05-11 19:12:26'),
(2, 'box_1746986125543', 'MY Stuffs.xlsx', 'uploads/1746991370_MY Stuffs.xlsx', 'U003', '\"[\\\"U002\\\",\\\"U004\\\"]\"', '', '', '2025-05-11 19:22:50');

-- --------------------------------------------------------

--
-- Table structure for table `lawyers`
--

CREATE TABLE `lawyers` (
  `lawyer_id` varchar(10) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `prefix` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mobile_number` varchar(20) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `address1` text DEFAULT NULL,
  `address2` text DEFAULT NULL,
  `nic_passport_number` varchar(50) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `security_question` text DEFAULT NULL,
  `answer` text DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `law_firm_name` varchar(100) DEFAULT NULL,
  `law_firm_address` text DEFAULT NULL,
  `professional_license_number` varchar(100) DEFAULT NULL,
  `license_expiry_date` date DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `document_path` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_picture_url` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lawyers`
--

INSERT INTO `lawyers` (`lawyer_id`, `first_name`, `last_name`, `prefix`, `email`, `mobile_number`, `date_of_birth`, `country`, `address1`, `address2`, `nic_passport_number`, `postal_code`, `security_question`, `answer`, `password_hash`, `law_firm_name`, `law_firm_address`, `professional_license_number`, `license_expiry_date`, `bio`, `document_path`, `created_at`, `profile_picture_url`) VALUES
('L001', 'Ravindu', 'yasas', 'ss', 'sam@gmail.com', '0775905443', '2025-05-08', 'Sri Lanka', 'No.48/E Wataddara, Veyangoda', 'No.48/E Wataddara, Veyangoda', '200211300317', '11100', 'pet name', 'rosi', '$2y$10$AF190gf.UUEWxvDGgptOregMxDpviZ/xNx/zAgkiMp0kV43oJrSKe', 'Law', 'No: 120/5, Wijerama Vidya Mawatha, Colombo 07, Sri', '45465985', '2025-05-08', 'uy38yrru5t', 'Luploads/1746675528_Admin.jpg', '2025-05-08 03:38:48', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `uploaded_files`
--

CREATE TABLE `uploaded_files` (
  `id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` text NOT NULL,
  `box_id` varchar(50) NOT NULL,
  `uploaded_by` varchar(10) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` varchar(10) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `prefix` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mobile_number` varchar(20) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `occupation` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `address1` text DEFAULT NULL,
  `address2` text DEFAULT NULL,
  `nic_passport_number` varchar(50) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `security_question` text DEFAULT NULL,
  `answer` text DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_picture_url` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `prefix`, `email`, `mobile_number`, `date_of_birth`, `occupation`, `country`, `address1`, `address2`, `nic_passport_number`, `postal_code`, `security_question`, `answer`, `password_hash`, `bio`, `created_at`, `profile_picture_url`) VALUES
('U001', 'Ravindu', 'yasas', 'ss', 'sryasaswijesinghe@outlook.com', '0775905443', '2025-05-08', 'CEO', 'Sri Lanka', 'No.48/E Wataddara, Veyangoda', 'wataddara', '200211300317', '11100', 'pet name', 'rosi', '$2y$10$WrT9Yf/FOOwSW7JZRQVmCOgbYDZ/S3zb5U1kEiZwHDDhxhoV7iQB.', 'gyegfyg', '2025-05-08 04:05:02', NULL),
('U002', 'deshan', 'lanka', 'Besama', 'deshanlanka@gmail.com', '0332233223', '2025-05-08', 'Director', 'Sri Lanka', 'colombo', 'udugampola', '20011234590', '34344', 'Wify', 'Athili', '$2y$10$6PPtLj6Nvaen82P92OeNouZre79p6NgMU5BBe4fwbIaVmCxYA2T7K', 'gceetdftfy', '2025-05-08 04:22:06', NULL),
('U003', 'Kamal', 'Subasinghe', 'Mr.', 'abc@gmail.com', '1234567890', '2025-05-08', 'Student', 'Sri Lnaka', 'abc1', 'abc2', '12345', '09876', 'what is your name?', 'vinuka', '$2y$10$qNHxKulil4KZNlt/F9IMxuWBLpSlPWb6iAFeZk4Cjn/IpsFuXuZtu', 'ndalndonwdla', '2025-05-08 09:45:58', 'https://www.w3schools.com/w3images/avatar2.png'),
('U004', 'Namal', 'Ariyarathne', 'Mr.', 'abc1234@gmail.com', '1387924658', '2025-05-10', 'Student', 'Sri Lnaka', 'abc1', 'abc2', '123123415', '09898', 'what is your name?', 'vinuka', '$2y$10$XvlG843acurkywg8twaxt.mwbzH1057BNVadc1l5NXEMgSftMrEU.', 'alskdlajksndansdj', '2025-05-10 12:16:46', NULL),
('U005', 'Bimal', 'Ariyarachi', 'Mr.', 'abc543@gmail.com', '123456786', '2025-05-10', 'Student', 'Sri Lnaka', 'abc1', 'abc2', '12345576809', '3457886', 'what is your name?', 'vinuka', '$2y$10$RiwNqxnHM8R8sgAt6UiNg.YHqRwcmwleUNaLBZPl9TBjVmlIzFXCK', 'sdgfsdffhgjghmgmjg', '2025-05-10 12:18:32', NULL),
('U006', 'Ramal', 'Aponsu', 'Mr.', 'vinukahansith.edu@gmail.com', '1234567890', '2025-05-28', 'Student', 'Sri Lnaka', 'abc1', 'abc2', '1231288h87', '0989800', 'what is your name?', 'vinuka', '$2y$10$egcKAN1suR9lZWP7cEqimuJQrOTIdBTU3EsN2YwUFIa/KChGifB.i', 'fdijaoqiwejdoqwidopwi', '2025-05-11 11:11:00', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `file_boxes`
--
ALTER TABLE `file_boxes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uploaded_by` (`uploaded_by`);

--
-- Indexes for table `file_box_files`
--
ALTER TABLE `file_box_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lawyers`
--
ALTER TABLE `lawyers`
  ADD PRIMARY KEY (`lawyer_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `uploaded_files`
--
ALTER TABLE `uploaded_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uploaded_by` (`uploaded_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `file_boxes`
--
ALTER TABLE `file_boxes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `file_box_files`
--
ALTER TABLE `file_box_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `uploaded_files`
--
ALTER TABLE `uploaded_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `file_boxes`
--
ALTER TABLE `file_boxes`
  ADD CONSTRAINT `file_boxes_ibfk_1` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `uploaded_files`
--
ALTER TABLE `uploaded_files`
  ADD CONSTRAINT `uploaded_files_ibfk_1` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
