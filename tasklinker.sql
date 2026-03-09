-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 09 mars 2026 à 11:20
-- Version du serveur : 8.4.7
-- Version de PHP : 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `tasklinker`
--

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20260302110437', '2026-03-02 11:04:48', 9),
('DoctrineMigrations\\Version20260303082915', '2026-03-03 08:29:21', 41),
('DoctrineMigrations\\Version20260303092632', '2026-03-03 09:26:43', 28),
('DoctrineMigrations\\Version20260303104722', '2026-03-03 10:47:29', 51),
('DoctrineMigrations\\Version20260304164644', '2026-03-04 16:46:52', 64),
('DoctrineMigrations\\Version20260304173749', '2026-03-04 17:37:58', 59),
('DoctrineMigrations\\Version20260305085307', '2026-03-05 08:53:18', 65),
('DoctrineMigrations\\Version20260305085605', '2026-03-05 08:56:14', 80),
('DoctrineMigrations\\Version20260305090005', '2026-03-05 09:00:14', 59),
('DoctrineMigrations\\Version20260305141902', '2026-03-05 14:21:10', 81),
('DoctrineMigrations\\Version20260306162303', '2026-03-06 16:23:55', 90);

-- --------------------------------------------------------

--
-- Structure de la table `employe`
--

DROP TABLE IF EXISTS `employe`;
CREATE TABLE IF NOT EXISTS `employe` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `date_entree` date NOT NULL,
  `statut` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=167 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `employe`
--

INSERT INTO `employe` (`id`, `nom`, `prenom`, `email`, `date_entree`, `statut`) VALUES
(157, 'Renard', 'Alphonse', 'jdelannoy@example.net', '2021-01-25', 'CDD'),
(158, 'Joubert', 'Zacharie', 'catherine.benard@example.net', '2019-03-04', 'Freelance'),
(159, 'Marques', 'Lucy', 'martel.colette@example.com', '2026-02-24', 'Freelance'),
(160, 'Hubert', 'Honoré', 'clemence01@example.net', '2024-01-31', 'CDD'),
(161, 'Leclerc', 'Odette', 'glebrun@example.net', '2021-01-19', 'CDI'),
(162, 'Briand', 'Martine', 'bernadette14@example.org', '2021-08-17', 'Stagiaire'),
(163, 'Lambert', 'Emmanuel', 'vincent63@example.net', '2018-08-01', 'CDI'),
(164, 'Petit', 'Christiane', 'elise31@example.org', '2022-06-15', 'Freelance'),
(165, 'Evrard', 'Lorraine', 'anouk.dupre@example.org', '2025-02-07', 'Stagiaire'),
(166, 'Blanc', 'Vincent', 'antoine93@example.net', '2021-06-15', 'Freelance');

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750` (`queue_name`,`available_at`,`delivered_at`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `projet`
--

DROP TABLE IF EXISTS `projet`;
CREATE TABLE IF NOT EXISTS `projet` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `archive` tinyint NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=75 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `projet`
--

INSERT INTO `projet` (`id`, `titre`, `archive`) VALUES
(70, 'Repellendus laboriosam ipsa.', 0),
(71, 'Architecto accusantium.', 0),
(72, 'Odit sequi dolor explicabo.', 0),
(73, 'Quo alias odio.', 0),
(74, 'Quis quo tenetur.', 0);

-- --------------------------------------------------------

--
-- Structure de la table `projet_employe`
--

DROP TABLE IF EXISTS `projet_employe`;
CREATE TABLE IF NOT EXISTS `projet_employe` (
  `projet_id` int NOT NULL,
  `employe_id` int NOT NULL,
  PRIMARY KEY (`projet_id`,`employe_id`),
  KEY `IDX_7A2E8EC8C18272` (`projet_id`),
  KEY `IDX_7A2E8EC81B65292` (`employe_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `projet_employe`
--

INSERT INTO `projet_employe` (`projet_id`, `employe_id`) VALUES
(70, 158),
(70, 159),
(70, 162),
(71, 157),
(71, 160),
(71, 166),
(72, 157),
(72, 160),
(73, 165),
(73, 166),
(74, 157),
(74, 163),
(74, 164),
(74, 165);

-- --------------------------------------------------------

--
-- Structure de la table `tache`
--

DROP TABLE IF EXISTS `tache`;
CREATE TABLE IF NOT EXISTS `tache` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `description` longtext,
  `date` date DEFAULT NULL,
  `statut` varchar(255) NOT NULL,
  `projet_id` int DEFAULT NULL,
  `employe_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_93872075C18272` (`projet_id`),
  KEY `IDX_938720751B65292` (`employe_id`)
) ENGINE=MyISAM AUTO_INCREMENT=114 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `tache`
--

INSERT INTO `tache` (`id`, `titre`, `description`, `date`, `statut`, `projet_id`, `employe_id`) VALUES
(99, 'molestias itaque laudantium', 'Quia vel earum et. Delectus est quaerat et. Enim ut at placeat. Quia sit laborum possimus officia. Doloribus minus pariatur error.', '2026-03-23', 'EN_COURS', 74, NULL),
(100, 'consequuntur molestiae inventore', 'Atque mollitia voluptatem vitae nihil. Magnam aspernatur expedita est et ut sed. Ut possimus voluptatem laboriosam ullam et deserunt aperiam.', NULL, 'EN_COURS', 71, 157),
(101, 'vel est in', NULL, NULL, 'TERMINE', 73, 165),
(102, 'dicta ducimus corporis', 'Rerum est et ut omnis quisquam dolor laboriosam. Voluptate voluptatem totam numquam nihil accusantium architecto. Cum consequatur sit ducimus facere velit aliquam aperiam. Sed maiores et sed necessitatibus ipsa aut et.', '2026-04-04', 'EN_COURS', 74, 165),
(103, 'ad incidunt esse', NULL, '2026-04-05', 'EN_COURS', 72, 160),
(104, 'distinctio molestiae rerum', 'Veniam est aut inventore odit omnis iusto. Consectetur nihil rerum sit. Illum occaecati quae impedit ullam consequatur rem. Sed porro itaque consectetur est ipsa corporis.', '2026-03-23', 'EN_COURS', 74, NULL),
(105, 'saepe earum perspiciatis', 'Tempore ipsa facere qui nemo nihil est quod. Quos quo molestias quis voluptate consectetur. Quo laboriosam aut eligendi.', '2026-03-22', 'TERMINE', 72, 160),
(106, 'porro culpa expedita', NULL, NULL, 'TERMINE', 72, NULL),
(107, 'aut ullam ducimus', 'Illo blanditiis voluptas vel et consectetur autem. Est praesentium fugit reiciendis et. Praesentium quia iusto ea eius quo minus. Maxime consequatur dolor necessitatibus corporis consequatur dolor.', '2026-03-23', 'EN_COURS', 72, 160),
(108, 'aut qui sed', NULL, NULL, 'TERMINE', 74, NULL),
(109, 'vel quod assumenda', 'Dolores dolorum et aliquid ratione commodi eius consequuntur ea. Voluptate quia similique dolore molestiae animi nihil. Id quos officia magnam qui cupiditate et fugiat. Omnis harum ut ratione tenetur et rem.', NULL, 'TERMINE', 70, 158),
(110, 'ratione omnis aliquam', 'Quis sunt corrupti iste aspernatur corrupti. Harum quas sed autem placeat id aperiam. Ea autem dolores molestiae id omnis alias ut.', NULL, 'TERMINE', 72, NULL),
(111, 'perspiciatis aut quae', NULL, '2026-03-13', 'TERMINE', 73, 166),
(112, 'et quaerat hic', NULL, NULL, 'A_FAIRE', 72, 160),
(113, 'nesciunt modi id', 'Esse aperiam quas in quisquam qui nostrum. Voluptas tempore laudantium eaque. Nemo laborum qui ipsum cupiditate qui non. Aut qui et et qui nam eaque.', NULL, 'EN_COURS', 70, 159);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
