<?php
require_once 'includes/init.php';

// Définition des variables de page
$pageTitle = "Gestion des Services";
$currentPage = 'services';

// Récupération du service pour modification si nécessaire
$service = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $service = $stmt->fetch();
    
    if (!$service) {
        setFlashMessage("Service non trouvé.", "danger");
        header("Location: services.php");
        exit;
    }
}

// Si un message de succès est présent, on redirige vers la page sans paramètre edit
if (isset($_SESSION['success']) && isset($_GET['edit'])) {
    header("Location: services.php");
    exit;
}

// Récupération des services
$stmt = $pdo->query("SELECT * FROM services ORDER BY ordre ASC, titre ASC");
$services = $stmt->fetchAll();

// Affichage des messages flash
$flash = getFlashMessage();
if ($flash) {
    echo '<div class="alert alert-' . e($flash['type']) . ' alert-dismissible fade show" role="alert">';
    echo e($flash['message']);
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
    echo '</div>';
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
    <title>Gestion des Services - Administration</title>
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
        .service-icon {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #2563eb;
        }
        .preview-image {
            max-width: 200px;
            height: auto;
            margin-top: 10px;
            border-radius: 10px;
        }
        .card {
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,.05);
            border-radius: 10px;
        }
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid rgba(0,0,0,.05);
            padding: 1rem;
        }
        .table th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }
        .btn-group .btn {
            padding: .25rem .5rem;
        }
        .badge {
            padding: .5em .75em;
        }
    </style>
</head>
<body>
    <main>
        <div class="container-fluid">
            <h1 class="mb-4">Gestion des Services</h1>

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
                                <a class="nav-link" href="equipe.php">
                                    <i class="fas fa-users"></i> Équipe
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="services.php">
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
                    <span class="navbar-brand">Services</span>
                </div>
            </nav>

            <!-- Formulaire d'ajout/modification -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0"><?php echo $service ? 'Modifier un service' : 'Ajouter un service'; ?></h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="enregistrement.php" enctype="multipart/form-data">
                        <input type="hidden" name="type" value="services">
                        <?php if ($service): ?>
                        <input type="hidden" name="id" value="<?php echo $service['id']; ?>">
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="titre" class="form-label">Titre *</label>
                                    <input type="text" class="form-control" id="titre" name="titre" required
                                           value="<?php echo $service ? e($service['titre']) : ''; ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="icone" class="form-label">Icône (Font Awesome) *</label>
                                    <input type="text" class="form-control" id="icone" name="icone" required
                                           value="<?php echo $service ? e($service['icone']) : ''; ?>"
                                           placeholder="fas fa-cog">
                                    <small class="text-muted">Exemple : fas fa-cog, fab fa-android, etc.</small>
                                </div>

                                <div class="mb-3">
                                    <label for="image" class="form-label">Image</label>
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                    <?php if ($service && $service['image']): ?>
                                    <div class="mt-2">
                                        <img src="../<?php echo e($service['image']); ?>" class="preview-image" alt="Image actuelle">
                                        <p class="text-muted small">Image actuelle</p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description *</label>
                                    <textarea class="form-control" id="description" name="description" rows="4" required><?php echo $service ? e($service['description']) : ''; ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="ordre" class="form-label">Ordre d'affichage</label>
                                    <input type="number" class="form-control" id="ordre" name="ordre"
                                           value="<?php echo $service ? e($service['ordre']) : '0'; ?>">
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="actif" name="actif"
                                               <?php echo (!$service || $service['actif']) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="actif">Service actif</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <a href="services.php" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-primary">
                                <?php echo $service ? 'Modifier' : 'Ajouter'; ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Liste des services -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Liste des services</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Icône</th>
                                    <th>Titre</th>
                                    <th>Description</th>
                                    <th>Ordre</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($services)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center">Aucun service trouvé</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($services as $service): ?>
                                        <tr>
                                            <td>
                                                <div class="service-icon">
                                                    <i class="<?php echo e($service['icone']); ?>"></i>
                                                </div>
                                            </td>
                                            <td><?php echo e($service['titre']); ?></td>
                                            <td><?php echo e(substr($service['description'], 0, 100)) . '...'; ?></td>
                                            <td><?php echo e($service['ordre']); ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo $service['actif'] ? 'success' : 'danger'; ?>">
                                                    <?php echo $service['actif'] ? 'Actif' : 'Inactif'; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="?edit=<?php echo $service['id']; ?>" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="enregistrement.php?delete=<?php echo $service['id']; ?>&type=services" 
                                                       class="btn btn-sm btn-danger"
                                                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce service ?');">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
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
        document.getElementById('image')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.querySelector('.preview-image') || document.createElement('img');
                    preview.src = e.target.result;
                    preview.classList.add('preview-image', 'mt-2');
                    if (!document.querySelector('.preview-image')) {
                        document.getElementById('image').parentNode.appendChild(preview);
                    }
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html> 