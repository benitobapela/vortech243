<?php 
// Vérification de la session et des variables nécessaires
if (!isset($entreprise)) {
    require_once __DIR__ . '/config.php';
}

// Vérification des variables requises
$entreprise_nom = isset($entreprise['nom']) ? htmlspecialchars($entreprise['nom']) : 'VorTech';

// Récupération du logo depuis la table parametres_generaux
try {
    $stmt = $pdo->query("SELECT logo FROM parametres_generaux WHERE id = 1");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Récupération des réseaux sociaux
    $stmt = $pdo->query("SELECT * FROM reseaux_sociaux WHERE actif = 1 ORDER BY ordre ASC");
    $reseaux_sociaux = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($result && !empty($result['logo'])) {
        // Utiliser un chemin relatif depuis le dossier public
        $logo_path = isset($result['logo']) ? htmlspecialchars($result['logo']) : 'uploads/generaux/logo.png';
        
        // Debug information
        echo "<div style='background: #f8f9fa; padding: 10px; margin: 10px; border: 1px solid #ddd;'>";
        echo "<h4>Debug Information:</h4>";
        echo "<p>Logo from DB: " . htmlspecialchars($result['logo']) . "</p>";
        echo "<p>Logo path: " . htmlspecialchars($logo_path) . "</p>";
        echo "<p>File exists: " . (file_exists($logo_path) ? 'Yes' : 'No') . "</p>";
        echo "<p>Document Root: " . htmlspecialchars($_SERVER['DOCUMENT_ROOT']) . "</p>";
        echo "<p>Current URL: " . htmlspecialchars($_SERVER['REQUEST_URI']) . "</p>";
        echo "<p>Script Filename: " . htmlspecialchars($_SERVER['SCRIPT_FILENAME']) . "</p>";
        echo "<p>HTTP Host: " . htmlspecialchars($_SERVER['HTTP_HOST']) . "</p>";
        echo "</div>";
        
        // Vérification si le fichier existe physiquement
        if (!file_exists($logo_path)) {
            error_log("Le fichier logo n'existe pas : " . $logo_path);
            $logo_path = 'uploads/generaux/logo.png';
        }
    } else {
        $logo_path = 'uploads/generaux/logo.png';
    }
} catch (PDOException $e) {
    error_log("Erreur lors de la récupération des données : " . $e->getMessage());
    $logo_path = 'uploads/generaux/logo.png';
    $reseaux_sociaux = [];
}

// Debug - à retirer en production
error_log("Chemin du logo : " . $logo_path);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Styles pour la navigation */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            padding: 15px 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            padding: 15px 0;
            background: rgba(255, 255, 255, 0.98);
        }

        .navbar .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            gap: 20px;
        }

        /* Logo styles */
        .navbar-brand {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #333;
            font-size: 1.5rem;
            font-weight: bold;
            flex-shrink: 0;
            position: relative;
            z-index: 1;
        }

        .navbar-brand img, .navbar-brand svg {
            height: 40px;
            width: auto;
            margin-right: 10px;
            transition: transform 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .navbar-brand svg {
            max-width: 200px;
            height: auto;
            display: block;
        }

        .navbar-brand:hover img, .navbar-brand:hover svg {
            transform: scale(1.05);
        }

        /* Navigation centrale */
        .navbar-center {
            display: flex;
            align-items: center;
            margin: 0;
            padding: 0;
            list-style: none;
            flex-grow: 1;
            justify-content: center;
            position: relative;
            z-index: 2;
        }

        .nav-item {
            margin: 0 15px;
        }

        .nav-link {
            color: #333;
            text-decoration: none;
            font-weight: 500;
            padding: 8px 0;
            position: relative;
            transition: all 0.3s ease;
        }

        .nav-link:after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            background: #2563eb;
            bottom: 0;
            left: 0;
            transition: width 0.3s ease;
        }

        .nav-link:hover {
            color: #2563eb;
        }

        .nav-link:hover:after {
            width: 100%;
        }

        .nav-link.active {
            color: #2563eb;
        }

        .nav-link.active:after {
            width: 100%;
        }

        /* Réseaux sociaux */
        .social-links {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }

        .social-links a {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1e40af !important;
            font-size: 1.2rem;
            text-decoration: none;
            position: relative;
            overflow: hidden;
            border: none;
            transform: translateY(-5px) scale(1.05);
        }

        .social-links a i {
            color: #1e40af !important;
            position: relative;
            z-index: 2;
            transform: scale(1.2) rotate(5deg);
        }

        /* Menu burger */
        .navbar-toggler {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            padding: 10px;
        }

        .navbar-toggler-icon {
            display: block;
            width: 25px;
            height: 2px;
            background: #333;
            position: relative;
            transition: all 0.3s ease;
        }

        .navbar-toggler-icon:before,
        .navbar-toggler-icon:after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: #333;
            transition: all 0.3s ease;
        }

        .navbar-toggler-icon:before {
            top: -8px;
        }

        .navbar-toggler-icon:after {
            bottom: -8px;
        }

        @media (max-width: 991px) {
            .navbar-toggler {
                display: block;
                order: 3;
            }

            .navbar-center, .social-links {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: #fff;
                padding: 20px 0;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                flex-direction: column;
            }

            .navbar-center.show, .social-links.show {
                display: flex;
            }

            .nav-item {
                margin: 10px 0;
            }

            .social-links {
                justify-content: center;
                margin-top: 20px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="index.php" class="navbar-brand">
                <?php
                if ($result && !empty($result['logo'])) {
                    echo '<img src="' . $logo_path . '" alt="' . $entreprise_nom . '" class="logo" style="max-width: 200px; height: auto;">';
                } else {
                    echo '<img src="uploads/generaux/logo.png" alt="' . $entreprise_nom . '" class="logo" style="max-width: 200px; height: auto;">';
                }
                ?>
                <span><?php echo $entreprise_nom; ?></span>
            </a>
            
            <ul class="navbar-center">
                <?php foreach ($navigation as $item): ?>
                <li class="nav-item">
                    <a href="<?php echo htmlspecialchars($item['lien']); ?>" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === $item['lien'] ? 'active' : ''; ?>">
                        <?php echo htmlspecialchars($item['titre']); ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
            
            <div class="social-links">
                <?php foreach ($reseaux_sociaux as $reseau): ?>
                <a href="<?php echo htmlspecialchars($reseau['url']); ?>" 
                   target="_blank" 
                   rel="noopener noreferrer" 
                   title="<?php echo htmlspecialchars($reseau['nom']); ?>">
                    <i class="<?php echo htmlspecialchars($reseau['icone']); ?>"></i>
                </a>
                <?php endforeach; ?>
            </div>
            
            <button class="navbar-toggler" aria-label="Menu">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <script>
        // Navigation responsive
        document.querySelector('.navbar-toggler').addEventListener('click', function() {
            document.querySelector('.navbar-center').classList.toggle('show');
            document.querySelector('.social-links').classList.toggle('show');
        });

        // Effet de scroll sur la navigation
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                document.querySelector('.navbar').classList.add('scrolled');
            } else {
                document.querySelector('.navbar').classList.remove('scrolled');
            }
        });

        // Marquer le lien actif
        document.addEventListener('DOMContentLoaded', function() {
            const currentLocation = location.href;
            const menuItems = document.querySelectorAll('.nav-link');
            menuItems.forEach(link => {
                if(link.href === currentLocation) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>