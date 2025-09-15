<?php
// Inclusion du fichier d'initialisation qui contient session_start et ENVIRONMENT
require_once 'includes/init.php';

// Définition des variables de page pour le template
$pageTitle = "Mon Profil";
$currentPage = 'profile';

// Récupération des informations de l'administrateur
try {
    $user_id = $_SESSION['admin_id'];
    $stmt = $pdo->prepare("SELECT * FROM administrateurs WHERE id = ?");
    $stmt->execute([$user_id]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$admin) {
        throw new Exception("Administrateur non trouvé");
    }
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header('Location: login.php');
    exit();
}

// Gestion de la mise à jour du profil
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validation des données
        $nom = trim($_POST['nom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        
        if (empty($nom) || empty($email)) {
            throw new Exception("Tous les champs sont obligatoires");
        }
        
        // Mise à jour du profil
        $stmt = $pdo->prepare("UPDATE administrateurs SET nom = ?, email = ? WHERE id = ?");
        $stmt->execute([$nom, $email, $user_id]);
        
        // Gestion de l'upload de l'image de profil
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['photo']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            if (in_array($ext, $allowed)) {
                $newname = uniqid() . '.' . $ext;
                $upload_dir = '../uploads/admin/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                // Supprimer l'ancienne photo si elle existe
                if ($admin['profile'] && file_exists('../' . $admin['profile'])) {
                    unlink('../' . $admin['profile']);
                }
                
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $upload_dir . $newname)) {
                    $profile = 'uploads/admin/' . $newname;
                    $stmt = $pdo->prepare("UPDATE administrateurs SET profile = ? WHERE id = ?");
                    $stmt->execute([$profile, $user_id]);
                }
            }
        }
        
        $_SESSION['success'] = "Profil mis à jour avec succès !";
        header('Location: profile.php');
        exit();
        
    } catch (Exception $e) {
        $_SESSION['error'] = "Erreur lors de la mise à jour du profil : " . $e->getMessage();
        header('Location: profile.php');
        exit();
    }
}

// Inclusion des fichiers de template
include 'includes/header.php';
include 'includes/sidebar.php';
?>

<!-- Styles personnalisés pour la page profil -->
<style>
.profile-header {
    background: #fff;
    padding: 2.5rem 0;
    margin-bottom: 2rem;
    border-radius: 0.5rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.1);
}

.profile-photo-container {
    position: relative;
    width: 120px;
    height: 120px;
    margin: 0 auto;
}

.profile-photo {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 3px solid #fff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    object-fit: cover;
    background-color: #f8f9fa;
}

.profile-photo-upload {
    position: absolute;
    bottom: 5px;
    right: 0;
    background: #fff;
    border: 1px solid rgba(0, 0, 0, 0.1);
    border-radius: 50%;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: all 0.2s ease;
}

.profile-photo-upload:hover {
    background: #f8f9fa;
    transform: scale(1.1);
}

.profile-photo-upload i {
    font-size: 14px;
    color: #495057;
}

.profile-info {
    text-align: left;
    padding: 0 1rem;
}

.profile-info h2 {
    color: #212529;
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.profile-info .user-role {
    color: #6c757d;
    font-size: 0.9rem;
    margin-bottom: 0.75rem;
}

.profile-info .user-email {
    color: #495057;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
}

.profile-info .user-email i {
    margin-right: 0.5rem;
    color: #6c757d;
}

.profile-stats {
    display: flex;
    gap: 2rem;
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid rgba(0, 0, 0, 0.1);
}

.stat-item {
    text-align: center;
}

.stat-value {
    font-size: 1.25rem;
    font-weight: 600;
    color: #212529;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.85rem;
    color: #6c757d;
}

@media (max-width: 768px) {
    .profile-info {
        text-align: center;
        margin-top: 1.5rem;
    }
    
    .profile-stats {
        justify-content: center;
    }
}

.profile-card {
    background: white;
    border-radius: 1rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
}

.profile-card .card-header {
    background: none;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    padding: 1.5rem;
}

.profile-card .card-body {
    padding: 2rem;
}

.form-control {
    border-radius: 0.5rem;
    padding: 0.75rem 1rem;
    border: 1px solid rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #6B73FF;
    box-shadow: 0 0 0 0.2rem rgba(107, 115, 255, 0.25);
}

.btn-primary {
    background: linear-gradient(135deg, #6B73FF 0%, #000DFF 100%);
    border: none;
    border-radius: 0.5rem;
    padding: 0.75rem 2rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
}

.alert {
    border-radius: 0.5rem;
    margin-bottom: 2rem;
}

.form-label {
    font-weight: 600;
    color: #444;
    margin-bottom: 0.5rem;
}

.input-group-text {
    background: none;
    border-radius: 0.5rem;
}
</style>

<main class="content">
    <div class="container-fluid">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['success']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['error']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="profile-header">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <div class="profile-photo-container">
                            <img src="<?php echo $admin['profile'] ? '../' . htmlspecialchars($admin['profile']) : 'https://via.placeholder.com/120?text=Photo'; ?>" 
                                 alt="Photo de profil" class="profile-photo">
                            <label for="photo" class="profile-photo-upload" title="Modifier la photo">
                                <i class="fas fa-camera"></i>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="profile-info">
                            <h2><?php echo htmlspecialchars($admin['nom']); ?></h2>
                            <div class="user-role">Administrateur</div>
                            <div class="user-email">
                                <i class="fas fa-envelope"></i>
                                <?php echo htmlspecialchars($admin['email']); ?>
                            </div>
                            <div class="profile-stats">
                                <div class="stat-item">
                                    <div class="stat-value">
                                        <?php 
                                            $stmt = $pdo->prepare("SELECT COUNT(*) FROM portfolio");
                                            $stmt->execute();
                                            echo $stmt->fetchColumn();
                                        ?>
                                    </div>
                                    <div class="stat-label">Projets</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value">
                                        <?php 
                                            $stmt = $pdo->prepare("SELECT COUNT(*) FROM equipe");
                                            $stmt->execute();
                                            echo $stmt->fetchColumn();
                                        ?>
                                    </div>
                                    <div class="stat-label">Membres</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value">
                                        <?php echo date('d/m/Y', strtotime($admin['last_login'] ?? 'now')); ?>
                                    </div>
                                    <div class="stat-label">Dernière connexion</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="profile-card">
                    <div class="card-header">
                        <h3 class="mb-0">Informations personnelles</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="form-group mb-4">
                                <label for="nom" class="form-label">Nom complet</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" id="nom" name="nom" 
                                           value="<?php echo htmlspecialchars($admin['nom']); ?>" required>
                                </div>
                            </div>
                            
                            <div class="form-group mb-4">
                                <label for="email" class="form-label">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?php echo htmlspecialchars($admin['email']); ?>" required>
                                </div>
                            </div>
                            
                            <div class="form-group mb-4">
                                <label for="photo" class="form-label">Photo de profil</label>
                                <input type="file" class="form-control" id="photo" name="photo" accept="image/*" hidden>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-image"></i></span>
                                    <input type="text" class="form-control" readonly placeholder="Aucun fichier sélectionné" 
                                           id="photo-name">
                                    <button type="button" class="btn btn-outline-secondary" onclick="document.getElementById('photo').click()">
                                        Choisir un fichier
                                    </button>
                                </div>
                            </div>
                            
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Mettre à jour le profil
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
// Mise à jour du nom du fichier sélectionné
document.getElementById('photo').addEventListener('change', function(e) {
    var fileName = e.target.files[0] ? e.target.files[0].name : 'Aucun fichier sélectionné';
    document.getElementById('photo-name').value = fileName;
});
</script>

</body>
</html>
