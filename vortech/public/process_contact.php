<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Validation
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $_SESSION['contact_error'] = "Tous les champs sont obligatoires.";
        header('Location: contact.php');
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['contact_error'] = "L'adresse email n'est pas valide.";
        header('Location: contact.php');
        exit;
    }

    try {
        // Préparation de la requête
        $sql = "INSERT INTO contacts (nom, email, sujet, message, date_envoi) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $pdo->prepare($sql);
        
        // Exécution de la requête
        if ($stmt->execute([$name, $email, $subject, $message])) {
            $_SESSION['contact_success'] = "Votre message a été envoyé avec succès !";
        } else {
            $_SESSION['contact_error'] = "Une erreur est survenue lors de l'envoi du message.";
        }
    } catch (PDOException $e) {
        $_SESSION['contact_error'] = "Une erreur est survenue lors de l'envoi du message.";
    }

    header('Location: contact.php');
    exit;
}

// Redirection si accès direct au fichier
header('Location: contact.php');
exit; 