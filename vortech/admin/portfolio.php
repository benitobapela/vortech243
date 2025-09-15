<?php
// Inclusion du fichier d'initialisation
require_once 'includes/init.php';

// Définition des variables de page
$pageTitle = "Gestion du Portfolio";
$currentPage = 'portfolio';

// Récupération du projet pour modification si nécessaire
$projet = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM portfolio WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $projet = $stmt->fetch();
    
    if (!$projet) {
        setFlashMessage("Projet non trouvé.", "danger");
        header("Location: portfolio.php");
        exit;
    }
}

// Si un message de succès est présent, on redirige vers la page sans paramètre edit
if (isset($_SESSION['success']) && isset($_GET['edit'])) {
    header("Location: portfolio.php");
    exit;
}

// Récupération des projets
$stmt = $pdo->query("SELECT * FROM portfolio ORDER BY date_realisation DESC");
$projets = $stmt->fetchAll();

// Inclusion des fichiers de template
include 'includes/header.php';
include 'includes/sidebar.php';

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
    <title>Gestion du Portfolio - Administration</title>
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
        .projet-image {
            width: 100px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
        }
        .preview-image {
            max-width: 150px;
            height: auto;
            border-radius: 5px;
            margin: 5px;
        }
        .images-preview {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <main>
        <div class="container-fluid">
            <h1 class="mb-4">Gestion du Portfolio</h1>

            <!-- Formulaire d'ajout/modification -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0"><?php echo $projet ? 'Modifier un projet' : 'Ajouter un projet'; ?></h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="enregistrement.php" enctype="multipart/form-data">
                        <input type="hidden" name="type" value="portfolio">
                        <?php if ($projet): ?>
                        <input type="hidden" name="id" value="<?php echo $projet['id']; ?>">
                        <input type="hidden" name="anciennes_images" value="<?php echo e($projet['images']); ?>">
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="titre" class="form-label">Titre *</label>
                                    <input type="text" class="form-control" id="titre" name="titre" required
                                           value="<?php echo $projet ? e($projet['titre']) : ''; ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="categorie" class="form-label">Catégorie *</label>
                                    <input type="text" class="form-control" id="categorie" name="categorie" required
                                           value="<?php echo $projet ? e($projet['categorie']) : ''; ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="client" class="form-label">Client</label>
                                    <input type="text" class="form-control" id="client" name="client"
                                           value="<?php echo $projet ? e($projet['client']) : ''; ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="date_realisation" class="form-label">Date de réalisation *</label>
                                    <input type="date" class="form-control" id="date_realisation" name="date_realisation" required
                                           value="<?php echo $projet ? e($projet['date_realisation']) : ''; ?>">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description *</label>
                                    <textarea class="form-control" id="description" name="description" rows="4" required><?php echo $projet ? e($projet['description']) : ''; ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="technologies" class="form-label">Technologies utilisées *</label>
                                    <input type="text" class="form-control" id="technologies" name="technologies" required
                                           value="<?php echo $projet ? e($projet['technologies']) : ''; ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="url" class="form-label">URL du projet</label>
                                    <input type="url" class="form-control" id="url" name="url"
                                           value="<?php echo $projet ? e($projet['url']) : ''; ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="images" class="form-label">Images</label>
                                    <input type="file" class="form-control" id="images" name="images[]" accept="image/*" multiple>
                                    <small class="text-muted">Vous pouvez sélectionner plusieurs images</small>
                                    <?php if ($projet && $projet['images']): ?>
                                    <div class="images-preview mt-2">
                                        <p class="text-muted small">Images actuelles :</p>
                                        <?php 
                                        $images = explode(',', $projet['images']);
                                        foreach ($images as $image): 
                                            if (trim($image)):
                                        ?>
                                        <div class="image-container position-relative d-inline-block me-2 mb-2">
                                            <img src="../public/<?php echo e(trim($image)); ?>" 
                                                 class="preview-image" 
                                                 alt="Image du projet">
                                            <input type="checkbox" name="supprimer_images[]" value="<?php echo e(trim($image)); ?>" 
                                                   class="position-absolute top-0 end-0 m-1" 
                                                   title="Cocher pour supprimer">
                                        </div>
                                        <?php 
                                            endif;
                                        endforeach; 
                                        ?>
                                    </div>
                                    <?php endif; ?>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="actif" name="actif" value="1"
                                               <?php echo (!$projet || $projet['actif']) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="actif">Actif</label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="featured" name="featured" value="1"
                                               <?php echo ($projet && $projet['featured']) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="featured">Projet mis en avant</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <a href="portfolio.php" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-primary">
                                <?php echo $projet ? 'Modifier' : 'Ajouter'; ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Liste des projets -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Liste des projets</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Images</th>
                                    <th>Titre</th>
                                    <th>Catégorie</th>
                                    <th>Client</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($projets as $projet): ?>
                                <tr>
                                    <td>
                                        <?php 
                                        if ($projet['images']) {
                                            $images = explode(',', $projet['images']);
                                            if (!empty($images[0])) {
                                                echo '<img src="../public/' . e(trim($images[0])) . '" class="projet-image" alt="' . e($projet['titre']) . '">';
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo e($projet['titre']); ?></td>
                                    <td><?php echo e($projet['categorie']); ?></td>
                                    <td><?php echo e($projet['client']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($projet['date_realisation'])); ?></td>
                                    <td>
                                        <span class="badge <?php echo $projet['actif'] ? 'bg-success' : 'bg-danger'; ?>">
                                            <?php echo $projet['actif'] ? 'Actif' : 'Inactif'; ?>
                                        </span>
                                        <?php if ($projet['featured']): ?>
                                        <span class="badge bg-primary">Mis en avant</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="?edit=<?php echo $projet['id']; ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="enregistrement.php?delete=<?php echo $projet['id']; ?>" 
                                               class="btn btn-sm btn-danger"
                                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ? Cette action supprimera également toutes les images associées.');">
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
        // Preview des images
        document.getElementById('images')?.addEventListener('change', function(e) {
            const previewDiv = document.querySelector('.images-preview');
            previewDiv.innerHTML = '';
            
            Array.from(e.target.files).forEach(file => {
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.classList.add('preview-image');
                        previewDiv.appendChild(img);
                    }
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
</body>
</html> 