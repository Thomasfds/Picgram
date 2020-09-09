-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : mer. 09 sep. 2020 à 11:31
-- Version du serveur :  5.7.24
-- Version de PHP : 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `picgram`
--

-- --------------------------------------------------------

--
-- Structure de la table `amis`
--

CREATE TABLE `amis` (
  `amis_id` int(11) NOT NULL,
  `membre_id` int(11) NOT NULL,
  `amis_membre_id` int(11) NOT NULL,
  `statut` varchar(40) NOT NULL DEFAULT 'empty'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `commentaires`
--

CREATE TABLE `commentaires` (
  `id` int(11) NOT NULL,
  `message` text NOT NULL,
  `feed_id` int(11) NOT NULL,
  `membre_id` int(11) NOT NULL,
  `membre_pseudo` varchar(40) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `conversations`
--

CREATE TABLE `conversations` (
  `conversation_id` int(11) NOT NULL,
  `conversation_membre_a` int(11) NOT NULL,
  `conversation_membre_b` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `demande_amis`
--

CREATE TABLE `demande_amis` (
  `demande_id` int(11) NOT NULL,
  `demande_membre_id` int(11) NOT NULL,
  `demande_sender` int(11) NOT NULL,
  `demande_pseudo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `feed`
--

CREATE TABLE `feed` (
  `feed_id` int(11) NOT NULL,
  `image` varchar(155) CHARACTER SET utf8 NOT NULL,
  `membre_id` int(11) NOT NULL,
  `description` text COLLATE utf8mb4_bin NOT NULL,
  `pseudo_membre` varchar(40) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Structure de la table `feed_like`
--

CREATE TABLE `feed_like` (
  `id` int(11) NOT NULL,
  `feed_id` int(11) NOT NULL,
  `membre_id` int(11) NOT NULL,
  `membre_pseudo` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `messages_id` int(11) NOT NULL,
  `conv_id` int(11) NOT NULL,
  `pseudo` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `membre_id` int(11) NOT NULL,
  `membre_sender` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `notification_type` varchar(100) COLLATE utf8_bin NOT NULL,
  `notification_description` varchar(255) COLLATE utf8_bin NOT NULL,
  `notification_membre_id` int(11) NOT NULL,
  `notification_lu` int(11) NOT NULL DEFAULT '0',
  `notification_feed_id` int(11) DEFAULT NULL,
  `notification_sender` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `pseudo` varchar(40) NOT NULL,
  `mdp` varchar(155) NOT NULL,
  `email` varchar(40) NOT NULL,
  `image_profil` varchar(255) DEFAULT 'https://www.w3schools.com/howto/img_avatar.png',
  `image_couverture` varchar(255) DEFAULT 'https://via.placeholder.com/350',
  `bio` varchar(155) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `nom` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `amis`
--
ALTER TABLE `amis`
  ADD PRIMARY KEY (`amis_id`),
  ADD KEY `amis_membre_id` (`amis_membre_id`),
  ADD KEY `membre_id` (`membre_id`);

--
-- Index pour la table `commentaires`
--
ALTER TABLE `commentaires`
  ADD PRIMARY KEY (`id`),
  ADD KEY `feed_id` (`feed_id`),
  ADD KEY `commentaires_ibfk_2` (`membre_id`);

--
-- Index pour la table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`conversation_id`);

--
-- Index pour la table `demande_amis`
--
ALTER TABLE `demande_amis`
  ADD PRIMARY KEY (`demande_id`);

--
-- Index pour la table `feed`
--
ALTER TABLE `feed`
  ADD PRIMARY KEY (`feed_id`),
  ADD KEY `membre_id` (`membre_id`);

--
-- Index pour la table `feed_like`
--
ALTER TABLE `feed_like`
  ADD PRIMARY KEY (`id`),
  ADD KEY `feed_id` (`feed_id`),
  ADD KEY `membre_id` (`membre_id`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`messages_id`),
  ADD KEY `conv_id` (`conv_id`);

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `notification_membre_id` (`notification_membre_id`),
  ADD KEY `notification_feed_id` (`notification_feed_id`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `amis`
--
ALTER TABLE `amis`
  MODIFY `amis_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `commentaires`
--
ALTER TABLE `commentaires`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `conversation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `demande_amis`
--
ALTER TABLE `demande_amis`
  MODIFY `demande_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `feed`
--
ALTER TABLE `feed`
  MODIFY `feed_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `feed_like`
--
ALTER TABLE `feed_like`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `messages_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commentaires`
--
ALTER TABLE `commentaires`
  ADD CONSTRAINT `commentaires_ibfk_1` FOREIGN KEY (`feed_id`) REFERENCES `feed` (`feed_id`),
  ADD CONSTRAINT `commentaires_ibfk_2` FOREIGN KEY (`membre_id`) REFERENCES `utilisateurs` (`id`);

--
-- Contraintes pour la table `feed_like`
--
ALTER TABLE `feed_like`
  ADD CONSTRAINT `feed_like_ibfk_1` FOREIGN KEY (`feed_id`) REFERENCES `feed` (`feed_id`),
  ADD CONSTRAINT `feed_like_ibfk_2` FOREIGN KEY (`membre_id`) REFERENCES `utilisateurs` (`id`);

--
-- Contraintes pour la table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`conv_id`) REFERENCES `conversations` (`conversation_id`);

--
-- Contraintes pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`notification_membre_id`) REFERENCES `utilisateurs` (`id`),
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`notification_feed_id`) REFERENCES `feed` (`feed_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
