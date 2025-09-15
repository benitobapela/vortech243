<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Contactez VorTech pour vos projets technologiques innovants. Notre équipe est à votre écoute.">
    <title>VorTech - Contact</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
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
        .contact-hero {
            min-height: 60vh;
            background: linear-gradient(rgba(0,0,0), rgba(0,0,0,0.4)), url('images/contact-bg.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            position: relative;
            padding: 120px 0 60px;
            color: #fff;
        }

        .contact-hero::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 100px;
            background: linear-gradient(to top, var(--light-color), transparent);
        }

        /* Contact Section */
        .contact-section {
            padding: 120px 0;
            background: var(--light-color);
            position: relative;
        }

        .contact-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            padding: 0 20px;
        }

        .contact-info {
            background: #fff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: var(--shadow-md);
            transition: var(--transition);
        }

        .contact-info:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-lg);
        }

        .contact-info-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 30px;
        }

        .contact-info-icon {
            width: 50px;
            height: 50px;
            background: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.2rem;
            margin-right: 20px;
            flex-shrink: 0;
        }

        .contact-info-content h4 {
            color: var(--dark-color);
            margin-bottom: 5px;
            font-size: 1.2rem;
        }

        .contact-info-content p {
            color: var(--secondary-color);
            font-size: 0.95rem;
        }

        /* Form Styles */
        .contact-form {
            background: #fff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: var(--shadow-md);
            transition: var(--transition);
        }

        .contact-form:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-lg);
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            color: var(--dark-color);
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 12px 20px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            transition: var(--transition);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }

        .submit-btn {
            background: var(--primary-color);
            color: #fff;
            padding: 15px 30px;
            border: none;
            border-radius: 30px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            display: inline-block;
            text-align: center;
            text-decoration: none;
        }

        .submit-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* Map Section */
        .map-section {
            padding: 120px 0;
            background: #fff;
        }

        .map-container {
            height: 500px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }

        #map {
            height: 100%;
            width: 100%;
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

        /* Footer */
        footer {
            background: var(--dark-color);
            color: #fff;
            padding: 80px 0 30px;
        }

        footer h3 {
            color: #fff;
            font-size: 1.5rem;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }

        footer h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 2px;
            background: var(--primary-color);
        }

        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .social-links a {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.2rem;
            transition: var(--transition);
            text-decoration: none;
        }

        .social-links a:hover {
            background: var(--primary-color);
            transform: translateY(-3px);
        }

        .copyright {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
            text-align: center;
            font-size: 0.9rem;
            color: rgba(255,255,255,0.7);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .contact-hero {
                min-height: 50vh;
                padding: 80px 0 40px;
            }

            .contact-container {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .contact-info, .contact-form {
                padding: 30px;
            }

            .map-container {
                height: 300px;
            }

            .section-title h2 {
                font-size: 2rem;
            }

            .col {
                flex: 0 0 100%;
            }
        }
    </style>
</head>
<body>
    <?php include("nav.php"); ?>

    <!-- Hero Section -->
    <section class="contact-hero">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Contactez-nous</h2>
                <p>Discutons de vos projets et de vos besoins</p>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section">
        <div class="container">
            <div class="contact-container">
                <!-- Contact Info -->
                <div class="contact-info" data-aos="fade-right">
                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-info-content">
                            <h4>Notre Adresse</h4>
                            <?php echo $entreprise['contact']['pays']; ?>/
                            <?php echo $entreprise['contact']['ville']; ?> /
                            <?php echo $entreprise['contact']['adresse']; ?>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="contact-info-content">
                            <h4>Téléphone</h4>
                            <p><?php echo $entreprise['contact']['telephone']; ?></p>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-info-content">
                            <h4>Email</h4>
                            <p><?php echo $entreprise['contact']['email']; ?></p>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="contact-info-content">
                            <h4>Horaires</h4>
                            <p>Lundi - Vendredi : 9h - 18h<br>Weekend : Fermé</p>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="contact-form" data-aos="fade-left">
                    <?php if (isset($_SESSION['contact_success'])): ?>
                    <div class="alert alert-success" role="alert">
                        <?php 
                        echo $_SESSION['contact_success'];
                        unset($_SESSION['contact_success']);
                        ?>
                    </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['contact_error'])): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php 
                        echo $_SESSION['contact_error'];
                        unset($_SESSION['contact_error']);
                        ?>
                    </div>
                    <?php endif; ?>

                    <form action="process_contact.php" method="POST">
                        <div class="form-group">
                            <label class="form-label" for="name">Nom complet</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="subject">Sujet</label>
                            <input type="text" class="form-control" id="subject" name="subject" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="message">Message</label>
                            <textarea class="form-control" id="message" name="message" required></textarea>
                        </div>
                        <button type="submit" class="submit-btn">Envoyer le message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="map-section">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Notre Localisation</h2>
                <p>Venez nous rencontrer dans nos locaux</p>
            </div>
            <div class="map-container" data-aos="zoom-in">
                <div id="map"></div>
            </div>
        </div>
    </section>

    <?php include("foot.php"); ?>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        // Initialisation de la carte
        var map = L.map('map').setView([48.8566, 2.3522], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Ajout du marqueur
        var marker = L.marker([48.8566, 2.3522]).addTo(map);
        marker.bindPopup("<b>VorTech</b><br>123 Avenue de l'Innovation").openPopup();
    </script>
</body>
</html>