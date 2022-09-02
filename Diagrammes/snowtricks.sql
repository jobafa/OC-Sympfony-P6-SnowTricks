-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 02 sep. 2022 à 14:10
-- Version du serveur : 5.7.36
-- Version de PHP : 7.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `snowtricks`
--

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `category`
--

INSERT INTO `category` (`id`, `name`, `description`) VALUES
(1, 'Les rotations', 'On désigne par le mot « rotation » uniquement des rotations horizontales ; les rotations verticales sont des flips. Le principe est d\'effectuer une rotation horizontale pendant le saut, puis d\'attérir en position switch ou normal'),
(2, 'Les flips', 'Un flip est une rotation verticale. On distingue les front flips, rotations en avant, et les back flips, rotations en arrière.\r\n\r\nIl est possible de faire plusieurs flips à la suite, et d\'ajouter un grab à la rotation.'),
(3, 'Les slides', 'Un slide consiste à glisser sur une barre de slide. Le slide se fait soit avec la planche dans l\'axe de la barre, soit perpendiculaire, soit plus ou moins désaxé.'),
(4, 'Les grabs', 'Un grab consiste à attraper la planche avec la main pendant le saut. Le verbe anglais to grab signifie « attraper. »\r\n\r\nIl existe plusieurs types de grabs selon la position de la saisie et la main choisie pour l\'effectuer, avec des difficultés variables'),
(9, 'Old school', 'Le terme old school désigne un style de freestyle caractérisée par en ensemble de figure et une manière de réaliser des figures passée de mode, qui fait penser au freestyle des années 1980 - début 1990 (par opposition à new school) '),
(10, 'Les one foot tricks', 'Figures réalisée avec un pied décroché de la fixation, afin de tendre la jambe correspondante pour mettre en évidence le fait que le pied n\'est pas fixé. '),
(11, 'Les rotations désaxées', 'Une rotation désaxée est une rotation initialement horizontale mais lancée avec un mouvement des épaules particulier qui désaxe la rotation.');

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

DROP TABLE IF EXISTS `comment`;
CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trick_id` int(11) NOT NULL,
  `author` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `users_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9474526CB281BE2E` (`trick_id`),
  KEY `IDX_9474526C67B3B43D` (`users_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `comment`
--

INSERT INTO `comment` (`id`, `trick_id`, `author`, `content`, `created_at`, `users_id`) VALUES
(5, 11, 'visiteur', 'mon premier commentaire, ça a l\'air facile !', '2022-07-08 00:41:32', 8),
(6, 11, 'test inscription', 'très belle photo\r\nque dire de la figure', '2022-07-08 15:21:23', 18),
(7, 4, 'tototo', 'le saut, puis d\'attérir en position switch ou normal.', '2022-07-08 15:39:10', 20),
(8, 11, 'visiteur', 'en effet la figure est magnifique !\r\npas facile', '2022-07-08 16:40:14', 8),
(9, 9, 'test inscription', 'commentaire du 15 07', '2022-07-15 10:43:48', 18),
(10, 9, 'test inscription', 'commentaire du 15 07', '2022-07-15 10:46:51', 18),
(11, 7, 'visiteur', 'un commentaire de plus pour test', '2022-07-15 13:43:33', 8),
(12, 10, 'visiteur', 'magnifique le 720', '2022-07-15 14:29:51', 8),
(13, 11, 'tototo', 'allez un quatrième', '2022-07-15 15:31:06', 20),
(14, 11, 'tototo', 'et puis cette rotation est facle', '2022-07-15 15:32:25', 20),
(15, 11, 'test inscription', 'tu rigole j\'espére', '2022-07-15 15:33:45', 18),
(16, 11, 'test inscription', 'bon autant pour moi', '2022-07-15 15:34:08', 18),
(17, 11, 'titi', 'Une rotation peut être frontside ou backside', '2022-07-22 17:34:04', 10),
(19, 11, 'test inscription', 'et u commentaire de plus !!!', '2022-08-23 14:26:31', 18),
(20, 11, 'test inscription2', 'je répond au précédant', '2022-08-23 14:27:55', 24),
(21, 11, 'test inscription2', 'un dernier comment', '2022-08-23 14:32:03', 24),
(22, 11, 'test inscription', 'le dernier c\'est pour moi', '2022-08-23 14:33:05', 18),
(24, 10, 'test inscription2', 'quelle classe dans le geste', '2022-08-28 12:36:43', 24);

-- --------------------------------------------------------

--
-- Structure de la table `image`
--

DROP TABLE IF EXISTS `image`;
CREATE TABLE IF NOT EXISTS `image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `imagecaption` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trick_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C53D045FB281BE2E` (`trick_id`)
) ENGINE=InnoDB AUTO_INCREMENT=119 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `image`
--

INSERT INTO `image` (`id`, `name`, `imagecaption`, `trick_id`) VALUES
(1, '5faeee3a05d4ae0265ec5bb6f72b56cb.jpg', NULL, 3),
(2, '419e3d7098f527483231192908c66c19.jpg', NULL, 3),
(3, '2c5943cadbe0855abbf4f46ef0e4d990.jpg', NULL, 4),
(4, '45e9b4f2a7a52f7d688d5528b5a76802.jpg', NULL, 4),
(5, '9e51ce4804195d11dc19ae55dfed6625.jpg', NULL, 5),
(6, 'fa44c2cf4d5334a81d2ec6fe8af14a29.jpg', NULL, 5),
(7, 'f0811d49ab2f286c3e2ae37cc39b1325.jpg', NULL, 6),
(8, 'bb1efa491c8f78bf4427164a05d4100f.jpg', NULL, 6),
(9, 'e3898ce30e66e6ca95c1d168e700c919.jpg', NULL, 7),
(10, 'a5e07f94147d45ba70d9f0a06bdb855f.jpg', NULL, 7),
(11, '1f34ead0c3f745a24e0274225bc75302.jpg', NULL, 8),
(12, 'e73d2f883d862d808fc7a41dca572507.jpg', NULL, 8),
(13, 'be67173dd62ba7d4cb59cc0a97da896e.jpg', NULL, 9),
(14, '7d0b0893ae098a32ac921265f8380700.jpg', NULL, 9),
(15, 'e4be5b7b14feecb1fed740cf1d64603a.jpg', NULL, 10),
(16, '04984d9826a3465a75dfa19e033029b8.jpg', NULL, 10),
(17, 'd26fa619f349776338a9359b73a378d2.jpg', NULL, 11),
(18, '02fd6ea458bb922de4ff7f84158cee0d.jpg', NULL, 11),
(20, '543efb9c64dd6d8623ff8b583351d328.jpg', NULL, 8),
(21, '1cc8fbcc424dc8a85e310a5f89bb6a1c.jpg', NULL, 11),
(22, 'c8432c09d5e81fb635bc05c89b98ae4e.jpg', NULL, 12),
(23, 'f2a68028dba4a7df87437e1919e7ccc9.jpg', NULL, 12);

-- --------------------------------------------------------

--
-- Structure de la table `trick`
--

DROP TABLE IF EXISTS `trick`;
CREATE TABLE IF NOT EXISTS `trick` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `defaultimage` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D8F0A91E12469DE2` (`category_id`),
  KEY `IDX_D8F0A91EA76ED395` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `trick`
--

INSERT INTO `trick` (`id`, `user_id`, `category_id`, `title`, `content`, `defaultimage`, `created_at`, `updated_at`) VALUES
(3, 20, 2, 'backflip', 'Un flip est une rotation verticale. On distingue les front flips, rotations en avant, et les back flips, rotations en arrière.\r\n\r\nIl est possible de faire plusieurs flips à la suite, et d\'ajouter un grab à la rotation.\r\n\r\nLes flips agrémentés d\'une vrille existent aussi (Mac Twist, Hakon Flip...), mais de manière beaucoup plus rare, et se confondent souvent avec certaines rotations horizontales désaxées.\r\n\r\nNéanmoins, en dépit de la difficulté technique relative d\'une telle figure, le danger de retomber sur la tête ou la nuque est réel et conduit certaines stations de ski à interdire de telles figures dans ses snowparks.', '43f8355f83c057eed4ba78e356868dd0.jpg', '2022-07-06 14:47:54', '2022-07-06 14:47:54'),
(4, 8, 1, '360 tour complet', 'On désigne par le mot « rotation » uniquement des rotations horizontales ; les rotations verticales sont des flips. Le principe est d\'effectuer une rotation horizontale pendant le saut, puis d\'attérir en position switch ou normal. La nomenclature se base sur le nombre de degrés de rotation effectués  :\r\n\r\n    360, trois six pour un tour complet ;', 'c23eae91ad3a962dd015fa8b0fb89aed.jpg', '2022-07-06 22:50:18', '2022-07-10 23:58:31'),
(5, 18, 4, 'Method air', 'Cette figure – qui consiste à attraper sa planche d\'une main et le tourner perpendiculairement au sol – est un classique \"old school\". Il n\'empêche qu\'il est indémodable, avec de vrais ambassadeurs comme Jamie Lynn ou la star Terje Haakonsen. En 2007, ce dernier a même battu le record du monde du \"air\" le plus haut en s\'élevant à 9,8 mètres au-dessus du kick (sommet d\'un mur d\'une rampe ou autre structure de saut).', 'a457d58c8a77b18c312dedbd7e01b502.jpg', '2022-07-07 12:30:59', '2022-07-22 11:53:28'),
(6, 18, 3, 'tail slide', 'Un slide consiste à glisser sur une barre de slide. Le slide se fait soit avec la planche dans l\'axe de la barre, soit perpendiculaire, soit plus ou moins désaxé.\r\n\r\nOn peut slider avec la planche centrée par rapport à la barre (celle-ci se situe approximativement au-dessous des pieds du rideur), mais aussi en nose slide, c\'est-à-dire l\'avant de la planche sur la barre, ou en tail slide, l\'arrière de la planche sur la barre.', '5b116341998af56d5633299b61889b1f.jpg', '2022-07-07 12:46:02', '2022-07-07 12:46:02'),
(7, 18, 4, 'le mute', 'Un grab consiste à attraper la planche avec la main pendant le saut. Le verbe anglais to grab signifie « attraper. »\r\n\r\nIl existe plusieurs types de grabs selon la position de la saisie et la main choisie pour l\'effectuer, avec des difficultés variables :\r\n\r\n    mute : saisie de la carre frontside de la planche entre les deux pieds avec la main avant ;', '6e2ed391cefbcb430e554845c4e16a72.jpg', '2022-07-07 12:50:46', '2022-07-07 12:50:46'),
(8, 18, 9, 'backside Air', 'Le terme old school désigne un style de freestyle caractérisée par en ensemble de figure et une manière de réaliser des figures passée de mode, qui fait penser au freestyle des années 1980 - début 1990 (par opposition à new school) :\r\n\r\n    figures désuètes : Japan air, rocket air, ...\r\n    rotations effectuées avec le corps tendu\r\n    figures saccadées, par opposition au style new school qui privilégie l\'amplitude\r\n\r\nÀ noter que certains tricks old school restent indémodables :\r\n\r\n    Backside Air\r\n    Method Air', 'f84e26a7e75e2ebd7a0f65d347b3a3a3.jpg', '2022-07-07 12:56:25', '2022-07-10 22:58:15'),
(9, 18, 2, 'frontflip', 'Un flip est une rotation verticale. On distingue les front flips, rotations en avant, et les back flips, rotations en arrière.\r\n\r\nIl est possible de faire plusieurs flips à la suite, et d\'ajouter un grab à la rotation.\r\n\r\nLes flips agrémentés d\'une vrille existent aussi (Mac Twist, Hakon Flip...), mais de manière beaucoup plus rare, et se confondent souvent avec certaines rotations horizontales désaxées.\r\n\r\nNéanmoins, en dépit de la difficulté technique relative d\'une telle figure, le danger de retomber sur la tête ou la nuque est réel et conduit certaines stations de ski à interdire de telles figures dans ses snowparks.', '6e815ec3abcb9c49cf17a52da3ad1130.jpg', '2022-07-07 13:29:43', '2022-07-22 12:24:04'),
(10, 10, 1, 'le 720° 2', 'On désigne par le mot « rotation » uniquement des rotations horizontales ; les rotations verticales sont des flips. Le principe est d\'effectuer une rotation horizontale pendant le saut, puis d\'attérir en position switch ou normal. La nomenclature se base sur le nombre de degrés de rotation effectués  :\r\n\r\n    720, sept deux pour deux tours complets ;', '1cf29ad9012297c68a908f8247e03da3.jpg', '2022-07-07 13:33:55', '2022-07-22 11:45:17'),
(11, 10, 11, 'misty', 'La rotation désaxée est une rotation initialement horizontale mais lancée avec un mouvement des épaules particulier qui désaxe la rotation. Il existe différents types de rotations désaxées (corkscrew ou cork, rodeo, misty, etc.) en fonction de la manière dont est lancé le buste. Certaines de ces rotations, bien qu\'initialement horizontales, font passer la tête en bas.\r\n\r\nBien que certaines de ces rotations soient plus faciles à faire sur un certain nombre de tours (ou de demi-tours) que d\'autres, il est en théorie possible de d\'attérir n\'importe quelle rotation désaxée avec n\'importe quel nombre de tours, en jouant sur la quantité de désaxage afin de se retrouver à la position verticale au moment voulu.\r\n\r\nIl est également possible d\'agrémenter une rotation désaxée par un grab.', 'e0081d345adfc38e62e8d2f72c1af3e8.jpg', '2022-07-07 13:38:33', '2022-07-22 11:43:04'),
(12, 10, 4, 'Nose Grab', 'Un grab consiste à attraper la planche avec la main pendant le saut. Un grab est d\'autant plus réussi que la saisie est longue. Le saut est d\'autant plus esthétique que la saisie du snowboard est franche, ce qui permet au rideur d\'accentuer la torsion de son corps grâce à la tension de sa main sur la planche. On dit alors que le grab est tweaké.', 'ab0a0fff9c42caa9edb36a28a351ef50.jpg', '2022-07-20 11:30:27', '2022-07-20 11:30:27');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activation_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `passreset_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password`, `lastname`, `firstname`, `photo`, `activation_token`, `passreset_token`, `created_at`, `updated_at`) VALUES
(7, 'jobafa2', 'cococo@dodo.com', '$2y$13$4rt4IxcmoN908VJdQbnnEuZ1MRMuE59xyyBZMTJfct409/.btM476', 'FATHI', 'ABDERRAHIM', NULL, NULL, NULL, '2022-06-28 14:28:42', '2022-06-28 14:28:42'),
(8, 'visiteur', 'toto@dodo.com', '$2y$13$EmETHLA4z22K52bhxi.Ty.epCSj3aNXFSjy7uMf08HWcHHLqwhC8e', 'FATHI', 'ABDERRAHIM', '17.jpg', NULL, NULL, '2022-06-28 15:04:54', '2022-06-28 15:04:54'),
(9, 'tata', 'tata@dodo.com', '$2y$13$AIlbVsXJPdJtGCLGA3dsp.ptvpAqXDfuYnYBaejxB9fHQN/khXdNe', 'FATHI', 'ABDERRAHIM', '53.jpg', NULL, NULL, '2022-06-29 18:21:04', '2022-06-29 18:21:04'),
(10, 'titi', 'titi@dodo.com', '$2y$13$b235jHW/j.5WSENDiT8g4e5j3reBnzvhzeELhSV91MDZjcbLVrHG6', 'FATHI', 'ABDERRAHIM', '63.jpg', NULL, NULL, '2022-06-29 18:45:15', '2022-06-29 18:45:15'),
(18, 'test inscription', 'a.fathi@capdeco.com', '$2y$13$LAUr/x.v1P2yKT7MwoQShuTq2Egsgx3d.k4tzfCeX8T9d/DD9EpJ6', NULL, NULL, '80.jpg', NULL, NULL, '2022-06-29 20:59:03', '2022-06-29 20:59:03'),
(19, 'jobafa', 'contact@capdeco.com', '$2y$13$hBJShpStmch8ugR51Cp/LOE344MbmQfmnr7efnQfYeR0oJjgOmhC.', NULL, NULL, '85.jpg', '18Zl57CFqFC4VADQspzrUoHJafTF6ekLcaoPMI1rsAk', 'jJMSACfu1_q30j2ASwFWrse8Nd_utdi9-7JAlk0v_6k', '2022-06-30 22:07:45', '2022-06-30 22:07:45'),
(20, 'tototo', 'a.fathiff@capdeco.com', '$2y$13$g3k/u/Y8LbltJCkro.0v...6Z.lVrNb.OggTAS41cCzwWiebouzqa', NULL, NULL, '83.jpg', NULL, NULL, '2022-07-01 16:42:40', '2022-07-01 16:42:40'),
(24, 'test inscription2', 'aim.fathi@gmail.com', '$2y$13$XWlIPDjKdIl1z4AeqEXxe.X9M5kbzXdLnw32pwH2d1/xKzemglgbO', 'FATHI', 'ABDERRAHIM', '53.jpg', NULL, NULL, '2022-07-23 10:42:05', '2022-07-23 10:42:05'),
(28, 'test', 'test@test.com', '$2y$13$4Mrmrs6oVbFTEkAEZiIWxeyFapwHVf0HH22nYBNnkm4HPceDqyWq6', NULL, NULL, NULL, NULL, NULL, '2022-08-23 15:32:56', '2022-08-23 15:32:56');

-- --------------------------------------------------------

--
-- Structure de la table `video`
--

DROP TABLE IF EXISTS `video`;
CREATE TABLE IF NOT EXISTS `video` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `videourl` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `trick_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_7CC7DA2CB281BE2E` (`trick_id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `video`
--

INSERT INTO `video` (`id`, `name`, `videourl`, `trick_id`) VALUES
(1, NULL, 'https://www.youtube.com/embed/SlhGVnFPTDE', 3),
(2, NULL, 'https://www.youtube.com/embed/9T5AWWDxYM4', 4),
(3, NULL, 'https://www.youtube.com/embed/I3Rm5Cw33Zw', 4),
(4, NULL, 'https://www.youtube.com/embed/JGeEbI7n9zw', 5),
(5, NULL, 'https://www.youtube.com/embed/RJc5cYp7HNA', 5),
(6, NULL, 'https://www.youtube.com/embed/HRNXjMBakwM', 6),
(7, NULL, 'https://www.youtube.com/embed/-u_OGubYzaU', 7),
(8, NULL, 'https://www.youtube.com/embed/hih9jIzOoRg', 7),
(9, NULL, 'https://www.youtube.com/embed/_CN_yyEn78M', 8),
(10, NULL, 'https://www.youtube.com/embed/gMfmjr-kuOg', 9),
(11, NULL, 'https://www.youtube.com/embed/1vtZXU15e38', 10),
(12, NULL, 'https://www.youtube.com/embed/hPuVJkw1MmI', 11),
(13, NULL, 'https://www.youtube.com/embed/M-W7Pmo-YMY', 12),
(15, NULL, 'https://www.youtube.com/embed/9_zC7CdvYu4', 9);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `FK_9474526C67B3B43D` FOREIGN KEY (`users_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_9474526CB281BE2E` FOREIGN KEY (`trick_id`) REFERENCES `trick` (`id`);

--
-- Contraintes pour la table `image`
--
ALTER TABLE `image`
  ADD CONSTRAINT `FK_C53D045FB281BE2E` FOREIGN KEY (`trick_id`) REFERENCES `trick` (`id`);

--
-- Contraintes pour la table `trick`
--
ALTER TABLE `trick`
  ADD CONSTRAINT `FK_D8F0A91E12469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `FK_D8F0A91EA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `video`
--
ALTER TABLE `video`
  ADD CONSTRAINT `FK_7CC7DA2CB281BE2E` FOREIGN KEY (`trick_id`) REFERENCES `trick` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
