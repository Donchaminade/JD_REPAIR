<?php
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_utilisateur = $_POST['id_utilisateur'];
    $nom_complet = htmlspecialchars($_POST['nom_complet']);
    $email = htmlspecialchars($_POST['email']);
    $role = htmlspecialchars($_POST['role']);

    $stmt = $pdo->prepare("UPDATE utilisateurs SET nom_complet = ?, email = ?, role = ? WHERE id_utilisateur = ?");
    $stmt->execute([$nom_complet, $email, $role, $id_utilisateur]);

    echo '<script>window.location.href = "utilisateurs.php";</script>';
    exit();
} else {
    echo "Méthode non autorisée.";
}
?>