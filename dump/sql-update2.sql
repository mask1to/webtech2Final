-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Hostiteľ: db
-- Čas generovania: Po 10.Máj 2021, 13:22
-- Verzia serveru: 8.0.24
-- Verzia PHP: 7.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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
-- Štruktúra tabuľky pre tabuľku `OptionsPair`
--

CREATE TABLE `OptionsPair` (
  `id` int NOT NULL,
  `question_id` int NOT NULL,
  `questionOption_id` int NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Sťahujem dáta pre tabuľku `OptionsPair`
--

--
-- Kľúče pre exportované tabuľky
--

--
-- Indexy pre tabuľku `OptionsPair`
--
ALTER TABLE `OptionsPair`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`),
  ADD KEY `OptionsPair_ibfk_2` (`questionOption_id`);

--
-- AUTO_INCREMENT pre exportované tabuľky
--

--
-- AUTO_INCREMENT pre tabuľku `OptionsPair`
--
ALTER TABLE `OptionsPair`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- Obmedzenie pre exportované tabuľky
--

--
-- Obmedzenie pre tabuľku `OptionsPair`
--
ALTER TABLE `OptionsPair`
  ADD CONSTRAINT `OptionsPair_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `question` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `OptionsPair_ibfk_2` FOREIGN KEY (`questionOption_id`) REFERENCES `questionOption` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
