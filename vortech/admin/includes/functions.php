/**
 * Gère l'upload d'une image
 * @param array $file Le fichier uploadé ($_FILES['nom_du_champ'])
 * @param string $type Le type d'image (par exemple, 'profile', 'post', etc.)
 * @return string Le nom du fichier généré
 * @throws Exception Si l'upload échoue
 */
function uploadImage($file, $type) {
    $upload_dir = 'uploads/' . $type . '/';
    $file_name = basename($file['name']);
    $target_path = $upload_dir . $file_name;
    
    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        // Retourner uniquement le nom du fichier
        return $file_name;
    }
    return null;
}

function updateImagePath($old_path, $type) {
    // Si le chemin contient déjà le dossier uploads, ne garder que le nom du fichier
    if (strpos($old_path, 'uploads/') !== false) {
        return basename($old_path);
    }
    return $old_path;
}

/**
 * Vérifier les erreurs d'upload
 * @param array $file Le fichier uploadé ($_FILES['nom_du_champ'])
 * @return bool True si les erreurs sont présentes, False sinon
 */
function checkUploadErrors($file) {
    return $file['error'] !== UPLOAD_ERR_OK;
}

/**
 * Vérifier le type MIME
 * @param array $file Le fichier uploadé ($_FILES['nom_du_champ'])
 * @return bool True si le type MIME est autorisé, False sinon
 */
function checkMimeType($file) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml', 'image/x-icon'];
    return in_array($file['type'], $allowed_types);
}

/**
 * Vérifier la taille (max 5MB)
 * @param array $file Le fichier uploadé ($_FILES['nom_du_champ'])
 * @return bool True si la taille est inférieure ou égale à 5MB, False sinon
 */
function checkFileSize($file) {
    return $file['size'] <= 5 * 1024 * 1024;
}

/**
 * Créer le dossier s'il n'existe pas
 * @param string $dossier Le sous-dossier dans uploads/ où stocker l'image
 */
function createUploadDir($dossier) {
    $upload_dir = __DIR__ . "/../../uploads/$dossier";
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
}

/**
 * Générer un nom de fichier unique
 * @param string $extension L'extension du fichier
 * @return string Le nom de fichier unique
 */
function generateUniqueFilename($extension) {
    return uniqid() . '.' . $extension;
}

/**
 * Déplacer le fichier
 * @param array $file Le fichier uploadé ($_FILES['nom_du_champ'])
 * @param string $destination Le chemin de destination du fichier
 * @return bool True si le déplacement est réussi, False sinon
 */
function moveFile($file, $destination) {
    return move_uploaded_file($file['tmp_name'], $destination);
}

/**
 * Vérifier le chemin de l'image
 * @param string $old_path Le chemin de l'image
 * @return string Le chemin de l'image
 */
function verifyImagePath($old_path) {
    // Si le chemin contient déjà le dossier uploads, ne garder que le nom du fichier
    if (strpos($old_path, 'uploads/') !== false) {
        return basename($old_path);
    }
    return $old_path;
} 