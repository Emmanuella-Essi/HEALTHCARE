<<<<<<< HEAD
=======
<<<<<<< HEAD
CREATE DATABASE IF NOT EXISTS healthcare CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE healthcare;
 
-- ─── Utilisateurs (tous les rôles) ───────────
CREATE TABLE utilisateurs (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nom         VARCHAR(100) NOT NULL,
    prenom      VARCHAR(100) NOT NULL,
    email       VARCHAR(150) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    role        ENUM('patient', 'medecin', 'admin') NOT NULL DEFAULT 'patient',
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
 
-- ─── Patients ────────────────────────────────
CREATE TABLE patients (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    user_id          INT NOT NULL UNIQUE,
    date_naissance   DATE,
    sexe             ENUM('M', 'F', 'Autre'),
    telephone        VARCHAR(20),
    adresse          TEXT,
    groupe_sanguin   ENUM('A+','A-','B+','B-','AB+','AB-','O+','O-'),
    FOREIGN KEY (user_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
);
 
-- ─── Médecins ────────────────────────────────
CREATE TABLE medecins (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    user_id      INT NOT NULL UNIQUE,
    specialite   VARCHAR(100),
    numero_ordre VARCHAR(50),
    telephone    VARCHAR(20),
    FOREIGN KEY (user_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
);
 
-- ─── Carnet de santé ─────────────────────────
CREATE TABLE carnets_sante (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    patient_id     INT NOT NULL UNIQUE,
    antecedents    TEXT,
    allergies      TEXT,
    traitements    TEXT,
    notes          TEXT,
    updated_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE
);
 
-- ─── Vaccinations ────────────────────────────
CREATE TABLE vaccinations (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    patient_id     INT NOT NULL,
    nom_vaccin     VARCHAR(150) NOT NULL,
    date_vaccin    DATE NOT NULL,
    prochain_rappel DATE,
    notes          TEXT,
    created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE
);
 
-- ─── Télé-expertises ─────────────────────────
CREATE TABLE tele_expertises (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    patient_id   INT NOT NULL,
    medecin_id   INT,
    titre        VARCHAR(200) NOT NULL,
    description  TEXT NOT NULL,
    statut       ENUM('en_attente', 'en_cours', 'resolu') DEFAULT 'en_attente',
    reponse      TEXT,
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    FOREIGN KEY (medecin_id) REFERENCES medecins(id) ON DELETE SET NULL
);
 
-- ─── Documents joints aux expertises ─────────
CREATE TABLE documents (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    expertise_id INT NOT NULL,
    nom_fichier  VARCHAR(255),
    chemin       VARCHAR(255),
    type_fichier VARCHAR(50),
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (expertise_id) REFERENCES tele_expertises(id) ON DELETE CASCADE
);
 
-- ─── Données de test ─────────────────────────
INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role) VALUES
('Admin', 'System', 'admin@healthcare.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Dupont', 'Marie', 'marie@patient.com',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'patient'),
('Martin', 'Jean',  'jean@medecin.com',     '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'medecin');
-- Mot de passe pour tous : password
 
INSERT INTO patients (user_id, date_naissance, sexe, groupe_sanguin) VALUES
(2, '1990-05-15', 'F', 'A+');
 
INSERT INTO medecins (user_id, specialite, numero_ordre) VALUES
(3, 'Médecine générale', 'MED-2024-001');
 
INSERT INTO carnets_sante (patient_id, antecedents, allergies) VALUES
(1, 'Aucun antécédent connu', 'Pénicilline');
=======

>>>>>>> 6f958a3cea5d26430fc196a6eab507dfb9259af7
>>>>>>> b03e9c7264bf0381aba716f52739e92a0b12316e
