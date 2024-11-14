-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 14 nov. 2024 à 16:59
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `db_coche`
--

-- --------------------------------------------------------

--
-- Structure de la table `lists`
--

DROP TABLE IF EXISTS `lists`;
CREATE TABLE IF NOT EXISTS `lists` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `lists`
--

INSERT INTO `lists` (`id`, `user_id`, `name`, `created_at`, `date`) VALUES
(8, 1, 'bujhbjbhu', '2024-11-11 03:14:38', '2024-11-11 03:14:38'),
(7, 1, 'liste de course', '2024-11-11 03:14:38', '2024-11-11 03:14:38'),
(6, 1, 'test', '2024-11-11 03:14:38', '2024-11-11 03:14:38'),
(18, 4, 'bonjour', '2024-11-12 20:15:48', '2024-11-12 20:15:48'),
(21, 3, 'test', '2024-11-14 16:27:13', '2024-11-14 16:27:13');

-- --------------------------------------------------------

--
-- Structure de la table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int NOT NULL AUTO_INCREMENT,
  `list_id` int NOT NULL,
  `text` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `completed` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `list_id` (`list_id`)
) ENGINE=MyISAM AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `tasks`
--

INSERT INTO `tasks` (`id`, `list_id`, `text`, `completed`, `created_at`, `date`) VALUES
(1, 1, 'test', 1, '2024-11-11 03:03:49', '2006-03-16 23:00:00'),
(2, 1, 'test3', 1, '2024-11-11 03:04:02', '0000-00-00 00:00:00'),
(3, 1, 'nouveau', 0, '2024-11-11 03:04:40', '2024-11-21 23:00:00'),
(4, 3, 'test', 1, '2024-11-11 03:11:39', '2006-03-16 23:00:00'),
(5, 3, 'test2', 0, '2024-11-11 03:11:39', '2019-04-17 22:00:00'),
(6, 3, 'Test3', 1, '2024-11-11 03:11:39', '2024-10-28 23:00:00'),
(7, 4, 'riz', 0, '2024-11-11 03:11:39', '2024-03-16 23:00:00'),
(8, 4, 'poulet', 1, '2024-11-11 03:11:39', '2006-03-16 23:00:00'),
(9, 5, 'bhubj', 1, '2024-11-11 03:11:39', '0000-00-00 00:00:00'),
(10, 5, 'gtugugjh', 0, '2024-11-11 03:11:39', '0000-00-00 00:00:00'),
(11, 6, 'test', 1, '2024-11-11 03:14:38', '2006-03-16 23:00:00'),
(12, 6, 'test2', 0, '2024-11-11 03:14:38', '2019-04-17 22:00:00'),
(13, 6, 'Test3', 1, '2024-11-11 03:14:38', '2024-10-28 23:00:00'),
(14, 7, 'riz', 0, '2024-11-11 03:14:38', '2024-03-16 23:00:00'),
(15, 7, 'poulet', 1, '2024-11-11 03:14:38', '2006-03-16 23:00:00'),
(16, 8, 'bhubj', 1, '2024-11-11 03:14:38', '0000-00-00 00:00:00'),
(17, 8, 'gtugugjh', 0, '2024-11-11 03:14:38', '0000-00-00 00:00:00'),
(18, 8, 'aie', 1, '2024-11-11 03:14:55', '2005-05-17 22:00:00'),
(19, 9, 'riz', 1, '2024-11-11 03:52:14', '2024-11-17 23:00:00'),
(20, 9, 'test', 0, '2024-11-11 03:52:25', '0000-00-00 00:00:00'),
(21, 9, 'poulet', 1, '2024-11-11 03:52:39', '2006-08-14 22:00:00'),
(22, 9, 'riz', 1, '2024-11-12 16:41:47', '2024-11-17 23:00:00'),
(23, 9, 'test', 0, '2024-11-12 16:41:47', '0000-00-00 00:00:00'),
(24, 9, 'poulet', 1, '2024-11-12 16:41:47', '2006-08-14 22:00:00'),
(25, 10, 'riz', 1, '2024-11-12 16:41:59', '2024-11-17 23:00:00'),
(26, 10, 'test', 0, '2024-11-12 16:41:59', '0000-00-00 00:00:00'),
(27, 10, 'poulet', 1, '2024-11-12 16:41:59', '2006-08-14 22:00:00'),
(28, 10, 'test', 1, '2024-11-12 16:50:15', '2006-03-16 23:00:00'),
(29, 10, 'test2', 0, '2024-11-12 16:50:15', '2019-04-17 22:00:00'),
(30, 10, 'Test3', 1, '2024-11-12 16:50:15', '2024-10-28 23:00:00'),
(31, 11, 'pas de riz', 1, '2024-11-12 16:50:15', '2024-05-19 13:30:17'),
(32, 11, 'lait', 0, '2024-11-12 16:50:15', '2024-04-16 22:00:00'),
(33, 11, 'poulet', 0, '2024-11-12 16:50:15', '1998-08-19 22:00:00'),
(34, 12, 'test', 1, '2024-11-12 17:04:41', '2006-03-16 23:00:00'),
(35, 12, 'bonjour', 1, '2024-11-12 17:04:56', '2024-11-21 23:00:00'),
(39, 10, 'test', 1, '2024-11-12 18:22:18', '2006-03-16 23:00:00'),
(37, 14, 'Pâtes 🍝', 1, '2024-11-12 18:20:20', '2025-11-12 23:00:00'),
(38, 14, 'dsfdsqf', 0, '2024-11-12 18:20:29', '2006-10-14 22:00:00'),
(40, 10, 'test2', 0, '2024-11-12 18:22:18', '2019-04-17 22:00:00'),
(41, 10, 'Test3', 1, '2024-11-12 18:22:18', '2024-10-28 23:00:00'),
(42, 14, 'riz', 0, '2024-11-12 18:22:18', '2024-03-16 23:00:00'),
(43, 14, 'poulet', 1, '2024-11-12 18:22:18', '2006-03-16 23:00:00'),
(44, 15, 'bhubj', 1, '2024-11-12 18:22:18', '0000-00-00 00:00:00'),
(45, 15, 'gtugugjh', 0, '2024-11-12 18:22:18', '0000-00-00 00:00:00'),
(46, 13, 'aa', 1, '2024-11-12 18:27:21', '0000-00-00 00:00:00'),
(47, 18, 'test', 0, '2024-11-12 20:18:31', '2024-01-11 23:00:00'),
(51, 19, 'dhhfh', 0, '2024-11-14 14:44:28', '2006-05-15 22:00:00'),
(50, 19, 'efgw<dfds', 1, '2024-11-14 14:44:18', '2006-05-14 22:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `gender` enum('male','female') COLLATE utf8mb4_general_ci NOT NULL,
  `country` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `custom_background` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `gender`, `country`, `created_at`, `custom_background`) VALUES
(1, 'test', 'test@test', '$2y$10$MhZc0pXfW4E9Jnx9uS9rLu2MhXhMhp/TsQIcGOPxvN9qQoJ9uzFzq', 'male', 'france', '2024-11-11 03:10:50', 'background_user_1.jpg'),
(2, 'test', 'test@test', '$2y$10$MoYdDg2PffcmZk/v/4FqEOssjBPCd3cu/FCrACyVIcB5iGH11PUVC', 'male', 'france', '2024-11-11 03:18:52', NULL),
(3, 'a', 'a@a', '$2y$10$WLnA27SxJmwDjZfclgOJ1u273EQCNODWbQzSJ.QI/o3PgsMZkO2vG', 'male', 'france', '2024-11-11 03:31:24', 'background_user_3.jpg'),
(4, 'b', 'b@b', '$2y$10$Wvz/aIXtSuA1CckFctMFzOAC/IxeR/HZ2LNsrcXYk/I/L0xR8gHRq', 'female', 'belgium', '2024-11-12 20:14:28', 'background_user_4.jpg'),
(5, 'l', 'l@l', '$2y$10$GAJaUCYSkPvQcrNX0F97fOCS/X8UA6jCCSlPVGB6snUdjEKOCjU12', 'female', 'other', '2024-11-14 14:42:13', NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
