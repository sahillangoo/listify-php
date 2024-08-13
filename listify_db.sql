-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 30, 2023 at 06:10 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `listify_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `listings`
--

CREATE TABLE `listings` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `businessName` varchar(50) NOT NULL DEFAULT 'No business name provided',
  `description` text NOT NULL DEFAULT 'No description provided',
  `category` varchar(20) NOT NULL DEFAULT 'other',
  `featured` tinyint(1) DEFAULT 0,
  `active` tinyint(1) DEFAULT 1,
  `latitude` double(10,8) NOT NULL DEFAULT 0.00000000,
  `longitude` double(10,8) NOT NULL DEFAULT 0.00000000,
  `address` varchar(50) NOT NULL DEFAULT 'No address provided',
  `city` varchar(20) NOT NULL DEFAULT 'No city provided',
  `pincode` int(6) UNSIGNED NOT NULL DEFAULT 0,
  `phoneNumber` bigint(10) UNSIGNED NOT NULL DEFAULT 0,
  `email` varchar(255) NOT NULL DEFAULT 'No email provided',
  `whatsapp` bigint(10) UNSIGNED DEFAULT NULL,
  `facebookId` varchar(50) DEFAULT NULL,
  `instagramId` varchar(50) DEFAULT NULL,
  `website` varchar(50) DEFAULT NULL,
  `displayImage` varchar(50) NOT NULL DEFAULT 'default.jpg',
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `listings`
--

INSERT INTO `listings` (`id`, `user_id`, `businessName`, `description`, `category`, `featured`, `active`, `latitude`, `longitude`, `address`, `city`, `pincode`, `phoneNumber`, `email`, `whatsapp`, `facebookId`, `instagramId`, `website`, `displayImage`, `createdAt`, `updatedAt`) VALUES
(1, 1, 'Winterfell Cafe', 'Best Place to Enjoy your time with friends and family in Srinagar Kashmir with a beautiful view of Dal Lake and Zabarwan Hills. We serve the best food in town. We have a wide range of food items to choose from.  ', 'restaurant', 1, 1, 34.08208576, 74.83346948, 'Boulevard Road Dal lake', 'srinagar', 190001, 9876543210, 'demo@demo.com', 9876543210, 'winterfell', 'winterfell', 'https://winterfell.com', 'Winterfell.jpg', '2023-08-15 08:01:37', '2023-09-11 07:13:10'),
(2, 1, 'J&K Bank', 'J&K Bank functions as a universal bank in Jammu & Kashmir and as a specialised bank in the rest of the country. It is also the only private sector bank designated as RBI’s agent for banking business', 'bank', 1, 1, 34.07560834, 74.82866427, 'Residency Road', 'srinagar', 190001, 9876543210, 'jkbank@demo.com', 9876543210, 'jkbank', 'jkbank', 'https://jkbank.com', 'default.jpg', '2023-08-15 08:01:37', '2023-09-09 11:05:27'),
(3, 1, 'Kashmir University', 'The University of Jammu and Kashmir was founded in the year 1948. In the year 1969 it was bifurcated into two full-fledged Universities: University of Kashmir at Srinagar and University of Jammu at Jammu. The University of Kashmir is situated at Hazratbal in Srinagar.', 'education', 1, 1, 0.00000000, 0.00000000, 'Hazratbal', 'srinagar', 190006, 1942272096, 'info@uok.edu.in', 0, '', '', 'https://www.kashmiruniversity.net', 'Kashmir University.jpg', '2023-09-09 16:36:44', '2023-09-11 07:13:15'),
(4, 1, 'Khyber Hospital', 'A dream started to take shape in the year 2002, when Late Dr. Ghulam Rasool Tramboo & Mr. Mohammad Maqbool Tramboo (Promoters of Khyber Group of Industries) envisaged to venture into Healthcare and stared Khyber Medical Institute to carry forward the Mission of delivering quality healthcare to the people of the Kashmir valley.', 'hospital', 0, 1, 0.00000000, 0.00000000, 'Khayam Chowk', 'srinagar', 190001, 0, 'info@khyberhealthcare.com', 0, 'KhyberMedicalInstitute', '', 'https://khyberhealthcare.com', 'Khyber Hospital.jpg', '2023-09-09 17:57:41', '2023-09-09 17:57:41'),
(9, 1, 'Tyndale Biscoe', 'The founders of the Tyndale Biscoe and Mallinson Schools, Srinagar, Kashmir, set their vision on all round strengthening of the social and moral fiber of the people of Kashmir, through Education and Healthcare among children, drawing from the Biblical ethos of love and compassion for one&#039;s environment.', 'education', 0, 1, 0.00000000, 0.00000000, 'Sheikh Bagh Lalchowk', 'srinagar', 190001, 1942452533, 'education@tbmes.org', NULL, NULL, NULL, 'https://www.tbmes.org/', 'Tyndale Biscoe.jpg', '2023-09-15 13:44:11', '2023-09-15 13:44:11'),
(10, 1, 'illaaj Healthcare', 'illaaj Healthcare is a well-known brand in Healthcare. illaaj has just launched Kashmir&#039;s own online medicine delivery app, which allows you to buy medicines &amp; healthcare products online. Just tap the App, upload your prescription, and your order will be delivered to your home within 24-48 hours. You can also book diagnostic tests online &amp; request the home sample collection.\r\nWe know how crucial medicine is to treating health conditions and we are extremely proud to be at the forefront of the online medicine industry in Kashmir. At illaaj Healthcare, we ensure that you get high-quality life-saving medicines are delivered to you on time.', 'pharmacy', 0, 1, 0.00000000, 0.00000000, 'Babademb Crossing, Khanyaar', 'srinagar', 190003, 9622123434, 'care@illaaj.com', 9622123434, 'illaajhealthcare', 'illaajhealthcare', 'https://illaaj.com/', 'illaaj Healthcare.jpg', '2023-09-15 13:50:53', '2023-09-15 13:50:53'),
(11, 1, 'HDFC Bank ATM', 'An ATM is a self service banking terminal. It allows users, like you, to perform various financial transactions without visiting a physical bank branch. To use an ATM, insert your bank card, commonly a debit or credit card, into the card slot. Then, follow on-screen prompts to select the desired transaction. For instance, if you want to withdraw cash, enter the amount, and the machine dispenses the money. ATMs are equipped with security features like PIN authentication and card reader technology to ensure the safety of your transactions.\r\nFurthermore, ATMs are widely accessible, found in banks, grocery stores, airports, and other locations, making them a vital part of modern banking infrastructure. They streamline financial operations, reduce the need for in-person visits to banks, and offer convenience to individuals like you, especially during exams or busy schedules.', 'atm', 0, 1, 0.00000000, 0.00000000, 'Badam Manzil Sheraz Chowk', 'srinagar', 190003, 9426792001, 'support@hdfcbank.com', 9426792001, NULL, NULL, 'https://www.hdfcbank.com', 'HDFC Bank ATM.jpg', '2023-09-15 14:02:38', '2023-09-15 14:02:38'),
(12, 1, 'Dominos Pizza', 'Having that crispy and melty pizza in the comfort of your own home with the ones you love, we say.\r\nThere is something for everyone here. The vegetarians, non-vegetarians, the sides lovers and also the ones who love to have something sweet by the time they reach the last bite of the last slice of pizza slice.', 'restaurant', 0, 1, 0.00000000, 0.00000000, 'Durrani Enclave, Sanat Nagar', 'srinagar', 190001, 1800208123, 'support@dominos.co.in', NULL, NULL, NULL, 'https://dominos.co.in', 'Dominos Pizza.jpg', '2023-09-15 14:12:54', '2023-09-15 14:16:52'),
(13, 1, 'KFC', 'It all started with Colonel Harland Sanders creating a finger lickin good recipe more than 75 years ago. A list of secret herbs and spices scratched out on the back of his kitchen door, that continues to be followed across 145 countries, with more than 800,000 team members breading and freshly preparing bucket after bucket of our signature Hot &amp; Crispy chicken.', 'restaurant', 0, 1, 0.00000000, 0.00000000, 'City Walk Mall MA Road', 'srinagar', 190001, 8042754444, 'support@kfc.co.in', NULL, NULL, NULL, 'https://restaurants.kfc.co.in/', 'KFC.jpg', '2023-09-15 14:25:08', '2023-09-15 14:25:08');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) UNSIGNED NOT NULL,
  `rating` float(2,1) NOT NULL DEFAULT 0.0,
  `review` text NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_id` int(11) UNSIGNED NOT NULL,
  `listing_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `rating`, `review`, `createdAt`, `user_id`, `listing_id`) VALUES
(1, 4.0, 'This is a good place to hangout with friends and family. The food is good and the service is also good. The view is amazing. I would recommend this place to everyone.', '2023-09-02 06:24:42', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `session_token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `session_token`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 4, 'a7bcf1a09fcf21b4b668aa52e254daf0', '2023-10-09 16:50:32', '2023-09-09 11:20:32', '2023-09-09 11:20:32'),
(2, 1, 'b05ad123fbd0789b1086accb3348713e', '2023-10-09 17:00:46', '2023-09-09 11:30:46', '2023-09-09 11:30:46'),
(3, 9, 'a2b94446101a3aa12e2c601e7885747b', '2023-11-03 19:28:46', '2023-10-04 13:58:46', '2023-10-04 13:58:46'),
(4, 10, '9092dfc89defc53ebd97d184df0ae430', '2023-11-03 19:39:04', '2023-10-04 14:09:04', '2023-10-04 14:09:04');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(30) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` bigint(12) UNSIGNED NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_image` varchar(255) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `user_since` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `phone`, `password`, `profile_image`, `status`, `role`, `user_since`) VALUES
(1, 'sahillangoo', 'sahilahmed3066@gmail.com', 7006588022, '$argon2id$v=19$m=2048,t=4,p=1$Q1JaN09YYVZPMTRCWE1Paw$Ol/j1Z4p2Pyy7NxOPxjo8Ht9+0ZmIozh7MIsQE7TMFs', 'https://api.dicebear.com/6.x/micah/svg?seed=sahill', 'active', 'admin', '2023-08-07 10:46:11'),
(4, 'salmaferooz', 'salmaferooz1@gmail.com', 9687968767, '$argon2id$v=19$m=2048,t=4,p=1$dC44eklRdVcuVWFkMm9jUA$nO4wLkEtTI8MApQkWJvPN0QZRsefOIvgwWh5+hEMrD8', 'https://api.dicebear.com/6.x/micah/svg?seed=salmaf', 'active', 'user', '2023-09-02 07:46:41'),
(6, 'smufaiz1111', 'smufaiz1111@gmail.com\n', 7780890074, '$argon2id$v=19$m=2048,t=4,p=1$SkFLNGk3MGxlcDMweGt1Vw$hG3YOIehbXL+TaBxmezrKNLWKNJ6KjT/++J2HqKAVbo', 'https://api.dicebear.com/6.x/micah/svg?seed=smufai', 'active', 'user', '2023-09-06 08:03:00'),
(7, 'Syedkaiser', 'syedkaiser04@gmail.com', 7006649526, '$argon2id$v=19$m=2048,t=4,p=1$YlVHbFY4ay5ZMG1rOUJYOA$nObL7PPSwtweDPi3jGr1cOvc+CUzlS3foynGf3xPbrQ', 'https://api.dicebear.com/6.x/micah/svg?seed=Syedkaiser&flip=true&background=%230000ff&radius=50&margin=10&baseColor=f9c9b6', 'active', 'user', '2023-10-04 19:19:04'),
(8, 'rocko.aa76', 'rocko.aa76@gmail.com', 7006952456, '$argon2id$v=19$m=2048,t=4,p=1$c3F2Vms1bFBoZm43SUZscQ$26SXIfnyHKkM1bhc1JbCXcfTKNTZCViihDBS3/FIAF0', 'https://api.dicebear.com/6.x/micah/svg?seed=rocko.aa76&flip=true&background=%230000ff&radius=50&margin=10&baseColor=f9c9b6', 'active', 'user', '2023-10-04 19:19:32'),
(9, 'deadlock', 'test@test.com', 9876543897, '$argon2id$v=19$m=2048,t=4,p=1$eUJPYzhPYUUzSDREZGNMag$ttb2lDgp1pwilG9fKa8NZAqAUpb00sv3hdctDR53YNE', 'https://api.dicebear.com/6.x/micah/svg?seed=deadlock&flip=true&background=%230000ff&radius=50&margin=10&baseColor=f9c9b6', 'active', 'user', '2023-10-04 19:21:05'),
(10, 'bellota', 'belota@gmai.con', 9876543899, '$argon2id$v=19$m=2048,t=4,p=1$TS8wd2h4YnRTdzJYR2dNdA$4g+AkKgB0+AZLntd0SQ6zOxm0Uz1tBYmRfXYTDwJmns', 'https://api.dicebear.com/6.x/micah/svg?seed=bellota&flip=true&background=%230000ff&radius=50&margin=10&baseColor=f9c9b6', 'active', 'user', '2023-10-04 19:32:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `listings`
--
ALTER TABLE `listings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `active_idx` (`active`),
  ADD KEY `id_idx` (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `listing_id` (`listing_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `listings`
--
ALTER TABLE `listings`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `listings`
--
ALTER TABLE `listings`
  ADD CONSTRAINT `listings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`listing_id`) REFERENCES `listings` (`id`);

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
