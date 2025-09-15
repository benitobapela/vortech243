-- Table des paramètres généraux
CREATE TABLE IF NOT EXISTS parametres_generaux (
    id INT PRIMARY KEY AUTO_INCREMENT,
    site_titre VARCHAR(255) NOT NULL,
    site_description TEXT,
    contact_email VARCHAR(255),
    contact_telephone VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table des paramètres de maintenance
CREATE TABLE IF NOT EXISTS parametres_maintenance (
    id INT PRIMARY KEY AUTO_INCREMENT,
    maintenance_mode TINYINT(1) DEFAULT 0,
    maintenance_message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table des paramètres de sécurité
CREATE TABLE IF NOT EXISTS parametres_securite (
    id INT PRIMARY KEY AUTO_INCREMENT,
    max_login_attempts INT DEFAULT 3,
    session_timeout INT DEFAULT 30,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table des paramètres SEO
CREATE TABLE IF NOT EXISTS parametres_seo (
    id INT PRIMARY KEY AUTO_INCREMENT,
    meta_title VARCHAR(255),
    meta_description TEXT,
    meta_keywords TEXT,
    google_analytics TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table des réseaux sociaux
CREATE TABLE IF NOT EXISTS reseaux_sociaux (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL,
    url VARCHAR(255),
    icone VARCHAR(50),
    actif TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insertion des valeurs par défaut
INSERT INTO parametres_generaux (id, site_titre, site_description) VALUES (1, 'Mon Site', 'Description du site');
INSERT INTO parametres_maintenance (id, maintenance_mode, maintenance_message) VALUES (1, 0, 'Site en maintenance. Veuillez réessayer plus tard.');
INSERT INTO parametres_securite (id, max_login_attempts, session_timeout) VALUES (1, 3, 30);
INSERT INTO parametres_seo (id, meta_title, meta_description) VALUES (1, 'Mon Site', 'Description du site');

-- Insertion des réseaux sociaux par défaut
INSERT INTO reseaux_sociaux (nom, url, icone) VALUES 
('Facebook', '', 'fab fa-facebook'),
('Telegram', '', 'fab fa-telegram'),
('LinkedIn', '', 'fab fa-linkedin'),
('Instagram', '', 'fab fa-instagram'),
('YouTube', '', 'fab fa-youtube'),
('WhatsApp', '', 'fab fa-whatsapp'); 