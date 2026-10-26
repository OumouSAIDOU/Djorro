-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  lun. 26 oct. 2026 à 12:44
-- Version du serveur :  5.7.24
-- Version de PHP :  7.2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `djorro_restaurant`
--

-- --------------------------------------------------------

--
-- Structure de la table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mot_de_passe` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `admins`
--

INSERT INTO `admins` (`id`, `email`, `mot_de_passe`) VALUES
(1, 'admin@djorro.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `nom`, `slug`) VALUES
(1, 'Spécialités Braisées', 'braises'),
(2, 'Sauces Traditionnelles', 'sauces'),
(3, 'Accompagnements', 'accompagnements'),
(4, 'Boissons & Desserts', 'boissons');

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

DROP TABLE IF EXISTS `commandes`;
CREATE TABLE IF NOT EXISTS `commandes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numero_commande` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom_client` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `heure_retrait` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total` decimal(6,2) NOT NULL,
  `details_panier` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `statut` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'En attente',
  `cree_le` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `commandes`
--

INSERT INTO `commandes` (`id`, `numero_commande`, `nom_client`, `telephone`, `heure_retrait`, `total`, `details_panier`, `statut`, `cree_le`) VALUES
(1, 'CMD-2081', 'oumuo', '0616684998', '19:10', '9.50', '1x Jus de Bouye (Pain de Singe) (4.50€) | 1x Foutou Banane (5.00€)', 'En attente', '2026-10-23 12:10:29');

-- --------------------------------------------------------

--
-- Structure de la table `plats`
--

DROP TABLE IF EXISTS `plats`;
CREATE TABLE IF NOT EXISTS `plats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categorie_id` int(11) NOT NULL,
  `nom` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'default.jpg',
  `prix` decimal(6,2) NOT NULL,
  `disponible` tinyint(1) DEFAULT '1',
  `populaire` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `categorie_id` (`categorie_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `plats`
--

INSERT INTO `plats` (`id`, `categorie_id`, `nom`, `description`, `image`, `prix`, `disponible`, `populaire`) VALUES
(1, 1, 'Choukouya de Mouton', 'Viande de mouton assaisonnée et grillée au feu de bois, servie avec piment et oignons frais.', 'ChoukouyadeMouton.jpg', '16.50', 1, 1),
(2, 1, 'Poulet Braisé (Demi)', 'Demi-poulet mariné aux épices africaines, grillé à la perfection.', 'PouletBraisé(Demi).jpg', '14.00', 1, 1),
(3, 1, 'Poisson Capitaine Braisé', 'Poisson entier braisé, mariné aux condiments maison, servi avec condiments et piment.', 'PoissonCapitaineBraisé.jpg', '19.50', 1, 0),
(4, 2, 'Sauce Gombo (Kopè)', 'Sauce gombo mijotée avec viande de bœuf, peau de bœuf (Kanda) et poisson fumé.', 'SauceGombo.jpg', '15.00', 1, 1),
(5, 2, 'Sauce Arachide (Tigadèguè)', 'Sauce crémeuse à la pâte d\'arachide, accompagnée de poulet ou bœuf au choix.', 'SauceArachide.jpg', '14.50', 1, 0),
(6, 2, 'Sauce Graine', 'Sauce traditionnelle à la pulpe de noix de palme mijotée avec des viandes sélectionnées.', 'SauceGraine.jpg', '15.50', 1, 0),
(7, 3, 'Attiéké', 'Semoule de manioc cuite à la vapeur, légère et parfumée.', 'Attiéké.jpg', '4.00', 1, 0),
(8, 3, 'Alloco', 'Bananes plantains mûres frites, dorées et fondantes.', 'Alloco.jpg', '5.00', 1, 1),
(9, 3, 'Riz Gras / Riz Djolof', 'Riz cuit dans un bouillon parfumé aux légumes et épices.', 'RizGras.jpg', '4.50', 1, 0),
(10, 3, 'Foutou Banane', 'Pâte traditionnelle à base de banane plantain et manioc.', 'foutoubanane.jpg', '5.00', 1, 0),
(11, 4, 'Bissap Maison', 'Jus de fleurs d\'hibiscus infusé à la menthe et à la vanille.', 'bissap.jpg', '4.00', 1, 1),
(12, 4, 'Jus de Gingembre (Gnamankoudji)', 'Jus de gingembre frais relevé d\'un zeste de citron.', 'ginger.jpg', '4.00', 1, 0),
(13, 4, 'Jus de Bouye (Pain de Singe)', 'Boisson onctueuse au fruit du baobab.', 'bouye.jpg', '4.50', 1, 0),
(14, 1, 'Choukouya de Mouton', 'Viande de mouton assaisonnée et grillée au feu de bois, servie avec piment et oignons frais.', 'ChoukouyadeMouton.jpg', '16.50', 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

DROP TABLE IF EXISTS `reservations`;
CREATE TABLE IF NOT EXISTS `reservations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `couverts` int(11) NOT NULL,
  `date_resa` date NOT NULL,
  `heure_resa` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remarque` text COLLATE utf8mb4_unicode_ci,
  `cree_le` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `statut` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'en_attente',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `reservations`
--

INSERT INTO `reservations` (`id`, `nom`, `telephone`, `email`, `couverts`, `date_resa`, `heure_resa`, `remarque`, `cree_le`, `statut`) VALUES
(1, 'Amar', '0616684998', 'amar@gmail.com', 4, '2026-11-04', '21:30', 'Table calme et sans porc. Merci !!', '2026-10-23 10:20:19', 'en_attente'),
(2, 'Rayan', '0616684998', 'Rayan@gmail.com', 5, '2026-11-07', '19:30', 'Rien A Signaler.', '2026-10-23 10:21:15', 'confirmee');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `plats`
--
ALTER TABLE `plats`
  ADD CONSTRAINT `plats_ibfk_1` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
