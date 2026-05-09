-- ============================================================
-- consultations_schema.sql — Structure Base de Données
-- Tables nécessaires pour le module Consultations
-- Compatible MySQL 8.0+
-- ============================================================

-- Création de la base de données si elle n'existe pas
CREATE DATABASE IF NOT EXISTS medicare_db
    CHARACTER SET utf8mb4        -- Encodage complet (émojis, accents)
    COLLATE utf8mb4_unicode_ci;  -- Collation insensible à la casse

-- Sélection de la base
USE medicare_db;

-- ============================================================
-- TABLE : medecins
-- Stocke les comptes médecins (authentification + profil)
-- ============================================================
CREATE TABLE IF NOT EXISTS medecins (
    id              INT UNSIGNED    NOT NULL AUTO_INCREMENT,  -- Clé primaire auto
    prenom          VARCHAR(100)    NOT NULL,                 -- Prénom du médecin
    nom             VARCHAR(100)    NOT NULL,                 -- Nom de famille
    email           VARCHAR(255)    NOT NULL UNIQUE,          -- Email (unique, sert de login)
    mot_de_passe    VARCHAR(255)    NOT NULL,                 -- Hash bcrypt du mot de passe
    specialite      VARCHAR(150)    DEFAULT 'Médecin Généraliste', -- Spécialité médicale
    telephone       VARCHAR(20)     DEFAULT NULL,             -- Téléphone cabinet
    photo_url       VARCHAR(500)    DEFAULT NULL,             -- URL photo de profil
    role            ENUM('medecin','admin') DEFAULT 'medecin', -- Rôle dans le système
    statut          ENUM('actif','inactif') DEFAULT 'actif',  -- Compte actif ou non
    created_at      TIMESTAMP       DEFAULT CURRENT_TIMESTAMP, -- Date de création
    updated_at      TIMESTAMP       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    INDEX idx_email (email)  -- Index pour la recherche par email (login)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE : patients
-- Stocke les dossiers patients rattachés à un médecin
-- ============================================================
CREATE TABLE IF NOT EXISTS patients (
    id              INT UNSIGNED    NOT NULL AUTO_INCREMENT,  -- Clé primaire
    medecin_id      INT UNSIGNED    NOT NULL,                 -- Médecin référent (FK)
    prenom          VARCHAR(100)    NOT NULL,                 -- Prénom du patient
    nom             VARCHAR(100)    NOT NULL,                 -- Nom du patient
    date_naissance  DATE            NOT NULL,                 -- Pour calculer l'âge
    sexe            ENUM('M','F','autre') DEFAULT 'M',       -- Sexe
    groupe_sanguin  VARCHAR(5)      DEFAULT NULL,             -- Ex: A+, O-, AB+
    telephone       VARCHAR(20)     DEFAULT NULL,             -- Téléphone
    email           VARCHAR(255)    DEFAULT NULL,             -- Email patient
    adresse         TEXT            DEFAULT NULL,             -- Adresse complète
    allergies       TEXT            DEFAULT NULL,             -- Liste des allergies
    antecedents     TEXT            DEFAULT NULL,             -- Antécédents médicaux
    photo_url       VARCHAR(500)    DEFAULT NULL,             -- Photo profil
    created_at      TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at      TIMESTAMP       DEFAULT NULL,             -- Soft delete

    PRIMARY KEY (id),
    FOREIGN KEY (medecin_id) REFERENCES medecins(id) ON DELETE CASCADE, -- Supprime si médecin supprimé
    INDEX idx_medecin (medecin_id),                    -- Index pour filtrer par médecin
    INDEX idx_nom (nom, prenom),                       -- Index recherche par nom
    INDEX idx_deleted (deleted_at)                     -- Index pour ignorer les supprimés
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE : consultations
-- Cœur du module : toutes les consultations médicales
-- ============================================================
CREATE TABLE IF NOT EXISTS consultations (
    id                  INT UNSIGNED    NOT NULL AUTO_INCREMENT,  -- Clé primaire
    medecin_id          INT UNSIGNED    NOT NULL,                 -- FK médecin
    patient_id          INT UNSIGNED    NOT NULL,                 -- FK patient

    -- Planification
    date_consultation   DATE            NOT NULL,                 -- Date prévue
    heure               TIME            NOT NULL,                 -- Heure prévue (HH:MM:SS)
    duree_minutes       SMALLINT        DEFAULT 30,               -- Durée estimée (min)

    -- Informations consultation
    motif               VARCHAR(500)    NOT NULL,                 -- Motif de la visite
    type_consultation   ENUM('presentiel','teleconsultation') DEFAULT 'presentiel', -- Mode
    urgence             TINYINT(1)      DEFAULT 0,                -- 1 = urgent, 0 = normal

    -- Statut workflow
    statut              ENUM('en_attente','confirme','termine','annule') DEFAULT 'en_attente',

    -- Compte rendu médical (rempli après consultation)
    notes_symptomes     TEXT            DEFAULT NULL,             -- Symptômes observés
    notes_diagnostic    TEXT            DEFAULT NULL,             -- Diagnostic
    notes_traitement    TEXT            DEFAULT NULL,             -- Traitement prescrit
    notes_suivi         TEXT            DEFAULT NULL,             -- Recommandations suivi

    -- Horodatages
    created_at          TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at          TIMESTAMP       DEFAULT NULL,             -- Soft delete

    PRIMARY KEY (id),
    FOREIGN KEY (medecin_id) REFERENCES medecins(id) ON DELETE CASCADE,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,

    -- Index pour les requêtes fréquentes
    INDEX idx_medecin_date (medecin_id, date_consultation),  -- Filtrage par médecin + date
    INDEX idx_statut (statut),                               -- Filtrage par statut
    INDEX idx_patient (patient_id),                          -- Consultation d'un patient
    INDEX idx_deleted (deleted_at),                          -- Exclure soft deleted

    -- Contrainte unicité : un médecin ne peut avoir 2 consultations au même créneau
    UNIQUE KEY unique_creneau (medecin_id, date_consultation, heure)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- DONNÉES DE TEST (développement uniquement)
-- ============================================================

-- Insertion d'un médecin de test
INSERT IGNORE INTO medecins (id, prenom, nom, email, mot_de_passe, specialite) VALUES
(1, 'Thomas', 'Martin', 'dr.martin@medicare.fr',
 '$2y$12$exampleHashBcrypt1234567890abcdef', -- Remplacer par un vrai hash bcrypt
 'Médecin Généraliste');

-- Insertion de patients de test
INSERT IGNORE INTO patients (id, medecin_id, prenom, nom, date_naissance, sexe, groupe_sanguin, email) VALUES
(1, 1, 'Marie',    'Dupont',   '1991-03-15', 'F', 'A+',  'marie.dupont@email.fr'),
(2, 1, 'Jean',     'Bernard',  '1973-07-22', 'M', 'O+',  'jean.bernard@email.fr'),
(3, 1, 'Sophie',   'Leclerc',  '1997-11-08', 'F', 'B+',  'sophie.leclerc@email.fr'),
(4, 1, 'Ahmed',    'Karim',    '1980-05-30', 'M', 'AB-', 'ahmed.karim@email.fr'),
(5, 1, 'Claire',   'Fontaine', '1958-09-12', 'F', 'A-',  'claire.fontaine@email.fr');

-- Insertion de consultations de test
INSERT IGNORE INTO consultations
    (medecin_id, patient_id, date_consultation, heure, motif, type_consultation, statut, urgence)
VALUES
(1, 1, CURDATE(), '09:00', 'Douleurs abdominales récurrentes', 'presentiel',       'en_attente', 1),
(1, 2, CURDATE(), '10:30', 'Suivi diabète type 2',            'presentiel',       'confirme',   0),
(1, 3, CURDATE(), '11:15', 'Consultation générale - fatigue', 'teleconsultation', 'en_attente', 0),
(1, 4, DATE_SUB(CURDATE(), INTERVAL 1 DAY), '14:00', 'Renouvellement ordonnance', 'teleconsultation', 'termine', 0),
(1, 5, DATE_SUB(CURDATE(), INTERVAL 1 DAY), '15:30', 'Douleurs articulaires',     'presentiel',       'termine', 0);

-- ============================================================
-- FIN DU FICHIER SQL
-- ============================================================