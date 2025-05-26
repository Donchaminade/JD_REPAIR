<?php
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_reparation = $_POST['id_reparation'];
    $date_reparation = $_POST['date_reparation'];
    $montant_total = $_POST['montant_total'];
    $montant_paye = $_POST['montant_paye'];
    $statut = $_POST['statut'];

    try {
        $stmt = $pdo->prepare("
            UPDATE reparation 
            SET date_reparation = ?, montant_total = ?, montant_paye = ?, statut = ?
            WHERE id_reparation = ?
        ");
        $stmt->execute([$date_reparation, $montant_total, $montant_paye, $statut, $id_reparation]);

        header("Location: index.php?update_success=1"); // Redirect with success message
        exit();

    } catch (PDOException $e) {
        header("Location: index.php?update_error=" . urlencode("Erreur lors de la mise à jour: " . $e->getMessage()));
        exit();
    }
} else {
    header("Location: index.php"); // Redirect if not a POST request
    exit();
}
?>