<?php
// Inclure les fichiers nécessaires
require_once 'includes/init.php';

// Vérifier si l'admin est connecté
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

// Récupérer les informations de l'admin
$admin_id = $_SESSION['admin_id'];
$admin_email = $_SESSION['admin_email'];
$admin_nom = $_SESSION['admin_nom'];

// Récupérer les statistiques
try {
    // Nombre total de projets
    $stmt = $pdo->query("SELECT COUNT(*) FROM portfolio");
    $total_projets = $stmt->fetchColumn();

    // Nombre de clients
    $stmt = $pdo->query("SELECT COUNT(DISTINCT client_id) FROM portfolio");
    $total_clients = $stmt->fetchColumn();

    // Derniers projets
    $stmt = $pdo->query("SELECT * FROM portfolio ORDER BY date_creation DESC LIMIT 5");
    $derniers_projets = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("Erreur lors de la récupération des statistiques : " . $e->getMessage());
    $total_projets = 0;
    $total_clients = 0;
    $derniers_projets = [];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - Administration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
        .dashboard-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        .stat-card {
            text-align: center;
            padding: 20px;
        }
        .stat-card i {
            font-size: 2rem;
            color: #2563eb;
            margin-bottom: 10px;
        }
        .stat-card h3 {
            font-size: 2rem;
            margin: 10px 0;
        }
        .recent-projects {
            margin-top: 30px;
        }
        .project-item {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .project-item:last-child {
            border-bottom: none;
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
                        <a class="nav-link active" href="index.php">
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
            <span class="navbar-brand">Bienvenue, <?php echo htmlspecialchars($admin_nom); ?></span>
        </div>
    </nav>

    <!-- Contenu principal -->
    <main>
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col">
                    <h1>Tableau de bord</h1>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="dashboard-card stat-card">
                        <i class="fas fa-project-diagram"></i>
                        <h3><?php echo $total_projets; ?></h3>
                        <p>Projets réalisés</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="dashboard-card stat-card">
                        <i class="fas fa-users"></i>
                        <h3><?php echo $total_clients; ?></h3>
                        <p>Clients satisfaits</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="dashboard-card recent-projects">
                        <h2>Derniers projets</h2>
                        <?php if (!empty($derniers_projets)): ?>
                            <?php foreach ($derniers_projets as $projet): ?>
                                <div class="project-item">
                                    <h4><?php echo htmlspecialchars($projet['titre']); ?></h4>
                                    <p><?php echo htmlspecialchars($projet['description']); ?></p>
                                    <small>Date: <?php echo date('d/m/Y', strtotime($projet['date_creation'])); ?></small>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Aucun projet récent</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 