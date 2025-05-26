<?php
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/config/db.php';

if (isset($_GET['id_reparation']) && is_numeric($_GET['id_reparation'])) {
    $id_reparation = $_GET['id_reparation'];

    try {
        // Fetch reparation details
        $stmt_reparation = $pdo->prepare("SELECT * FROM reparation WHERE id_reparation = ?");
        $stmt_reparation->execute([$id_reparation]);
        $reparation_data = $stmt_reparation->fetch(PDO::FETCH_ASSOC);

        if ($reparation_data) {
            $date_facture = date('Y-m-d');
            $montant_total = $reparation_data['montant_total'];
            // You might want to adjust these based on your logic
            $montant_paye = $reparation_data['montant_paye'];
            $reste_a_payer = $reparation_data['reste_a_payer'];
            $statut_paiement = ($montant_total <= $montant_paye) ? 'Payée' : 'Non payée';

            // Insert into facture table
            $stmt_facture = $pdo->prepare("
                INSERT INTO facture (id_reparation, date_facture, montant_total, montant_paye, reste_a_payer, statut_paiement)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt_facture->execute([$id_reparation, $date_facture, $montant_total, $montant_paye, $reste_a_payer, $statut_paiement]);
            $id_facture = $pdo->lastInsertId();

            // Redirect to a page to view/print the facture
            header("Location: view_facture.php?id_facture=" . $id_facture);
            exit();

        } else {
            // Handle case where reparation ID is not found
            header("Location: index.php?error=reparation_not_found");
            exit();
        }

    } catch (PDOException $e) {
        // Handle database errors
        header("Location: index.php?error=" . urlencode("Erreur de base de données: " . $e->getMessage()));
        exit();
    }

} else {
    // Invalid reparation ID
    header("Location: index.php?error=invalid_reparation_id");
    exit();
}
?>