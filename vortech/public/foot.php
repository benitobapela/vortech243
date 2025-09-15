<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
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
    </style>
</head>
<body>
    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col">
                    <h3>VorTech</h3>
                    <p>Innovation et excellence technologique</p>
                </div>
                <div class="col">
                    <h3>Contact</h3>
                    <p>
                        <?php echo $entreprise['contact']['pays']; ?>/
                        <?php echo $entreprise['contact']['ville']; ?> /
                        <?php echo $entreprise['contact']['adresse']; ?> <br>
                        <?php echo $entreprise['contact']['telephone']; ?> <br>
                        <?php echo $entreprise['contact']['email']; ?>
                    </p>
                </div>
                <div class="col">
                    <h3>Suivez-nous</h3>
                    <!-- Réseaux sociaux -->
                    <div class="social-links">
                        <?php
                        // Récupération des réseaux sociaux depuis la base de données
                        $stmt = $pdo->query("SELECT facebook, telegram, linkedin, instagram, youtube, whatsapp FROM reseaux_sociaux WHERE id = 1");
                        $reseaux = $stmt->fetch(PDO::FETCH_ASSOC);

                        if ($reseaux) {
                            if (!empty($reseaux['facebook'])) {
                                echo '<a href="' . htmlspecialchars($reseaux['facebook']) . '" target="_blank" class="social-link" title="Facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>';
                            }
                            if (!empty($reseaux['telegram'])) {
                                echo '<a href="' . htmlspecialchars($reseaux['telegram']) . '" target="_blank" class="social-link" title="Telegram">
                                    <i class="fab fa-telegram-plane"></i>
                                </a>';
                            }
                            if (!empty($reseaux['linkedin'])) {
                                echo '<a href="' . htmlspecialchars($reseaux['linkedin']) . '" target="_blank" class="social-link" title="LinkedIn">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>';
                            }
                            if (!empty($reseaux['instagram'])) {
                                echo '<a href="' . htmlspecialchars($reseaux['instagram']) . '" target="_blank" class="social-link" title="Instagram">
                                    <i class="fab fa-instagram"></i>
                                </a>';
                            }
                            if (!empty($reseaux['youtube'])) {
                                echo '<a href="' . htmlspecialchars($reseaux['youtube']) . '" target="_blank" class="social-link" title="YouTube">
                                    <i class="fab fa-youtube"></i>
                                </a>';
                            }
                            if (!empty($reseaux['whatsapp'])) {
                                echo '<a href="' . htmlspecialchars($reseaux['whatsapp']) . '" target="_blank" class="social-link" title="WhatsApp">
                                    <i class="fab fa-whatsapp"></i>
                                </a>';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; 2024 VorTech. Tous droits réservés.</p>
            </div>
        </div>
    </footer>
</body>
</html>