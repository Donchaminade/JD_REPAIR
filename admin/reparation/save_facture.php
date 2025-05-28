<?php
ob_start(); // Start output buffering
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/config/db.php';
require_once($_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/libs/fpdf/fpdf.php');

class PDF extends FPDF
{
    private $primaryColor = array(63, 169, 245);
    private $secondaryColor = array(52, 58, 64);

    function Header()
    {
        $this->SetMargins(15, 15, 15);
        $this->SetFillColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
        $this->Rect(0, 0, 210, 10, 'F');

        $this->SetFont('Arial','B',12);
        $this->SetTextColor($this->secondaryColor[0], $this->secondaryColor[1], $this->secondaryColor[2]);
        $this->SetXY(15, 15);
        $this->Cell(0, 6, $this->sansAccent('JD Repair'), 0, 1, 'L');
        $this->SetFont('Arial','',10);
        $this->Cell(0, 5, $this->sansAccent('Votre Adresse Ici'), 0, 1, 'L');

        $this->SetFont('Arial','B',14);
        $this->SetXY(15, 35);
        $this->SetTextColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
        $this->Cell(0, 7, $this->sansAccent('Facture'), 0, 1, 'L');
        $this->SetTextColor($this->secondaryColor[0], $this->secondaryColor[1], $this->secondaryColor[2]);

        $this->SetFont('Arial','B',10);
        $this->SetXY(150, 15);
        $this->Cell(30, 5, $this->sansAccent('Facture No'), 0, 0, 'L');
        $this->SetFont('Arial','',10);
        global $id_facture_pdf;
        $this->Cell(0, 5, 'FAC-'.$id_facture_pdf, 0, 1, 'L');

        $this->SetFont('Arial','B',10);
        $this->SetXY(150, 20);
        $this->Cell(30, 5, $this->sansAccent('Date'), 0, 0, 'L');
        $this->SetFont('Arial','',10);
        global $date_facture_pdf;
        $this->Cell(0, 5, $date_facture_pdf, 0, 1, 'L');

        $this->Ln(20);
    }

    function Footer()
    {
        $this->SetFillColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
        $this->Rect(0, $this->GetY() + 15, 210, 5, 'F');

        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->SetTextColor($this->secondaryColor[0], $this->secondaryColor[1], $this->secondaryColor[2]);
        $this->Cell(0,10,$this->sansAccent('Page ').$this->PageNo().'/{nb}',0,0,'C');
    }

    function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        $this->SetTextColor($this->secondaryColor[0], $this->secondaryColor[1], $this->secondaryColor[2]);
        parent::Cell($w, $h, $this->sansAccent($txt), $border, $ln, $align, $fill, $link);
    }

    function MultiCell($w, $h, $txt='', $border=0, $align='L', $fill=false)
    {
        $this->SetTextColor($this->secondaryColor[0], $this->secondaryColor[1], $this->secondaryColor[2]);
        parent::MultiCell($w, $h, $this->sansAccent($txt), $border, $align, $fill);
    }

    function sansAccent($str) {
        $str = str_replace(array('é', 'è', 'ê', 'ë', 'à', 'â', 'ä', 'î', 'ï', 'ô', 'ö', 'û', 'ü', 'ç'),
                            array('e', 'e', 'e', 'e', 'a', 'a', 'a', 'i', 'i', 'o', 'o', 'u', 'u', 'c'),
                            $str);
        return $str;
    }

    function FancyTable($header, $data)
    {
        $this->SetFillColor(220, 220, 220);
        $this->SetTextColor($this->secondaryColor[0], $this->secondaryColor[1], $this->secondaryColor[2]);
        $this->SetDrawColor(200, 200, 200);
        $this->SetLineWidth(.3);
        $this->SetFont('Arial','B',12);

        $w = array(70, 0);
        $w[1] = $this->GetPageWidth() - $this->GetX() - $this->rMargin;
        for($i=0;$i<count($header);$i++)
            $this->Cell($w[$i],7,$this->sansAccent($header[$i]),1,0,'L',true);
        $this->Ln();

        $this->SetFont('Arial','',11);
        $fill = false;
        foreach($data as $row)
        {
            $this->Cell($w[0],6,$this->sansAccent($row[0]),'LR',0,'L',$fill);
            $this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
            $this->Ln();
            $fill = !$fill;
        }
        $this->Cell(array_sum($w),0,'','T');
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_reparation = $_POST['id_reparation'];
    $date_facture = $_POST['date_facture'];
    $montant_total = $_POST['facture_montant_total'];
    $montant_regle = $_POST['montant_regle'];
    $details = $_POST['details'];
    $statut_paiement = $_POST['statut_paiement']; // Récupérer le statut de paiement
    $date_facture_pdf = $date_facture;

    // Insertion des informations de la facture
    $stmt_facture_insert = $pdo->prepare("INSERT INTO facture (id_reparation, date_facture, montant_total, montant_regle, reste_a_payer, details, statut_paiement) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $reste_a_payer = $montant_total - $montant_regle;
    $stmt_facture_insert->execute([$id_reparation, $date_facture, $montant_total, $montant_regle, $reste_a_payer, $details, $statut_paiement]);

    $id_facture = $pdo->lastInsertId();
    $id_facture_pdf = $id_facture;

    // Récupération des informations de la réparation APRÈS l'insertion de la facture
    $stmt_reparation = $pdo->prepare("
        SELECT
            dr.nom_complet,
            dr.marque_telephone,
            dr.probleme,
            r.montant_total AS montant_total_reparation -- Alias pour éviter la confusion
        FROM reparation r
        INNER JOIN demande_reparation dr ON r.id_demande = dr.id_demande
        WHERE r.id_reparation = ?
    ");
    $stmt_reparation->execute([$id_reparation]);
    $reparation_info = $stmt_reparation->fetch(PDO::FETCH_ASSOC);

    if ($reparation_info) {
        $nom_demandeur = $reparation_info['nom_complet'];
        $marque_telephone = $reparation_info['marque_telephone'];
        $probleme = $reparation_info['probleme'];
        $montant_total_reparation = $reparation_info['montant_total_reparation'];
        $montant_paye = $montant_regle; // Utiliser le montant réglé lors de la création de la facture
        $solde = ($montant_paye >= $montant_total) ? 'Oui' : 'Non'; // Vérifier le solde

        $pdf = new PDF('P');
        $pdf->AliasNbPages();
        $pdf->AddPage();

        $header = array('Description', 'Details');
        $data = array(
            array('Nom du demandeur', $nom_demandeur),
            array('Marque du telephone', $marque_telephone),
            array('Probleme', $probleme),
            array('Montant Total', number_format($montant_total_reparation, 2) . ' FCFA'),
            array('Montant Paye', number_format($montant_paye, 2) . ' FCFA'),
            array('Solde', $solde)
        );
        $pdf->FancyTable($header, $data);

        $pdf->Ln(10);
        $pdf->SetFont('Arial','',10);
        $pdf->MultiCell(0, 6, $pdf->sansAccent('Details supplementaires: ') . htmlspecialchars($pdf->sansAccent($details)), 0, 'L');

        $pdf->Ln(5);
        $pdf->SetFont('Arial','',10);
        $pdf->MultiCell(0, 6, $pdf->sansAccent('Conditions et modalites de paiement: Le paiement est du dans 15 jours.'), 0, 'L');

        $pdf->SetY(-30);
        $pdf->SetFont('Arial','I',10);
        $pdf->Cell(0, 5, $pdf->sansAccent('Signature'), 0, 1, 'R');

        $pdf->Output('I', 'facture_' . $id_facture . '.pdf');
        ob_end_flush(); // Send output buffer
        exit();

    } else {
        echo "Erreur: Informations de la réparation non trouvées.";
    }

} else {
    echo "Methode non autorisee.";
}
?>