// Création de la table parametres_generaux
$sql = "CREATE TABLE IF NOT EXISTS parametres_generaux (
    id INT PRIMARY KEY AUTO_INCREMENT,
    site_title VARCHAR(255) NOT NULL,
    site_description TEXT,
    site_keywords TEXT,
    site_email VARCHAR(255),
    logo VARCHAR(255),
    favicon VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
$pdo->exec($sql);

// ... rest of the file ... 