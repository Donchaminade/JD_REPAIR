<?php
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_reparation = $_POST['id_reparation'];
    // $nom_demandeur = $_POST['nom_demandeur']; // Vous l'avez rendu readonly
    // $appareil = $_POST['appareil']; // Vous ne l'avez pas inclus dans le modal actuel
    // $panne = $_POST['panne'];   // Vous ne l'avez pas inclus dans le modal actuel
    $date_reparation = $_POST['date_reparation'];
    $montant_total = $_POST['montant_total'];
    $montant_paye = $_POST['montant_paye'];
    $reste_a_payer = $_POST['reste_a_payer'];
    $statut = $_POST['statut'];

    $stmt = $pdo->prepare("
        UPDATE reparation
        SET
            date_reparation = ?,
            montant_total = ?,
            montant_paye = ?,
            reste_a_payer = ?,
            statut = ?
        WHERE id_reparation = ?
    ");
    $stmt->execute([
        $date_reparation,
        $montant_total,
        $montant_paye,
        $reste_a_payer,
        $statut,
        $id_reparation
    ]);

    header("Location: index.php");
    exit();
} else {
    echo "Méthode non autorisée.";
}
?>