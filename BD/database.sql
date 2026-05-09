-- ============================================================
--  TÉLÉ-EXPERTISE & CARNET DE SANTÉ NUMÉRIQUE
--  Schéma de base de données MySQL
-- ============================================================

CREATE DATABASE IF NOT EXISTS healthcare CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE healthcare;

SET NAMES utf8mb4;

SET time_zone = '+00:00';

-- ─── UTILISATEURS ────────────────────────────────────────────
CREATE TABLE utilisateurs (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    nom           VARCHAR(100) NOT NULL,
    prenom        VARCHAR(100) NOT NULL,
    email         VARCHAR(150) UNIQUE NOT NULL,
    mot_de_passe  VARCHAR(255) NOT NULL,          -- bcrypt hash
    role          ENUM('patient','medecin','admin') DEFAULT 'patient',
    telephone     VARCHAR(20),
    photo_profil  VARCHAR(255),
    est_actif     BOOLEAN DEFAULT TRUE,
    token_reset   VARCHAR(255) DEFAULT NULL,
    token_expire  DATETIME DEFAULT NULL,
    created_at    DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at    DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ─── PROFILS PATIENTS ────────────────────────────────────────
CREATE TABLE patients (
    id                INT AUTO_INCREMENT PRIMARY KEY,
    user_id           INT UNIQUE NOT NULL,
    date_naissance    DATE NOT NULL,
    sexe              ENUM('M','F','Autre') NOT NULL,
    groupe_sanguin    ENUM('A+','A-','B+','B-','AB+','AB-','O+','O-') DEFAULT NULL,
    adresse           TEXT,
    ville             VARCHAR(100),
    pays              VARCHAR(100) DEFAULT 'Côte d\'Ivoire',
    numero_assurance  VARCHAR(100),
    contact_urgence   VARCHAR(100),
    tel_urgence       VARCHAR(20),
    allergies         TEXT,
    antecedents       TEXT,
    FOREIGN KEY (user_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

-- ─── PROFILS MÉDECINS ────────────────────────────────────────
CREATE TABLE medecins (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    user_id          INT UNIQUE NOT NULL,
    numero_ordre     VARCHAR(50) UNIQUE NOT NULL,
    specialite       VARCHAR(100) NOT NULL,
    hopital          VARCHAR(150),
    ville            VARCHAR(100),
    disponible       BOOLEAN DEFAULT TRUE,
    tarif_consultation DECIMAL(10,2) DEFAULT 0.00,
    biographie       TEXT,
    FOREIGN KEY (user_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

-- ─── VACCINS (RÉFÉRENTIEL) ───────────────────────────────────
CREATE TABLE vaccins (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    nom             VARCHAR(150) NOT NULL,
    description     TEXT,
    fabricant       VARCHAR(100),
    nombre_doses   INT DEFAULT 1,
    intervalle_jours INT DEFAULT NULL,   -- entre les doses
    obligatoire     BOOLEAN DEFAULT FALSE,
    tranche_age     VARCHAR(50),         -- ex: "0-5 ans", "Adulte"
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ─── CARNET VACCINAL ─────────────────────────────────────────
CREATE TABLE vaccinations (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    patient_id      INT NOT NULL,
    vaccin_id       INT NOT NULL,
    medecin_id      INT DEFAULT NULL,
    numero_dose     INT DEFAULT 1,
    date_injection  DATE NOT NULL,
    date_rappel     DATE DEFAULT NULL,
    lot_vaccin      VARCHAR(50),
    centre          VARCHAR(150),
    observations    TEXT,
    certificat_url  VARCHAR(255),
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    FOREIGN KEY (vaccin_id)  REFERENCES vaccins(id),
    FOREIGN KEY (medecin_id) REFERENCES medecins(id) ON DELETE SET NULL
);

-- ─── RAPPELS VACCINS ─────────────────────────────────────────
CREATE TABLE rappels_vaccins (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    patient_id      INT NOT NULL,
    vaccin_id       INT NOT NULL,
    date_rappel     DATE NOT NULL,
    est_envoye      BOOLEAN DEFAULT FALSE,
    envoye_le       DATETIME DEFAULT NULL,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    FOREIGN KEY (vaccin_id)  REFERENCES vaccins(id)
);

-- ─── CONSULTATIONS À DISTANCE ────────────────────────────────
CREATE TABLE consultations (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    patient_id      INT NOT NULL,
    medecin_id      INT NOT NULL,
    date_heure      DATETIME NOT NULL,
    duree_minutes   INT DEFAULT 30,
    type            ENUM('video','audio','chat') DEFAULT 'video',
    statut          ENUM('demandee','confirmee','en_cours','terminee','annulee') DEFAULT 'demandee',
    motif           TEXT NOT NULL,
    notes_medecin   TEXT,
    diagnostic      TEXT,
    prescription    TEXT,
    lien_reunion    VARCHAR(255),            -- URL Jitsi/Zoom
    montant         DECIMAL(10,2) DEFAULT 0.00,
    paye            BOOLEAN DEFAULT FALSE,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id)  REFERENCES patients(id) ON DELETE CASCADE,
    FOREIGN KEY (medecin_id)  REFERENCES medecins(id) ON DELETE CASCADE
);

-- ─── MESSAGES (TCHAT CONSULTATION) ───────────────────────────
CREATE TABLE messages (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    consultation_id INT NOT NULL,
    expediteur_id   INT NOT NULL,
    contenu         TEXT NOT NULL,
    type            ENUM('texte','fichier','image') DEFAULT 'texte',
    fichier_url     VARCHAR(255),
    lu              BOOLEAN DEFAULT FALSE,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (consultation_id) REFERENCES consultations(id) ON DELETE CASCADE,
    FOREIGN KEY (expediteur_id)   REFERENCES utilisateurs(id)
);

-- ─── DOCUMENTS MÉDICAUX ──────────────────────────────────────
CREATE TABLE documents (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    patient_id      INT NOT NULL,
    consultation_id INT DEFAULT NULL,
    titre           VARCHAR(200) NOT NULL,
    type            ENUM('ordonnance','analyse','radio','compte_rendu','autre') DEFAULT 'autre',
    fichier_url     VARCHAR(255) NOT NULL,
    taille_ko       INT,
    uploader_id     INT NOT NULL,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id)      REFERENCES patients(id) ON DELETE CASCADE,
    FOREIGN KEY (consultation_id) REFERENCES consultations(id) ON DELETE SET NULL,
    FOREIGN KEY (uploader_id)     REFERENCES utilisateurs(id)
);

-- ─── DISPONIBILITÉS MÉDECIN ──────────────────────────────────
CREATE TABLE disponibilites (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    medecin_id  INT NOT NULL,
    jour        ENUM('Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche') NOT NULL,
    heure_debut TIME NOT NULL,
    heure_fin   TIME NOT NULL,
    FOREIGN KEY (medecin_id) REFERENCES medecins(id) ON DELETE CASCADE
);

-- ─── TOKENS JWT (RÉVOCATION) ─────────────────────────────────
CREATE TABLE tokens_blacklist (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    token_hash  VARCHAR(255) NOT NULL,
    expire_le   DATETIME NOT NULL,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ─── INDEX ───────────────────────────────────────────────────
CREATE INDEX idx_vaccinations_patient  ON vaccinations(patient_id);
CREATE INDEX idx_vaccinations_date     ON vaccinations(date_injection);
CREATE INDEX idx_consultations_patient ON consultations(patient_id);
CREATE INDEX idx_consultations_medecin ON consultations(medecin_id);
CREATE INDEX idx_consultations_statut  ON consultations(statut);
CREATE INDEX idx_rappels_date          ON rappels_vaccins(date_rappel);
CREATE INDEX idx_messages_consultation ON messages(consultation_id);

-- ─── DONNÉES DE BASE ─────────────────────────────────────────
INSERT INTO vaccins (nom, description, fabricant, nombre_doses, intervalle_jours, obligatoire, tranche_age) VALUES
('BCG',               'Tuberculose',                    'Serum Institute', 1,  NULL,  TRUE,  '0-12 mois'),
('DTC-HepB-Hib',      'Diphtérie, Tétanos, Coqueluche', 'Sanofi',          3,  28,    TRUE,  '0-12 mois'),
('Polio oral',        'Poliomyélite',                   'GSK',             4,  28,    TRUE,  '0-5 ans'),
('ROR',               'Rougeole, Oreillons, Rubéole',   'MSD',             2,  90,    TRUE,  '12-24 mois'),
('Fièvre jaune',      'Yellow Fever',                   'Sanofi Pasteur',  1,  NULL,  TRUE,  'Tous âges'),
('Méningite A',       'Méningocoque A',                 'Serum Institute', 1,  NULL,  FALSE, '1-29 ans'),
('HPV',               'Papillomavirus humain',          'MSD',             2,  180,   FALSE, '9-14 ans'),
('COVID-19',          'SARS-CoV-2',                     'Multi',           2,  21,    FALSE, 'Adulte'),
('Hépatite B',        'Hépatite B',                     'GSK',             3,  28,    TRUE,  'Tous âges'),
('Tétanos adulte',    'Rappel tétanos',                 'Sanofi',          1,  NULL,  FALSE, 'Adulte');

