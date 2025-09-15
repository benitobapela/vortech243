<?php
// Fonction pour échapper les caractères spéciaux
if (!function_exists('e')) {
    function e($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}

// Fonction pour nettoyer les entrées
if (!function_exists('cleanInput')) {
    function cleanInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}

// Fonction pour obtenir le nombre de projets réalisés
if (!function_exists('getNombreProjetsRealises')) {
    function getNombreProjetsRealises() {
        global $pdo;
        $stmt = $pdo->query("SELECT COUNT(*) FROM portfolio WHERE actif = 1");
        return $stmt->fetchColumn();
    }
}

// Fonction pour formater le numéro de téléphone
if (!function_exists('formatTelephone')) {
    function formatTelephone($numero) {
        // Format: +33 (0)1 23 45 67 89
        $numero = preg_replace('/[^0-9+]/', '', $numero);
        if (strlen($numero) === 12 && substr($numero, 0, 3) === '+33') {
            return substr($numero, 0, 3) . ' (0)' . 
                   substr($numero, 3, 1) . ' ' . 
                   substr($numero, 4, 2) . ' ' . 
                   substr($numero, 6, 2) . ' ' . 
                   substr($numero, 8, 2) . ' ' . 
                   substr($numero, 10, 2);
        }
        return $numero;
    }
}

// Fonction pour obtenir l'adresse complète
if (!function_exists('getAdresseComplete')) {
    function getAdresseComplete() {
        global $entreprise;
        return $entreprise['contact']['adresse'] . "\n" .
               $entreprise['contact']['code_postal'] . ' ' . $entreprise['contact']['ville'] . "\n" .
               $entreprise['contact']['pays'];
    }
}

// Fonction pour vérifier si une page est active
if (!function_exists('isPageActive')) {
    function isPageActive($page) {
        $current_page = basename($_SERVER['PHP_SELF']);
        return $current_page === $page;
    }
}

// Fonction pour calculer l'âge de l'entreprise
if (!function_exists('getAgeEntreprise')) {
    function getAgeEntreprise() {
        global $entreprise;
        return date('Y') - $entreprise['annee_creation'];
    }
}

// Fonction pour calculer les années d'expérience depuis la création
if (!function_exists('getAnneesExperience')) {
    function getAnneesExperience() {
        global $pdo;
        try {
            $stmt = $pdo->query("SELECT annee FROM timeline WHERE evenement LIKE '%création%' OR evenement LIKE '%creation%' ORDER BY annee ASC LIMIT 1");
            $creation = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($creation) {
                $annee_creation = intval($creation['annee']);
                $annee_actuelle = date('Y');
                return $annee_actuelle - $annee_creation;
            }
            return 1; // Valeur par défaut si pas de date de création trouvée
        } catch (PDOException $e) {
            error_log("Erreur lors du calcul des années d'expérience : " . $e->getMessage());
            return 1;
        }
    }
}

// Fonction pour compter le nombre de projets
if (!function_exists('getNombreProjets')) {
    function getNombreProjets() {
        global $pdo;
        try {
            $stmt = $pdo->query("SELECT COUNT(*) FROM portfolio");
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Erreur lors du comptage des projets : " . $e->getMessage());
            return 0;
        }
    }
}

// Fonction pour compter le nombre de clients satisfaits
if (!function_exists('getNombreClientsSatisfaits')) {
    function getNombreClientsSatisfaits() {
        global $pdo;
        try {
            $stmt = $pdo->query("SELECT COUNT(DISTINCT client_id) FROM portfolio");
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Erreur lors du comptage des clients : " . $e->getMessage());
            return 0;
        }
    }
} 