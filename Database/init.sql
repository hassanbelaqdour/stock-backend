-- Création de la base de données
CREATE DATABASE gestion_stock;
\c gestion_stock;

-- Table : categorie_produit
CREATE TABLE categorie_produit (
    id INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT
);

-- Table : fournisseur
CREATE TABLE fournisseur (
    id INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    coordonnees TEXT
);

-- Table : produit
-- Cette table représente la classe abstraite de produits avec un type précisant s'il s'agit d'un produit générique ou liquide
CREATE TABLE produit (
    id INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    code VARCHAR(50) UNIQUE NOT NULL,
    quantite_stock INT NOT NULL DEFAULT 0,
    seuil_alerte INT NOT NULL DEFAULT 0,
    id_categorie INT NOT NULL,
    id_fournisseur INT NOT NULL,
    type_produit VARCHAR(50) NOT NULL CHECK (type_produit IN ('generique', 'liquide')),
    CONSTRAINT fk_categorie FOREIGN KEY (id_categorie) REFERENCES categorie_produit(id) ON DELETE CASCADE,
    CONSTRAINT fk_fournisseur FOREIGN KEY (id_fournisseur) REFERENCES fournisseur(id) ON DELETE CASCADE
);

-- Table : produit_liquide
-- Contient les informations spécifiques aux produits liquides et hérite des informations de la table produit.
CREATE TABLE produit_liquide (
    id INT PRIMARY KEY,
    volume DECIMAL(10,2) NOT NULL,         -- ex : 1.50 (litres)
    conditionnement VARCHAR(50) NOT NULL,    -- ex : bouteille, bidon, etc.
    CONSTRAINT fk_produit_liquide FOREIGN KEY (id) REFERENCES produit(id) ON DELETE CASCADE
);

-- Table : mouvement_stock
-- Enregistre chaque mouvement de stock (entrée, sortie ou ajustement)
CREATE TABLE mouvement_stock (
    id INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    date_mouvement TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    type_mouvement VARCHAR(20) NOT NULL CHECK (type_mouvement IN ('entree', 'sortie', 'ajustement')),
    quantite INT NOT NULL,
    id_produit INT NOT NULL,
    CONSTRAINT fk_mouvement_produit FOREIGN KEY (id_produit) REFERENCES produit(id) ON DELETE CASCADE
);

-- Table : historique
-- Archive l'ensemble des opérations pour assurer la traçabilité et l'audit
CREATE TABLE historique (
    id INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    evenement VARCHAR(255) NOT NULL,
    details TEXT,
    date_evenement TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Insertion des catégories
INSERT INTO categorie_produit (nom, description) VALUES
('Produits d’entretien', 'Produits pour le nettoyage et l’entretien des locaux'),
('Lubrifiants', 'Huiles et graisses industrielles'),
('Pièces mécaniques', 'Composants pour la maintenance des machines');

-- Insertion des fournisseurs
INSERT INTO fournisseur (nom, coordonnees) VALUES
('Fourniture Maroc', '12 rue des ateliers, Casablanca, +212 522 45 67 89'),
('IndusPro', 'Zone industrielle, Tanger, +212 539 99 88 77'),
('TechLub', 'Avenue des entreprises, Rabat, +212 537 65 43 21');

-- Insertion des produits génériques
INSERT INTO produit (nom, code, quantite_stock, seuil_alerte, id_categorie, id_fournisseur, type_produit) VALUES
('Gants de protection', 'PDT001', 150, 50, 1, 1, 'generique'),
('Courroie moteur', 'PDT002', 30, 10, 3, 2, 'generique');

-- Insertion de produits liquides
-- Étape 1 : Insérer dans la table produit
INSERT INTO produit (nom, code, quantite_stock, seuil_alerte, id_categorie, id_fournisseur, type_produit) VALUES
('Huile moteur 5W30', 'LQD001', 80, 20, 2, 3, 'liquide'),
('Dégraissant industriel', 'LQD002', 40, 15, 1, 1, 'liquide');

-- Étape 2 : Récupérer les IDs générés et insérer dans produit_liquide
-- Supposons que les IDs générés pour les produits liquides soient 3 et 4
INSERT INTO produit_liquide (id, volume, conditionnement) VALUES
(3, 5.00, 'bidon'),
(4, 1.00, 'bouteille');

-- Insertion de mouvements de stock
INSERT INTO mouvement_stock (type_mouvement, quantite, id_produit) VALUES
('entree', 50, 1),  -- 50 gants ajoutés
('sortie', 10, 2),  -- 10 courroies sorties
('ajustement', -5, 3); -- ajustement négatif sur huile moteur

-- Insertion d’historique d’événements
INSERT INTO historique (evenement, details) VALUES
('entree de stock', 'Ajout de 50 gants de protection'),
('sortie de stock', 'Sortie de 10 courroies moteur'),
('ajustement de stock', 'Ajustement négatif de 5 bidons d’huile');
