<?php
session_start();
require_once 'db.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$pageTitle = "Paramètres";
$currentPage = 'settings';

// Récupérer les paramètres existants
$query = $db->query("SELECT * FROM configurations");
$settings = $query->fetchAll(PDO::FETCH_ASSOC);

// Gestion de la mise à jour des paramètres
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        foreach ($_POST as $key => $value) {
            if ($key !== 'type') {
                $stmt = $db->prepare("UPDATE configurations SET valeur = ? WHERE cle = ?");
                $stmt->execute([$value, $key]);
            }
        }
        $_SESSION['success'] = "Paramètres mis à jour avec succès !";
    } catch (Exception $e) {
        $_SESSION['error'] = "Erreur lors de la mise à jour des paramètres : " . $e->getMessage();
    }
}

// Inclusion du header
include 'includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-3">
            <?php include 'includes/sidebar.php'; ?>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">Paramètres du site</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success"><?php echo $_SESSION['success']; ?></div>
                        <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger"><?php echo $_SESSION['error']; ?></div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="row">
                            <?php foreach ($settings as $setting): ?>
                                <div class="col-md-6 mb-3">
                                    <label for="<?php echo $setting['cle']; ?>" class="form-label">
                                        <?php echo htmlspecialchars($setting['nom']); ?>
                                    </label>
                                    <input type="text" class="form-control" 
                                           id="<?php echo $setting['cle']; ?>" 
                                           name="<?php echo $setting['cle']; ?>" 
                                           value="<?php echo htmlspecialchars($setting['valeur']); ?>">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
