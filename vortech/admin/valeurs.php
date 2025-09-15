<?php
// Inclusion du fichier d'initialisation
require_once 'includes/init.php';

// Définition des variables de page
$pageTitle = "Gestion des Valeurs";
$currentPage = 'valeurs';

// Récupération de la valeur pour modification si nécessaire
$valeur = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM valeurs WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $valeur = $stmt->fetch();
    
    if (!$valeur) {
        setFlashMessage("danger", "Valeur non trouvée.");
        header("Location: valeurs.php");
        exit;
    }
}

// Si un message de succès est présent, on redirige vers la page sans paramètre edit
if (isset($_SESSION['success']) && isset($_GET['edit'])) {
    header("Location: valeurs.php");
    exit;
}

// Récupération des valeurs
$stmt = $pdo->query("SELECT * FROM valeurs ORDER BY ordre ASC");
$valeurs = $stmt->fetchAll();

// Inclusion des fichiers de template
include 'includes/header.php';
include 'includes/sidebar.php';
?>

<div class="wrapper">
    <main>
        <div class="container-fluid">
            <h1 class="mb-4">Gestion des Valeurs</h1>

            <?php 
            // Affichage des messages flash
            $flash = getFlashMessage();
            if ($flash): ?>
                <div class="alert alert-<?php echo e($flash['type']); ?> alert-dismissible fade show" role="alert">
                    <?php echo e($flash['message']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Formulaire d'ajout/modification -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0"><?php echo $valeur ? 'Modifier une valeur' : 'Ajouter une valeur'; ?></h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="enregistrement.php" enctype="multipart/form-data">
                        <input type="hidden" name="type" value="valeurs">
                        <?php if ($valeur): ?>
                            <input type="hidden" name="id" value="<?php echo $valeur['id']; ?>">
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="titre" class="form-label">Titre *</label>
                                    <input type="text" class="form-control" id="titre" name="titre" required
                                           value="<?php echo $valeur ? e($valeur['titre']) : ''; ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description *</label>
                                    <textarea class="form-control" id="description" name="description" rows="4" required><?php echo $valeur ? e($valeur['description']) : ''; ?></textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="icone" class="form-label">Icône (classe FontAwesome) *</label>
                                    <input type="text" class="form-control" id="icone" name="icone" required
                                           value="<?php echo $valeur ? e($valeur['icone']) : ''; ?>">
                                    <small class="text-muted">Ex: fas fa-star, far fa-heart, etc.</small>
                                    <?php if ($valeur): ?>
                                        <div class="mt-2">
                                            <i class="<?php echo e($valeur['icone']); ?> fa-2x"></i>
                                            <span class="ms-2">Aperçu de l'icône actuelle</span>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="mb-3">
                                    <label for="ordre" class="form-label">Ordre d'affichage</label>
                                    <input type="number" class="form-control" id="ordre" name="ordre"
                                           value="<?php echo $valeur ? e($valeur['ordre']) : '0'; ?>">
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <?php if ($valeur): ?>
                                <a href="valeurs.php" class="btn btn-secondary">Annuler</a>
                            <?php endif; ?>
                            <button type="submit" class="btn btn-primary">
                                <?php echo $valeur ? 'Modifier' : 'Ajouter'; ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Liste des valeurs -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Liste des valeurs</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Ordre</th>
                                    <th>Titre</th>
                                    <th>Description</th>
                                    <th>Icône</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($valeurs as $item): ?>
                                    <tr>
                                        <td><?php echo e($item['ordre']); ?></td>
                                        <td><?php echo e($item['titre']); ?></td>
                                        <td><?php echo e($item['description']); ?></td>
                                        <td><i class="<?php echo e($item['icone']); ?>"></i></td>
                                        <td>
                                            <a href="valeurs.php?edit=<?php echo $item['id']; ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="enregistrement.php?delete=<?php echo $item['id']; ?>&type=valeurs" 
                                               class="btn btn-sm btn-danger"
                                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette valeur ?');">
                                                <i class="fas fa-trash"></i>
                                            </a>
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
</div>

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

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Prévisualisation de l'icône
    const iconeInput = document.getElementById('icone');
    if (iconeInput) {
        iconeInput.addEventListener('input', function() {
            const preview = this.parentElement.querySelector('i');
            if (preview) {
                preview.className = this.value + ' fa-2x';
            } else {
                const newPreview = document.createElement('div');
                newPreview.className = 'mt-2';
                newPreview.innerHTML = `<i class="${this.value} fa-2x"></i><span class="ms-2">Aperçu de l'icône</span>`;
                this.parentElement.appendChild(newPreview);
            }
        });
    }
});
</script>

<?php include 'includes/footer.php'; ?> 