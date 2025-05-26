<?php
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_demande = $_POST['id_demande'] ?? null;
    $date_reception = $_POST['date_reception'] ?? null;
    $montant_total = $_POST['montant_total'] ?? 0.00;
    $montant_paye = $_POST['montant_paye'] ?? 0.00;
    $type_reparation = $_POST['type_reparation'] ?? 'standard';
    $id_technicien = $_POST['id_technicien'] ?? null;

    if ($id_demande === null || $date_reception === null || $id_technicien === null) {
        echo "Erreur : Des informations obligatoires sont manquantes.";
        exit;
    }

    try {
        $sql = "INSERT INTO traitement (id_demande, date_reception, montant_total, montant_paye, type_reparation, id_technicien)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_demande, $date_reception, $montant_total, $montant_paye, $type_reparation, $id_technicien]);

        echo 'success'; // Réponse pour indiquer le succès

    } catch (PDOException $e) {
        echo "Erreur lors de l'enregistrement : " . $e->getMessage();
    }
} else {
    header("Location: index.php");
    exit();
}
?>