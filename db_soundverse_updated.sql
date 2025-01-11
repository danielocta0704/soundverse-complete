-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jan 09, 2025 at 12:15 AM
-- Server version: 8.0.40
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_soundverse`
--

-- --------------------------------------------------------

--
-- Table structure for table `albums`
--

CREATE TABLE `albums` (
  `album_id` int NOT NULL,
  `album_title` varchar(255) NOT NULL,
  `artist_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `albums`
--

INSERT INTO `albums` (`album_id`, `album_title`, `artist_id`) VALUES
(1, 'Blue Album', 1),
(2, 'Pinkerton', 1),
(3, 'My Way', 2),
(4, 'Songs for Swingin\' Lovers!', 2),
(5, 'Paranoid', 3),
(6, 'Master of Reality', 3),
(7, 'Nevermind', 4),
(8, 'In Utero', 4),
(9, 'Immunity', 5),
(10, 'Diary 001', 5),
(11, 'kata kata', 6);

-- --------------------------------------------------------

--
-- Table structure for table `artists`
--

CREATE TABLE `artists` (
  `artist_id` int NOT NULL,
  `artist_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `artists`
--

INSERT INTO `artists` (`artist_id`, `artist_name`) VALUES
(1, 'Weezer'),
(2, 'Frank Sinatra'),
(3, 'Black Sabbath'),
(4, 'Nirvana'),
(5, 'Clairo'),
(6, 'sugeng sutrisno');

-- --------------------------------------------------------

--
-- Table structure for table `genres`
--

CREATE TABLE `genres` (
  `genre_id` int NOT NULL,
  `genre_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `genres`
--

INSERT INTO `genres` (`genre_id`, `genre_name`) VALUES
(1, 'Grunge'),
(2, 'Rock'),
(3, 'Metal'),
(4, 'Indie'),
(5, 'Jazz'),
(6, 'kata kata');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `rating_id` int NOT NULL,
  `user_id` int NOT NULL,
  `song_id` int NOT NULL,
  `rating` float NOT NULL,
  `username` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`rating_id`, `user_id`, `song_id`, `rating`, `username`) VALUES
(4, 9, 9, 5, 'arno'),
(5, 10, 10, 5, '3'),
(6, 13, 9, 5, 'abdul'),
(7, 10, 1, 1, '3'),
(8, 10, 8, 3, '3');

-- --------------------------------------------------------

--
-- Table structure for table `songs`
--

CREATE TABLE `songs` (
  `song_id` int NOT NULL,
  `song_title` varchar(255) NOT NULL,
  `album_id` int DEFAULT NULL,
  `genre_id` int DEFAULT NULL,
  `spotify_link` varchar(255) DEFAULT NULL,
  `artist_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `songs`
--

INSERT INTO `songs` (`song_id`, `song_title`, `album_id`, `genre_id`, `spotify_link`, `artist_id`) VALUES
(1, 'Buddy Holly', 1, 2, 'https://open.spotify.com/embed/track/3mwvKOyMmG77zZRunnxp9E?utm_source=generator', 1),
(2, 'Say It Ain\'t So', 1, 2, 'https://open.spotify.com/embed/track/6VoIBz0VhCyz7OdEoRYDiA?utm_source=generator', 1),
(3, 'Fly Me to the Moon', 2, 5, 'https://open.spotify.com/embed/track/2dR5WkrpwylTuT3jRWNufa?utm_source=generator', 2),
(4, 'My Way', 2, 5, 'https://open.spotify.com/embed/track/6lTTzSk1hRrxp4VMwXBp2l?utm_source=generator', 2),
(5, 'Iron Man', 3, 3, 'https://open.spotify.com/embed/track/74fURW8XNuLYHOvKmjA2XT?utm_source=generator', 3),
(6, 'Paranoid', 3, 3, 'https://open.spotify.com/embed/track/3hwuHzRicnu6Ji9i1JLzor?utm_source=generator', 3),
(8, 'Come as You Are', 4, 1, 'https://open.spotify.com/embed/track/2RsAajgo0g7bMCHxwH3Sk0?utm_source=generator', 4),
(9, 'Bags', 5, 4, 'https://open.spotify.com/embed/track/6UFivO2zqqPFPoQYsEMuCc?utm_source=generator', 5),
(10, 'Sofia', 5, 4, 'https://open.spotify.com/embed/track/7B3z0ySL9Rr0XvZEAjWZzM?utm_source=generator', 5),
(31, 'gunung bromo gunung kelud', 11, 6, 'https://www.tiktok.com/@retanza7/video/7409234823937641734?is_from_webapp=1&amp;sender_device=pc', 6);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bio` varchar(255) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `bio`, `profile_picture`, `password`, `email`, `role`) VALUES
(9, 'arno', 'aku admin koe opo?', '0', 'arno', 'arno@gmail.com', 'admin'),
(10, '3', '3', 'uploads/7ef7d3e23ef9daf302cec8af1c3914a3.jpg', '3', 'a@gmail.com', 'user'),
(11, 'orang', 'orang', '0', 'orang', '123@GMAIL.COM', 'user'),
(13, 'abdulrozak', 'aku gasuka marlboro', 'uploads/resize (1).webp', '1', 'abdul@gmail.com', 'user'),
(14, 'sugeng tambler', 'gunung bromo gunung kelud?', 'uploads/sugeng.jpeg', 'sugeng', 'gbromogkelud@gmail.com', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `user_songs`
--

CREATE TABLE `user_songs` (
  `user_id` int NOT NULL,
  `song_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `albums`
--
ALTER TABLE `albums`
  ADD PRIMARY KEY (`album_id`),
  ADD KEY `artist_id` (`artist_id`);

--
-- Indexes for table `artists`
--
ALTER TABLE `artists`
  ADD PRIMARY KEY (`artist_id`);

--
-- Indexes for table `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`genre_id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`rating_id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`song_id`),
  ADD KEY `song_id` (`song_id`);

--
-- Indexes for table `songs`
--
ALTER TABLE `songs`
  ADD PRIMARY KEY (`song_id`),
  ADD KEY `album_id` (`album_id`),
  ADD KEY `genre_id` (`genre_id`),
  ADD KEY `fk_artist` (`artist_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_songs`
--
ALTER TABLE `user_songs`
  ADD PRIMARY KEY (`user_id`,`song_id`),
  ADD KEY `song_id` (`song_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `albums`
--
ALTER TABLE `albums`
  MODIFY `album_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `artists`
--
ALTER TABLE `artists`
  MODIFY `artist_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `genres`
--
ALTER TABLE `genres`
  MODIFY `genre_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `rating_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `songs`
--
ALTER TABLE `songs`
  MODIFY `song_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `albums`
--
ALTER TABLE `albums`
  ADD CONSTRAINT `albums_ibfk_1` FOREIGN KEY (`artist_id`) REFERENCES `artists` (`artist_id`) ON DELETE CASCADE;

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`song_id`) REFERENCES `songs` (`song_id`) ON DELETE CASCADE;

--
-- Constraints for table `songs`
--
ALTER TABLE `songs`
  ADD CONSTRAINT `fk_artist` FOREIGN KEY (`artist_id`) REFERENCES `artists` (`artist_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `songs_ibfk_1` FOREIGN KEY (`album_id`) REFERENCES `albums` (`album_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `songs_ibfk_2` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`genre_id`) ON DELETE SET NULL;

--
-- Constraints for table `user_songs`
--
ALTER TABLE `user_songs`
  ADD CONSTRAINT `user_songs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `user_songs_ibfk_2` FOREIGN KEY (`song_id`) REFERENCES `songs` (`song_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
