<?php
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/config/db.php';

if (isset($_GET['id_utilisateur']) && is_numeric($_GET['id_utilisateur'])) {
    $id_utilisateur = $_GET['id_utilisateur'];

    $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id_utilisateur = ?");
    $stmt->execute([$id_utilisateur]);

    echo '<script>window.location.href = "utilisateurs.php";</script>';
    exit();
} else {
    echo "ID d'utilisateur invalide.";
}
?>