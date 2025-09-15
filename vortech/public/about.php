<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Vérification de la session et des variables nécessaires
if (!isset($entreprise)) {
    require_once __DIR__ . '/config.php';
}

// Vérification des variables requises
$entreprise_nom = isset($entreprise['nom']) ? htmlspecialchars($entreprise['nom']) : 'VorTech';
$logo_path = isset($stats['logo']) ? htmlspecialchars($stats['logo']) : 'uploads/generaux/logo.png';

// Fonction pour calculer les années d'expérience depuis la création
function getAnneesExperience() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT annee FROM timeline WHERE evenement LIKE '%création%' OR evenement LIKE '%creation%' ORDER BY annee ASC LIMIT 1");
        $creation = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($creation) {
            $annee_creation = intval($creation['annee']);
            $annee_actuelle = date('Y');
            return $annee_actuelle - $annee_creation;
        }
        return 1; // Valeur par défaut si pas de date de création trouvée
    } catch (PDOException $e) {
        error_log("Erreur lors du calcul des années d'expérience : " . $e->getMessage());
        return 1;
    }
}

// Fonction pour compter le nombre de projets
function getNombreProjets() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM portfolio");
        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log("Erreur lors du comptage des projets : " . $e->getMessage());
        return 0;
    }
}

// Fonction pour compter le nombre de clients satisfaits
function getNombreClientsSatisfaits() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT COUNT(DISTINCT client_id) FROM portfolio");
        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log("Erreur lors du comptage des clients : " . $e->getMessage());
        return 0;
    }
}

// Calculer les statistiques
$stats = [
    'annees_experience' => getAnneesExperience(),
    'projets_reussis' => getNombreProjets(),
    'clients_satisfaits' => getNombreClientsSatisfaits()
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Découvrez <?php echo $entreprise_nom; ?>, leader en solutions technologiques innovantes. Notre histoire, notre mission et nos valeurs.">
    <title><?php echo $entreprise_nom; ?> - À propos</title>
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

        /* About Hero Section */
        .about-section {
            min-height: 80vh;
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('./images/about-bg.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            padding: 120px 0;
            color: #fff;
            position: relative;
        }

        .about-section::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 100px;
            background: linear-gradient(to top, var(--light-color), transparent);
        }

        .about-image {
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
            transition: var(--transition);
            position: relative;
            z-index: 1;
        }

        .about-image:hover {
            transform: translateY(-10px);
        }

        .stats-section {
            margin-top: 40px;
        }

        .stats-row {
            display: flex;
            justify-content: space-between;
            gap: 30px;
        }

        .stat-item {
            flex: 1;
            text-align: center;
            padding: 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            backdrop-filter: blur(10px);
            transition: var(--transition);
        }

        .stat-item:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.2);
        }

        .stat-item h3 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 10px;
            background: linear-gradient(to right, var(--primary-color), var(--primary-dark));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .stat-item p {
            color: #fff;
            font-size: 1.1rem;
            margin: 0;
        }

        /* Section Styles */
        .section-padding {
            padding: 120px 0;
        }

        .why-us-item {
            padding: 40px 30px;
            border-radius: 20px;
            background: #fff;
            box-shadow: var(--shadow-md);
            transition: var(--transition);
            height: 100%;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .why-us-item::before {
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

        .why-us-item:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-lg);
            color: #fff;
        }

        .why-us-item:hover::before {
            opacity: 1;
        }

        .why-us-item i {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 20px;
            transition: var(--transition);
        }

        .why-us-item:hover i {
            color: #fff;
        }

        /* Section Title */
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

        /* Timeline Section */
        .timeline-item {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: var(--shadow-md);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .timeline-item:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .timeline-item h4 {
            color: var(--primary-color);
            font-size: 1.5rem;
            margin-bottom: 10px;
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

        .btn-light {
            background: #fff;
            color: var(--primary-color);
        }

        .btn-light:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255,255,255,0.2);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .about-section {
                padding: 80px 0;
            }

            .stats-row {
                flex-direction: column;
                gap: 20px;
            }

            .stat-item {
                margin: 0 20px;
            }

            .section-title h2 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <?php include("nav.php"); ?>

    <!-- Hero Section -->
    <section class="about-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col" data-aos="fade-right">
                    <h1 class="display-4 mb-4">Notre Histoire</h1>
                    <p class="lead mb-4">Fondée en <?php echo $entreprise['annee_creation']; ?>, <?php echo $entreprise_nom; ?> est née de la vision d'une équipe passionnée par l'innovation technologique.</p>
                    <p class="mb-4">Notre mission est de transformer le paysage numérique en créant des solutions technologiques innovantes et accessibles pour les entreprises de toutes tailles.</p>
                    <div class="stats-section">
                        <div class="stats-row">
                            <div class="stat-item" data-aos="fade-up">
                                <h3><?php echo $stats['annees_experience']; ?>+</h3>
                                <p>Années d'expérience</p>
                            </div>
                            <div class="stat-item" data-aos="fade-up" data-aos-delay="100">
                                <h3><?php echo $stats['projets_reussis']; ?>+</h3>
                                <p>Projets réussis</p>
                            </div>
                            <div class="stat-item" data-aos="fade-up" data-aos-delay="200">
                                <h3><?php echo $stats['clients_satisfaits']; ?>+</h3>
                                <p>Clients satisfaits</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col" data-aos="fade-left">
                    <img src="<?php echo $logo_path; ?>" 
                         alt="L'équipe <?php echo $entreprise_nom; ?>" 
                         class="img-fluid about-image">
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Vision Section -->
    <section class="section-padding bg-light">
        <div class="container">
            <div class="row">
                <div class="col" data-aos="fade-up">
                    <div class="why-us-item">
                        <i class="fas fa-rocket"></i>
                        <h3>Notre Mission</h3>
                        <p>Nous nous engageons à fournir des solutions technologiques innovantes qui permettent à nos clients de prospérer dans l'ère numérique. Notre approche combine expertise technique, créativité et compréhension approfondie des besoins business.</p>
                    </div>
                </div>
                <div class="col" data-aos="fade-up" data-aos-delay="100">
                    <div class="why-us-item">
                        <i class="fas fa-eye"></i>
                        <h3>Notre Vision</h3>
                        <p>Devenir le partenaire technologique de référence pour les entreprises innovantes, en créant des solutions qui façonnent l'avenir du numérique tout en maintenant une approche éthique et durable.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Valeurs Section -->
    <section class="section-padding">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Nos Valeurs</h2>
                <p>Les principes qui guident nos actions et nos décisions</p>
            </div>
            <div class="row">
                <?php
                // Récupération des valeurs depuis la base de données
                $stmt = $pdo->query("SELECT * FROM valeurs ORDER BY ordre ASC");
                while($valeur = $stmt->fetch()) {
                ?>
                <div class="col" data-aos="fade-up">
                    <div class="why-us-item text-center">
                        <i class="<?php echo htmlspecialchars($valeur['icone']); ?>"></i>
                        <h4><?php echo htmlspecialchars($valeur['titre']); ?></h4>
                        <p><?php echo htmlspecialchars($valeur['description']); ?></p>
                    </div>
                </div>
                <?php
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Timeline Section -->
    <section class="section-padding bg-light">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Notre Parcours</h2>
                <p>Les étapes clés de notre croissance</p>
            </div>
            <div class="row">
                <?php
                // Récupération des événements de la timeline depuis la base de données
                $stmt = $pdo->query("SELECT * FROM timeline ORDER BY annee ASC, ordre ASC");
                while($event = $stmt->fetch()) {
                ?>
                <div class="col" data-aos="fade-up">
                    <div class="timeline-item text-center">
                        <h4><?php echo htmlspecialchars($event['annee']); ?></h4>
                        <p><?php echo htmlspecialchars($event['evenement']); ?></p>
                        <?php if(!empty($event['description'])): ?>
                        <p class="text-muted"><?php echo htmlspecialchars($event['description']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php
                }
                ?>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container text-center">
            <h2 class="mb-4" data-aos="fade-up">Prêt à Rejoindre l'Aventure ?</h2>
            <p class="lead mb-4" data-aos="fade-up" data-aos-delay="100">Découvrez comment nous pouvons transformer vos idées en réalité</p>
            <a href="contact.php" class="btn btn-light" data-aos="fade-up" data-aos-delay="200">Contactez-nous</a>
        </div>
    </section>
    
    <?php include("foot.php"); ?>

</body>
</html>