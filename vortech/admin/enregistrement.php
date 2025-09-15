<?php
require_once 'includes/init.php';

// Vérification de l'authentification
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

// Fonction d'upload d'image
function uploadImage($file, $folder) {
    // Définir le dossier d'upload en fonction du type
    $upload_dir = "uploads/$folder/";
    
    // Vérifier si le dossier existe, sinon le créer
    if (!file_exists($upload_dir)) {
        if (!mkdir($upload_dir, 0777, true)) {
            throw new Exception("Impossible de créer le dossier d'upload : $upload_dir");
        }
    }
    
    // Vérifier le type de fichier
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'ico'];
    
    if (!in_array($file_extension, $allowed_extensions)) {
        throw new Exception("Type de fichier non autorisé. Formats acceptés : " . implode(', ', $allowed_extensions));
    }
    
    // Vérifier la taille du fichier (max 5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        throw new Exception("Le fichier est trop volumineux (max 5MB)");
    }
    
    // Générer un nom de fichier unique
    $new_filename = uniqid() . '.' . $file_extension;
    
    // Déplacer le fichier
    if (!move_uploaded_file($file['tmp_name'], $upload_dir . $new_filename)) {
        throw new Exception("Erreur lors de l'upload du fichier");
    }
    
    // Retourner le nom du fichier pour la base de données
    return $new_filename;
}

// Fonction pour mettre à jour les chemins existants
function updateImagePath($path, $folder) {
    if (empty($path)) return $path;
    
    // Retourner uniquement le nom du fichier
    return basename($path);
}

// Traitement des formulaires
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'] ?? '';
    
    try {
        switch ($type) {
            case 'generaux':
                // Vérifier si un enregistrement existe déjà
                $check = $pdo->query("SELECT COUNT(*) FROM parametres_generaux")->fetchColumn();
                
                // Préparation des données
                $data = [
                    'site_title' => cleanInput($_POST['site_titre']),
                    'site_description' => cleanInput($_POST['site_description']),
                    'site_keywords' => cleanInput($_POST['site_keywords'] ?? ''),
                    'site_email' => cleanInput($_POST['contact_email'])
                ];
                
                // Gestion du logo
                if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                    $logo = uploadImage($_FILES['logo'], 'generaux');
                    $data['logo'] = $logo;
                } else if (isset($_POST['logo'])) {
                    $data['logo'] = updateImagePath($_POST['logo'], 'generaux');
                }
                
                // Gestion du favicon
                if (isset($_FILES['favicon']) && $_FILES['favicon']['error'] === UPLOAD_ERR_OK) {
                    $favicon = uploadImage($_FILES['favicon'], 'generaux');
                    $data['favicon'] = $favicon;
                } else if (isset($_POST['favicon'])) {
                    $data['favicon'] = updateImagePath($_POST['favicon'], 'generaux');
                }
                
                if ($check == 0) {
                    // Premier enregistrement
                    $sql = "INSERT INTO parametres_generaux 
                        (site_title, site_description, site_keywords, site_email, logo, favicon, created_at, updated_at) 
                        VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        $data['site_title'],
                        $data['site_description'],
                        $data['site_keywords'],
                        $data['site_email'],
                        $data['logo'] ?? null,
                        $data['favicon'] ?? null
                    ]);
                } else {
                    // Mise à jour de l'enregistrement existant
                    $sql = "UPDATE parametres_generaux SET 
                        site_title = ?, 
                        site_description = ?, 
                        site_keywords = ?,
                        site_email = ?,
                        updated_at = NOW()";
                    
                    // Ajouter les champs logo et favicon seulement s'ils sont présents
                    if (isset($data['logo'])) {
                        $sql .= ", logo = ?";
                    }
                    if (isset($data['favicon'])) {
                        $sql .= ", favicon = ?";
                    }
                    
                    $sql .= " WHERE id = 1";
                    
                    $stmt = $pdo->prepare($sql);
                    $params = [
                        $data['site_title'],
                        $data['site_description'],
                        $data['site_keywords'],
                        $data['site_email']
                    ];
                    
                    if (isset($data['logo'])) {
                        $params[] = $data['logo'];
                    }
                    if (isset($data['favicon'])) {
                        $params[] = $data['favicon'];
                    }
                    
                    $stmt->execute($params);
                }
                
                setFlashMessage("success", "Paramètres généraux mis à jour avec succès.");
                header('Location: parametres.php');
                exit;
                break;

            case 'valeurs':
                // Traitement du formulaire des valeurs
                $titre = cleanInput($_POST['titre']);
                $description = cleanInput($_POST['description']);
                $icone = cleanInput($_POST['icone']);
                $ordre = (int)$_POST['ordre'];
                
                if (isset($_POST['id'])) {
                    // Mise à jour
                    $sql = "UPDATE valeurs SET titre = ?, description = ?, icone = ?, ordre = ? WHERE id = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$titre, $description, $icone, $ordre, $_POST['id']]);
                    setFlashMessage("success", "La valeur a été mise à jour avec succès.");
                } else {
                    // Création
                    $sql = "INSERT INTO valeurs (titre, description, icone, ordre) VALUES (?, ?, ?, ?)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$titre, $description, $icone, $ordre]);
                    setFlashMessage("success", "La valeur a été créée avec succès.");
                }
                header('Location: valeurs.php');
                break;
                
            case 'equipe':
                // Traitement du formulaire de l'équipe
                $nom = cleanInput($_POST['nom']);
                $poste = cleanInput($_POST['poste']);
                $description = cleanInput($_POST['description']);
                $email = cleanInput($_POST['email']);
                $linkedin = cleanInput($_POST['linkedin'] ?? '');
                $facebook = cleanInput($_POST['facebook'] ?? '');
                $instagram = cleanInput($_POST['instagram'] ?? '');
                $whatsapp = cleanInput($_POST['whatsapp'] ?? '');
                $twitter = cleanInput($_POST['twitter'] ?? '');
                $ordre = (int)$_POST['ordre'];
                $actif = isset($_POST['actif']) ? 1 : 0;

                // Gestion de l'image
                $photo = null;
                if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                    try {
                        $photo = uploadImage($_FILES['photo'], 'equipe');
                    } catch (Exception $e) {
                        setFlashMessage("danger", $e->getMessage());
                        header('Location: equipe.php');
                        exit;
                    }
                } else if (isset($_POST['photo'])) {
                    $photo = updateImagePath($_POST['photo'], 'equipe');
                }

                if (isset($_POST['id'])) {
                    // Mise à jour
                    $sql = "UPDATE equipe SET 
                        nom = ?, 
                        poste = ?, 
                        description = ?, 
                        email = ?, 
                        linkedin = ?,
                        facebook = ?,
                        instagram = ?,
                        whatsapp = ?,
                        twitter = ?,
                        photo = ?, 
                        ordre = ?, 
                        actif = ? 
                        WHERE id = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        $nom, 
                        $poste, 
                        $description, 
                        $email, 
                        $linkedin,
                        $facebook,
                        $instagram,
                        $whatsapp,
                        $twitter,
                        $photo, 
                        $ordre, 
                        $actif, 
                        $_POST['id']
                    ]);
                    setFlashMessage("success", "Le membre a été mis à jour avec succès.");
                } else {
                    // Création
                    $sql = "INSERT INTO equipe (
                        nom, 
                        poste, 
                        description, 
                        email, 
                        linkedin,
                        facebook,
                        instagram,
                        whatsapp,
                        twitter,
                        photo, 
                        ordre, 
                        actif
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        $nom, 
                        $poste, 
                        $description, 
                        $email, 
                        $linkedin,
                        $facebook,
                        $instagram,
                        $whatsapp,
                        $twitter,
                        $photo, 
                        $ordre, 
                        $actif
                    ]);
                    setFlashMessage("success", "Le membre a été ajouté avec succès.");
                }
                header('Location: equipe.php');
                break;

            case 'portfolio':
                // Traitement du formulaire du portfolio
                $titre = cleanInput($_POST['titre']);
                $description = cleanInput($_POST['description']);
                $categorie = cleanInput($_POST['categorie']);
                $technologies = cleanInput($_POST['technologies']);
                $url = cleanInput($_POST['url'] ?? '');
                $client = cleanInput($_POST['client'] ?? '');
                $date_realisation = cleanInput($_POST['date_realisation']);
                $actif = isset($_POST['actif']) ? 1 : 0;
                $featured = isset($_POST['featured']) ? 1 : 0;

                // Gestion des images
                $images = [];
                
                // Conserver les images existantes si on est en mode modification
                if (isset($_POST['anciennes_images'])) {
                    $anciennes_images = explode(',', $_POST['anciennes_images']);
                    foreach ($anciennes_images as $image) {
                        $image = trim($image);
                        if (!empty($image)) {
                            $images[] = updateImagePath($image, 'portfolio');
                        }
                    }
                }
                
                // Ajouter les nouvelles images
                if (isset($_FILES['images'])) {
                    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                        if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
                            $file = [
                                'name' => $_FILES['images']['name'][$key],
                                'type' => $_FILES['images']['type'][$key],
                                'tmp_name' => $tmp_name,
                                'error' => $_FILES['images']['error'][$key],
                                'size' => $_FILES['images']['size'][$key]
                            ];
                            $uploaded_image = uploadImage($file, 'portfolio');
                            if ($uploaded_image) {
                                $images[] = $uploaded_image;
                            }
                        }
                    }
                }
                
                $images_str = implode(',', array_filter($images));

                if (isset($_POST['id'])) {
                    // Mise à jour
                        $sql = "UPDATE portfolio SET 
                            titre = ?, 
                            description = ?, 
                            categorie = ?, 
                            technologies = ?, 
                            images = ?, 
                            url = ?, 
                            client = ?,
                            date_realisation = ?,
                            actif = ?,
                            featured = ?
                                WHERE id = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        $titre, 
                        $description, 
                        $categorie, 
                        $technologies, 
                        $images_str, 
                        $url,
                        $client,
                        $date_realisation,
                        $actif,
                        $featured,
                        $_POST['id']
                    ]);
                    setFlashMessage("success", "Le projet a été mis à jour avec succès.");
                } else {
                    // Création
                    $sql = "INSERT INTO portfolio (
                            titre, description, categorie, technologies, 
                            images, url, client, date_realisation, actif, featured
                        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        $titre, 
                        $description, 
                        $categorie, 
                        $technologies, 
                        $images_str, 
                        $url,
                        $client,
                        $date_realisation,
                        $actif,
                        $featured
                    ]);
                    setFlashMessage("success", "Le projet a été ajouté avec succès.");
                }
                header('Location: portfolio.php');
                break;

            case 'timeline':
                // Traitement du formulaire de la timeline
                $evenement = cleanInput($_POST['evenement']);
                $description = cleanInput($_POST['description']);
                $annee = (int)$_POST['annee'];
                $ordre = (int)$_POST['ordre'];

                    if (isset($_POST['id'])) {
                    // Mise à jour
                    $sql = "UPDATE timeline SET evenement = ?, description = ?, annee = ?, ordre = ? WHERE id = ?";
                        $stmt = $pdo->prepare($sql);
                    $stmt->execute([$evenement, $description, $annee, $ordre, $_POST['id']]);
                    setFlashMessage("success", "Événement enregistré avec succès.");
                    } else {
                    // Création
                    $sql = "INSERT INTO timeline (evenement, description, annee, ordre) VALUES (?, ?, ?, ?)";
                        $stmt = $pdo->prepare($sql);
                    $stmt->execute([$evenement, $description, $annee, $ordre]);
                    setFlashMessage("success", "Événement enregistré avec succès.");
                }
                header('Location: parcours.php');
                break;
                
            case 'parametres':
                // Traitement des paramètres
                $stmt = $pdo->prepare("UPDATE parametres SET valeur = ? WHERE id = ?");
                
                // Liste des champs à traiter
                $champs = [
                    'site_titre', 'site_description',
                    'contact_email', 'contact_telephone', 'contact_adresse',
                    'contact_ville', 'contact_code_postal', 'contact_pays',
                    'social_facebook', 'social_telegram', 'social_linkedin',
                    'social_instagram', 'social_youtube', 'social_whatsapp'
                ];
                
                foreach ($champs as $champ) {
                    if (isset($_POST[$champ])) {
                        $stmt->execute([$_POST[$champ], $champ]);
                    }
                }
                
                // Traitement des images
                $uploadDir = '../uploads/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                // Traitement du logo
                if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                    $logoFile = $_FILES['logo'];
                    $logoExt = strtolower(pathinfo($logoFile['name'], PATHINFO_EXTENSION));
                    $logoNewName = 'logo_' . time() . '.' . $logoExt;
                    $logoPath = $uploadDir . $logoNewName;
                    
                    if (move_uploaded_file($logoFile['tmp_name'], $logoPath)) {
                        $stmt->execute(['uploads/' . $logoNewName, 'logo']);
                    }
                }
                
                // Traitement du favicon
                if (isset($_FILES['favicon']) && $_FILES['favicon']['error'] === UPLOAD_ERR_OK) {
                    $faviconFile = $_FILES['favicon'];
                    $faviconExt = strtolower(pathinfo($faviconFile['name'], PATHINFO_EXTENSION));
                    $faviconNewName = 'favicon_' . time() . '.' . $faviconExt;
                    $faviconPath = $uploadDir . $faviconNewName;
                    
                    if (move_uploaded_file($faviconFile['tmp_name'], $faviconPath)) {
                        $stmt->execute(['uploads/' . $faviconNewName, 'favicon']);
                    }
                }
                
                setFlashMessage('success', 'Les paramètres ont été mis à jour avec succès.');
                break;
                
            case 'maintenance':
                // Vérifier si un enregistrement existe déjà
                $check = $pdo->query("SELECT COUNT(*) FROM parametres_maintenance")->fetchColumn();
                
                if ($check == 0) {
                    // Premier enregistrement
                    $stmt = $pdo->prepare("INSERT INTO parametres_maintenance 
                        (mode_maintenance, message_maintenance, created_at, updated_at) 
                        VALUES (?, ?, NOW(), NOW())");
                } else {
                    // Mise à jour de l'enregistrement existant
                    $stmt = $pdo->prepare("UPDATE parametres_maintenance SET 
                        mode_maintenance = ?, 
                        message_maintenance = ?,
                        updated_at = NOW()
                        WHERE id = 1");
                }
                
                $stmt->execute([
                    isset($_POST['maintenance_mode']) ? 1 : 0,
                    cleanInput($_POST['maintenance_message'])
                ]);
                setFlashMessage("success", "Paramètres de maintenance mis à jour avec succès.");
                header('Location: parametres.php');
                exit;

            case 'seo':
                // Vérifier si un enregistrement existe déjà
                $check = $pdo->query("SELECT COUNT(*) FROM parametres_seo")->fetchColumn();
                
                if ($check == 0) {
                    // Premier enregistrement
                    $stmt = $pdo->prepare("INSERT INTO parametres_seo 
                        (meta_title, meta_description, meta_keywords, google_analytics, created_at, updated_at) 
                        VALUES (?, ?, ?, ?, NOW(), NOW())");
                } else {
                    // Mise à jour de l'enregistrement existant
                    $stmt = $pdo->prepare("UPDATE parametres_seo SET 
                        meta_title = ?, 
                        meta_description = ?, 
                        meta_keywords = ?, 
                        google_analytics = ?,
                        updated_at = NOW()
                        WHERE id = 1");
                }
                
                $stmt->execute([
                    cleanInput($_POST['meta_title']),
                    cleanInput($_POST['meta_description']),
                    cleanInput($_POST['meta_keywords']),
                    cleanInput($_POST['google_analytics'])
                ]);
                setFlashMessage("success", "Paramètres SEO mis à jour avec succès.");
                header('Location: parametres.php');
                exit;

            case 'social':
                try {
                    // Vérifier si un enregistrement existe déjà
                    $check = $pdo->query("SELECT COUNT(*) FROM reseaux_sociaux")->fetchColumn();
                    
                    if ($check == 0) {
                        // Premier enregistrement
                        $stmt = $pdo->prepare("INSERT INTO reseaux_sociaux 
                            (facebook, telegram, linkedin, instagram, youtube, whatsapp, created_at, updated_at) 
                            VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
                    } else {
                        // Mise à jour de l'enregistrement existant
                        $stmt = $pdo->prepare("UPDATE reseaux_sociaux SET 
                            facebook = ?,
                            telegram = ?,
                            linkedin = ?,
                            instagram = ?,
                            youtube = ?,
                            whatsapp = ?,
                            updated_at = NOW()
                            WHERE id = 1");
                    }
                    
                    $values = [
                        cleanInput($_POST['facebook_url']),
                        cleanInput($_POST['telegram_url']),
                        cleanInput($_POST['linkedin_url']),
                        cleanInput($_POST['instagram_url']),
                        cleanInput($_POST['youtube_url']),
                        cleanInput($_POST['whatsapp_url'])
                    ];
                    
                    $result = $stmt->execute($values);
                    
                    if ($result) {
                        setFlashMessage("success", "Réseaux sociaux mis à jour avec succès.");
                    } else {
                        setFlashMessage("error", "Erreur lors de la mise à jour des réseaux sociaux.");
                    }
                } catch (PDOException $e) {
                    error_log("Erreur SQL : " . $e->getMessage());
                    setFlashMessage("error", "Erreur lors de la mise à jour des réseaux sociaux : " . $e->getMessage());
                }
                header('Location: parametres.php');
                exit;
                
            case 'services':
                // Vérification si c'est une modification ou un ajout
                $id = $_POST['id'] ?? null;
                
                // Préparation des données
                $titre = $_POST['titre'] ?? '';
                $description = $_POST['description'] ?? '';
                $icone = $_POST['icone'] ?? '';
                $ordre = $_POST['ordre'] ?? 0;
                $actif = isset($_POST['actif']) ? 1 : 0;
                
                // Validation des données
                if (empty($titre) || empty($description)) {
                    throw new Exception('Le titre et la description sont obligatoires.');
                }
                
                if ($id) {
                    // Modification d'un service existant
                    $stmt = $pdo->prepare("UPDATE services SET titre = ?, description = ?, icone = ?, ordre = ?, actif = ? WHERE id = ?");
                    $stmt->execute([$titre, $description, $icone, $ordre, $actif, $id]);
                    $message = 'Le service a été modifié avec succès.';
                } else {
                    // Ajout d'un nouveau service
                    $stmt = $pdo->prepare("INSERT INTO services (titre, description, icone, ordre, actif) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$titre, $description, $icone, $ordre, $actif]);
                    $message = 'Le service a été ajouté avec succès.';
                }
                
                // Traitement de l'image si présente
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = '../uploads/services/';
                    if (!file_exists($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    
                    $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                    
                    if (!in_array($fileExtension, $allowedExtensions)) {
                        throw new Exception('Format d\'image non autorisé. Formats acceptés : ' . implode(', ', $allowedExtensions));
                    }
                    
                    $fileName = uniqid('service_') . '.' . $fileExtension;
                    $uploadFile = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                        $imagePath = 'uploads/services/' . $fileName;
                        
                        // Mise à jour de l'image dans la base de données
                        $stmt = $pdo->prepare("UPDATE services SET image = ? WHERE id = ?");
                        $stmt->execute([$imagePath, $id ?? $pdo->lastInsertId()]);
                    } else {
                        throw new Exception('Erreur lors du téléchargement de l\'image.');
                    }
                }
                
                setFlashMessage('success', $message);
                header('Location: services.php');
                exit;

            default:
                throw new Exception("Type de formulaire non reconnu.");
        }
    } catch (Exception $e) {
        $_SESSION['upload_error'] = $e->getMessage();
        header('Location: parametres.php');
        exit;
    }
    exit;
}

// Gestion des suppressions
if (isset($_GET['delete']) && isset($_GET['type'])) {
    $id = (int)$_GET['delete'];
    $type = $_GET['type'];
    
    try {
        switch ($type) {
            case 'valeurs':
                $stmt = $pdo->prepare("DELETE FROM valeurs WHERE id = ?");
                $stmt->execute([$id]);
                setFlashMessage("success", "La valeur a été supprimée avec succès.");
                header('Location: valeurs.php');
                break;
                
            case 'equipe':
                // Récupérer la photo avant suppression
                $stmt = $pdo->prepare("SELECT photo FROM equipe WHERE id = ?");
                $stmt->execute([$id]);
                $photo = $stmt->fetchColumn();
                
                // Supprimer l'enregistrement
                $stmt = $pdo->prepare("DELETE FROM equipe WHERE id = ?");
                $stmt->execute([$id]);
                
                    // Supprimer la photo si elle existe
                if ($photo) {
                    $photo_path = "../public/uploads/equipe/" . $photo;
                    if (file_exists($photo_path)) {
                        unlink($photo_path);
                    }
                }
                
                setFlashMessage("success", "Le membre a été supprimé avec succès.");
                header('Location: equipe.php');
                break;

            case 'portfolio':
                // Récupérer les images avant suppression
                $stmt = $pdo->prepare("SELECT images FROM portfolio WHERE id = ?");
                $stmt->execute([$id]);
                $images = $stmt->fetchColumn();
                
                // Supprimer l'enregistrement
                $stmt = $pdo->prepare("DELETE FROM portfolio WHERE id = ?");
                $stmt->execute([$id]);
                
                    // Supprimer les images si elles existent
                    if ($images) {
                    $image_array = explode(',', $images);
                    foreach ($image_array as $image) {
                            $image_path = "../public/uploads/portfolio/" . trim($image);
                            if (file_exists($image_path)) {
                            unlink($image_path);
                        }
                    }
                }
                
                setFlashMessage("success", "Le projet a été supprimé avec succès.");
                header('Location: portfolio.php');
                break;

            case 'timeline':
                $stmt = $pdo->prepare("DELETE FROM timeline WHERE id = ?");
                $stmt->execute([$id]);
                setFlashMessage("success", "Événement supprimé avec succès.");
                header('Location: parcours.php');
                break;

            default:
                throw new Exception("Type de suppression non reconnu.");
        }
    } catch (Exception $e) {
        setFlashMessage("error", $e->getMessage());
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
    exit;
} 

// Redirection si accès direct au fichier
header('Location: index.php');
exit;
