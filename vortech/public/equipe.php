<?php
require_once 'config.php';

// Récupération des membres de l'équipe actifs
$stmt = $pdo->query("SELECT * FROM equipe WHERE actif = 1 ORDER BY ordre ASC, nom ASC");
$membres = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Découvrez l'équipe de <?php echo $entreprise['nom']; ?>, des experts passionnés par l'innovation technologique.">
    <title><?php echo $entreprise['nom']; ?> - Notre Équipe</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        .team-hero {
            background: linear-gradient(rgba(0,0,0, 0.7), rgba(0,0,0,0.7)), url('images/team-hero-bg.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 60vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #fff;
            padding: 100px 0;
            position: relative;
        }

        .team-hero::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 100px;
            background: linear-gradient(to top, var(--light-color), transparent);
        }

        .team-hero h1 {
            font-size: 3.5rem;
            margin-bottom: 20px;
            animation: fadeInDown 1s ease;
        }

        .team-hero p {
            font-size: 1.2rem;
            max-width: 800px;
            margin: 0 auto;
            animation: fadeInUp 1s ease;
        }

        /* Team Section */
        .team-section {
            padding: 100px 0;
            background-color: var(--light-color);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .section-title {
            text-align: center;
            margin-bottom: 60px;
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
            font-size: 1.1rem;
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            padding: 20px;
        }

        .team-member {
            background: #fff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--shadow-md);
            transition: var(--transition);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .team-member:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-lg);
        }

        .member-img {
            position: relative;
            overflow: hidden;
            height: 300px;
        }

        .member-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .team-member:hover .member-img img {
            transform: scale(1.1);
        }

        .member-info {
            padding: 25px;
            text-align: center;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .member-info h3 {
            color: var(--dark-color);
            font-size: 1.25rem;
            margin-bottom: 5px;
        }

        .member-info .position {
            color: var(--primary-color);
            font-weight: 500;
            margin-bottom: 15px;
        }

        .member-info .description {
            color: var(--secondary-color);
            font-size: 0.9rem;
            margin-bottom: 20px;
            flex-grow: 1;
        }

        /* Réseaux sociaux */
        .social-links {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin-top: auto;
            padding-top: 20px;
        }

        .social-links a {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1rem;
            transition: all 0.3s ease;
            text-decoration: none;
            position: relative;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3);
        }

        .social-links a:hover {
            background: #1d4ed8;
            color: #fff;
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
        }

        .social-links a i {
            position: relative;
            z-index: 1;
            transition: all 0.3s ease;
        }

        .social-links a:hover i {
            transform: scale(1.2);
        }

        @media (max-width: 991px) {
            .social-links {
                justify-content: center;
                margin-top: 20px;
            }
        }

        @media (max-width: 768px) {
            .team-hero h1 {
                font-size: 2.5rem;
            }
            
            .team-hero p {
                font-size: 1rem;
            }
            
            .section-title h2 {
                font-size: 2rem;
            }
            
            .team-grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 20px;
            }
            
            .member-img {
                height: 250px;
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .team-member {
            text-align: center;
            margin-bottom: 30px;
            transition: transform 0.3s ease;
        }
        .team-member:hover {
            transform: translateY(-10px);
        }
        .team-member img {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 20px;
            border: 5px solid #fff;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .team-member h3 {
            margin-bottom: 10px;
            color: #333;
        }
        .team-member .position {
            color: #666;
            margin-bottom: 15px;
        }
        .team-member .description {
            color: #777;
            margin-bottom: 20px;
        }
        .social-links {
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        .social-links a {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .social-links a:hover {
            background: #2563eb;
            color: #fff;
            transform: translateY(-3px);
        }
    </style>
</head>
<body>
    <?php include './nav.php'; ?>

    <section class="team-hero">
        <div class="container">
            <h1 data-aos="fade-down">Notre Équipe</h1>
            <p data-aos="fade-up">Découvrez les talents passionnés qui font de <?php echo $entreprise['nom']; ?> une entreprise unique et innovante.</p>
        </div>
    </section>

    <section class="team-section">
        <div class="container">
            <div class="section-title">
                <h2 data-aos="fade-up">Des Experts Passionnés</h2>
                <p data-aos="fade-up" data-aos-delay="100">Une équipe dévouée qui travaille ensemble pour donner vie à vos projets.</p>
            </div>

            <div class="team-grid">
                <?php foreach ($membres as $membre): ?>
                <div class="col-md-4">
                    <div class="team-member">
                        <?php if ($membre['photo']): ?>
                            <img src="../admin/uploads/equipe/<?php echo e($membre['photo']); ?>" 
                                 alt="<?php echo e($membre['nom']); ?>" 
                                 class="img-fluid">
                        <?php else: ?>
                            <img src="../admin/images/default-profile.jpg" 
                                 alt="<?php echo e($membre['nom']); ?>" 
                                 class="img-fluid">
                        <?php endif; ?>
                        
                        <h3><?php echo e($membre['nom']); ?></h3>
                        <div class="position"><?php echo e($membre['poste']); ?></div>
                        
                        <?php if ($membre['description']): ?>
                        <div class="description">
                            <?php echo nl2br(e($membre['description'])); ?>
                        </div>
                        <?php endif; ?>
                        
                        <div class="social-links">
                            <?php if ($membre['linkedin']): ?>
                            <a href="<?php echo e($membre['linkedin']); ?>" target="_blank" title="LinkedIn">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <?php endif; ?>
                            
                            <?php if ($membre['facebook']): ?>
                            <a href="<?php echo e($membre['facebook']); ?>" target="_blank" title="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <?php endif; ?>
                            
                            <?php if ($membre['instagram']): ?>
                            <a href="<?php echo e($membre['instagram']); ?>" target="_blank" title="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <?php endif; ?>
                            
                            <?php if ($membre['whatsapp']): ?>
                            <a href="<?php echo e($membre['whatsapp']); ?>" target="_blank" title="WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <?php endif; ?>
                            
                            <?php if ($membre['twitter']): ?>
                            <a href="<?php echo e($membre['twitter']); ?>" target="_blank" title="Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <?php include("./foot.php"); ?>

    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        AOS.init({
            duration: 800,
            offset: 100,
            once: true
        });
    </script>
</body>
</html>