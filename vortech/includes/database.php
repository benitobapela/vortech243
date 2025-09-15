<?php
/**
 * Fichier contenant toutes les fonctions de gestion de la base de données
 */

// ==================== FONCTIONS DE GESTION DES ADMINISTRATEURS ====================

/**
 * Crée un nouvel administrateur
 */
function createAdmin($pdo, $nom, $email, $mot_de_passe, $role = 'admin') {
    $stmt = $pdo->prepare("INSERT INTO administrateurs (nom, email, mot_de_passe, role) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$nom, $email, password_hash($mot_de_passe, PASSWORD_DEFAULT), $role]);
}

/**
 * Met à jour un administrateur
 */
function updateAdmin($pdo, $id, $nom, $email, $role = null) {
    $sql = "UPDATE administrateurs SET nom = ?, email = ?";
    $params = [$nom, $email];
    
    if ($role !== null) {
        $sql .= ", role = ?";
        $params[] = $role;
    }
    
    $sql .= " WHERE id = ?";
    $params[] = $id;
    
    $stmt = $pdo->prepare($sql);
    return $stmt->execute($params);
}

/**
 * Change le mot de passe d'un administrateur
 */
function changeAdminPassword($pdo, $id, $nouveau_mot_de_passe) {
    $stmt = $pdo->prepare("UPDATE administrateurs SET mot_de_passe = ? WHERE id = ?");
    return $stmt->execute([password_hash($nouveau_mot_de_passe, PASSWORD_DEFAULT), $id]);
}

/**
 * Désactive un administrateur
 */
function disableAdmin($pdo, $id) {
    $stmt = $pdo->prepare("UPDATE administrateurs SET actif = 0 WHERE id = ?");
    return $stmt->execute([$id]);
}

// ==================== FONCTIONS DE GESTION DU PORTFOLIO ====================

/**
 * Crée un nouveau projet
 */
function createProject($pdo, $titre, $description, $categorie, $technologies, $images, $url) {
    $stmt = $pdo->prepare("INSERT INTO portfolio (titre, description, categorie, technologies, images, url) VALUES (?, ?, ?, ?, ?, ?)");
    return $stmt->execute([$titre, $description, $categorie, $technologies, $images, $url]);
}

/**
 * Met à jour un projet
 */
function updateProject($pdo, $id, $titre, $description, $categorie, $technologies, $images, $url) {
    $stmt = $pdo->prepare("UPDATE portfolio SET titre = ?, description = ?, categorie = ?, technologies = ?, images = ?, url = ? WHERE id = ?");
    return $stmt->execute([$titre, $description, $categorie, $technologies, $images, $url, $id]);
}

/**
 * Désactive un projet
 */
function disableProject($pdo, $id) {
    $stmt = $pdo->prepare("UPDATE portfolio SET actif = 0 WHERE id = ?");
    return $stmt->execute([$id]);
}

// ==================== FONCTIONS DE GESTION DE L'ÉQUIPE ====================

/**
 * Crée un nouveau membre d'équipe
 */
function createTeamMember($pdo, $nom, $poste, $description, $photo, $ordre) {
    $stmt = $pdo->prepare("INSERT INTO equipe (nom, poste, description, photo, ordre) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$nom, $poste, $description, $photo, $ordre]);
}

/**
 * Met à jour un membre d'équipe
 */
function updateTeamMember($pdo, $id, $nom, $poste, $description, $photo, $ordre) {
    $stmt = $pdo->prepare("UPDATE equipe SET nom = ?, poste = ?, description = ?, photo = ?, ordre = ? WHERE id = ?");
    return $stmt->execute([$nom, $poste, $description, $photo, $ordre, $id]);
}

/**
 * Désactive un membre d'équipe
 */
function disableTeamMember($pdo, $id) {
    $stmt = $pdo->prepare("UPDATE equipe SET actif = 0 WHERE id = ?");
    return $stmt->execute([$id]);
}

// ==================== FONCTIONS DE GESTION DES VALEURS ====================

/**
 * Crée une nouvelle valeur
 */
function createValue($pdo, $titre, $description, $icone, $ordre) {
    $stmt = $pdo->prepare("INSERT INTO valeurs (titre, description, icone, ordre) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$titre, $description, $icone, $ordre]);
}

/**
 * Met à jour une valeur
 */
function updateValue($pdo, $id, $titre, $description, $icone, $ordre) {
    $stmt = $pdo->prepare("UPDATE valeurs SET titre = ?, description = ?, icone = ?, ordre = ? WHERE id = ?");
    return $stmt->execute([$titre, $description, $icone, $ordre, $id]);
}

// ==================== FONCTIONS DE GESTION DE LA TIMELINE ====================

/**
 * Crée un nouvel événement de timeline
 */
function createTimelineEvent($pdo, $titre, $description, $annee, $ordre) {
    $stmt = $pdo->prepare("INSERT INTO timeline (titre, description, annee, ordre) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$titre, $description, $annee, $ordre]);
}

/**
 * Met à jour un événement de timeline
 */
function updateTimelineEvent($pdo, $id, $titre, $description, $annee, $ordre) {
    $stmt = $pdo->prepare("UPDATE timeline SET titre = ?, description = ?, annee = ?, ordre = ? WHERE id = ?");
    return $stmt->execute([$titre, $description, $annee, $ordre, $id]);
}

// ==================== FONCTIONS DE GESTION DES PARAMÈTRES ====================

/**
 * Met à jour les paramètres généraux
 */
function updateGeneralParameters($pdo, $nom_site, $description_site, $email_contact, $telephone, $adresse) {
    $params = [
        'site_name' => $nom_site,
        'site_description' => $description_site,
        'contact_email' => $email_contact,
        'contact_phone' => $telephone,
        'contact_address' => $adresse
    ];
    
    foreach ($params as $cle => $valeur) {
        $stmt = $pdo->prepare("UPDATE configurations SET valeur = ? WHERE cle = ?");
        $stmt->execute([$valeur, $cle]);
    }
    return true;
}

/**
 * Met à jour les réseaux sociaux
 */
function updateSocialNetworks($pdo, $facebook, $twitter, $linkedin, $instagram) {
    $params = [
        'social_facebook' => $facebook,
        'social_twitter' => $twitter,
        'social_linkedin' => $linkedin,
        'social_instagram' => $instagram
    ];
    
    foreach ($params as $cle => $valeur) {
        $stmt = $pdo->prepare("UPDATE configurations SET valeur = ? WHERE cle = ?");
        $stmt->execute([$valeur, $cle]);
    }
    return true;
}

/**
 * Met à jour les paramètres SEO
 */
function updateSeoParameters($pdo, $titre_seo, $description_seo, $mots_cles) {
    $params = [
        'seo_title' => $titre_seo,
        'seo_description' => $description_seo,
        'seo_keywords' => $mots_cles
    ];
    
    foreach ($params as $cle => $valeur) {
        $stmt = $pdo->prepare("UPDATE configurations SET valeur = ? WHERE cle = ?");
        $stmt->execute([$valeur, $cle]);
    }
    return true;
}

/**
 * Met à jour les paramètres de maintenance
 */
function updateMaintenanceParameters($pdo, $mode_maintenance, $message_maintenance) {
    $params = [
        'mode_maintenance' => $mode_maintenance,
        'message_maintenance' => $message_maintenance
    ];
    
    foreach ($params as $cle => $valeur) {
        $stmt = $pdo->prepare("UPDATE configurations SET valeur = ? WHERE cle = ?");
        $stmt->execute([$valeur, $cle]);
    }
    return true;
}

// ==================== FONCTIONS DE GESTION DES CONTACTS ====================

/**
 * Enregistre un nouveau message de contact
 */
function saveContactMessage($pdo, $nom, $email, $sujet, $message) {
    $stmt = $pdo->prepare("INSERT INTO contacts (nom, email, sujet, message) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$nom, $email, $sujet, $message]);
}

/**
 * Marque un message comme lu
 */
function markContactAsRead($pdo, $id) {
    $stmt = $pdo->prepare("UPDATE contacts SET lu = 1 WHERE id = ?");
    return $stmt->execute([$id]);
}

/**
 * Supprime un message de contact
 */
function deleteContact($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM contacts WHERE id = ?");
    return $stmt->execute([$id]);
} 