-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Hostiteľ: localhost:3306
-- Čas generovania: Št 06.Máj 2021, 14:17
-- Verzia serveru: 8.0.23-0ubuntu0.20.04.1
-- Verzia PHP: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáza: `finalne`
--

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `answer`
--

CREATE TABLE `answer` (
  `id` int NOT NULL,
  `question_id` int NOT NULL,
  `text` varchar(255) NOT NULL,
  `isCorrect` tinyint NOT NULL,
  `user_id` int NOT NULL,
  `points` int NOT NULL,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `question`
--

CREATE TABLE `question` (
  `id` int NOT NULL,
  `test_id` int NOT NULL,
  `type` enum('checkbox','short','connect','draw','math') NOT NULL,
  `total_points` int NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `questionOption`
--

CREATE TABLE `questionOption` (
  `id` int NOT NULL,
  `question_id` int NOT NULL,
  `isCorrect` tinyint NOT NULL,
  `name` varchar(100) NOT NULL,
  `points` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `test`
--

CREATE TABLE `test` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `test_code` varchar(8) NOT NULL,
  `isActive` tinyint NOT NULL,
  `total_time` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `total_points` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `type` enum('teacher','student') NOT NULL,
  `name` varchar(100) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Kľúče pre exportované tabuľky
--

--
-- Indexy pre tabuľku `answer`
--
ALTER TABLE `answer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexy pre tabuľku `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`id`),
  ADD KEY `test_id` (`test_id`);

--
-- Indexy pre tabuľku `questionOption`
--
ALTER TABLE `questionOption`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexy pre tabuľku `test`
--
ALTER TABLE `test`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexy pre tabuľku `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pre exportované tabuľky
--

--
-- AUTO_INCREMENT pre tabuľku `answer`
--
ALTER TABLE `answer`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pre tabuľku `question`
--
ALTER TABLE `question`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pre tabuľku `questionOption`
--
ALTER TABLE `questionOption`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pre tabuľku `test`
--
ALTER TABLE `test`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pre tabuľku `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Obmedzenie pre exportované tabuľky
--

--
-- Obmedzenie pre tabuľku `answer`
--
ALTER TABLE `answer`
  ADD CONSTRAINT `answer_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `question` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `answer_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Obmedzenie pre tabuľku `question`
--
ALTER TABLE `question`
  ADD CONSTRAINT `question_ibfk_1` FOREIGN KEY (`test_id`) REFERENCES `test` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Obmedzenie pre tabuľku `questionOption`
--
ALTER TABLE `questionOption`
  ADD CONSTRAINT `questionOption_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `question` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Obmedzenie pre tabuľku `test`
--
ALTER TABLE `test`
  ADD CONSTRAINT `test_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
