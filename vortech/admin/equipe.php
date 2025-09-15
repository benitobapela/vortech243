<?php
// Inclusion du fichier d'initialisation
require_once 'includes/init.php';

// Définition des variables de page
$pageTitle = "Gestion de l'équipe";
$currentPage = 'equipe';

// Récupération du membre pour modification si nécessaire
$membre = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM equipe WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $membre = $stmt->fetch();
    
    if (!$membre) {
        setFlashMessage("Membre non trouvé.", "danger");
        header("Location: equipe.php");
        exit;
    }
}

// Si un message de succès est présent, on redirige vers la page sans paramètre edit
if (isset($_SESSION['success']) && isset($_GET['edit'])) {
    header("Location: equipe.php");
    exit;
}

// Récupération de tous les membres de l'équipe
$stmt = $pdo->query("SELECT id, nom, poste, email, photo, ordre, actif FROM equipe ORDER BY ordre ASC, nom ASC");
$membres = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Log des données récupérées
echo '<div style="background: #f8f9fa; padding: 15px; margin: 15px; border: 1px solid #ddd; border-radius: 5px;">';
echo '<h4>Debug Information</h4>';
echo '<pre>';
echo "=== DEBUG EQUIPE ===\n";
echo "Nombre de membres trouvés : " . count($membres) . "\n\n";

foreach ($membres as $membre) {
    echo "Membre ID: " . $membre['id'] . "\n";
    echo "Nom: " . $membre['nom'] . "\n";
    echo "Photo: " . $membre['photo'] . "\n";
    echo "Chemin complet: " . __DIR__ . "/uploads/equipe/" . $membre['photo'] . "\n";
    echo "Le fichier existe: " . (file_exists(__DIR__ . "/uploads/equipe/" . $membre['photo']) ? "OUI" : "NON") . "\n";
    echo "Permissions du dossier uploads: " . substr(sprintf('%o', fileperms(__DIR__ . "/uploads/equipe")), -4) . "\n";
    echo "---\n\n";
}
echo '</pre>';
echo '</div>';

// Debug des données
echo "<!-- Debug des données : ";
print_r($membres);
echo " -->";

// Affichage des messages flash
$flash = getFlashMessage();
if ($flash) {
    echo '<div class="alert alert-' . e($flash['type']) . ' alert-dismissible fade show" role="alert">';
    echo e($flash['message']);
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
    echo '</div>';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de l'équipe - Administration</title>
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
        .membre-photo {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
        }
        .preview-photo {
            max-width: 200px;
            height: auto;
            border-radius: 10px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <main>
        <div class="container-fluid">
            <h1 class="mb-4">Gestion de l'équipe</h1>

            <?php if ($success): ?>
            <div class="alert alert-success" role="alert">
                <?php echo $success; ?>
            </div>
            <?php endif; ?>

            <?php if ($error): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
            <?php endif; ?>

            <!-- Barre latérale -->
            <nav class="sidebar">
                <div class="sidebar-sticky">
                    <div class="px-3 py-4">
                        <h5 class="text-white mb-4">Administration</h5>
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="index.php">
                                    <i class="fas fa-home"></i> Tableau de bord
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="portfolio.php">
                                    <i class="fas fa-briefcase"></i> Portfolio
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="equipe.php">
                                    <i class="fas fa-users"></i> Équipe
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="services.php">
                                    <i class="fas fa-cogs"></i> Services
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="parametres.php">
                                    <i class="fas fa-cog"></i> Paramètres
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="administrateurs.php">
                                    <i class="fas fa-user-shield"></i> Administrateurs
                                </a>
                            </li>
                            <li class="nav-item mt-4">
                                <a class="nav-link" href="logout.php">
                                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Barre de navigation supérieure -->
            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="container-fluid">
                    <span class="navbar-brand">Équipe</span>
                </div>
            </nav>

            <!-- Formulaire d'ajout/modification -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0"><?php echo $membre ? 'Modifier un membre' : 'Ajouter un membre'; ?></h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="enregistrement.php" enctype="multipart/form-data">
                        <input type="hidden" name="type" value="equipe">
                        <?php if ($membre): ?>
                        <input type="hidden" name="id" value="<?php echo $membre['id']; ?>">
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nom" class="form-label">Nom *</label>
                                    <input type="text" class="form-control" id="nom" name="nom" required
                                           value="<?php echo $membre ? e($membre['nom']) : ''; ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="poste" class="form-label">Poste *</label>
                                    <input type="text" class="form-control" id="poste" name="poste" required
                                           value="<?php echo $membre ? e($membre['poste']) : ''; ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="email" name="email" required
                                           value="<?php echo $membre ? e($membre['email']) : ''; ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="photo" class="form-label">Photo</label>
                                    <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                                    <?php if ($membre && $membre['photo']): ?>
                                    <div class="mt-2">
                                        <img src="/VorTech/admin/uploads/equipe/<?php echo e($membre['photo']); ?>" class="preview-photo" alt="Photo actuelle">
                                        <p class="text-muted small">Photo actuelle</p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="4"><?php echo $membre ? e($membre['description']) : ''; ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="linkedin" class="form-label">LinkedIn</label>
                                    <input type="url" class="form-control" id="linkedin" name="linkedin"
                                           value="<?php echo $membre ? e($membre['linkedin']) : ''; ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="facebook" class="form-label">Facebook</label>
                                    <input type="url" class="form-control" id="facebook" name="facebook"
                                           value="<?php echo $membre ? e($membre['facebook']) : ''; ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="instagram" class="form-label">Instagram</label>
                                    <input type="url" class="form-control" id="instagram" name="instagram"
                                           value="<?php echo $membre ? e($membre['instagram']) : ''; ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="whatsapp" class="form-label">WhatsApp</label>
                                    <input type="url" class="form-control" id="whatsapp" name="whatsapp"
                                           value="<?php echo $membre ? e($membre['whatsapp']) : ''; ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="twitter" class="form-label">Twitter</label>
                                    <input type="url" class="form-control" id="twitter" name="twitter"
                                           value="<?php echo $membre ? e($membre['twitter']) : ''; ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="ordre" class="form-label">Ordre d'affichage</label>
                                    <input type="number" class="form-control" id="ordre" name="ordre"
                                           value="<?php echo $membre ? e($membre['ordre']) : '0'; ?>">
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="actif" name="actif"
                                               <?php echo (!$membre || $membre['actif']) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="actif">Actif</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <a href="equipe.php" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-primary">
                                <?php echo $membre ? 'Modifier' : 'Ajouter'; ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Liste des membres -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Liste des membres</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Photo</th>
                                    <th>Nom</th>
                                    <th>Poste</th>
                                    <th>Email</th>
                                    <th>Ordre</th>
                                    <th>Actif</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($membres as $membre): ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($membre['photo'])): ?>
                                            <img src="uploads/equipe/<?php echo e($membre['photo']); ?>" 
                                                 alt="<?php echo e($membre['nom']); ?>" 
                                                 class="img-thumbnail" 
                                                 style="max-width: 100px;">
                                        <?php else: ?>
                                            <img src="images/default-avatar.jpg" 
                                                 alt="Image par défaut" 
                                                 class="img-thumbnail" 
                                                 style="max-width: 100px;">
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($membre['nom']); ?></td>
                                    <td><?php echo e($membre['poste']); ?></td>
                                    <td><?php echo e($membre['email']); ?></td>
                                    <td><?php echo e($membre['ordre']); ?></td>
                                    <td>
                                        <span class="badge <?php echo $membre['actif'] ? 'bg-success' : 'bg-danger'; ?>">
                                            <?php echo $membre['actif'] ? 'Actif' : 'Inactif'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="?edit=<?php echo $membre['id']; ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="enregistrement.php?delete=<?php echo $membre['id']; ?>" 
                                               class="btn btn-sm btn-danger"
                                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce membre ?');">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Preview de l'image
        document.getElementById('photo')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.querySelector('.preview-photo') || document.createElement('img');
                    preview.src = e.target.result;
                    preview.classList.add('preview-photo', 'mt-2');
                    if (!document.querySelector('.preview-photo')) {
                        document.getElementById('photo').parentNode.appendChild(preview);
                    }
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html> 