<?php
// Configuration de base de la session AVANT de la démarrer
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_samesite', 'Lax');

// Démarrer la session
session_start();

// Inclure les fichiers nécessaires
require_once 'includes/init.php';

// Vérifier si déjà connecté
if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';
$debug_logs = [];

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = cleanInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = "Veuillez remplir tous les champs.";
    } else {
        // Vérifier les identifiants
        $admin = verifyCredentials($email, $password);
        
        if ($admin) {
            // Stocker les informations de session
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_email'] = $admin['email'];
            $_SESSION['admin_nom'] = $admin['nom'];
            $_SESSION['last_activity'] = time();
            
            // Rediriger vers la page demandée ou l'index
            $redirect = $_SESSION['redirect_after_login'] ?? 'index.php';
            unset($_SESSION['redirect_after_login']);
            
            header('Location: ' . $redirect);
            exit;
        } else {
            $error = "Email ou mot de passe incorrect";
            // Récupérer les logs de débogage
            $debug_logs = $_SESSION['debug_logs'] ?? [];
            unset($_SESSION['debug_logs']);
        }
    }
}

// Vérifier si la session a expiré
if (isset($_GET['expired'])) {
    $error = "Votre session a expiré. Veuillez vous reconnecter.";
}

// Vérifier si déconnexion demandée
if (isset($_GET['logout'])) {
    logout();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Administration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo img {
            max-width: 200px;
            height: auto;
        }
        .debug-logs {
            margin-top: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            font-family: monospace;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="logo">
                <img src="../public/images/logo.png" alt="Logo" class="img-fluid">
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo e($error); ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required 
                           value="<?php echo isset($_POST['email']) ? e($_POST['email']) : ''; ?>">
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Se connecter</button>
                </div>
            </form>
            
            <?php if (!empty($debug_logs)): ?>
                <div class="debug-logs">
                    <strong>Logs de débogage :</strong><br>
                    <?php foreach ($debug_logs as $log): ?>
                        <?php echo e($log); ?><br>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 