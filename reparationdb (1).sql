-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 26 mai 2025 à 20:47
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `reparationdb`
--

-- --------------------------------------------------------

--
-- Structure de la table `demande_reparation`
--

CREATE TABLE `demande_reparation` (
  `id_demande` int(11) NOT NULL,
  `nom_complet` varchar(100) NOT NULL,
  `numero` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `marque_telephone` varchar(50) NOT NULL,
  `probleme` text NOT NULL,
  `date_demande` date NOT NULL,
  `type_reparation` enum('express','standard') DEFAULT 'standard'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `demande_reparation`
--

INSERT INTO `demande_reparation` (`id_demande`, `nom_complet`, `numero`, `email`, `adresse`, `marque_telephone`, `probleme`, `date_demande`, `type_reparation`) VALUES
(3, 'kknknk smith', '+22899181626', 'chamiawsadjolou@gmail.com', 'dfvdhidllfe', 'SAMSUNG NOTE 10+ 5G 256GB', 'ECRAN COMPLET', '2025-05-26', 'express'),
(4, 'rkgklrgklrlkfgr', '+22899181726', 'adc@email.com', 'dfvdhidllfe', 'SAMSUNG NOTE 10+ 5G 256GB', 'WRJLGLLLGHOEIOLHVSDVBKEWEGW', '2025-05-26', 'standard'),
(5, 'rkgklrgklrlkfgr', '+22899181726', 'adc@email.com', 'dfvdhidllfe', 'SAMSUNG NOTE 10+ 5G 256GB', 'WRJLGLLLGHOEIOLHVSDVBKEWEGW', '2025-05-26', 'standard'),
(9, 'rkgklrgklrlkfgr', '+22899181726', 'adc@email.com', 'dfvdhidllfe', 'SAMSUNG NOTE 10+ 5G 256GB', 'jkdjrjgjkeeooflef', '2025-05-26', 'express');

-- --------------------------------------------------------

--
-- Structure de la table `facture`
--

CREATE TABLE `facture` (
  `id_facture` int(11) NOT NULL,
  `id_reparation` int(11) DEFAULT NULL,
  `date_facture` date DEFAULT NULL,
  `montant_total` decimal(10,2) DEFAULT NULL,
  `montant_regle` decimal(10,2) DEFAULT NULL,
  `reste_a_payer` decimal(10,2) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `statut_paiement` enum('Payée','Partiellement payée','Non payée') DEFAULT 'Non payée'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reparation`
--

CREATE TABLE `reparation` (
  `id_reparation` int(11) NOT NULL,
  `id_demande` int(11) NOT NULL,
  `id_traitement` int(11) NOT NULL,
  `date_reparation` date NOT NULL,
  `montant_total` decimal(10,2) DEFAULT NULL,
  `montant_paye` decimal(10,2) DEFAULT NULL,
  `reste_a_payer` decimal(10,2) DEFAULT NULL,
  `statut` enum('En cours','Terminé','Prêt à récupérer') DEFAULT 'En cours'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reparation`
--

INSERT INTO `reparation` (`id_reparation`, `id_demande`, `id_traitement`, `date_reparation`, `montant_total`, `montant_paye`, `reste_a_payer`, `statut`) VALUES
(2, 5, 2, '2025-05-26', 12000.00, 11000.00, 1000.00, 'Prêt à récupérer');

-- --------------------------------------------------------

--
-- Structure de la table `traitement`
--

CREATE TABLE `traitement` (
  `id_traitement` int(11) NOT NULL,
  `id_demande` int(11) NOT NULL,
  `date_reception` date NOT NULL,
  `montant_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `montant_paye` decimal(10,2) DEFAULT 0.00,
  `reste_a_payer` decimal(10,2) GENERATED ALWAYS AS (`montant_total` - `montant_paye`) STORED,
  `type_reparation` enum('express','standard') DEFAULT 'standard',
  `id_technicien` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `traitement`
--

INSERT INTO `traitement` (`id_traitement`, `id_demande`, `date_reception`, `montant_total`, `montant_paye`, `type_reparation`, `id_technicien`) VALUES
(1, 3, '2025-05-26', 15000.00, 12000.00, 'express', 2),
(2, 5, '2025-05-26', 12000.00, 11000.00, 'standard', 2);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id_utilisateur` int(11) NOT NULL,
  `nom_complet` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('admin','technicien') DEFAULT 'technicien'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id_utilisateur`, `nom_complet`, `email`, `mot_de_passe`, `role`) VALUES
(1, 'Jean Dupont', 'jean@example.com', '123456', 'admin'),
(2, 'Alice Diop', 'alice@example.com', 'abcdef', 'technicien'),
(3, 'Mamadou Fall', 'mamadou@example.com', 'pass123', '');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `demande_reparation`
--
ALTER TABLE `demande_reparation`
  ADD PRIMARY KEY (`id_demande`);

--
-- Index pour la table `facture`
--
ALTER TABLE `facture`
  ADD PRIMARY KEY (`id_facture`),
  ADD KEY `id_reparation` (`id_reparation`);

--
-- Index pour la table `reparation`
--
ALTER TABLE `reparation`
  ADD PRIMARY KEY (`id_reparation`),
  ADD KEY `id_demande` (`id_demande`),
  ADD KEY `id_traitement` (`id_traitement`);

--
-- Index pour la table `traitement`
--
ALTER TABLE `traitement`
  ADD PRIMARY KEY (`id_traitement`),
  ADD KEY `id_demande` (`id_demande`),
  ADD KEY `fk_technicien` (`id_technicien`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id_utilisateur`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `demande_reparation`
--
ALTER TABLE `demande_reparation`
  MODIFY `id_demande` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `facture`
--
ALTER TABLE `facture`
  MODIFY `id_facture` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `reparation`
--
ALTER TABLE `reparation`
  MODIFY `id_reparation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `traitement`
--
ALTER TABLE `traitement`
  MODIFY `id_traitement` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `facture`
--
ALTER TABLE `facture`
  ADD CONSTRAINT `facture_ibfk_1` FOREIGN KEY (`id_reparation`) REFERENCES `reparation` (`id_reparation`) ON DELETE SET NULL;

--
-- Contraintes pour la table `reparation`
--
ALTER TABLE `reparation`
  ADD CONSTRAINT `reparation_ibfk_1` FOREIGN KEY (`id_demande`) REFERENCES `demande_reparation` (`id_demande`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reparation_ibfk_2` FOREIGN KEY (`id_traitement`) REFERENCES `traitement` (`id_traitement`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `traitement`
--
ALTER TABLE `traitement`
  ADD CONSTRAINT `traitement_ibfk_1` FOREIGN KEY (`id_demande`) REFERENCES `demande_reparation` (`id_demande`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `traitement_ibfk_2` FOREIGN KEY (`id_technicien`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
