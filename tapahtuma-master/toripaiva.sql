-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 07.01.2026 klo 23:43
-- Palvelimen versio: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `toripaiva`
--

-- --------------------------------------------------------

--
-- Rakenne taululle `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_pic` varchar(255) DEFAULT NULL,
  `participating` tinyint(1) DEFAULT 0,
  `role` varchar(20) DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vedos taulusta `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `profile_pic`, `participating`, `role`, `created_at`) VALUES
(2, 'niilo', '$2y$10$zf8IFKplqf4fkwgKloM7ouWw4dt9aZCe28o/kWDtVrnijiy2FCtx6', 'profile_6951e95a1444e.png', 1, 'admin', '2025-12-09 10:12:59'),
(3, 'kakka', '$2y$10$ED2fm1jyUQydF.708xoj4.N6xC1hSZzuhieo.r5TXdHww8wy3Iobu', '', 1, 'user', '2025-12-09 10:12:59'),
(4, 'kryptonakki@protonmail.com', '$2y$10$nyKe3GL5hi8luqp6s9BLIujAhfYoicMBjdWhvbDmiEyjquHLR9tSW', NULL, 0, 'user', '2025-12-09 10:14:21'),
(5, 'niilo22', '$2y$10$xiYwptkmAKX3tvPWN1zO5.8pUZkdB/yaiqpwBGILpIOs5tennshSK', 'profile_6951e79962ab7.webp', 0, 'user', '2025-12-29 02:26:57');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
