<?php
session_start();

// Vérification de la connexion
if(!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Inclusion de la configuration
require_once '../public/config.php';

// Fonction pour sécuriser les sorties
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Fonction pour vérifier si l'utilisateur est connecté
function isLoggedIn() {
    return isset($_SESSION['admin_id']);
}

// Fonction pour obtenir le nom de l'administrateur connecté
function getAdminName() {
    return $_SESSION['admin_nom'] ?? 'Administrateur';
}

// Fonction pour déconnecter l'administrateur
function logout() {
    session_destroy();
    header('Location: login.php');
    exit;
}

// Traitement de la déconnexion
if(isset($_GET['logout'])) {
    logout();
}
?> 