-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 02. Mrz 2021 um 10:11
-- Server-Version: 10.4.14-MariaDB
-- PHP-Version: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `todo_db`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `categories`
--

CREATE TABLE `categories` (
  `id_category` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `categories`
--

INSERT INTO `categories` (`id_category`, `name`) VALUES
(1, 'Hünde'),
(3, 'Farben'),
(4, 'Hausaufgaben'),
(6, 'Papier2'),
(7, 'Tiere'),
(8, 'Länder'),
(9, 'Wichtig'),
(10, 'Familie');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `todos`
--

CREATE TABLE `todos` (
  `id_todo` int(11) NOT NULL,
  `archive` tinyint(1) NOT NULL,
  `priority` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `date_create` date NOT NULL DEFAULT current_timestamp(),
  `date_expiration` date NOT NULL,
  `date_done` date DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `fk_id_category` int(11) NOT NULL,
  `fk_id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `todos`
--

INSERT INTO `todos` (`id_todo`, `archive`, `priority`, `title`, `date_create`, `date_expiration`, `date_done`, `content`, `fk_id_category`, `fk_id_user`) VALUES
(5, 0, 2, 'Archive', '2021-03-01', '2022-01-01', '0000-00-00', 'archive', 1, 4),
(6, 0, 1, 'Hünde', '2021-02-28', '2222-12-12', '0000-00-00', '1212121212', 1, 4),
(7, 0, 4, 'GEht exp-date?', '2021-02-28', '2021-02-10', '2021-02-28', 'fosduvpweirj', 4, 4),
(9, 0, 2, 'Douglas Botgohst', '2021-03-02', '2021-03-21', '0000-00-00', 'AD', 8, 4),
(10, 1, 4, 'vrrvvr', '2021-03-01', '2021-04-01', '2021-03-01', 'AxcxcDadsfv', 3, 4),
(11, 0, 2, 'asd', '2021-03-01', '2021-03-01', '0000-00-00', 'yxc', 6, 4),
(12, 0, 2, 'Archive', '2021-03-01', '2022-01-01', '0000-00-00', 'archive', 1, 4),
(13, 0, 1, 'Hünde', '2021-02-28', '2222-12-12', '0000-00-00', '1212121212', 1, 4),
(14, 0, 4, 'GEht exp-date?', '2021-02-28', '2021-02-10', '2021-02-28', 'fosduvpweirj', 4, 4),
(15, 0, 2, 'Douglas Botgohst', '2021-03-01', '2021-03-21', '2021-03-01', 'AD', 8, 4),
(16, 1, 4, 'vrrvvr', '2021-03-01', '2021-04-01', '2021-03-01', 'AxcxcDadsfv', 3, 4),
(17, 0, 2, 'Archive', '2021-03-01', '2022-01-01', '0000-00-00', 'archive', 1, 4),
(18, 0, 1, 'Hünde', '2021-02-28', '2222-12-12', '0000-00-00', '1212121212', 1, 4),
(19, 0, 4, 'GEht exp-date?', '2021-02-28', '2021-02-10', '2021-02-28', 'fosduvpweirj', 4, 4),
(20, 0, 2, 'Douglas Botgohst', '2021-03-01', '2021-03-21', '2021-03-01', 'AD', 8, 4),
(21, 1, 4, 'vrrvvr', '2021-03-01', '2021-04-01', '2021-03-01', 'AxcxcDadsfv', 3, 4),
(22, 0, 2, 'asd', '2021-03-01', '2021-03-01', '0000-00-00', 'yxc', 6, 4),
(23, 0, 2, 'Archive', '2021-03-01', '2022-01-01', '0000-00-00', 'archive', 1, 4),
(24, 0, 1, 'Hünde', '2021-02-28', '2222-12-12', '0000-00-00', '1212121212', 1, 4),
(25, 0, 4, 'GEht exp-date?', '2021-02-28', '2021-02-10', '2021-02-28', 'fosduvpweirj', 4, 4),
(26, 0, 2, 'Douglas Botgohst', '2021-03-01', '2021-03-21', '2021-03-01', 'AD', 8, 4),
(27, 1, 4, 'vrrvvr', '2021-03-01', '2021-04-01', '2021-03-01', 'AxcxcDadsfv', 3, 4),
(28, 0, 2, 'Archive', '2021-03-01', '2022-01-01', '0000-00-00', 'archive', 1, 4),
(29, 0, 1, 'Hünde', '2021-02-28', '2222-12-12', '0000-00-00', '1212121212', 1, 4),
(30, 0, 4, 'GEht exp-date?', '2021-02-28', '2021-02-10', '2021-02-28', 'fosduvpweirj', 4, 4),
(31, 0, 2, 'Douglas Botgohst', '2021-03-01', '2021-03-21', '2021-03-01', 'AD', 8, 4),
(32, 1, 4, 'vrrvvr', '2021-03-01', '2021-04-01', '2021-03-01', 'AxcxcDadsfv', 3, 4),
(33, 0, 2, 'asd', '2021-03-01', '2021-03-01', '0000-00-00', 'yxc', 6, 4),
(34, 0, 2, 'Archive', '2021-03-01', '2022-01-01', '0000-00-00', 'archive', 1, 4),
(35, 0, 1, 'Hünde', '2021-02-28', '2222-12-12', '0000-00-00', '1212121212', 1, 4),
(36, 0, 4, 'GEht exp-date?', '2021-02-28', '2021-02-10', '2021-02-28', 'fosduvpweirj', 4, 4),
(37, 0, 2, 'Douglas Botgohst', '2021-03-01', '2021-03-21', '2021-03-01', 'AD', 8, 4),
(38, 1, 4, 'vrrvvr', '2021-03-01', '2021-04-01', '2021-03-01', 'AxcxcDadsfv', 3, 4),
(39, 0, 2, 'Archive', '2021-03-01', '2022-01-01', '0000-00-00', 'archive', 1, 4);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `admin` tinyint(1) NOT NULL,
  `username` varchar(255) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`id_user`, `admin`, `username`, `fname`, `lname`, `password`) VALUES
(3, 1, 'test', 'Manfred', 'William', '$2y$10$qZGxffYQgXwIWAsJkLVq..FFmCNS.pLIRjmr4aE6Kp5w8BO4HJhGe'),
(4, 0, 'erik', 'Erik', 'Steinacher', '$2y$10$I27BfDbzvVKJRBQ9AVMBhOB3VVF6q6H1TxNIH3RiKh6HmpJsHS9QC');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users_categories`
--

CREATE TABLE `users_categories` (
  `id_user_category` int(11) NOT NULL,
  `fk_id_users` int(11) NOT NULL,
  `fk_id_category` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `users_categories`
--

INSERT INTO `users_categories` (`id_user_category`, `fk_id_users`, `fk_id_category`) VALUES
(88, 3, 1),
(89, 3, 3),
(90, 3, 6),
(91, 3, 7),
(92, 3, 8),
(93, 3, 9),
(94, 3, 10),
(103, 4, 1),
(104, 4, 3),
(105, 4, 4),
(106, 4, 6),
(107, 4, 7),
(108, 4, 8),
(109, 4, 9),
(110, 4, 10);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id_category`);

--
-- Indizes für die Tabelle `todos`
--
ALTER TABLE `todos`
  ADD PRIMARY KEY (`id_todo`),
  ADD KEY `fk_id_category` (`fk_id_category`),
  ADD KEY `fk_id_users` (`fk_id_user`);

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- Indizes für die Tabelle `users_categories`
--
ALTER TABLE `users_categories`
  ADD PRIMARY KEY (`id_user_category`),
  ADD KEY `fk_id_category` (`fk_id_category`),
  ADD KEY `fk_id_users` (`fk_id_users`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `categories`
--
ALTER TABLE `categories`
  MODIFY `id_category` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT für Tabelle `todos`
--
ALTER TABLE `todos`
  MODIFY `id_todo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT für Tabelle `users_categories`
--
ALTER TABLE `users_categories`
  MODIFY `id_user_category` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `todos`
--
ALTER TABLE `todos`
  ADD CONSTRAINT `todos_ibfk_1` FOREIGN KEY (`fk_id_category`) REFERENCES `categories` (`id_category`),
  ADD CONSTRAINT `todos_ibfk_2` FOREIGN KEY (`fk_id_user`) REFERENCES `users` (`id_user`);

--
-- Constraints der Tabelle `users_categories`
--
ALTER TABLE `users_categories`
  ADD CONSTRAINT `users_categories_ibfk_1` FOREIGN KEY (`fk_id_category`) REFERENCES `categories` (`id_category`),
  ADD CONSTRAINT `users_categories_ibfk_2` FOREIGN KEY (`fk_id_users`) REFERENCES `users` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
