<?php
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/config/db.php';

if (isset($_GET['id_reparation']) && is_numeric($_GET['id_reparation'])) {
    $id_reparation = $_GET['id_reparation'];

    $stmt = $pdo->prepare("DELETE FROM reparation WHERE id_reparation = ?");
    $stmt->execute([$id_reparation]);

    // Rediriger vers la page principale après la suppression
    header("Location: index.php?delete_success=1"); // You can add a success parameter
    exit();
} else {
    // Si l'ID n'est pas valide, rediriger avec un message d'erreur (optionnel)
    header("Location: index.php?error=invalid_reparation_id");
    exit();
}
?>