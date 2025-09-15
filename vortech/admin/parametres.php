<?php
// Inclusion du fichier d'initialisation
require_once 'includes/init.php';

// Définition des variables de page
$pageTitle = "Paramètres";
$currentPage = 'parametres';

// Initialisation des variables
$success = '';
$error = '';

try {
    // Récupération des paramètres généraux
    $stmt = $pdo->query("SELECT * FROM parametres_generaux WHERE id = 1");
    $parametres_generaux = $stmt->fetch(PDO::FETCH_ASSOC);

    // Récupération des paramètres de maintenance
    $stmt = $pdo->query("SELECT * FROM parametres_maintenance WHERE id = 1");
    $parametres_maintenance = $stmt->fetch(PDO::FETCH_ASSOC);

    // Récupération des paramètres de sécurité
    $stmt = $pdo->query("SELECT * FROM parametres_securite WHERE id = 1");
    $parametres_securite = $stmt->fetch(PDO::FETCH_ASSOC);

    // Récupération des paramètres SEO
    $stmt = $pdo->query("SELECT * FROM parametres_seo WHERE id = 1");
    $parametres_seo = $stmt->fetch(PDO::FETCH_ASSOC);

    // Récupération des réseaux sociaux
    $stmt = $pdo->query("SELECT * FROM reseaux_sociaux ORDER BY id ASC");
    $reseaux_sociaux = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $error = "Erreur lors de l'accès à la base de données : " . $e->getMessage();
}

// Récupération des messages flash
$flash = getFlashMessage();
if ($flash) {
    if ($flash['type'] === 'success') {
        $success = $flash['message'];
    } else {
        $error = $flash['message'];
    }
}

// Inclusion des fichiers de template
include 'includes/header.php';
include 'includes/sidebar.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres - Administration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 48px 0 0;
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
            background: #2563eb;
            width: 250px;
        }
        .sidebar-sticky {
            position: relative;
            top: 0;
            height: calc(100vh - 48px);
            padding-top: .5rem;
            overflow-x: hidden;
            overflow-y: auto;
        }
        main {
            margin-left: 250px;
            padding: 48px 30px;
        }
        .navbar {
            position: fixed;
            top: 0;
            right: 0;
            left: 250px;
            z-index: 99;
            height: 48px;
            background: #fff !important;
            box-shadow: 0 1px 3px rgba(0,0,0,.1);
        }
        .param-group {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,.1);
        }
        .param-group h3 {
            color: #333;
            font-size: 1.2rem;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .param-item {
            margin-bottom: 15px;
        }
        .param-item label {
            font-weight: 500;
            color: #555;
        }
        .param-item .form-text {
            color: #6c757d;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <main>
        <div class="container-fluid">
            <h1 class="mb-4">Paramètres</h1>

            <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $success; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <!-- Logs d'erreur d'upload -->
            <?php
            // Afficher les erreurs d'upload stockées dans la session
            if (isset($_SESSION['upload_error'])) {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
                echo $_SESSION['upload_error'];
                echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                echo '</div>';
                // Supprimer l'erreur de la session après l'avoir affichée
                unset($_SESSION['upload_error']);
            }

            $upload_errors = [
                UPLOAD_ERR_INI_SIZE => "Le fichier dépasse la taille maximale autorisée par PHP (upload_max_filesize dans php.ini)",
                UPLOAD_ERR_FORM_SIZE => "Le fichier dépasse la taille maximale autorisée par le formulaire (MAX_FILE_SIZE)",
                UPLOAD_ERR_PARTIAL => "Le fichier n'a été que partiellement uploadé",
                UPLOAD_ERR_NO_FILE => "Aucun fichier n'a été uploadé",
                UPLOAD_ERR_NO_TMP_DIR => "Dossier temporaire manquant",
                UPLOAD_ERR_CANT_WRITE => "Échec de l'écriture du fichier sur le disque",
                UPLOAD_ERR_EXTENSION => "Une extension PHP a arrêté l'upload du fichier"
            ];

            if (isset($_FILES['logo']) && $_FILES['logo']['error'] !== UPLOAD_ERR_OK) {
                $error_message = $upload_errors[$_FILES['logo']['error']] ?? "Erreur inconnue lors de l'upload du logo";
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
                echo '<strong>Erreur Logo :</strong> ' . $error_message;
                echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                echo '</div>';
            }

            if (isset($_FILES['favicon']) && $_FILES['favicon']['error'] !== UPLOAD_ERR_OK) {
                $error_message = $upload_errors[$_FILES['favicon']['error']] ?? "Erreur inconnue lors de l'upload du favicon";
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
                echo '<strong>Erreur Favicon :</strong> ' . $error_message;
                echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                echo '</div>';
            }
            ?>

            <!-- Onglets -->
            <ul class="nav nav-tabs mb-4" id="parametresTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="generaux-tab" data-bs-toggle="tab" data-bs-target="#generaux" type="button" role="tab">
                        <i class="fas fa-cog me-2"></i>Généraux
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="maintenance-tab" data-bs-toggle="tab" data-bs-target="#maintenance" type="button" role="tab">
                        <i class="fas fa-tools me-2"></i>Maintenance
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="securite-tab" data-bs-toggle="tab" data-bs-target="#securite" type="button" role="tab">
                        <i class="fas fa-shield-alt me-2"></i>Sécurité
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo" type="button" role="tab">
                        <i class="fas fa-search me-2"></i>SEO
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="social-tab" data-bs-toggle="tab" data-bs-target="#social" type="button" role="tab">
                        <i class="fas fa-share-alt me-2"></i>Réseaux sociaux
                    </button>
                </li>
            </ul>

            <!-- Contenu des onglets -->
            <div class="tab-content" id="parametresTabsContent">
                <!-- Paramètres généraux -->
                <div class="tab-pane fade show active" id="generaux" role="tabpanel">
                    <form method="POST" action="enregistrement.php" class="param-group" enctype="multipart/form-data">
                        <input type="hidden" name="type" value="generaux">
                        <h3>Paramètres généraux</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="param-item">
                                    <label for="site_titre" class="form-label">Titre du site</label>
                                    <input type="text" class="form-control" id="site_titre" name="site_titre" 
                                           value="<?php echo e($parametres_generaux['site_title'] ?? ''); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="param-item">
                                    <label for="site_description" class="form-label">Description du site</label>
                                    <textarea class="form-control" id="site_description" name="site_description" rows="3"><?php echo e($parametres_generaux['site_description'] ?? ''); ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="param-item">
                                    <label for="contact_email" class="form-label">
                                        <i class="fas fa-envelope text-primary me-2"></i>Email
                                    </label>
                                    <input type="email" class="form-control" id="contact_email" name="contact_email" 
                                           value="<?php echo e($parametres_generaux['site_email'] ?? ''); ?>"
                                           placeholder="contact@votresite.com">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="param-item">
                                    <label for="site_keywords" class="form-label">
                                        <i class="fas fa-tags text-primary me-2"></i>Mots-clés
                                    </label>
                                    <input type="text" class="form-control" id="site_keywords" name="site_keywords" 
                                           value="<?php echo e($parametres_generaux['site_keywords'] ?? ''); ?>"
                                           placeholder="Séparez les mots-clés par des virgules">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="param-item">
                                    <label for="logo" class="form-label">
                                        <i class="fas fa-image text-primary me-2"></i>Logo
                                    </label>
                                    <?php if (!empty($parametres_generaux['logo'])): ?>
                                        <div class="mb-2">
                                            <img src="../<?php echo e($parametres_generaux['logo']); ?>" alt="Logo actuel" style="max-height: 50px;">
                                        </div>
                                    <?php endif; ?>
                                    <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                                    <small class="form-text">Format recommandé : PNG ou SVG, max 500KB</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="param-item">
                                    <label for="favicon" class="form-label">
                                        <i class="fas fa-image text-primary me-2"></i>Favicon
                                    </label>
                                    <?php if (!empty($parametres_generaux['favicon'])): ?>
                                        <div class="mb-2">
                                            <img src="../<?php echo e($parametres_generaux['favicon']); ?>" alt="Favicon actuel" style="max-height: 32px;">
                                        </div>
                                    <?php endif; ?>
                                    <input type="file" class="form-control" id="favicon" name="favicon" accept="image/x-icon,image/png">
                                    <small class="form-text">Format recommandé : ICO ou PNG, max 100KB</small>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Paramètres de maintenance -->
                <div class="tab-pane fade" id="maintenance" role="tabpanel">
                    <form method="POST" action="enregistrement.php" class="param-group">
                        <input type="hidden" name="type" value="maintenance">
                        <h3>Paramètres de maintenance</h3>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="param-item">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="maintenance_mode" name="maintenance_mode" 
                                               <?php echo ($parametres_maintenance['maintenance_mode'] ?? 0) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="maintenance_mode">Mode maintenance</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="param-item">
                                    <label for="maintenance_message" class="form-label">Message de maintenance</label>
                                    <textarea class="form-control" id="maintenance_message" name="maintenance_message" rows="3"><?php echo e($parametres_maintenance['maintenance_message'] ?? ''); ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Enregistrer les paramètres de maintenance
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Paramètres de sécurité -->
                <div class="tab-pane fade" id="securite" role="tabpanel">
                    <form method="POST" action="enregistrement.php" class="param-group">
                        <input type="hidden" name="type" value="securite">
                        <h3>Paramètres de sécurité</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="param-item">
                                    <label for="max_login_attempts" class="form-label">Nombre maximum de tentatives de connexion</label>
                                    <input type="number" class="form-control" id="max_login_attempts" name="max_login_attempts" 
                                           value="<?php echo e($parametres_securite['max_login_attempts'] ?? 3); ?>" min="1" max="10">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="param-item">
                                    <label for="session_timeout" class="form-label">Délai d'expiration de session (minutes)</label>
                                    <input type="number" class="form-control" id="session_timeout" name="session_timeout" 
                                           value="<?php echo e($parametres_securite['session_timeout'] ?? 30); ?>" min="5" max="120">
                                </div>
                            </div>
                        </div>
                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Enregistrer les paramètres de sécurité
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Paramètres SEO -->
                <div class="tab-pane fade" id="seo" role="tabpanel">
                    <form method="POST" action="enregistrement.php" class="param-group">
                        <input type="hidden" name="type" value="seo">
                        <h3>Paramètres SEO</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="param-item">
                                    <label for="meta_title" class="form-label">Meta Title</label>
                                    <input type="text" class="form-control" id="meta_title" name="meta_title" 
                                           value="<?php echo e($parametres_seo['meta_title'] ?? ''); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="param-item">
                                    <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                    <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" 
                                           value="<?php echo e($parametres_seo['meta_keywords'] ?? ''); ?>"
                                           placeholder="mot-clé1, mot-clé2, mot-clé3">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="param-item">
                                    <label for="meta_description" class="form-label">Meta Description</label>
                                    <textarea class="form-control" id="meta_description" name="meta_description" rows="3"><?php echo e($parametres_seo['meta_description'] ?? ''); ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="param-item">
                                    <label for="google_analytics" class="form-label">Code Google Analytics</label>
                                    <textarea class="form-control" id="google_analytics" name="google_analytics" rows="3"><?php echo e($parametres_seo['google_analytics'] ?? ''); ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Enregistrer les paramètres SEO
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Réseaux sociaux -->
                <div class="tab-pane fade" id="social" role="tabpanel">
                    <form method="POST" action="enregistrement.php" class="param-group">
                        <input type="hidden" name="type" value="social">
                        <h3>Réseaux sociaux</h3>
                        
                        <!-- Facebook -->
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <div class="param-item">
                                    <label class="form-label">
                                        <i class="fab fa-facebook text-primary me-2"></i>Facebook
                                    </label>
                                    <input type="url" class="form-control" name="facebook_url" 
                                           value="<?php echo e($reseaux_sociaux[0]['url'] ?? ''); ?>"
                                           placeholder="https://facebook.com/votre-page">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="param-item">
                                    <div class="form-check form-switch mt-4">
                                        <input class="form-check-input" type="checkbox" name="facebook_actif" 
                                               <?php echo ($reseaux_sociaux[0]['actif'] ?? 1) ? 'checked' : ''; ?>>
                                        <label class="form-check-label">Actif</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Telegram -->
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <div class="param-item">
                                    <label class="form-label">
                                        <i class="fab fa-telegram text-info me-2"></i>Telegram
                                    </label>
                                    <input type="url" class="form-control" name="telegram_url" 
                                           value="<?php echo e($reseaux_sociaux[1]['url'] ?? ''); ?>"
                                           placeholder="https://t.me/votre-compte">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="param-item">
                                    <div class="form-check form-switch mt-4">
                                        <input class="form-check-input" type="checkbox" name="telegram_actif" 
                                               <?php echo ($reseaux_sociaux[1]['actif'] ?? 1) ? 'checked' : ''; ?>>
                                        <label class="form-check-label">Actif</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- LinkedIn -->
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <div class="param-item">
                                    <label class="form-label">
                                        <i class="fab fa-linkedin text-primary me-2"></i>LinkedIn
                                    </label>
                                    <input type="url" class="form-control" name="linkedin_url" 
                                           value="<?php echo e($reseaux_sociaux[2]['url'] ?? ''); ?>"
                                           placeholder="https://linkedin.com/in/votre-profil">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="param-item">
                                    <div class="form-check form-switch mt-4">
                                        <input class="form-check-input" type="checkbox" name="linkedin_actif" 
                                               <?php echo ($reseaux_sociaux[2]['actif'] ?? 1) ? 'checked' : ''; ?>>
                                        <label class="form-check-label">Actif</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Instagram -->
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <div class="param-item">
                                    <label class="form-label">
                                        <i class="fab fa-instagram text-danger me-2"></i>Instagram
                                    </label>
                                    <input type="url" class="form-control" name="instagram_url" 
                                           value="<?php echo e($reseaux_sociaux[3]['url'] ?? ''); ?>"
                                           placeholder="https://instagram.com/votre-compte">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="param-item">
                                    <div class="form-check form-switch mt-4">
                                        <input class="form-check-input" type="checkbox" name="instagram_actif" 
                                               <?php echo ($reseaux_sociaux[3]['actif'] ?? 1) ? 'checked' : ''; ?>>
                                        <label class="form-check-label">Actif</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- YouTube -->
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <div class="param-item">
                                    <label class="form-label">
                                        <i class="fab fa-youtube text-danger me-2"></i>YouTube
                                    </label>
                                    <input type="url" class="form-control" name="youtube_url" 
                                           value="<?php echo e($reseaux_sociaux[4]['url'] ?? ''); ?>"
                                           placeholder="https://youtube.com/votre-chaine">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="param-item">
                                    <div class="form-check form-switch mt-4">
                                        <input class="form-check-input" type="checkbox" name="youtube_actif" 
                                               <?php echo ($reseaux_sociaux[4]['actif'] ?? 1) ? 'checked' : ''; ?>>
                                        <label class="form-check-label">Actif</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- WhatsApp -->
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <div class="param-item">
                                    <label class="form-label">
                                        <i class="fab fa-whatsapp text-success me-2"></i>WhatsApp
                                    </label>
                                    <input type="tel" class="form-control" name="whatsapp_url" 
                                           value="<?php echo e($reseaux_sociaux[5]['url'] ?? ''); ?>"
                                           placeholder="+33612345678">
                                    <small class="form-text">Format : +33 suivi du numéro sans espaces</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="param-item">
                                    <div class="form-check form-switch mt-4">
                                        <input class="form-check-input" type="checkbox" name="whatsapp_actif" 
                                               <?php echo ($reseaux_sociaux[5]['actif'] ?? 1) ? 'checked' : ''; ?>>
                                        <label class="form-check-label">Actif</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Enregistrer les réseaux sociaux
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 