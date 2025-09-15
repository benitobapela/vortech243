<?php
require_once 'config.php';

// Définition de toutes les catégories possibles
$toutes_categories = [
    'web' => 'Web',
    'mobile' => 'Mobile',
    'desktop' => 'Desktop',
    'design' => 'Design',
    'autre' => 'Autre'
];

// Récupération de tous les projets actifs
$stmt = $pdo->query("SELECT * FROM portfolio WHERE actif = 1 ORDER BY created_at DESC");
$projets = $stmt->fetchAll();

// Compter les projets par catégorie
$projets_par_categorie = [];
foreach ($toutes_categories as $cat_key => $cat_name) {
    $projets_par_categorie[$cat_key] = 0;
}
foreach ($projets as $projet) {
    if (isset($projets_par_categorie[$projet['categorie']])) {
        $projets_par_categorie[$projet['categorie']]++;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Découvrez nos projets innovants et réalisations technologiques chez VorTech">
    <title>VorTech - Portfolio</title>
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
        .portfolio-hero {
            min-height: 60vh;
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('images/portfolio-bg.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            position: relative;
            padding: 120px 0 60px;
            color: #fff;
        }

        .portfolio-hero::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 100px;
            background: linear-gradient(to top, var(--light-color), transparent);
        }

        /* Portfolio Grid */
        .portfolio-grid {
            padding: 120px 0;
            background: var(--light-color);
        }

        .portfolio-filters {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 50px;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 8px 20px;
            border: none;
            background: #fff;
            color: var(--dark-color);
            border-radius: 25px;
            cursor: pointer;
            transition: var(--transition);
            font-weight: 500;
            box-shadow: var(--shadow-sm);
        }

        .filter-btn:hover, .filter-btn.active {
            background: var(--primary-color);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .portfolio-items {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            padding: 0 20px;
        }

        .portfolio-item {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow-md);
            transition: var(--transition);
            aspect-ratio: 4/3;
        }

        .portfolio-item:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-lg);
        }

        .portfolio-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .portfolio-item:hover img {
            transform: scale(1.1);
        }

        .portfolio-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 30px;
            color: #fff;
            opacity: 0;
            transition: var(--transition);
        }

        .portfolio-item:hover .portfolio-overlay {
            opacity: 1;
        }

        .portfolio-overlay h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
            transform: translateY(20px);
            transition: var(--transition);
        }

        .portfolio-item:hover .portfolio-overlay h3 {
            transform: translateY(0);
        }

        .portfolio-overlay p {
            font-size: 0.9rem;
            opacity: 0.9;
            transform: translateY(20px);
            transition: var(--transition);
            transition-delay: 0.1s;
        }

        .portfolio-item:hover .portfolio-overlay p {
            transform: translateY(0);
        }

        .portfolio-tags {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .portfolio-tag {
            padding: 4px 12px;
            background: rgba(255,255,255,0.2);
            border-radius: 15px;
            font-size: 0.8rem;
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
            .portfolio-hero {
                min-height: 50vh;
                padding: 80px 0 40px;
            }

            .portfolio-items {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 20px;
            }

            .portfolio-filters {
                gap: 10px;
                margin-bottom: 30px;
            }

            .filter-btn {
                padding: 6px 15px;
                font-size: 0.9rem;
            }

            .section-title h2 {
                font-size: 2rem;
            }

            .stat-item {
                margin-bottom: 30px;
            }

            .col {
                flex: 0 0 100%;
            }
        }

        /* Ajout des styles pour le message "aucun projet" */
        .no-projects-message {
            text-align: center;
            padding: 60px 20px;
            background: #fff;
            border-radius: 20px;
            box-shadow: var(--shadow-md);
            grid-column: 1 / -1;
        }

        .no-projects-message i {
            font-size: 3rem;
            color: var(--secondary-color);
            margin-bottom: 20px;
        }

        .no-projects-message h3 {
            color: var(--dark-color);
            margin-bottom: 10px;
        }

        .no-projects-message p {
            color: var(--secondary-color);
        }
    </style>
</head>
<body>
    <?php include("nav.php"); ?>

    <!-- Hero Section -->
    <section class="portfolio-hero">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Nos Réalisations</h2>
                <p>Découvrez nos projets innovants et nos solutions sur mesure</p>
            </div>
        </div>
    </section>

    <!-- Portfolio Section -->
    <section class="portfolio-grid">
        <div class="container">
            <div class="portfolio-filters" data-aos="fade-up">
                <button class="filter-btn active" data-category="all">Tous (<?php echo count($projets); ?>)</button>
                <?php foreach($toutes_categories as $cat_key => $cat_name): ?>
                    <button class="filter-btn" data-category="<?php echo $cat_key; ?>">
                        <?php echo $cat_name; ?> (<?php echo $projets_par_categorie[$cat_key]; ?>)
                    </button>
                <?php endforeach; ?>
            </div>
            <div class="portfolio-items">
                <?php if(empty($projets)): ?>
                    <div class="no-projects-message" data-aos="fade-up">
                        <i class="fas fa-folder-open"></i>
                        <h3>Aucun projet disponible</h3>
                        <p>Revenez bientôt pour découvrir nos réalisations !</p>
                    </div>
                <?php else: ?>
                    <?php foreach($projets as $projet): ?>
                        <div class="portfolio-item" data-category="<?php echo $projet['categorie']; ?>" data-aos="fade-up">
                            <?php 
                            $images = explode(',', $projet['images']);
                            $image_principale = !empty($images[0]) ? trim($images[0]) : 'images/default-project.jpg';
                            ?>
                            <img src="<?php echo $image_principale; ?>" alt="<?php echo htmlspecialchars($projet['titre']); ?>">
                            <div class="portfolio-overlay">
                                <h3><?php echo htmlspecialchars($projet['titre']); ?></h3>
                                <p><?php echo htmlspecialchars(substr($projet['description'], 0, 100)) . '...'; ?></p>
                                <div class="portfolio-tags">
                                    <span class="portfolio-tag"><?php echo $toutes_categories[$projet['categorie']] ?? ucfirst($projet['categorie']); ?></span>
                                    <?php 
                                    $technologies = explode(',', $projet['technologies']);
                                    $tech_count = 0;
                                    foreach($technologies as $tech): 
                                        if($tech_count < 2): // Limiter à 2 technologies affichées
                                    ?>
                                        <span class="portfolio-tag"><?php echo htmlspecialchars(trim($tech)); ?></span>
                                    <?php 
                                        endif;
                                        $tech_count++;
                                    endforeach; 
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                <!-- Message pour catégorie vide -->
                <div class="no-projects-message" style="display: none;" data-aos="fade-up">
                    <i class="fas fa-folder-open"></i>
                    <h3>Aucun projet dans cette catégorie</h3>
                    <p>Revenez bientôt pour découvrir nos réalisations dans cette catégorie !</p>
                </div>
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
                    <div class="stat-number"><?php echo $stats['clients_satisfaits']; ?>+</div>                        
                    <p>Clients Satisfaits</p>                    
                </div>                
            </div>                
            <div class="col">                    
                <div class="stat-item">                        
                    <div class="stat-number"><?php echo $stats['annees_experience']; ?>+</div>                        
                    <p>Années d'Expérience</p>                    
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container text-center">
            <h2 class="mb-4" data-aos="fade-up">Prêt à Concrétiser Votre Projet ?</h2>
            <p class="lead mb-4" data-aos="fade-up" data-aos-delay="100">Contactez-nous pour discuter de vos besoins</p>
            <a href="contact.php" class="btn btn-light" data-aos="fade-up" data-aos-delay="200">Démarrer un Projet</a>
        </div>
    </section>

    <script>
        // Gestion du filtrage des projets
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.filter-btn');
            const portfolioItems = document.querySelectorAll('.portfolio-item');
            const noProjectsMessage = document.querySelector('.no-projects-message');

            filterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    // Retirer la classe active de tous les boutons
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    // Ajouter la classe active au bouton cliqué
                    button.classList.add('active');

                    const category = button.getAttribute('data-category');
                    let hasVisibleItems = false;

                    portfolioItems.forEach(item => {
                        if (category === 'all' || item.getAttribute('data-category') === category) {
                            item.style.display = 'block';
                            hasVisibleItems = true;
                            // Animation de fade
                            item.style.opacity = '0';
                            setTimeout(() => {
                                item.style.opacity = '1';
                            }, 50);
                        } else {
                            item.style.display = 'none';
                        }
                    });

                    // Afficher/masquer le message "aucun projet"
                    if (!hasVisibleItems && category !== 'all') {
                        noProjectsMessage.style.display = 'block';
                        noProjectsMessage.style.opacity = '0';
                        setTimeout(() => {
                            noProjectsMessage.style.opacity = '1';
                        }, 50);
                    } else {
                        noProjectsMessage.style.display = 'none';
                    }
                });
            });
        });
    </script>

    <?php include("foot.php"); ?>

</body>
</html>