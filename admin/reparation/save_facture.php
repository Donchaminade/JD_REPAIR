<?php
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/config/db.php';

// Chemin vers la librairie TCPDF (à adapter selon votre structure de dossiers)
require_once($_SERVER['DOCUMENT_ROOT'] . '/JD_REPAIR/tcpdf/tcpdf.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_reparation = $_POST['id_reparation'] ?? null;
    $montant_total = $_POST['facture_montant_total'] ?? null;
    $montant_regle = $_POST['montant_regle'] ?? null;
    $date_facture = $_POST['date_facture'] ?? null;
    $details = $_POST['details'] ?? '';
    $from_traitement = isset($_POST['from_traitement']);

    if ($id_reparation === null || $montant_total === null || $montant_regle === null || $date_facture === null) {
        echo "Erreur : Veuillez fournir toutes les informations nécessaires pour la facture.";
        exit;
    }

    $reste_a_payer = $montant_total - $montant_regle;
    $statut_paiement = ($reste_a_payer <= 0) ? 'Payée' : (($montant_regle > 0) ? 'Partiellement payée' : 'Non payée');

    $nom_demandeur = '';
    $date_reparation_facture = '';

    if ($from_traitement) {
        // Récupérer les informations du traitement
        $stmt_traitement = $pdo->prepare("
            SELECT
                t.id_traitement,
                d.nom_complet AS nom_demandeur,
                t.date_reception AS date_reparation,
                t.montant_total
            FROM traitement t
            JOIN demande_reparation d ON t.id_demande = d.id_demande
            WHERE t.id_traitement = ?
        ");
        $stmt_traitement->execute([$id_reparation]);
        $source_info = $stmt_traitement->fetch(PDO::FETCH_ASSOC);
        if ($source_info) {
            $nom_demandeur = $source_info['nom_demandeur'];
            $date_reparation_facture = $source_info['date_reparation'];
            $montant_total = $source_info['montant_total']; // S'assurer d'utiliser le montant du traitement
        } else {
            echo "Erreur: Traitement non trouvé.";
            header("Location: liste_traitements.php?error=traitement_not_found");
            exit();
        }

        // Vérifier si une facture existe déjà pour ce traitement
        $stmt_check_facture = $pdo->prepare("SELECT id_facture FROM facture WHERE id_reparation = ?");
        $stmt_check_facture->execute([$id_reparation]);
        if ($stmt_check_facture->fetch()) {
            echo "<script>alert('Une facture existe déjà pour ce traitement.'); window.location.href = 'liste_traitements.php';</script>";
            exit();
        }

        // Sauvegarder la facture en utilisant l'ID du traitement comme id_reparation
        $stmt_facture = $pdo->prepare("
            INSERT INTO facture (id_reparation, date_facture, montant_total, montant_regle, reste_a_payer, details, statut_paiement)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt_facture->execute([$id_reparation, $date_facture, $montant_total, $montant_regle, $reste_a_payer, $details, $statut_paiement]);
        $id_facture = $pdo->lastInsertId();

    } else {
        // Si la facturation provient de la page des réparations
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
            $nom_demandeur = $reparation['nom_demandeur'];
            $date_reparation_facture = $reparation['date_reparation'];

            // Sauvegarder dans la table facture
            $stmt_facture = $pdo->prepare("
                INSERT INTO facture (id_reparation, date_facture, montant_total, montant_regle, reste_a_payer, details, statut_paiement)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt_facture->execute([$id_reparation, $date_facture, $montant_total, $montant_regle, $reste_a_payer, $details, $statut_paiement]);
            $id_facture = $pdo->lastInsertId();
        } else {
            echo "Erreur: Réparation non trouvée.";
            header("Location: index.php?error=reparation_not_found");
            exit();
        }
    }

    // Générer le PDF avec TCPDF
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Votre Nom/Nom de l\'Entreprise');
    $pdf->SetTitle('Facture N° ' . $id_facture);
    $pdf->SetSubject('Facture');
    $pdf->SetKeywords('facture, réparation');

    // set default header data
    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING);

    // set header and footer fonts
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    // set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    // set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // set some language-dependent strings (optional)
    if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
        require_once(dirname(__FILE__).'/lang/eng.php');
        $pdf->setLanguageArray($l);
    }

    // ---------------------------------------------------------

    // set default font subsetting mode
    $pdf->setFontSubsetting(true);

    // Set font
    $pdf->SetFont('helvetica', '', 12, '', true);

    // Add a page
    $pdf->AddPage();

    // Titre de la facture
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, 'FACTURE N° ' . $id_facture, 0, 1, 'C');
    $pdf->Ln(10);

    // Informations
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(60, 10, 'Nom du client:', 0, 0);
    $pdf->Cell(130, 10, htmlspecialchars($nom_demandeur), 0, 1);

    $pdf->Cell(60, 10, 'Date de reparation:', 0, 0);
    $pdf->Cell(130, 10, htmlspecialchars($date_reparation_facture), 0, 1);

    $pdf->Cell(60, 10, 'Date de la facture:', 0, 0);
    $pdf->Cell(130, 10, htmlspecialchars($date_facture), 0, 1);

    $pdf->Cell(60, 10, 'Montant Total:', 0, 0);
    $pdf->Cell(130, 10, htmlspecialchars($montant_total) . ' FCFA', 0, 1);

    $pdf->Cell(60, 10, 'Montant Regle:', 0, 0);
    $pdf->Cell(130, 10, htmlspecialchars($montant_regle) . ' FCFA', 0, 1);

    $pdf->Cell(60, 10, 'Reste a Payer:', 0, 0);
    $pdf->Cell(130, 10, htmlspecialchars($reste_a_payer) . ' FCFA', 0, 1);

    $pdf->Cell(60, 10, 'Statut Paiement:', 0, 0);
    $pdf->Cell(130, 10, htmlspecialchars($statut_paiement), 0, 1);

    if ($details) {
        $pdf->Ln(5);
        $pdf->MultiCell(0, 10, 'Details: ' . htmlspecialchars($details));
    }

    // ---------------------------------------------------------

    // Close and output PDF document
    $pdf->Output('facture_' . $id_facture . '.pdf', 'D');
    exit();

} else {
    header("Location: index.php");
    exit();
}
?>