<?php
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/config/db.php';

if (isset($_GET['id_demande']) && is_numeric($_GET['id_demande'])) {
    $id_demande = $_GET['id_demande'];

    $stmt = $pdo->prepare("DELETE FROM demande_reparation WHERE id_demande = ?");
    $stmt->execute([$id_demande]);

    // Rediriger vers la page principale après la suppression
    header("Location: index.php");
    exit();
} else {
    // Si l'ID n'est pas valide, rediriger avec un message d'erreur (optionnel)
    header("Location: index.php?error=invalid_id");
    exit();
}
?>