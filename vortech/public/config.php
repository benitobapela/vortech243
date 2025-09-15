<?php
// Configuration de la base de données
$host = 'localhost';
$dbname = 'vortech';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Inclusion des fonctions communes
require_once __DIR__ . '/functions.php';

// Informations de l'entreprise
$entreprise = [
    'nom' => 'VorTech',
    'slogan' => 'Innovation et excellence technologique',
    'annee_creation' => '2024',
    'contact' => [
        'adresse' => 'Universite de kinsasa',
        'ville' => 'Kinshasa',
        'code_postal' => '75001',
        'pays' => 'RDC',
        'email' => 'contact@vortech.com',
        'telephone' => '+243832590173'
    ],
    'reseaux_sociaux' => [
        'linkedin' => 'https://linkedin.com/company/vortech',
        'twitter' => 'https://twitter.com/vortech',
        'github' => 'https://github.com/vortech',
        'facebook' => 'https://facebook.com/company/vortech',
        'whatsapp' => 'https://wa.me/vortech',
        'instagram' => 'https://instagram.com/vortech',
        'telegram' => 'https://te.me/vortech',
    ]
];

// Récupération des statistiques
try {
    // Vérifier si la table existe
    $stmt = $pdo->query("SHOW TABLES LIKE 'parametres_generaux'");
    if ($stmt->rowCount() > 0) {
        // Vérifier la structure de la table
        $stmt = $pdo->query("DESCRIBE parametres_generaux");
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Si la table n'a pas les colonnes nécessaires, les créer
        if (!in_array('logo', $columns)) {
            $pdo->exec("ALTER TABLE parametres_generaux ADD COLUMN logo VARCHAR(255) DEFAULT 'uploads/generaux/logo.png'");
        }
        
        // Récupérer les paramètres
        $stmt = $pdo->query("SELECT * FROM parametres_generaux WHERE id = 1");
        $stats = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Vérifier si le logo existe dans le répertoire
        if (isset($stats['logo']) && !empty($stats['logo'])) {
            $logo_path = 'uploads/generaux/' . basename($stats['logo']);
            if (!file_exists(__DIR__ . '/' . $logo_path)) {
                // Si le fichier n'existe pas, utiliser le logo par défaut
                $stats['logo'] = 'uploads/generaux/logo.png';
            } else {
                $stats['logo'] = $logo_path;
            }
        }
    } else {
        // Créer la table si elle n'existe pas
        $pdo->exec("CREATE TABLE IF NOT EXISTS parametres_generaux (
            id INT PRIMARY KEY AUTO_INCREMENT,
            logo VARCHAR(255) DEFAULT 'uploads/generaux/logo.png',
            favicon VARCHAR(255) DEFAULT 'uploads/generaux/favicon.ico',
            annees_experience INT DEFAULT 1,
            projets_reussis INT DEFAULT 0,
            clients_satisfaits INT DEFAULT 0
        )");
        
        // Insérer les valeurs par défaut
        $pdo->exec("INSERT INTO parametres_generaux (id, logo) VALUES (1, 'uploads/generaux/logo.png')");
        $stats = [
            'logo' => 'uploads/generaux/logo.png',
            'favicon' => 'uploads/generaux/favicon.ico',
            'annees_experience' => 1,
            'projets_reussis' => 0,
            'clients_satisfaits' => 0
        ];
    }
} catch (PDOException $e) {
    error_log("Erreur lors de la gestion des paramètres : " . $e->getMessage());
    $stats = [];
}

// Valeurs par défaut pour les statistiques
$default_stats = [
    'annees_experience' => date('Y') - $entreprise['annee_creation'],
    'projets_reussis' => getNombreProjets(),
    'clients_satisfaits' => 50,
    'logo' => 'uploads/generaux/logo.png',
    'favicon' => 'uploads/generaux/favicon.ico'
];

// Fusionner les statistiques de la base de données avec les valeurs par défaut
$stats = array_merge($default_stats, $stats ?: []);

// S'assurer que le logo est toujours défini et existe
if (empty($stats['logo']) || !file_exists(__DIR__ . '/' . $stats['logo'])) {
    $stats['logo'] = 'uploads/generaux/logo.png';
}

// Valeurs de l'entreprise
$valeurs = [
    [
        'titre' => 'Innovation',
        'description' => 'Nous repoussons constamment les limites de la technologie pour créer des solutions d\'avant-garde.',
        'icone' => 'fas fa-lightbulb'
    ],
    [
        'titre' => 'Excellence',
        'description' => 'Nous nous engageons à fournir un travail de la plus haute qualité dans chaque projet.',
        'icone' => 'fas fa-star'
    ],
    [
        'titre' => 'Collaboration',
        'description' => 'Nous croyons en la force du travail d\'équipe et de la communication transparente.',
        'icone' => 'fas fa-hands-helping'
    ]
];

// Timeline de l'entreprise
$timeline = [
    [
        'annee' => '2020',
        'evenement' => 'Création de VorTech'
    ],
    [
        'annee' => '2021',
        'evenement' => 'Premier grand projet international'
    ],
    [
        'annee' => '2022',
        'evenement' => 'Ouverture du bureau à Paris'
    ],
    [
        'annee' => '2023',
        'evenement' => 'Certification ISO 9001'
    ]
];

// Navigation principale
$navigation = [
    [
        'titre' => 'Accueil',
        'lien' => 'index.php'
    ],
    [
        'titre' => 'À propos',
        'lien' => 'about.php'
    ],
    [
        'titre' => 'Portfolio',
        'lien' => 'portfolio.php'
    ],
    [
        'titre' => 'Équipe',
        'lien' => 'equipe.php'
    ],
    [
        'titre' => 'Contact',
        'lien' => 'contact.php'
    ]
]; 