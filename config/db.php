<?php
// Paramètres de connexion à la base de données
$host = 'localhost';      // Serveur MySQL (généralement localhost)
$dbname = 'reparationdb'; // Nom de ta base
$user = 'root';           // Utilisateur MySQL (par défaut sur XAMPP)
$pass = '';               // Mot de passe vide si non défini

try {
    // Connexion à la base (port par défaut = 3306)
    $pdo = new PDO("mysql:host=$host;port=3306;dbname=$dbname;charset=utf8", $user, $pass);
    // Options d'erreur
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
