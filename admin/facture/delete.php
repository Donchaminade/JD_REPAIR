<?php
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/config/db.php';

if (isset($_GET['id_facture']) && is_numeric($_GET['id_facture'])) {
    $id_facture = $_GET['id_facture'];

    $stmt = $pdo->prepare("DELETE FROM facture WHERE id_facture = ?");
    $stmt->execute([$id_facture]);

    // Rediriger vers la page de gestion des factures après la suppression
    header("Location: index.php?delete_success=facture"); // Ajout d'un paramètre de succès spécifique aux factures
    exit();
} else {
    // Si l'ID de la facture n'est pas valide
    header("Location: index.php?error=invalid_facture_id");
    exit();
}
?>