<?php
// Vérification que le script n'est pas appelé directement
if (!defined('ENVIRONMENT')) {
    exit('Accès direct au script non autorisé');
}
?>
<!-- Barre de navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <span class="navbar-brand"><?php echo $pageTitle; ?></span>
        <div class="ms-auto">
            <div class="dropdown">
                <button class="btn btn-link dropdown-toggle text-dark text-decoration-none" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown">
                    <i class="fas fa-user-circle"></i> <?php echo e($_SESSION['admin_nom'] ?? 'Administrateur'); ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user"></i> Mon profil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<!-- Barre latérale -->
<aside class="sidebar">
    <div class="sidebar-header">
        <h3 class="text-white mb-0">VorTech</h3>
    </div>
    <nav class="sidebar-nav">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo $currentPage === 'dashboard' ? 'active' : ''; ?>" href="index.php">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Tableau de bord</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $currentPage === 'portfolio' ? 'active' : ''; ?>" href="portfolio.php">
                    <i class="fas fa-briefcase"></i>
                    <span>Portfolio</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $currentPage === 'services' ? 'active' : ''; ?>" href="services.php">
                    <i class="fas fa-cogs"></i>
                    <span>Services</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $currentPage === 'equipe' ? 'active' : ''; ?>" href="equipe.php">
                    <i class="fas fa-users"></i>
                    <span>Équipe</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $currentPage === 'parametres' ? 'active' : ''; ?>" href="parametres.php">
                    <i class="fas fa-cog"></i>
                    <span>Paramètres</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $currentPage === 'administrateurs' ? 'active' : ''; ?>" href="administrateurs.php">
                    <i class="fas fa-user-shield"></i>
                    <span>Administrateurs</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../logout.php">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Déconnexion</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>

<style>
/* Style de la barre latérale */
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    width: 250px;
    background-color: #0d6efd;
    z-index: 1000;
    transition: all 0.3s ease;
}

.sidebar-header {
    padding: 20px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.sidebar-header h3 {
    font-size: 1.5rem;
    font-weight: 600;
}

.sidebar-nav {
    padding: 20px 0;
}

.sidebar .nav-link {
    color: rgba(255, 255, 255, 0.8);
    padding: 12px 20px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
}

.sidebar .nav-link:hover,
.sidebar .nav-link.active {
    color: #fff;
    background-color: rgba(255, 255, 255, 0.1);
}

.sidebar .nav-link i {
    margin-right: 10px;
    width: 20px;
    text-align: center;
    font-size: 1.1rem;
}

.sidebar .nav-link span {
    font-size: 1rem;
}

/* Responsive */
@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
    }
    
    .sidebar.show {
        transform: translateX(0);
    }
}
</style> 