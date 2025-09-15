<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="En savoir plus sur VorTech et notre équipe d'experts en technologie">
    <title>VorTech - À propos de moi</title>
    <style>
        /* Styles pour la page About Me */
        :root {
            --primary-color: #007bff;
            --secondary-color: #6c757d;
            --dark-color: #343a40;
            --light-color: #f8f9fa;
            --transition: all 0.3s ease;
        }

        /* Hero Section */
        .aboutme-hero {
            height: 60vh;
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('images/aboutme-bg.jpg');
            background-size: cover;
            background-position: center;
            color: #fff;
            display: flex;
            align-items: center;
            text-align: center;
            padding-top: 80px;
        }

        .aboutme-hero h1 {
            font-size: 3.5rem;
            margin-bottom: 20px;
            animation: fadeInDown 1s ease;
        }

        .aboutme-hero p {
            font-size: 1.2rem;
            max-width: 800px;
            margin: 0 auto 30px;
            animation: fadeInUp 1s ease;
        }

        /* Profile Section */
        .profile-section {
            padding: 100px 0;
            background: var(--light-color);
        }

        .profile-image {
            width: 300px;
            height: 300px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 30px;
            display: block;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: var(--transition);
        }

        .profile-image:hover {
            transform: scale(1.05);
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        }

        /* Skills Section */
        .skills-section {
            padding: 100px 0;
        }

        .skill-card {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            transition: var(--transition);
            height: 100%;
            text-align: center;
        }

        .skill-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .skill-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        /* Experience Section */
        .experience-section {
            padding: 100px 0;
            background: var(--light-color);
        }

        .timeline {
            position: relative;
            max-width: 800px;
            margin: 0 auto;
        }

        .timeline-item {
            padding: 30px;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            position: relative;
            transition: var(--transition);
        }

        .timeline-item:hover {
            transform: translateX(10px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .timeline-date {
            color: var(--primary-color);
            font-weight: bold;
            margin-bottom: 10px;
        }

        /* Contact Section */
        .contact-section {
            padding: 100px 0;
            text-align: center;
            background: linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.8)), url('images/contact-bg.jpg');
            background-size: cover;
            background-position: center;
            color: #fff;
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }

        .social-link {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.5rem;
            transition: var(--transition);
        }

        .social-link:hover {
            background: var(--primary-color);
            transform: translateY(-5px);
        }

        /* Animations */
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

        /* Container and Grid */
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin: -15px;
        }

        .col {
            flex: 1;
            padding: 15px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .aboutme-hero h1 {
                font-size: 2.5rem;
            }

            .profile-image {
                width: 200px;
                height: 200px;
            }

            .col {
                flex: 0 0 100%;
            }

            .timeline-item {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <?php include("nav.php"); ?>

    <!-- Hero Section -->
    <section class="aboutme-hero">
        <div class="container">
            <h1>À Propos de Moi</h1>
            <p>Passionné par la technologie et l'innovation, je crée des solutions numériques qui font la différence</p>
        </div>
    </section>

    <!-- Profile Section -->
    <section class="profile-section">
        <div class="container">
            <img src="images/profile.jpg" alt="Mon profil" class="profile-image">
            <div class="text-center">
                <h2>John Doe</h2>
                <p class="lead">Développeur Full Stack & Fondateur de VorTech</p>
                <p>Avec plus de 10 ans d'expérience dans le développement web et mobile, je suis passionné par la création de solutions innovantes qui répondent aux besoins des entreprises modernes.</p>
            </div>
        </div>
    </section>

    <!-- Skills Section -->
    <section class="skills-section">
        <div class="container">
            <h2 class="text-center mb-5">Mes Compétences</h2>
            <div class="row">
                <div class="col">
                    <div class="skill-card">
                        <i class="fas fa-code skill-icon"></i>
                        <h3>Développement Web</h3>
                        <p>HTML5, CSS3, JavaScript, React, Vue.js, Node.js</p>
                    </div>
                </div>
                <div class="col">
                    <div class="skill-card">
                        <i class="fas fa-mobile-alt skill-icon"></i>
                        <h3>Développement Mobile</h3>
                        <p>React Native, Flutter, iOS, Android</p>
                    </div>
                </div>
                <div class="col">
                    <div class="skill-card">
                        <i class="fas fa-database skill-icon"></i>
                        <h3>Base de Données</h3>
                        <p>MySQL, MongoDB, PostgreSQL, Firebase</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Experience Section -->
    <section class="experience-section">
        <div class="container">
            <h2 class="text-center mb-5">Mon Parcours</h2>
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-date">2019 - Présent</div>
                    <h3>Fondateur & CEO - VorTech</h3>
                    <p>Création et direction de VorTech, entreprise spécialisée dans le développement de solutions numériques innovantes.</p>
                </div>
                <div class="timeline-item">
                    <div class="timeline-date">2015 - 2019</div>
                    <h3>Lead Developer - TechCorp</h3>
                    <p>Direction d'une équipe de développeurs sur des projets d'envergure internationale.</p>
                </div>
                <div class="timeline-item">
                    <div class="timeline-date">2012 - 2015</div>
                    <h3>Développeur Full Stack - StartupInc</h3>
                    <p>Développement de solutions web et mobiles pour des startups innovantes.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section">
        <div class="container">
            <h2>Me Contacter</h2>
            <p>Vous avez un projet ? N'hésitez pas à me contacter</p>
            <div class="social-links">
                <a href="#" class="social-link"><i class="fab fa-linkedin"></i></a>
                <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                <a href="#" class="social-link"><i class="fab fa-github"></i></a>
                <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </section>
</body>
</html>