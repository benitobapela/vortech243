<?php
// Inclusion du fichier d'initialisation
require_once 'includes/init.php';

// Définition des variables de page
$pageTitle = "Gestion du Parcours";
$currentPage = 'parcours';

// Récupération de l'événement pour modification si nécessaire
$evenement = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM timeline WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $evenement = $stmt->fetch();
    
    if (!$evenement) {
        setFlashMessage("Événement non trouvé.", "danger");
        header("Location: parcours.php");
        exit;
    }
}

// Si un message de succès est présent, on redirige vers la page sans paramètre edit
if (isset($_SESSION['success']) && isset($_GET['edit'])) {
    header("Location: parcours.php");
    exit;
}

// Récupération des événements
$stmt = $pdo->query("SELECT * FROM timeline ORDER BY annee DESC, ordre ASC");
$evenements = $stmt->fetchAll();

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
    <title>Gestion du Parcours - Administration</title>
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
    </style>
</head>
<body>
    <main>
        <div class="container-fluid">
            <h1 class="mb-4">Gestion du Parcours</h1>

            <!-- Formulaire d'ajout/modification -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0"><?php echo $evenement ? 'Modifier un événement' : 'Ajouter un événement'; ?></h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="enregistrement.php" enctype="multipart/form-data">
                        <input type="hidden" name="type" value="timeline">
                        <?php if ($evenement): ?>
                        <input type="hidden" name="id" value="<?php echo $evenement['id']; ?>">
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="evenement" class="form-label">Événement *</label>
                                    <input type="text" class="form-control" id="evenement" name="evenement" required
                                           value="<?php echo $evenement ? e($evenement['evenement']) : ''; ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description *</label>
                                    <textarea class="form-control" id="description" name="description" rows="4" required><?php echo $evenement ? e($evenement['description']) : ''; ?></textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="annee" class="form-label">Année *</label>
                                    <input type="number" class="form-control" id="annee" name="annee" required
                                           value="<?php echo $evenement ? e($evenement['annee']) : ''; ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="ordre" class="form-label">Ordre d'affichage</label>
                                    <input type="number" class="form-control" id="ordre" name="ordre"
                                           value="<?php echo $evenement ? e($evenement['ordre']) : '0'; ?>">
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <?php if ($evenement): ?>
                                <a href="parcours.php" class="btn btn-secondary">Annuler</a>
                            <?php endif; ?>
                            <button type="submit" class="btn btn-primary">
                                <?php echo $evenement ? 'Modifier' : 'Ajouter'; ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Liste des événements -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Liste des événements</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Année</th>
                                    <th>Événement</th>
                                    <th>Description</th>
                                    <th>Ordre</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($evenements as $evt): ?>
                                <tr>
                                    <td><?php echo e($evt['annee']); ?></td>
                                    <td><?php echo e($evt['evenement']); ?></td>
                                    <td><?php echo e($evt['description']); ?></td>
                                    <td><?php echo e($evt['ordre']); ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="?edit=<?php echo $evt['id']; ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="enregistrement.php?delete=<?php echo $evt['id']; ?>" 
                                               class="btn btn-sm btn-danger"
                                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?');">
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
</body>
</html> 