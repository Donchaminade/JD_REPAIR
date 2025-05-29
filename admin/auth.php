<?php
session_start();

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

if (!is_logged_in()) {
    header("Location: index.php"); // Rediriger vers la page de connexion (votre index.php)
    exit; // Important : arrêter l'exécution du script
}
?>