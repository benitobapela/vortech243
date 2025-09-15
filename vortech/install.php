<?php
// Configuration de la connexion à la base de données
$host = 'localhost';
$user = 'root';
$password = '';

try {
    // Connexion à MySQL sans sélectionner de base de données
    $pdo = new PDO("mysql:host=$host", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Installation de la base de données VorTech</h2>";
    
    // Création de la base de données
    $pdo->exec("CREATE DATABASE IF NOT EXISTS vortech CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "<p style='color: green;'>✓ Base de données 'vortech' créée avec succès</p>";
    
    // Sélection de la base de données
    $pdo->exec("USE vortech");
    
    // Création des tables
    $tables = [
        // Table entreprise
        "CREATE TABLE IF NOT EXISTS entreprise (
            id INT PRIMARY KEY AUTO_INCREMENT,
            nom VARCHAR(100) NOT NULL,
            slogan TEXT,
            description TEXT,
            adresse TEXT,
            telephone VARCHAR(20),
            email VARCHAR(100),
            site_web VARCHAR(100),
            logo VARCHAR(255),
            favicon VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )",
        
        // Table equipe
        "CREATE TABLE IF NOT EXISTS equipe (
            id INT PRIMARY KEY AUTO_INCREMENT,
            nom VARCHAR(100) NOT NULL,
            poste VARCHAR(100) NOT NULL,
            description TEXT,
            photo VARCHAR(255),
            linkedin VARCHAR(255),
            facebook VARCHAR(255),
            instagram VARCHAR(255),
            whatsapp VARCHAR(255),
            twitter VARCHAR(255),
            ordre INT DEFAULT 0,
            actif TINYINT(1) DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )",
        
        // Table services
        "CREATE TABLE IF NOT EXISTS services (
            id INT PRIMARY KEY AUTO_INCREMENT,
            titre VARCHAR(100) NOT NULL,
            description TEXT,
            icone VARCHAR(50),
            image VARCHAR(255),
            ordre INT DEFAULT 0,
            actif TINYINT(1) DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )",
        
        // Table projets
        "CREATE TABLE IF NOT EXISTS projets (
            id INT PRIMARY KEY AUTO_INCREMENT,
            titre VARCHAR(100) NOT NULL,
            description TEXT,
            image VARCHAR(255),
            client VARCHAR(100),
            date_realisation DATE,
            technologies TEXT,
            lien VARCHAR(255),
            ordre INT DEFAULT 0,
            actif TINYINT(1) DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )",
        
        // Table temoignages
        "CREATE TABLE IF NOT EXISTS temoignages (
            id INT PRIMARY KEY AUTO_INCREMENT,
            nom VARCHAR(100) NOT NULL,
            poste VARCHAR(100),
            entreprise VARCHAR(100),
            photo VARCHAR(255),
            temoignage TEXT NOT NULL,
            note INT DEFAULT 5,
            ordre INT DEFAULT 0,
            actif TINYINT(1) DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )",
        
        // Table utilisateurs
        "CREATE TABLE IF NOT EXISTS utilisateurs (
            id INT PRIMARY KEY AUTO_INCREMENT,
            nom VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            mot_de_passe VARCHAR(255) NOT NULL,
            role ENUM('admin', 'user') DEFAULT 'user',
            actif TINYINT(1) DEFAULT 1,
            derniere_connexion TIMESTAMP NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )"
    ];
    
    // Exécution de la création des tables
    foreach ($tables as $table) {
        $pdo->exec($table);
    }
    echo "<p style='color: green;'>✓ Toutes les tables ont été créées avec succès</p>";
    
    // Insertion des données par défaut
    // Entreprise
    $pdo->exec("INSERT INTO entreprise (nom, slogan, description) 
                VALUES ('VorTech', 'Votre partenaire technologique', 'VorTech est une entreprise spécialisée dans le développement de solutions technologiques innovantes.')
                ON DUPLICATE KEY UPDATE id = id");
    echo "<p style='color: green;'>✓ Données de l'entreprise ajoutées</p>";
    
    // Utilisateur admin
    $pdo->exec("INSERT INTO utilisateurs (nom, email, mot_de_passe, role)
                VALUES ('Administrateur', 'admin@vortech.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin')
                ON DUPLICATE KEY UPDATE id = id");
    echo "<p style='color: green;'>✓ Utilisateur administrateur créé</p>";
    
    echo "<div style='margin-top: 20px; padding: 15px; background-color: #e8f5e9; border-radius: 5px;'>";
    echo "<h3 style='color: #2e7d32;'>Installation terminée avec succès!</h3>";
    echo "<p>Identifiants administrateur par défaut :</p>";
    echo "<ul>";
    echo "<li>Email : admin@vortech.com</li>";
    echo "<li>Mot de passe : admin123</li>";
    echo "</ul>";
    echo "<p style='color: #d32f2f;'><strong>Important :</strong> N'oubliez pas de changer le mot de passe administrateur après la première connexion!</p>";
    echo "</div>";
    
} catch(PDOException $e) {
    echo "<div style='color: red; padding: 15px; background-color: #ffebee; border-radius: 5px;'>";
    echo "<h3>Erreur lors de l'installation :</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "</div>";
}
?> 