<?php
session_start();
require_once 'includes/init.php';

// Vérifier si l'admin est connecté
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

// Traitement de l'ajout d'un administrateur
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add') {
        try {
            $stmt = $pdo->prepare("INSERT INTO admins (email, password, nom) VALUES (?, ?, ?)");
            $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt->execute([$_POST['email'], $hashedPassword, $_POST['nom']]);
            setFlashMessage('success', 'Administrateur ajouté avec succès.');
        } catch (PDOException $e) {
            setFlashMessage('error', 'Erreur lors de l\'ajout de l\'administrateur.');
        }
    }
    header('Location: administrateurs.php');
    exit;
}

// Récupération des administrateurs
try {
    $stmt = $pdo->query("SELECT * FROM admins ORDER BY nom");
    $administrateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $administrateurs = [];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrateurs - Administration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Copier les styles de la barre latérale depuis index.php */
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
        .nav-link {
            color: rgba(255,255,255,.8);
            padding: 0.5rem 1rem;
            margin: 0.2rem 0;
        }
        .nav-link:hover {
            color: #fff;
            background: rgba(255,255,255,.1);
        }
        .nav-link.active {
            color: #fff;
            background: rgba(255,255,255,.2);
        }
        .nav-link i {
            margin-right: 10px;
        }
    </style>
</head>
<body>
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
                        <a class="nav-link active" href="administrateurs.php">
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
            <span class="navbar-brand">Administrateurs</span>
        </div>
    </nav>

    <!-- Contenu principal -->
    <main>
        <div class="container-fluid">
            <?php
            $flash = getFlashMessage();
            if ($flash) {
                echo '<div class="alert alert-' . $flash['type'] . ' alert-dismissible fade show" role="alert">';
                echo $flash['message'];
                echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                echo '</div>';
            }
            ?>

            <div class="card mb-4">
                <div class="card-body">
                    <h4>Ajouter un administrateur</h4>
                    <form method="POST" action="" class="row g-3">
                        <input type="hidden" name="action" value="add">
                        <div class="col-md-4">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="nom" name="nom" required>
                        </div>
                        <div class="col-md-4">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="col-md-4">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Ajouter</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h4>Liste des administrateurs</h4>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>Date de création</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($administrateurs as $admin): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($admin['nom']); ?></td>
                                    <td><?php echo htmlspecialchars($admin['email']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($admin['date_creation'])); ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-warning" onclick="editAdmin(<?php echo $admin['id']; ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="deleteAdmin(<?php echo $admin['id']; ?>)">
                                            <i class="fas fa-trash"></i>
                                        </button>
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
        function editAdmin(id) {
            // À implémenter : logique d'édition
            alert('Édition de l\'administrateur ' + id);
        }

        function deleteAdmin(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cet administrateur ?')) {
                // À implémenter : logique de suppression
                alert('Suppression de l\'administrateur ' + id);
            }
        }
    </script>
</body>
</html> 