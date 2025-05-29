<?php
// Fichier: includes/auth.php
// Ce fichier doit être inclus au tout début de chaque page protégée.

/**
 * Démarre la session si elle n'est pas déjà active.
 * Doit être appelée au début de chaque script qui utilise les sessions.
 */
function ensure_session_started(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

/**
 * Définit les entêtes HTTP pour empêcher la mise en cache de la page.
 * Ceci est crucial pour empêcher l'accès via le bouton "Précédent" après déconnexion.
 */
function prevent_caching(): void {
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    // Pas besoin de Expires si Cache-Control est bien configuré
    // header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); 
}

/**
 * Vérifie si l'utilisateur est connecté.
 * @return bool True si l'utilisateur est connecté, false sinon.
 */
function is_user_logged_in(): bool {
    return isset($_SESSION['user_id']);
}

/**
 * Vérifie si l'utilisateur a le rôle autorisé pour accéder à la page.
 * @param string $required_role Le rôle requis pour accéder à la page.
 * @return bool True si l'utilisateur a le rôle autorisé, false sinon.
 */
function is_user_authorized(string $required_role): bool {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === $required_role;
}

// -----------------------------------------------------------------------------
// Exécution du code d'authentification
// -----------------------------------------------------------------------------

ensure_session_started();
prevent_caching();

if (!is_user_logged_in()) {
    // Si l'utilisateur n'est pas connecté, le rediriger vers la page de connexion
    header("Location: index.php"); // Assurez-vous que 'index.php' est votre page de connexion
    exit; // Arrêter l'exécution du script pour empêcher l'affichage du contenu protégé
}

// Optionnel: Exemple de vérification de rôle
/*
$required_role = 'admin'; // Le rôle requis pour cette page
if (!is_user_authorized($required_role)) {
    // Rediriger si le rôle n'est pas autorisé
    header("Location: unauthorized.php"); // Page d'accès non autorisé
    exit;
}
*/
?>```