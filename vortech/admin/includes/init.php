<?php
// Démarrer la session seulement si elle n'est pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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

// Définition de l'environnement
define('ENVIRONMENT', 'production');

// Définition du chemin racine
define('ROOT_PATH', dirname(dirname(__DIR__)));

// Vérification des chemins
$config_path = ROOT_PATH . '/public/config.php';
if (!file_exists($config_path)) {
    die("Erreur : Le fichier de configuration n'existe pas à l'emplacement : " . $config_path);
}

// Configuration de base
require_once $config_path;

// Vérification de la connexion à la base de données
if (!isset($pdo)) {
    die("Erreur : La connexion à la base de données n'a pas été établie.");
}

// Fonction pour vérifier si l'admin est connecté
function isLoggedIn() {
    return isset($_SESSION['admin_id']);
}

// Fonction pour rediriger si non connecté
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

// Fonction pour échapper les caractères spéciaux
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Fonction pour gérer les messages flash
function setFlashMessage($message, $type = 'success') {
    $_SESSION['flash'] = [
        'message' => $message,
        'type' => $type
    ];
}

function getFlashMessage() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

// Vérifier la connexion pour toutes les pages sauf login.php
$current_page = basename($_SERVER['PHP_SELF']);
if ($current_page !== 'login.php') {
    requireLogin();
}

// Vérification de l'expiration de session (1 heure)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 3600)) {
    session_unset();
    session_destroy();
    header('Location: login.php?expired=1');
    exit;
}

// Mise à jour du timestamp de dernière activité
$_SESSION['last_activity'] = time();

// Fonction pour obtenir le nom de la page courante
function getCurrentPage() {
    $script = $_SERVER['SCRIPT_NAME'];
    return basename($script, '.php');
}

// Définition des variables globales
if (!isset($pageTitle)) {
    $pageTitle = "Administration";
}

if (!isset($currentPage)) {
    $currentPage = getCurrentPage();
}

// Configuration de la timezone
date_default_timezone_set('Europe/Paris');

// Gestion des erreurs en mode développement
if (ENVIRONMENT === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Fonction pour vérifier les permissions d'accès
function checkPermission($permission) {
    // À implémenter : système de permissions
    return true;
}

// Fonction pour journaliser les actions
function logAction($action, $details = '') {
    global $pdo;
    if (isset($_SESSION['admin_id'])) {
        $admin_id = $_SESSION['admin_id'];
        $sql = "INSERT INTO admin_logs (admin_id, action, details, ip_address) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$admin_id, $action, $details, $_SERVER['REMOTE_ADDR']]);
    }
}

// Fonction pour nettoyer les entrées
function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Fonction pour mettre à jour le hash du mot de passe
function updatePasswordHash($email, $password) {
    global $pdo;
    try {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE admins SET password = ? WHERE email = ?");
        return $stmt->execute([$hashedPassword, $email]);
    } catch (PDOException $e) {
        error_log("Erreur lors de la mise à jour du hash : " . $e->getMessage());
        return false;
    }
}

// Fonction pour vérifier les identifiants
function verifyCredentials($email, $password) {
    global $pdo;
    $debug_logs = [];
    
    try {
        // Vérifier si l'email existe
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
        $stmt->execute([$email]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$admin) {
            $debug_logs[] = "Email non trouvé : " . $email;
            $_SESSION['debug_logs'] = $debug_logs;
            return false;
        }
        
        // Collecter les informations de débogage
        $debug_logs[] = "Tentative de connexion pour : " . $email;
        $debug_logs[] = "Hash stocké : " . $admin['password'];
        $debug_logs[] = "Longueur du hash : " . strlen($admin['password']);
        
        // Vérifier si le hash est au format bcrypt
        if (strlen($admin['password']) === 64) {
            $debug_logs[] = "Le hash n'est pas au format bcrypt, mise à jour nécessaire";
            // Mettre à jour le hash
            if (updatePasswordHash($email, $password)) {
                $debug_logs[] = "Hash mis à jour avec succès";
                // Récupérer l'admin mis à jour
                $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
                $stmt->execute([$email]);
                $admin = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $debug_logs[] = "Échec de la mise à jour du hash";
            }
        }
        
        // Vérifier le mot de passe
        $verify = password_verify($password, $admin['password']);
        $debug_logs[] = "Résultat de password_verify : " . ($verify ? "true" : "false");
        
        if ($verify) {
            $debug_logs[] = "Connexion réussie pour l'admin : " . $email;
            $_SESSION['debug_logs'] = $debug_logs;
            return $admin;
        } else {
            $debug_logs[] = "Mot de passe incorrect pour : " . $email;
            $_SESSION['debug_logs'] = $debug_logs;
            return false;
        }
    } catch (PDOException $e) {
        $debug_logs[] = "Erreur PDO : " . $e->getMessage();
        $_SESSION['debug_logs'] = $debug_logs;
        return false;
    }
}

// Fonction pour créer un nouvel admin
function createAdmin($email, $password, $nom) {
    global $pdo;
    
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("INSERT INTO admins (email, password, nom) VALUES (?, ?, ?)");
    return $stmt->execute([$email, $hashedPassword, $nom]);
}

// Fonction pour changer le mot de passe
function changePassword($adminId, $newPassword) {
    global $pdo;
    
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("UPDATE admins SET password = ? WHERE id = ?");
    return $stmt->execute([$hashedPassword, $adminId]);
}

// Fonction pour déconnecter l'admin
function logout() {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit;
} 