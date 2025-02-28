-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 28 fév. 2025 à 09:35
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ecommerce_books`
--

-- --------------------------------------------------------

--
-- Structure de la table `books`
--

DROP TABLE IF EXISTS `books`;
CREATE TABLE IF NOT EXISTS `books` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `auteur` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `prix` decimal(10,2) NOT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `image` varchar(255) NOT NULL,
  `date_publication` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `books`
--

INSERT INTO `books` (`id`, `titre`, `auteur`, `description`, `prix`, `stock`, `image`, `date_publication`) VALUES
(1, 'One Piece', 'Eiichirō Oda', 'De nombreux pirates sont partis à la recherche de ce trésor mais tous sont morts avant même de l\'atteindre. Monkey D. Luffy rêve de retrouver ce trésor légendaire et de devenir le nouveau \"Roi des Pirates\". Après avoir mangé un fruit du démon, il possède un pouvoir incroyable lui permettant peut être de réaliser un jour son rêve.', 7.20, 10, '1740598171_OnePiece1.jpg', '2025-02-26'),
(2, 'Harry Potter à l\'école des sorciers', 'J. K. Rowling', 'Le jour de ses onze ans, Harry Potter, un orphelin élevé par un oncle et une tante qui le détestent, voit son existence bouleversée. Un géant vient le chercher pour l\'emmener à Poudlard, la célèbre école de sorcellerie où une place l\'attend depuis toujours.', 20.00, 6, '1740646479_HarryPotter1.jpg', '2025-02-27'),
(3, 'Le Seigneur des Anneaux', 'J. R. R. Tolkien', 'Dans les vertes prairies du Comté, les Hobbits, ou Demi-hommes, vivaient en paix... Jusqu\'au jour fatal où l\'un d\'entre eux, au cours de ses voyages, entra en possession de l\'Anneau Unique aux immenses pouvoirs. Pour le reconquérir, Sauron, le seigneur Sombre, va déchaîner toutes les forces du Mal.', 22.00, 4, '1740650773_LeSeigneurDesAnneaux.jpg', '2025-02-27'),
(4, 'Twilight', 'Stephenie Meyer', 'Bella, dix-sept ans, décide de quitter l\'Arizona ensoleillé où elle vivait avec sa mère, pour s\'installer chez son père. Elle croit renoncer à tout ce qu\'elle aime, certaine qu\'elle ne s\'habituera jamais ni à la pluie ni à Forks où l\'anonymat est interdit.', 7.00, 3, '1740718022_Twilight.jpg', '2025-02-28'),
(5, 'Réfléchissez et devenez riche', 'Napoleon Hill', 'Le but de ce livre est d\'aider tous ceux qui souhaitent apprendre à changer d\'état d\'esprit, pour passer de la conscience de l\'échec à la conscience de la réussite.', 7.50, 6, '1740718146_Réfléchissezetdevenezriche.jpg', '2025-02-28'),
(6, 'Harry Potter et la Chambre des secrets', 'J. K. Rowling', 'Avant même de début de sa deuxième année scolaire à Poudlard, un drôle de petit Elfe de maison empêche Harry Potter de retourner dans cet endroit dangereux. Au cours de l\'année scolaire, une série d\'attaques est perpétrée sur des élèves, prétendument par un monstre d\'une légendaire Chambre des secrets.', 16.00, 2, '1740718251_HarryPotter2.jpg', '2025-02-28'),
(7, 'Harry Potter et le Prisonnier d\'Azkaban', 'J. K. Rowling', 'Sirius Black, un dangereux sorcier criminel, s\'échappe de la sombre prison d\'Azkaban avec un seul et unique but : se venger d\'Harry Potter, entré avec ses amis Ron et Hermione en troisième année à l\'école de sorcellerie de Poudlard, où ils auront aussi à faire avec les terrifiants Détraqueurs.', 23.50, 4, '1740718335_HarryPotter3.jpg', '2025-02-28'),
(8, 'L\'Alchimiste', 'Paulo Coelho', 'Santiago, un jeune berger andalou, part à la recherche d\'un trésor enfoui au pied des Pyramides. Lorsqu\'il rencontre l\'Alchimiste dans le désert, celui-ci lui apprend à écouter son coeur, à lire les signes du destin et, par-dessus tout, à aller au bout de ses rêves.', 7.90, 8, '1740718440_Lalchimiste.jpg', '2025-02-28'),
(10, 'Harry Potter et la Coupe de feu', 'J. K. Rowling', 'Harry Potter a quatorze ans et entre en quatrième année au collège Poudlard. Une grande nouvelle attend Harry, Ron et Hermione à leur arrivé : la tenue d\'un tournoi de magie exceptionnel entre les plus célèbres écoles de sorcellerie. Déjà les délégations étrangère font leur entrée.', 20.19, 4, '1740718949_HarryPotter4.jpg', '2025-02-28');

-- --------------------------------------------------------

--
-- Structure de la table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
CREATE TABLE IF NOT EXISTS `invoices` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_order` int NOT NULL,
  `date_facture` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `montant_total` decimal(10,2) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `ville` varchar(100) NOT NULL,
  `code_postal` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_order` (`id_order`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `invoices`
--

INSERT INTO `invoices` (`id`, `id_order`, `date_facture`, `montant_total`, `adresse`, `ville`, `code_postal`) VALUES
(17, 17, '2025-02-28 09:22:48', 39.50, 'adresseQays', 'villeqays', '95000'),
(16, 16, '2025-02-28 09:21:59', 49.20, 'adresseQays', 'villeqays', '95000'),
(19, 19, '2025-02-28 09:25:08', 27.00, 'AdresseClaire', 'VilleClaire', '95000'),
(18, 18, '2025-02-28 09:24:05', 29.90, 'Adressejean', 'Villejean', '95000');

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_user` int NOT NULL,
  `date_commande` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `total_prix` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `orders`
--

INSERT INTO `orders` (`id`, `id_user`, `date_commande`, `total_prix`) VALUES
(18, 5, '2025-02-28 09:24:05', 29.90),
(17, 3, '2025-02-28 09:22:48', 39.50),
(16, 3, '2025-02-28 09:21:59', 49.20),
(19, 6, '2025-02-28 09:25:08', 27.00);

-- --------------------------------------------------------

--
-- Structure de la table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_order` int NOT NULL,
  `id_book` int NOT NULL,
  `quantite` int NOT NULL,
  `prix_unitaire` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_order` (`id_order`),
  KEY `id_book` (`id_book`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `order_items`
--

INSERT INTO `order_items` (`id`, `id_order`, `id_book`, `quantite`, `prix_unitaire`) VALUES
(21, 16, 3, 1, 22.00),
(20, 16, 2, 1, 20.00),
(19, 16, 1, 1, 7.20),
(27, 19, 4, 1, 7.00),
(22, 17, 6, 1, 16.00),
(24, 18, 3, 1, 22.00),
(23, 17, 7, 1, 23.50),
(26, 19, 2, 1, 20.00),
(25, 18, 8, 1, 7.90);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('utilisateur','admin') DEFAULT 'utilisateur',
  `date_inscription` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `remember_token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `nom`, `prenom`, `email`, `mot_de_passe`, `role`, `date_inscription`, `remember_token`) VALUES
(3, 'Arab', 'Qays', 'qays@qays.com', '$2y$10$gAG/u4uS3uCz0HCCQ9JT8.GkqwH59fvLA3iRhkf4Td4mK88fWFm7u', 'utilisateur', '2025-02-27 08:38:15', NULL),
(6, 'Martin', 'Claire', 'claire.martin@example.com', '$2y$10$.GVyXEgQkw/9700QjEX1VOExAY9urHT41qli1Wj.H4lPsQk6wpEoC', 'utilisateur', '2025-02-28 09:19:09', NULL),
(2, 'admin', 'admin', 'admin@admin.com', '$2y$10$Ui133tAySe/dvEUMKYNI/ussN2WybME32aazRhpkOMpX7NhJbWklC', 'admin', '2025-02-26 18:05:50', NULL),
(5, 'Dupont', 'Jean', 'jean.dupont@example.com', '$2y$10$7wk77ZtUh/PHuJrt/C15w.NuqJuGrFrguwOEiQ1DI0CtJqG5w4zgO', 'utilisateur', '2025-02-28 09:19:09', NULL),
(7, 'Durand', 'Luc', 'luc.durand@example.com', '$2y$10$3MxXGKjumqxGmW.7VDaaleNvKLX8ds7aGBxQVd26Pn/9l2hrsaW8i', 'utilisateur', '2025-02-28 09:19:09', NULL),
(8, 'Bernard', 'Sophie', 'sophie.bernard@example.com', '$2y$10$aY0uIs.V/cU0un4eA4j6XugjuVpUBrP/HboRVGjPAhEbJefnIrZxS', 'utilisateur', '2025-02-28 09:19:09', NULL),
(9, 'Petit', 'Paul', 'paul.petit@example.com', '$2y$10$1ok/i3FOWndv8.yp8N1Nju0UbGE9FddsGOJcHdmcEfLu3wr0mpM2.', 'utilisateur', '2025-02-28 09:19:09', NULL),
(10, 'Robert', 'Marie', 'marie.robert@example.com', '$2y$10$Rg5vuiUHz.vQrYunIH8Q5.SJCGh9dilNt5Uj7mUdHmidaltMPRgqm', 'utilisateur', '2025-02-28 09:19:09', NULL),
(11, 'Richard', 'Antoine', 'antoine.richard@example.com', '$2y$10$5V1T4UBDpF9xUZc6j5IiceJASBI0DpYoT8gFR4SZVeuVXfQAsak2S', 'utilisateur', '2025-02-28 09:19:09', NULL),
(12, 'Morel', 'Julie', 'julie.morel@example.com', '$2y$10$GNuQI7Yzg5cSosGd5gToMuroakxrsr6qC/PWa6ksakegMgSJUfZyC', 'utilisateur', '2025-02-28 09:19:09', NULL),
(13, 'Simon', 'Lucas', 'lucas.simon@example.com', '$2y$10$Rlos/1DkjY1uH8qIwQ8IW.f75SYzoNM6qloq.ppfmi6kPLGc4r/BW', 'utilisateur', '2025-02-28 09:19:09', NULL),
(14, 'Lefevre', 'Camille', 'camille.lefevre@example.com', '$2y$10$1tGCZqTLI3kUBuQ9m6LXD.Qrzn2t7AGdlh.zsZ./PUHNicMYqmOKe', 'utilisateur', '2025-02-28 09:19:09', NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
