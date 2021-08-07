-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 14, 2021 at 05:39 PM
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
(2021002, 'Alexis', 'salvador.alexis01@gmail.com', '847d76de437d05420cc3dfc9d04f536b'),
(2021003, 'Byrone', 'byronekeith@gmail.com', 'e83f4628fb6d6e64bef9bb9f89a426ac'),
(2021004, 'Kirk', 'saquekiramy05@gmail.com', 'a6c07d878bf9564d5eeaf03a6fe9490b');

-- --------------------------------------------------------

--
-- Table structure for table `adventure`
--

CREATE TABLE `adventure` (
  `adv_id` int(11) NOT NULL,
  `adv_type` varchar(10) NOT NULL,
  `adv_location` varchar(50) NOT NULL,
  `adv_totalcostprice` decimal(7,2) NOT NULL,
  `adv_date` date NOT NULL,
  `adv_details` varchar(100) NOT NULL,
  `adv_postedDate` date NOT NULL,
  `adv_maxguests` int(3) DEFAULT NULL,
  `adv_currentGuest` int(3) DEFAULT NULL,
  `adv_itineraryImg` varchar(25) NOT NULL,
  `adv_status` varchar(25) NOT NULL,
  `orga_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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

-- --------------------------------------------------------

--
-- Table structure for table `favorite`
--

CREATE TABLE `favorite` (
  `joiner_id` int(11) NOT NULL,
  `adv_id` int(11) NOT NULL,
  `fav_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `joiner`
--

CREATE TABLE `joiner` (
  `joiner_id` int(11) NOT NULL,
  `joiner_fname` varchar(25) NOT NULL,
  `joiner_lname` varchar(25) NOT NULL,
  `joiner_mi` char(1) NOT NULL,
  `joiner_address` varchar(50) NOT NULL,
  `joiner_phone` varchar(11) NOT NULL,
  `joiner_email` varchar(50) NOT NULL,
  `joiner_password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `legal_document`
--

CREATE TABLE `legal_document` (
  `orga_id` int(11) NOT NULL,
  `docu_type` varchar(25) NOT NULL,
  `docu_description` varchar(100) NOT NULL,
  `docu_image` varchar(100) NOT NULL,
  `docu_dateadded` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `legal_document`
--

INSERT INTO `legal_document` (`orga_id`, `docu_type`, `docu_description`, `docu_image`, `docu_dateadded`) VALUES
(1, 'Docu Type2', 'No description being added!', '60ef029ee77b07.48886988.jpg', '2021-07-14');

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `notif_id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `joiner_id` int(11) NOT NULL,
  `orga_id` int(11) NOT NULL,
  `notif_message` varchar(50) NOT NULL,
  `notif_date` date NOT NULL,
  `notif_status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `organizer`
--

CREATE TABLE `organizer` (
  `orga_id` int(11) NOT NULL,
  `orga_company` varchar(50) NOT NULL,
  `orga_fname` varchar(25) NOT NULL,
  `orga_lname` varchar(25) NOT NULL,
  `orga_mi` char(1) NOT NULL,
  `orga_address` varchar(50) NOT NULL,
  `orga_phone` varchar(11) NOT NULL,
  `orga_email` varchar(50) NOT NULL,
  `orga_password` varchar(100) NOT NULL,
  `orga_verified` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `organizer`
--

INSERT INTO `organizer` (`orga_id`, `orga_company`, `orga_fname`, `orga_lname`, `orga_mi`, `orga_address`, `orga_phone`, `orga_email`, `orga_password`, `orga_verified`) VALUES
(1, 'ABC Company', 'Nar', 'Ancit', 'B', 'Sitio Granada Quiot Pardo', '09345734757', 'narancit@gmail.com', 'e07ac1db65fbdd768477e5c79e3642d0', 1),
(3, '', 'Merry Joy', 'Blanco', 'G', '', '', 'joyblanco@gmail.com', 'e07ac1db65fbdd768477e5c79e3642d0', 0),
(4, '', 'John', 'Doe', 'A', '', '', 'johndoe@gmail.com', 'e07ac1db65fbdd768477e5c79e3642d0', 0);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL,
  `payment_method` varchar(10) NOT NULL,
  `payment_datetime` datetime NOT NULL,
  `book_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `rating`
--

CREATE TABLE `rating` (
  `rating_id` int(11) NOT NULL,
  `rating_stars` int(11) NOT NULL,
  `rating_message` int(11) NOT NULL,
  `joiner_id` int(11) NOT NULL,
  `adv_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `receipt`
--

CREATE TABLE `receipt` (
  `payment_id` int(11) NOT NULL,
  `rcpt_code` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `receipt_itinerary`
--

CREATE TABLE `receipt_itinerary` (
  `payment_id` int(11) NOT NULL,
  `rcptiti_img` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `refund`
--

CREATE TABLE `refund` (
  `req_id` int(11) NOT NULL,
  `ref_amount` decimal(7,2) NOT NULL,
  `ref_dateprocess` date NOT NULL,
  `ref_dateapproved` date NOT NULL,
  `ref_status` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `reports_id` int(11) NOT NULL,
  `reports_status` varchar(25) NOT NULL,
  `book_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `req_id` int(11) NOT NULL,
  `request_type` varchar(10) NOT NULL,
  `request_date` date NOT NULL,
  `request_status` varchar(25) NOT NULL,
  `request_reason` varchar(100) NOT NULL,
  `book_id` int(11) NOT NULL,
  `adv_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `voucher`
--

CREATE TABLE `voucher` (
  `vouch_code` varchar(10) NOT NULL,
  `vouch_name` varchar(25) NOT NULL,
  `vouch_startdate` date NOT NULL,
  `vouch_enddate` date NOT NULL,
  `vouch_minspent` decimal(7,2) NOT NULL,
  `orga_id` int(11) NOT NULL,
  `adv_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `voucher`
--

INSERT INTO `voucher` (`vouch_code`, `vouch_name`, `vouch_startdate`, `vouch_enddate`, `vouch_minspent`, `orga_id`, `adv_id`) VALUES
('1222', 'SampleName', '2021-07-15', '2021-07-17', '1000.00', 1, NULL);

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
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`notif_id`),
  ADD KEY `payment_id` (`payment_id`),
  ADD KEY `joiner_id` (`joiner_id`),
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
  ADD KEY `adv_id` (`adv_id`);

--
-- Indexes for table `receipt`
--
ALTER TABLE `receipt`
  ADD KEY `payment_id` (`payment_id`);

--
-- Indexes for table `receipt_itinerary`
--
ALTER TABLE `receipt_itinerary`
  ADD KEY `payment_id` (`payment_id`);

--
-- Indexes for table `refund`
--
ALTER TABLE `refund`
  ADD KEY `req_id` (`req_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`reports_id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `admin_id` (`admin_id`);

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
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2021005;

--
-- AUTO_INCREMENT for table `adventure`
--
ALTER TABLE `adventure`
  MODIFY `adv_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `joiner`
--
ALTER TABLE `joiner`
  MODIFY `joiner_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `notif_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `organizer`
--
ALTER TABLE `organizer`
  MODIFY `orga_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rating`
--
ALTER TABLE `rating`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `reports_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `req_id` int(11) NOT NULL AUTO_INCREMENT;

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
-- Constraints for table `legal_document`
--
ALTER TABLE `legal_document`
  ADD CONSTRAINT `legal_document_ibfk_1` FOREIGN KEY (`orga_id`) REFERENCES `organizer` (`orga_id`);

--
-- Constraints for table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `notification_ibfk_1` FOREIGN KEY (`payment_id`) REFERENCES `payment` (`payment_id`),
  ADD CONSTRAINT `notification_ibfk_2` FOREIGN KEY (`joiner_id`) REFERENCES `joiner` (`joiner_id`),
  ADD CONSTRAINT `notification_ibfk_3` FOREIGN KEY (`orga_id`) REFERENCES `organizer` (`orga_id`);

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
  ADD CONSTRAINT `rating_ibfk_2` FOREIGN KEY (`adv_id`) REFERENCES `adventure` (`adv_id`);

--
-- Constraints for table `receipt`
--
ALTER TABLE `receipt`
  ADD CONSTRAINT `receipt_ibfk_1` FOREIGN KEY (`payment_id`) REFERENCES `payment` (`payment_id`);

--
-- Constraints for table `receipt_itinerary`
--
ALTER TABLE `receipt_itinerary`
  ADD CONSTRAINT `receipt_itinerary_ibfk_1` FOREIGN KEY (`payment_id`) REFERENCES `payment` (`payment_id`);

--
-- Constraints for table `refund`
--
ALTER TABLE `refund`
  ADD CONSTRAINT `refund_ibfk_1` FOREIGN KEY (`req_id`) REFERENCES `request` (`req_id`);

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `booking` (`book_id`),
  ADD CONSTRAINT `reports_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`admin_id`);

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
  ADD CONSTRAINT `voucher_ibfk_2` FOREIGN KEY (`orga_id`) REFERENCES `organizer` (`orga_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
