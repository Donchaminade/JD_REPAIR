<?php
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/config/db.php';
require('fpdf/fpdf.php'); // Assurez-vous que le chemin est correct

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_reparation = $_POST['id_reparation'];
    $montant_total = $_POST['facture_montant_total'];
    $montant_regle = $_POST['montant_regle'];
    $date_facture = $_POST['date_facture'];
    $details = $_POST['details'];
    $reste_a_payer = $montant_total - $montant_regle;
    $statut_paiement = ($reste_a_payer <= 0) ? 'Payée' : (($montant_regle > 0) ? 'Partiellement payée' : 'Non payée');

    // Récupérer les informations de la réparation
    $stmt_reparation = $pdo->prepare("
        SELECT
            r.*,
            dr.nom_complet AS nom_demandeur
        FROM reparation r
        INNER JOIN demande_reparation dr ON r.id_demande = dr.id_demande
        WHERE r.id_reparation = ?
    ");
    $stmt_reparation->execute([$id_reparation]);
    $reparation = $stmt_reparation->fetch(PDO::FETCH_ASSOC);

    if ($reparation) {
        // Sauvegarder dans la table facture
        $stmt_facture = $pdo->prepare("
            INSERT INTO facture (id_reparation, date_facture, montant_total, montant_regle, reste_a_payer, details, statut_paiement)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt_facture->execute([$id_reparation, $date_facture, $montant_total, $montant_regle, $reste_a_payer, $details, $statut_paiement]);
        $id_facture = $pdo->lastInsertId();

        if ($reste_a_payer > 0) {
            echo "<script>alert('Attention : Le montant réglé est incomplet. La facture a été sauvegardée.');</script>";
        }

        // Générer le PDF de la facture
        $nom_fichier = 'facture_' . $id_facture . '.pdf';
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(190,10,'FACTURE N° ' . $id_facture,0,1,'C');
        $pdf->Ln(10);

        $pdf->SetFont('Arial','',12);
        $pdf->Cell(60,10,'Nom du client:',0,0);
        $pdf->Cell(130,10,htmlspecialchars($reparation['nom_demandeur']),0,1);

        $pdf->Cell(60,10,'Date de reparation:',0,0);
        $pdf->Cell(130,10,htmlspecialchars($reparation['date_reparation']),0,1);

        $pdf->Cell(60,10,'Date de la facture:',0,0);
        $pdf->Cell(130,10,htmlspecialchars($date_facture),0,1);

        $pdf->Cell(60,10,'Montant Total:',0,0);
        $pdf->Cell(130,10,htmlspecialchars($montant_total) . ' FCFA',0,1);

        $pdf->Cell(60,10,'Montant Regle:',0,0);
        $pdf->Cell(130,10,htmlspecialchars($montant_regle) . ' FCFA',0,1);

        $pdf->Cell(60,10,'Reste a Payer:',0,0);
        $pdf->Cell(130,10,htmlspecialchars($reste_a_payer) . ' FCFA',0,1);

        $pdf->Cell(60,10,'Statut Paiement:',0,0);
        $pdf->Cell(130,10,htmlspecialchars($statut_paiement),0,1);

        if ($details) {
            $pdf->Ln(5);
            $pdf->MultiCell(0, 10, 'Details: ' . htmlspecialchars($details));
        }

        $pdf->Output('D', $nom_fichier);
        exit();

    } else {
        echo "Erreur: Réparation non trouvée.";
        header("Location: index.php?error=reparation_not_found");
        exit();
    }

} else {
    header("Location: index.php");
    exit();
}
?>