SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS `sunsonsolar` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `sunsonsolar`;

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `role` enum('admin','employee','customer') NOT NULL DEFAULT 'customer',
  `firstname` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `birthdate` date DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `department` varchar(50) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` (`user_id`, `role`, `firstname`, `middle_name`, `last_name`, `birthdate`, `gender`, `email`, `phone_number`, `address`, `department`, `username`, `password`, `created_at`) VALUES
(1, 'admin', 'Katherine', NULL, 'Sinagaraw', NULL, NULL, NULL, NULL, NULL, NULL, 'KittyKat16', 'Password@SunShine16', '2026-09-23 03:16:54'),
(2, 'admin', 'Sol', NULL, 'Solis', NULL, NULL, NULL, NULL, NULL, NULL, 'sol_admin', 'admin123', '2026-09-23 03:16:54'),
(3, 'admin', 'Rian', 'S.', 'Villanueva', '2006-06-07', 'male', 'krrsvillanueva2007@gmail.com', '096983436050', 'Pasig City', NULL, 'Rixtre', 'Rixtre@2006', '2026-09-23 03:16:54'),
(4, 'admin', 'Wency', NULL, 'Dela Cruz', NULL, NULL, NULL, NULL, NULL, NULL, 'wency', 'Wency@2026', '2026-09-23 03:16:54'),
(5, 'employee', 'Marielle', NULL, 'Reyes', NULL, NULL, NULL, NULL, NULL, 'customer_support', 'marielle', 'Marielle@2026', '2026-09-23 03:16:54');

ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

COMMIT;