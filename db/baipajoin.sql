-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 09, 2021 at 03:48 PM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 7.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `baipajoin`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `admin_name` varchar(25) NOT NULL,
  `admin_email` varchar(50) NOT NULL,
  `admin_pass` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `admin_name`, `admin_email`, `admin_pass`) VALUES
(2021001, 'Melnar', 'narancit@gmail.com', 'e07ac1db65fbdd768477e5c79e3642d0'),
(2021002, 'Alexis', 'salvador.alexis01@gmail.com', 'e07ac1db65fbdd768477e5c79e3642d0'),
(2021007, 'Byrone', 'byronekeith@gmail.com', 'e07ac1db65fbdd768477e5c79e3642d0');

-- --------------------------------------------------------

--
-- Table structure for table `adventure`
--

CREATE TABLE `adventure` (
  `adv_id` int(11) NOT NULL,
  `adv_images` varchar(200) NOT NULL,
  `adv_name` varchar(50) NOT NULL,
  `adv_kind` varchar(25) NOT NULL,
  `adv_type` varchar(15) NOT NULL,
  `adv_address` varchar(50) NOT NULL,
  `adv_town` varchar(50) NOT NULL,
  `adv_totalcostprice` decimal(7,2) NOT NULL,
  `adv_date` date NOT NULL,
  `adv_details` varchar(500) NOT NULL,
  `adv_postedDate` date NOT NULL,
  `adv_maxguests` int(3) DEFAULT NULL,
  `adv_currentGuest` int(3) DEFAULT NULL,
  `adv_itineraryImg` varchar(50) NOT NULL,
  `adv_status` varchar(25) NOT NULL,
  `orga_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `adventure`
--

INSERT INTO `adventure` (`adv_id`, `adv_images`, `adv_name`, `adv_kind`, `adv_type`, `adv_address`, `adv_town`, `adv_totalcostprice`, `adv_date`, `adv_details`, `adv_postedDate`, `adv_maxguests`, `adv_currentGuest`, `adv_itineraryImg`, `adv_status`, `orga_id`) VALUES
(10, ',614de60d9c7d06.52059083.jpg,614de60d9d8ca7.58183104.jpg,614de60d9da808.55042739.jpg', 'Scia Hills Resort', 'Swimming', 'Packaged', 'Oslob', 'Oslob', '2345.00', '2021-11-17', 'This Is A Sample Details For Such An Adventure!', '2021-09-24', 5, 1, '614de60d9c5f58.27351583.jpg', 'not full', 10),
(11, ',614de7c0021ff0.86589175.jpg,614de7c0031d08.00892863.jpg,614de7c0033878.54957980.jpg,614de7c0034ef4.83617050.jpg', 'Nug-As Forest Reserve', 'Mountain Hiking', 'Packaged', 'Alcoy', 'Alcoy', '5543.00', '2021-10-30', 'Sample Adventure Details Is Inputted Here!', '2021-09-24', 8, 3, '614de7c0020274.61583006.jpg', 'not full', 10),
(13, ',6155d55dbab4f1.43871558.jpg,6155d55dbadf83.26943549.jpg,6155d55dbb0929.00538715.jpg', 'Nug-As Forest Reserve', 'Mountain Hiking', 'Packaged', 'Alcoy', 'Alcoy', '2771.50', '2021-11-15', 'Sample Adventure Details Is Inputted Here!', '2021-09-30', 4, 3, '6155d55dba6510.44506753.jpg', 'canceled', 10),
(14, ',6157cd7730dfc2.76878732.jpg,6157cd77310990.38295703.jpg,6157cd77313b97.35623522.jpg', 'Nug-As Forest Reserve', 'Mountain Hiking', 'Packaged', 'Alcoy', 'Alcoy', '1385.75', '2021-10-12', 'Sample Adventure Details Is Inputted Here!', '2021-10-02', 2, 2, '6157cd7730af64.45749091.jpg', 'full', 10),
(15, ',61596259b89020.11328347.jpg,61596259b9a772.10029728.jpg,61596259b9d472.59038419.jpg', 'Scia Hills Resort', 'Swimming', 'Packaged', 'Oslob', 'Oslob', '938.00', '2021-12-30', 'This Is A Sample Details For Such An Adventure!', '2021-10-03', 2, 0, '61596259b7f729.86559965.jpg', 'not full', 10),
(16, ',616159060c08a0.15431763.jpg,616159060d7b17.80858850.jpg,616159060da784.56668034.jpg', 'Scia Hills Resort', 'Swimming', 'Packaged', 'Oslob', 'Oslob', '2345.00', '2021-12-30', 'This Is A Sample Details For Such An Adventure!', '2021-10-09', 5, 0, '616159060b8d67.89059115.jpg', 'not full', 10);

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `book_id` int(11) NOT NULL,
  `book_guests` int(3) NOT NULL,
  `book_datetime` datetime NOT NULL,
  `book_totalcosts` decimal(7,2) NOT NULL,
  `book_status` varchar(25) NOT NULL,
  `joiner_id` int(11) NOT NULL,
  `adv_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`book_id`, `book_guests`, `book_datetime`, `book_totalcosts`, `book_status`, `joiner_id`, `adv_id`) VALUES
(202248, 1, '2021-09-25 19:16:04', '732.13', 'refunded', 6, 11),
(202268, 2, '2021-09-26 00:18:28', '1449.26', 'paid', 7, 14),
(202269, 2, '2021-09-26 09:13:30', '938.00', 'waiting for payment', 8, 10),
(202273, 1, '2021-09-26 10:09:29', '500.42', 'paid', 8, 10),
(202282, 2, '2021-09-26 10:18:11', '1385.76', 'waiting for payment', 8, 11),
(202283, 3, '2021-09-26 10:19:54', '1471.25', 'refunded', 6, 10),
(202284, 2, '2021-09-26 10:36:32', '1449.26', 'paid', 7, 11),
(202285, 3, '2021-10-03 15:17:13', '2166.39', 'paid', 6, 13),
(202286, 1, '2021-10-08 21:09:44', '732.13', 'refunded', 7, 13),
(202287, 3, '2021-10-09 15:11:54', '2166.39', 'refunded', 6, 11),
(202288, 1, '2021-10-09 15:54:34', '732.13', 'paid', 7, 11);

-- --------------------------------------------------------

--
-- Table structure for table `favorite`
--

CREATE TABLE `favorite` (
  `joiner_id` int(11) NOT NULL,
  `adv_id` int(11) NOT NULL,
  `fav_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `favorite`
--

INSERT INTO `favorite` (`joiner_id`, `adv_id`, `fav_date`) VALUES
(7, 11, '2021-09-25');

-- --------------------------------------------------------

--
-- Table structure for table `guest`
--

CREATE TABLE `guest` (
  `book_id` int(11) NOT NULL,
  `guest_name` varchar(50) NOT NULL,
  `guest_phone` varchar(11) NOT NULL,
  `guest_email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `guest`
--

INSERT INTO `guest` (`book_id`, `guest_name`, `guest_phone`, `guest_email`) VALUES
(202268, 'Alexis Salvador', '09456757757', 'alexis@gmail.com'),
(202269, 'Liam Jurial', '04568348538', 'liam.j@gmail.com'),
(202269, 'Alexis Salvador', '09673445234', 'alexis@gmail.com'),
(202273, 'Kenneth Bonghanoy', '09456757757', 'kenneth.j@gmail.com'),
(202282, 'Matt', '09456757757', 'matt@gmail.com'),
(202283, 'Mary Mae Blanco', '09345774747', 'maeblanco@gmail.com'),
(202283, 'Grace Blanco', '09456757757', 'gblanco@gmail.com'),
(202283, 'Melnar Ancit', '09755315755', 'narancit@gmail.com'),
(202284, 'Melnar Ancit', '09755315755', 'narancit@gmail.com'),
(202284, 'Merry Joy Blanco', '09345774747', 'joyblanco@gmail.com'),
(202285, 'Melnar', '09673445234', 'narancit@gmail.com'),
(202285, 'Alexis Salvador', '09456757757', 'alexis@gmail.com'),
(202286, 'Lindor', '09425723472', 'lindor@gmail.com'),
(202287, 'Melnar Ancit', '09345345345', 'narancit@gmail.com'),
(202287, 'Alexis Salvador', '09345734573', 'alexis@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `joiner`
--

CREATE TABLE `joiner` (
  `joiner_id` int(11) NOT NULL,
  `joiner_fname` varchar(25) NOT NULL,
  `joiner_lname` varchar(25) NOT NULL,
  `joiner_mi` char(1) DEFAULT NULL,
  `joiner_address` varchar(50) DEFAULT NULL,
  `joiner_phone` varchar(11) DEFAULT NULL,
  `joiner_email` varchar(50) NOT NULL,
  `joiner_password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `joiner`
--

INSERT INTO `joiner` (`joiner_id`, `joiner_fname`, `joiner_lname`, `joiner_mi`, `joiner_address`, `joiner_phone`, `joiner_email`, `joiner_password`) VALUES
(6, 'Joy', 'Blanco', 'G', 'Sitio Granada', '09755315755', 'joyblanco@gmail.com', 'e07ac1db65fbdd768477e5c79e3642d0'),
(7, 'Orlindo', 'Siton', 'M', 'Sitio Granada', '09755315755', 'orlindo@gmail.com', 'e07ac1db65fbdd768477e5c79e3642d0'),
(8, 'Patricia', 'Seares', 'B', 'Sitio Granada', '09755315755', 'patricia@gmail.com', 'e07ac1db65fbdd768477e5c79e3642d0');

-- --------------------------------------------------------

--
-- Table structure for table `legal_document`
--

CREATE TABLE `legal_document` (
  `orga_id` int(11) NOT NULL,
  `docu_type` varchar(25) NOT NULL,
  `docu_description` varchar(250) NOT NULL,
  `docu_image` varchar(100) NOT NULL,
  `docu_dateadded` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `legal_document`
--

INSERT INTO `legal_document` (`orga_id`, `docu_type`, `docu_description`, `docu_image`, `docu_dateadded`) VALUES
(10, 'Docu Type3', 'Sample Legal Documents', '614dd583db2328.15326178.jpg', '2021-09-24'),
(10, 'Docu Type1', 'Sample legal documents text here.', '614dd5995fe881.51187297.jpg', '2021-09-24'),
(12, 'Docu Type3', 'This is a sample legal documents!', '61504f077924e9.38479528.jpg', '2021-09-26'),
(12, 'Docu Type1', 'This is my 2nd legal documents details sample', '61504f3605bdd2.39930972.jpg', '2021-09-26'),
(11, 'Docu Type3', 'Sample text for legal docu', '615066a44bf9b9.00251434.jpg', '2021-09-26'),
(11, 'Docu Type4', 'This is a sample documentation', '615d8655e347a9.62129966.jpg', '2021-10-06'),
(10, 'Docu Type3', 'sample', '615d9abaadee37.63363443.jpg', '2021-10-06');

-- --------------------------------------------------------

--
-- Table structure for table `organizer`
--

CREATE TABLE `organizer` (
  `orga_id` int(11) NOT NULL,
  `orga_company` varchar(50) DEFAULT NULL,
  `orga_fname` varchar(25) NOT NULL,
  `orga_lname` varchar(25) NOT NULL,
  `orga_mi` char(1) NOT NULL,
  `orga_address` varchar(50) DEFAULT NULL,
  `orga_phone` varchar(11) DEFAULT NULL,
  `orga_email` varchar(50) NOT NULL,
  `orga_password` varchar(100) NOT NULL,
  `orga_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `organizer`
--

INSERT INTO `organizer` (`orga_id`, `orga_company`, `orga_fname`, `orga_lname`, `orga_mi`, `orga_address`, `orga_phone`, `orga_email`, `orga_password`, `orga_status`) VALUES
(10, 'ABC Company', 'Melnar', 'Ancit', 'B', 'Sitio Granada', '09755315755', 'melnar.a@bbdmgroup.com', 'e07ac1db65fbdd768477e5c79e3642d0', 1),
(11, 'JKL Wanders', 'Kenneth', 'Bonghanoy', 'B', 'Bonbon', '09348272734', 'kenneth@gmail.com', 'e07ac1db65fbdd768477e5c79e3642d0', 2),
(12, 'XYZ Company Inc.', 'Liam', 'Jurial', 'A', 'Tisa', '09755315755', 'jliam@gmail.com', 'e07ac1db65fbdd768477e5c79e3642d0', 0);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` varchar(50) NOT NULL,
  `payment_method` varchar(10) NOT NULL,
  `payment_total` decimal(7,2) NOT NULL,
  `payment_datetime` datetime NOT NULL,
  `book_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `payment_method`, `payment_total`, `payment_datetime`, `book_id`) VALUES
('pi_AnAHUxwohwW9Z7hzDEtNzuSN', 'card', '732.13', '2021-09-25 19:21:10', 202248),
('pi_f31FJgJwV3Mp4VN3QEanpZ61', 'card', '1449.26', '2021-10-09 01:04:23', 202284),
('pi_FeTqHJecHqF3H5DcYNMqpXG3', 'card', '732.13', '2021-10-09 16:01:29', 202288),
('pi_fuFFJNcfQtQeTQX6qAmvUWV3', 'card', '2166.39', '2021-10-09 15:18:23', 202287),
('pi_nJzKxHU8EfwXW3Ccz7ejhVvE', 'card', '2166.39', '2021-10-03 15:20:03', 202285),
('pi_Qn1MjQ8csNTues33hLoowQt7', 'card', '732.13', '2021-10-08 21:11:18', 202286),
('pi_SEfvsjfyZNBHQEEaiMQGcgpR', 'card', '1449.26', '2021-09-26 00:41:12', 202268),
('pi_Vf49nzisYfij1dhmr9MvVuyH', 'card', '500.42', '2021-09-26 10:12:18', 202273),
('pi_ZvKbstEqUW7M31S6beNwyEJy', 'card', '1471.25', '2021-10-03 15:30:19', 202283);

-- --------------------------------------------------------

--
-- Table structure for table `rating`
--

CREATE TABLE `rating` (
  `rating_id` int(11) NOT NULL,
  `rating_stars` int(11) NOT NULL,
  `rating_message` varchar(100) NOT NULL,
  `joiner_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `rating`
--

INSERT INTO `rating` (`rating_id`, `rating_stars`, `rating_message`, `joiner_id`, `book_id`) VALUES
(5, 4, 'Very Nice!', 7, 202268);

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `req_id` int(11) NOT NULL,
  `req_user` varchar(10) NOT NULL,
  `req_type` varchar(10) NOT NULL,
  `req_dateprocess` date NOT NULL,
  `req_dateresponded` date DEFAULT NULL,
  `req_amount` decimal(7,2) DEFAULT NULL,
  `req_status` varchar(25) NOT NULL,
  `req_reason` varchar(100) DEFAULT NULL,
  `req_rcvd` int(11) NOT NULL,
  `book_id` int(11) DEFAULT NULL,
  `adv_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`req_id`, `req_user`, `req_type`, `req_dateprocess`, `req_dateresponded`, `req_amount`, `req_status`, `req_reason`, `req_rcvd`, `book_id`, `adv_id`) VALUES
(5, 'joiner', 'cancel', '2021-09-26', '2021-09-28', '732.13', 'approved', 'This is my new request cancelation reason for admins approval', 0, 202248, NULL),
(13, 'joiner', 'cancel', '2021-09-28', '2021-09-28', '500.42', 'disapproved', 'My valid reason for cancelling', 0, 202273, NULL),
(14, 'joiner', 'refund', '2021-09-28', '2021-09-28', '485.01', 'approved', NULL, 1, 202248, NULL),
(15, 'joiner', 'payout', '2021-09-28', '2021-09-28', '485.01', 'refunded', NULL, 1, 202248, NULL),
(16, 'joiner', 'cancel', '2021-09-29', '2021-09-29', '1449.26', 'disapproved', 'My valid reason for canceling', 0, 202268, NULL),
(17, 'joiner', 'cancel', '2021-09-30', '2021-10-01', '1449.26', 'disapproved', 'This is my reason for canceling', 0, 202268, NULL),
(18, 'joiner', 'resched', '2021-10-02', '2021-10-02', '1449.26', 'rescheduled', NULL, 0, 202268, NULL),
(20, 'joiner', 'cancel', '2021-10-03', '2021-10-03', '1471.25', 'disapproved', 'This is my updated reason for cancelling', 0, 202283, NULL),
(21, 'joiner', 'cancel', '2021-10-03', '2021-10-03', '1471.25', 'approved', 'This is my valid reason for canceling', 0, 202283, NULL),
(22, 'joiner', 'refund', '2021-10-03', '2021-10-03', '984.90', 'approved', NULL, 1, 202283, NULL),
(23, 'joiner', 'payout', '2021-10-03', '2021-10-03', '984.90', 'refunded', NULL, 1, 202283, NULL),
(24, 'joiner', 'cancel', '2021-10-06', NULL, '500.42', 'pending', 'Valid reason for canceling', 0, 202273, NULL),
(25, 'organizer', 'cancel', '2021-10-08', '2021-10-08', '2771.50', 'disapproved', 'This is my valid reason for canceling!', 0, NULL, 13),
(26, 'organizer', 'cancel', '2021-10-08', '2021-10-09', '2771.50', 'approved', 'Organizer valid reason for canceling', 0, NULL, 13),
(27, 'joiner', 'cancel', '2021-10-09', '2021-10-09', '1449.26', 'disapproved', 'Valid reason for canceling', 0, 202284, NULL),
(28, 'joiner', 'refund', '2021-10-09', '2021-10-09', '692.88', 'approved', NULL, 1, 202286, NULL),
(29, 'joiner', 'payout', '2021-10-09', '2021-10-09', '692.88', 'refunded', NULL, 0, 202286, NULL),
(30, 'joiner', 'cancel', '2021-10-09', '2021-10-09', '2166.39', 'approved', 'Isolated', 0, 202287, NULL),
(31, 'joiner', 'refund', '2021-10-09', '2021-10-09', '1455.04', 'approved', NULL, 1, 202287, NULL),
(32, 'joiner', 'payout', '2021-10-09', '2021-10-09', '1455.04', 'refunded', NULL, 0, 202287, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `voucher`
--

CREATE TABLE `voucher` (
  `vouch_code` varchar(25) NOT NULL,
  `vouch_discount` int(11) NOT NULL,
  `vouch_name` varchar(25) NOT NULL,
  `vouch_startdate` date NOT NULL,
  `vouch_enddate` date NOT NULL,
  `vouch_minspent` decimal(7,2) NOT NULL,
  `vouch_user` int(11) NOT NULL,
  `orga_id` int(11) NOT NULL,
  `adv_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `voucher`
--

INSERT INTO `voucher` (`vouch_code`, `vouch_discount`, `vouch_name`, `vouch_startdate`, `vouch_enddate`, `vouch_minspent`, `vouch_user`, `orga_id`, `adv_id`) VALUES
('614def1543b6f5.21106216', 5, 'Voucher Name 1', '2021-10-04', '2021-10-09', '500.00', 0, 10, 10);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `adventure`
--
ALTER TABLE `adventure`
  ADD PRIMARY KEY (`adv_id`),
  ADD KEY `orga_id` (`orga_id`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`book_id`),
  ADD KEY `joiner_id` (`joiner_id`),
  ADD KEY `adv_id` (`adv_id`);

--
-- Indexes for table `favorite`
--
ALTER TABLE `favorite`
  ADD KEY `joiner_id` (`joiner_id`),
  ADD KEY `adv_id` (`adv_id`);

--
-- Indexes for table `guest`
--
ALTER TABLE `guest`
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `joiner`
--
ALTER TABLE `joiner`
  ADD PRIMARY KEY (`joiner_id`);

--
-- Indexes for table `legal_document`
--
ALTER TABLE `legal_document`
  ADD KEY `orga_id` (`orga_id`);

--
-- Indexes for table `organizer`
--
ALTER TABLE `organizer`
  ADD PRIMARY KEY (`orga_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `rating`
--
ALTER TABLE `rating`
  ADD PRIMARY KEY (`rating_id`),
  ADD KEY `joiner_id` (`joiner_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`req_id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `adv_id` (`adv_id`);

--
-- Indexes for table `voucher`
--
ALTER TABLE `voucher`
  ADD PRIMARY KEY (`vouch_code`),
  ADD KEY `orga_id` (`orga_id`),
  ADD KEY `adv_id` (`adv_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2021010;

--
-- AUTO_INCREMENT for table `adventure`
--
ALTER TABLE `adventure`
  MODIFY `adv_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=202290;

--
-- AUTO_INCREMENT for table `joiner`
--
ALTER TABLE `joiner`
  MODIFY `joiner_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `organizer`
--
ALTER TABLE `organizer`
  MODIFY `orga_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `rating`
--
ALTER TABLE `rating`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `req_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `adventure`
--
ALTER TABLE `adventure`
  ADD CONSTRAINT `adventure_ibfk_1` FOREIGN KEY (`orga_id`) REFERENCES `organizer` (`orga_id`);

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`joiner_id`) REFERENCES `joiner` (`joiner_id`),
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`adv_id`) REFERENCES `adventure` (`adv_id`);

--
-- Constraints for table `favorite`
--
ALTER TABLE `favorite`
  ADD CONSTRAINT `favorite_ibfk_1` FOREIGN KEY (`joiner_id`) REFERENCES `joiner` (`joiner_id`),
  ADD CONSTRAINT `favorite_ibfk_2` FOREIGN KEY (`adv_id`) REFERENCES `adventure` (`adv_id`);

--
-- Constraints for table `guest`
--
ALTER TABLE `guest`
  ADD CONSTRAINT `guest_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `booking` (`book_id`);

--
-- Constraints for table `legal_document`
--
ALTER TABLE `legal_document`
  ADD CONSTRAINT `legal_document_ibfk_1` FOREIGN KEY (`orga_id`) REFERENCES `organizer` (`orga_id`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `booking` (`book_id`);

--
-- Constraints for table `rating`
--
ALTER TABLE `rating`
  ADD CONSTRAINT `rating_ibfk_1` FOREIGN KEY (`joiner_id`) REFERENCES `joiner` (`joiner_id`),
  ADD CONSTRAINT `rating_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `booking` (`book_id`);

--
-- Constraints for table `request`
--
ALTER TABLE `request`
  ADD CONSTRAINT `request_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `booking` (`book_id`),
  ADD CONSTRAINT `request_ibfk_2` FOREIGN KEY (`adv_id`) REFERENCES `adventure` (`adv_id`);

--
-- Constraints for table `voucher`
--
ALTER TABLE `voucher`
  ADD CONSTRAINT `voucher_ibfk_2` FOREIGN KEY (`orga_id`) REFERENCES `organizer` (`orga_id`),
  ADD CONSTRAINT `voucher_ibfk_3` FOREIGN KEY (`adv_id`) REFERENCES `adventure` (`adv_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
