<?php
// Paramètres de connexion à la base de données
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'vortech');

// Création de la connexion
try {
    $db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Fonction pour sécuriser les entrées
function secure($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Fonction pour gérer l'upload d'images
function uploadImage($file, $destination) {
    $target_dir = "../uploads/" . $destination . "/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $imageFileType = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $target_file = $target_dir . uniqid() . '.' . $imageFileType;
    
    // Vérifier si c'est une vraie image
    $check = getimagesize($file["tmp_name"]);
    if($check === false) {
        throw new Exception("Le fichier n'est pas une image.");
    }
    
    // Vérifier la taille (max 5MB)
    if ($file["size"] > 5000000) {
        throw new Exception("Le fichier est trop volumineux.");
    }
    
    // Autoriser certains formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        throw new Exception("Seuls les fichiers JPG, JPEG, PNG & GIF sont autorisés.");
    }
    
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return basename($target_file);
    } else {
        throw new Exception("Erreur lors de l'upload du fichier.");
    }
}
?> 