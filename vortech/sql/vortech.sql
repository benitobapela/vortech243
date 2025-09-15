-- Paramètres SEO
('seo', 'seo_title', 'VorTech - Innovation et excellence technologique'),
('seo', 'seo_description', 'VorTech est une entreprise spécialisée dans l\'innovation et l\'excellence technologique.'),
('seo', 'seo_keywords', 'vortech, innovation, technologie, excellence'),
('seo', 'seo_analytics', ''),

-- Paramètres de maintenance
('maintenance', 'mode_maintenance', '0'),
('maintenance', 'message_maintenance', 'Le site est actuellement en maintenance. Veuillez revenir plus tard.');

-- Insertion de l'administrateur par défaut
-- Mot de passe: admin123 (à changer après la première connexion)
INSERT INTO administrateurs (username, password, nom, email, role) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrateur', 'admin@vortech.com', 'super_admin');


INSERT INTO parametres_maintenance (mode_maintenance, message_maintenance) VALUES
(0, 'Site en maintenance. Merci de revenir plus tard.');

-- Insertion des paramètres de sécurité par défaut
INSERT INTO parametres_securite (force_mdp_min, duree_session, max_tentatives, delai_verrouillage) VALUES
(8, 3600, 3, 900); -- Insertion des paramètres généraux par défaut
INSERT INTO parametres_generaux (site_title, site_description, site_keywords) VALUES
('VorTech', 'Description de VorTech', 'vortech, technologie, innovation');

-- Insertion des coordonnées de contact par défaut
INSERT INTO coordonnees_contact (email, telephone, adresse, ville, code_postal, pays) VALUES
('contact@vortech.com', '+1234567890', 'Adresse de VorTech', 'Ville', '75000', 'France');

-- Insertion des réseaux sociaux par défaut
INSERT INTO reseaux_sociaux (facebook, twitter, linkedin, instagram) VALUES
('', '', '', '');

-- Insertion des paramètres SEO par défaut
INSERT INTO parametres_seo (meta_title, meta_description, meta_keywords) VALUES
('VorTech - Innovation et Technologie', 'Description SEO de VorTech', 'vortech, technologie, innovation');

-- Insertion des paramètres de maintenance par défaut

-- Table des tentatives de connexion
CREATE TABLE IF NOT EXISTS login_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(45) NOT NULL,
    username VARCHAR(255) NOT NULL,
    attempt_time DATETIME NOT NULL,
    INDEX idx_ip_time (ip_address, attempt_time)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des logs d'administration
CREATE TABLE IF NOT EXISTS admin_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT NOT NULL,
    action VARCHAR(50) NOT NULL,
    details TEXT,
    ip_address VARCHAR(45) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES administrateurs(id) ON DELETE CASCADE,
    INDEX idx_admin_time (admin_id, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Modification de la table administrateurs pour ajouter last_ip
ALTER TABLE administrateurs ADD COLUMN last_ip VARCHAR(45) AFTER last_login;