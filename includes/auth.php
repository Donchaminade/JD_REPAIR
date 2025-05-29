<?php
// Fichier: includes/auth.php
// Ce fichier doit être inclus au tout début de chaque page protégée.

session_start(); // Démarrer la session

// Définir les entêtes HTTP pour empêcher la mise en cache de la page
// Ceci est crucial pour empêcher l'accès via le bouton "Précédent" après déconnexion
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date d'expiration dans le passé

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

if (!is_logged_in()) {
    // Si l'utilisateur n'est pas connecté, le rediriger vers la page de connexion
    header("Location: index.php"); // Assurez-vous que 'index.php' est votre page de connexion
    exit; // Arrêter l'exécution du script pour empêcher l'affichage du contenu protégé
}

// Optionnel: Vous pouvez ajouter ici une vérification de rôle si certaines pages
// sont spécifiques à 'admin' ou 'technicien'.
/*
if (isset($_SESSION['user_role']) && $_SESSION['user_role'] !== 'admin') {
    // Rediriger si le rôle n'est pas autorisé pour cette page
    header("Location: unauthorized.php"); // Page d'accès non autorisé
    exit;
}
*/
?>```