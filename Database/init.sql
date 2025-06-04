
DROP DATABASE IF EXISTS gestion_stock;


CREATE DATABASE gestion_stock;


\c gestion_stock;


CREATE TABLE categorie_produit (
    id INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT
);



CREATE TABLE fournisseur (
    id INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(100),      
    telephone VARCHAR(50),   
    adresse TEXT             
    
);


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


CREATE TABLE produit_liquide (
    id INT PRIMARY KEY,
    volume DECIMAL(10,2) NOT NULL,
    conditionnement VARCHAR(50) NOT NULL,
    CONSTRAINT fk_produit_liquide FOREIGN KEY (id) REFERENCES produit(id) ON DELETE CASCADE
);


CREATE TABLE mouvement_stock (
    id INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    date_mouvement TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    type_mouvement VARCHAR(20) NOT NULL CHECK (type_mouvement IN ('entree', 'sortie', 'ajustement')),
    quantite INT NOT NULL,
    id_produit INT NOT NULL,
    CONSTRAINT fk_mouvement_produit FOREIGN KEY (id_produit) REFERENCES produit(id) ON DELETE CASCADE
);


CREATE TABLE historique (
    id INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    evenement VARCHAR(255) NOT NULL,
    details TEXT,
    date_evenement TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);




INSERT INTO categorie_produit (nom, description) VALUES
('Produits d’entretien', 'Produits pour le nettoyage et l’entretien des locaux'),
('Lubrifiants', 'Huiles et graisses industrielles'),
('Pièces mécaniques', 'Composants pour la maintenance des machines');


INSERT INTO fournisseur (nom, email, telephone, adresse) VALUES
('Fourniture Maroc', 'contact@fournituremaroc.ma', '+212 522 45 67 89', '12 rue des ateliers, Casablanca'),
('IndusPro', 'info@induspro.com', '+212 539 99 88 77', 'Zone industrielle, Tanger'),
('TechLub', 'support@techlub.net', '+212 537 65 43 21', 'Avenue des entreprises, Rabat');




INSERT INTO produit (nom, code, quantite_stock, seuil_alerte, id_categorie, id_fournisseur, type_produit) VALUES
('Gants de protection', 'PDT001', 150, 50, 1, 1, 'generique'),
('Courroie moteur', 'PDT002', 30, 10, 3, 2, 'generique');




INSERT INTO produit (nom, code, quantite_stock, seuil_alerte, id_categorie, id_fournisseur, type_produit) VALUES
('Huile moteur 5W30', 'LQD001', 80, 20, 2, 3, 'liquide'), 
('Dégraissant industriel', 'LQD002', 40, 15, 1, 1, 'liquide'); 






INSERT INTO produit_liquide (id, volume, conditionnement) VALUES
(3, 5.00, 'bidon'),
(4, 1.00, 'bouteille');




INSERT INTO mouvement_stock (type_mouvement, quantite, id_produit) VALUES
('entree', 50, 1),  
('sortie', 10, 2),  
('ajustement', -5, 3); 


INSERT INTO historique (evenement, details) VALUES
('entree de stock', 'Ajout de 50 gants de protection'),
('sortie de stock', 'Sortie de 10 courroies moteur'),
('ajustement de stock', 'Ajustement négatif de 5 bidons d’huile');

