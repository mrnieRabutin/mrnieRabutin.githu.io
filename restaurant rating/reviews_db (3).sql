-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 12, 2024 at 09:43 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `reviews_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `venue_id` int(11) NOT NULL,
  `booking_date` date DEFAULT current_timestamp(),
  `event_type` varchar(255) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_email` varchar(255) DEFAULT NULL,
  `booking_details` text DEFAULT NULL,
  `image` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `venue_id`, `booking_date`, `event_type`, `customer_name`, `customer_email`, `booking_details`, `image`) VALUES
(1, 0, '2024-03-12', 'Casual', 'Gagard', 'gagard@gmail.com', 'ghdhgdsuf', '');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `username` varchar(115) NOT NULL,
  `activity` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `username`, `activity`, `timestamp`) VALUES
(32, '5', 'User logged in: melca@gmail.com', '2024-03-11 12:16:36'),
(33, '8', 'User logged in: gagard@gmail.com', '2024-03-11 13:30:16'),
(34, '15', 'User logged in: gagard@gmail.com', '2024-03-11 14:55:10'),
(35, '16', 'User logged in: melca@gmail.com', '2024-03-11 15:00:38'),
(36, '15', 'User logged in: gagard@gmail.com', '2024-03-11 15:01:32'),
(37, '17', 'User logged in: student@gmail.com', '2024-03-11 15:02:27'),
(38, '16', 'User logged in: melca@gmail.com', '2024-03-11 15:09:38'),
(39, '16', 'User logged in: melca@gmail.com', '2024-03-11 15:46:06'),
(40, '15', 'User logged in: gagard@gmail.com', '2024-03-11 15:46:27'),
(41, '15', 'User logged in: gagard@gmail.com', '2024-03-11 23:02:39'),
(42, '16', 'User logged in: melca@gmail.com', '2024-03-12 04:03:02'),
(43, '16', 'User logged in: melca@gmail.com', '2024-03-12 08:20:05');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` varchar(20) NOT NULL,
  `title` varchar(50) NOT NULL,
  `image` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `image`) VALUES
('h12FAnxY6JoXb51iDpda', '01 example post title', 'post_1.webp'),
('sRKX0vSREJbBzO07wM1H', '02 example post title', 'post_2.webp'),
('G6zDaxTTS0fV5UT4BQ46', '03 example post title', 'post_3.webp'),
('6zQRsklaYIO38cLIgYZN', '04 example post title', 'post_4.webp'),
('mMj2FWPRVWZPsfOsjSUL', '05 example post title', 'post_5.webp'),
('hK2tgabAaK1c1FAak6UW', '06 example post title', 'post_6.webp');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(20) NOT NULL,
  `booking_id` varchar(20) NOT NULL,
  `username` varchar(255) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `rating` varchar(1) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `booking_id`, `username`, `comment`, `rating`, `date`) VALUES
(8, '1', 'gagard@gmail.com', 'huhuhuhuuhu', '3', '2024-03-12'),
(9, '1', 'gagard@gmail.com', 'jhbhjhj', '5', '2024-03-12');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `name`, `last_name`, `image`) VALUES
(15, 'gagard@gmail.com', '$2y$10$2Sx1Q3jP0L8lrRIJoW7oCOrWqaoElTQUvq/qQvYbjWNmZMjkr5d/u', 'Gardenia Concillo', '', '4ddnv4HR6frDBwFZEic7.jpg'),
(16, 'melca@gmail.com', '$2y$10$a7dDJvTigDxBbh2gKkoLUOyflOf4zLicTPkdKIvPhTRDhDlcWV/ge', 'melca', '', 'plkrDNbDmvsylBjEad0n.jpg'),
(17, 'student@gmail.com', '$2y$10$tQqSzEn1uikwAjl/lu16eOsAzDwWZ/4xGFFMdRpm7kJ7N9nxBitMe', 'Student', '', 'RQz3drKqCBlB515lmxPa.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `user_requests`
--

CREATE TABLE `user_requests` (
  `id` int(11) NOT NULL,
  `event_type` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `admin_response` enum('approved','pending','rejected') DEFAULT 'pending',
  `image` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_requests`
--

INSERT INTO `user_requests` (`id`, `event_type`, `location`, `admin_response`, `image`) VALUES
(1, 'gdfgdfg', 'hh b nb', 'approved', ''),
(2, 'hjbhj', 'gghvgh', 'approved', 'gcgh');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_requests`
--
ALTER TABLE `user_requests`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `user_requests`
--
ALTER TABLE `user_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
