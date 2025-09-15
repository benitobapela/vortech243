<?php
// Inclure les fichiers nécessaires
require_once '../admin/includes/init.php';

// Calculer les années d'expérience depuis 2024
$date_creation = new DateTime('2024-01-01');
$date_actuelle = new DateTime();
$difference = $date_creation->diff($date_actuelle);
$annees_experience = $difference->y;

// Récupérer les statistiques
try {
    $stmt = $pdo->query("SELECT * FROM parametres_generaux WHERE id = 1");
    $stats = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Valeurs par défaut si les statistiques ne sont pas définies
    if (!$stats) {
        $stats = [
            'nombre_projets' => 0,
            'nombre_clients' => 0
        ];
    }
    
    // Ajouter les années d'expérience calculées
    $stats['annees_experience'] = $annees_experience;
    
} catch (PDOException $e) {
    // En cas d'erreur, utiliser des valeurs par défaut
    $stats = [
        'nombre_projets' => 0,
        'nombre_clients' => 0,
        'annees_experience' => $annees_experience
    ];
}

// Fonction pour obtenir le nombre de projets réalisés
function getNombreProjetsRealises() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM portfolio WHERE actif = 1");
        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        return 0;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="VorTech - Solutions technologiques innovantes pour votre entreprise">
    <title>VorTech - Accueil</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Variables globales */
        :root {
            --primary-color: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary-color: #64748b;
            --dark-color: #1e293b;
            --light-color: #f8fafc;
            --success-color: #059669;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
        }

        /* Reset et styles de base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: var(--dark-color);
            overflow-x: hidden;
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('images/hero-bg.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            position: relative;
            padding-top: 80px;
        }

        .hero::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 100px;
            background: linear-gradient(to top, var(--light-color), transparent);
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
            color: #fff;
            position: relative;
            z-index: 1;
            padding: 0 20px;
        }

        .hero h1 {
            font-size: 4rem;
            font-weight: 700;
            margin-bottom: 30px;
            line-height: 1.2;
            animation: fadeInDown 1s ease;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .hero p {
            font-size: 1.25rem;
            margin-bottom: 40px;
            animation: fadeInUp 1s ease 0.3s;
            opacity: 0.9;
        }

        /* Services Section */
        .services {
            padding: 120px 0;
            background: var(--light-color);
            position: relative;
        }

        .service-card {
            background: #fff;
            padding: 40px 30px;
            border-radius: 20px;
            box-shadow: var(--shadow-md);
            transition: var(--transition);
            height: 100%;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            opacity: 0;
            z-index: -1;
            transition: var(--transition);
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-lg);
            color: #fff;
        }

        .service-card:hover::before {
            opacity: 1;
        }

        .service-icon {
            font-size: 3rem;
            margin-bottom: 25px;
            color: var(--primary-color);
            transition: var(--transition);
        }

        .service-card:hover .service-icon {
            color: #fff;
            transform: scale(1.1);
        }

        .service-card h3 {
            font-size: 1.5rem;
            margin-bottom: 15px;
            font-weight: 600;
        }

        /* Stats Section */
        .stats {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: #fff;
            padding: 100px 0;
            position: relative;
        }

        .stats::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 100%;
            background: url('images/pattern.png') repeat;
            opacity: 0.1;
        }

        .stat-item {
            text-align: center;
            padding: 30px;
            border-radius: 15px;
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            transition: var(--transition);
        }

        .stat-item:hover {
            transform: translateY(-5px);
            background: rgba(255,255,255,0.2);
        }

        .stat-number {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            background: linear-gradient(to right, #fff, #e2e8f0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Features Section */
        .features {
            padding: 120px 0;
            background: #fff;
        }

        .feature-item {
            display: flex;
            align-items: flex-start;
            padding: 30px;
            border-radius: 15px;
            transition: var(--transition);
            background: var(--light-color);
            margin-bottom: 30px;
        }

        .feature-item:hover {
            transform: translateX(10px);
            box-shadow: var(--shadow-md);
        }

        .feature-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-right: 25px;
            padding: 15px;
            background: rgba(37, 99, 235, 0.1);
            border-radius: 12px;
            transition: var(--transition);
        }

        .feature-item:hover .feature-icon {
            transform: rotateY(180deg);
        }

        /* CTA Section */
        .cta {
            background: linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.8)), url('images/cta-bg.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: #fff;
            padding: 120px 0;
            text-align: center;
            position: relative;
        }

        .cta::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, var(--primary-color), transparent);
            opacity: 0.3;
        }

        /* Buttons */
        .btn {
            display: inline-block;
            padding: 15px 40px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 100%;
            background: rgba(255,255,255,0.1);
            transition: var(--transition);
            z-index: -1;
        }

        .btn:hover::before {
            width: 100%;
        }

        .btn-primary {
            background: var(--primary-color);
            color: #fff;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
        }

        .btn-light {
            background: #fff;
            color: var(--primary-color);
        }

        .btn-light:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255,255,255,0.2);
        }
        
        /* Animations */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Container et Grid */
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin: -15px;
        }

        .col {
            flex: 1;
            padding: 15px;
            min-width: 300px;
        }

        /* Section Titles */
        .section-title {
            text-align: center;
            margin-bottom: 80px;
            position: relative;
        }

        .section-title h2 {
            font-size: 2.5rem;
            color: var(--dark-color);
            margin-bottom: 20px;
            position: relative;
            display: inline-block;
        }

        .section-title h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: var(--primary-color);
        }

        .section-title p {
            color: var(--secondary-color);
            max-width: 600px;
            margin: 0 auto;
            font-size: 1.1rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }

            .service-card {
                margin-bottom: 30px;
            }

            .stat-item {
                margin-bottom: 30px;
            }

            .col {
                flex: 0 0 100%;
            }

            .feature-item {
                flex-direction: column;
                text-align: center;
            }

            .feature-icon {
                margin: 0 0 20px 0;
            }
        }
    </style>
</head>
<body>
    <?php include("nav.php"); ?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>Innovez avec VorTech</h1>
                <p>Solutions technologiques sur mesure pour propulser votre entreprise vers l'avenir</p>
                <a href="contact.php" class="btn btn-primary">Démarrer un projet</a>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services">
        <div class="container">
            <div class="section-title">
                <h2>Nos Services</h2>
                <p>Des solutions adaptées à vos besoins</p>
            </div>
            <div class="row">
                <?php
                // Récupération des services actifs depuis la base de données
                $stmt = $pdo->query("SELECT * FROM services WHERE actif = 1 ORDER BY ordre ASC, titre ASC");
                while($service = $stmt->fetch()) {
                ?>
                <div class="col">
                    <div class="service-card">
                        <i class="<?php echo e($service['icone']); ?> service-icon"></i>
                        <h3><?php echo e($service['titre']); ?></h3>
                        <p><?php echo e($service['description']); ?></p>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="stat-item">
                        <div class="stat-number"><?php echo getNombreProjetsRealises(); ?>+</div> 
                        <p>Projets Réalisés</p>
                    </div>
                </div>
                <div class="col">
                    <div class="stat-item">
                        <div class="stat-number">50+</div>
                        <p>Clients Satisfaits</p>
                    </div>
                </div>
                <div class="col">
                    <div class="stat-item">
                        <div class="stat-number"><?php echo $stats['annees_experience']; ?>+</div> 
                        <p>Années d'expérience</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <div class="section-title">
                <h2>Pourquoi Nous Choisir</h2>
                <p>Des solutions innovantes adaptées à vos besoins</p>
            </div>
            <div class="row">
                <div class="col">
                    <div class="feature-item">
                        <i class="fas fa-check-circle feature-icon"></i>
                        <div>
                            <h3>Expertise Technique</h3>
                            <p>Une équipe d'experts passionnés par les dernières technologies</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="feature-item">
                        <i class="fas fa-clock feature-icon"></i>
                        <div>
                            <h3>Respect des Délais</h3>
                            <p>Livraison dans les temps et suivi régulier de l'avancement</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <h2>Prêt à Démarrer Votre Projet ?</h2>
            <p>Contactez-nous pour discuter de vos besoins</p>
            <a href="contact.php" class="btn btn-light">Contactez-nous</a>
        </div>
    </section>

    <?php include("foot.php"); ?>
    
</body>
</html>